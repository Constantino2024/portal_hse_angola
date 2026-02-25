<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Trabalho;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PartnerJobController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $q = Trabalho::query()->latest('created_at');

        if (!$user->isAdmin()) {
            $q->where('user_id', $user->id);
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function ($x) use ($term) {
                $x->where('title','like',"%$term%")
                  ->orWhere('company','like',"%$term%")
                  ->orWhere('location','like',"%$term%");
            });
        }

        $jobs = $q->paginate(15)->withQueryString();
        return view('partner.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('partner.jobs.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'apply_link' => 'nullable|url',
            'apply_email' => 'nullable|email',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['user_id'] = $user->id;
        $data['slug'] = Str::slug($data['title']).'-'.time();
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = false;   // reservado para admin
        $data['is_sponsored'] = false;  // reservado para admin
        $data['published_at'] = now();

        // opcional: se empresa não vier preenchida, tenta puxar do nome do user
        if (empty($data['company'])) {
            $data['company'] = $user->name;
        }

        Trabalho::create($data);

        return redirect()->route('partner.jobs.index')->with('success', 'Vaga publicada com sucesso!');
    }

    public function edit(Trabalho $job)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $job->user_id !== $user->id) {
            abort(403);
        }

        return view('partner.jobs.edit', compact('job'));
    }

    public function update(Request $request, Trabalho $job)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $job->user_id !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'apply_link' => 'nullable|url',
            'apply_email' => 'nullable|email',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($job->title !== $data['title']) {
            $data['slug'] = Str::slug($data['title']).'-'.time();
        }

        $data['is_active'] = $request->boolean('is_active', true);

        // parceiros não mexem nisso
        unset($data['is_featured'], $data['is_sponsored']);

        $job->update($data);

        return redirect()->route('partner.jobs.index')->with('success', 'Vaga atualizada!');
    }

    public function destroy(Trabalho $job)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $job->user_id !== $user->id) {
            abort(403);
        }

        $job->delete();
        return redirect()->route('partner.jobs.index')->with('success', 'Vaga removida!');
    }
}
