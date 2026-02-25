<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filtros
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Estatísticas
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'by_role' => [
                'admin' => User::where('role', 'admin')->count(),
                'empresa' => User::where('role', 'empresa')->count(),
                'profissional' => User::where('role', 'profissional')->count(),
            ]
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,empresa,profissional',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        $user = User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilizador criado com sucesso!');
    }

    public function show(User $user)
    {
        $user->load(['company', 'talentProfile', 'trabalhos' => function($q) {
            $q->latest()->limit(5);
        }]);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,empresa,profissional',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'role']);
        
        // Handle is_active checkbox
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        
        $user->update($data);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilizador atualizado com sucesso!');
    }

    public function toggleStatus(Request $request, User $user)
    {
        // Evitar desativar o próprio usuário
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Não pode alterar o status do seu próprio utilizador.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'ativado' : 'desativado';
        return back()->with('success', "Utilizador {$status} com sucesso!");
    }

    public function destroy(Request $request, User $user)
    {
        // Evitar deletar o próprio usuário
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Não pode eliminar o seu próprio utilizador.');
        }

        // Verificar se tem registros relacionados
        if ($this->hasRelatedRecords($user)) {
            return back()->with('error', 'Este utilizador possui registros associados e não pode ser eliminado.');
        }

        try {
            $user->delete();
            return redirect()->route('admin.users.index')
                ->with('success', 'Utilizador eliminado permanentemente!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao eliminar utilizador: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id'
        ]);

        // Decode users if they come as JSON string
        $users = is_string($request->users) ? json_decode($request->users, true) : $request->users;
        
        // Remover o próprio usuário da seleção para ações
        $users = array_filter($users, function($id) {
            return $id != auth()->id();
        });

        if (empty($users)) {
            return back()->with('error', 'Não pode realizar esta ação no seu próprio utilizador.');
        }

        $count = count($users);

        try {
            DB::beginTransaction();

            switch ($request->action) {
                case 'activate':
                    User::whereIn('id', $users)->update(['is_active' => true]);
                    $message = "{$count} utilizador(es) ativado(s) com sucesso!";
                    break;
                    
                case 'deactivate':
                    User::whereIn('id', $users)->update(['is_active' => false]);
                    $message = "{$count} utilizador(es) desativado(s) com sucesso!";
                    break;
                    
                case 'delete':
                    // Verificar registros relacionados antes de deletar
                    foreach ($users as $userId) {
                        $user = User::find($userId);
                        if ($this->hasRelatedRecords($user)) {
                            DB::rollBack();
                            return back()->with('error', "O utilizador {$user->name} possui registros associados e não pode ser eliminado.");
                        }
                    }

                    User::whereIn('id', $users)->delete();
                    $message = "{$count} utilizador(es) eliminado(s) com sucesso!";
                    break;
            }

            DB::commit();
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao processar ação: ' . $e->getMessage());
        }
    }

    private function hasRelatedRecords(User $user): bool
    {
        return $user->company()->exists() ||
               $user->talentProfile()->exists() ||
               $user->trabalhos()->exists() ||
               $user->esgInitiatives()->exists() ||
               $user->companyProjects()->exists() ||
               $user->companyNeeds()->exists() ||
               $user->jobPosts()->exists();
    }
}