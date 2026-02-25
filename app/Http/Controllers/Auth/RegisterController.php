<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Rules\ValidEmail;
use App\Services\EmailValidationService;

class RegisterController extends Controller
{
    private $emailValidationService;

    public function __construct()
    {
        $this->emailValidationService = new EmailValidationService();
    }

    public function choose()
    {
        return view('auth.register_choice');
    }

    public function showProfessional()
    {
        return view('auth.register_professional');
    }

    public function showCompany()
    {
        return view('auth.register_company');
    }

    public function storeProfessional(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', new ValidEmail()],
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'profissional',
        ]);

        Auth::login($user);

        return redirect()->route('talent.profile.edit')
            ->with('success', 'Conta criada com sucesso! Complete o seu perfil profissional.');
    }

    public function storeCompany(Request $request)
    {
        // Validação dos dados
        $data = $request->validate([
            // Dados do usuário (responsável)
            'email' => ['required', 'email', 'max:255', 'unique:users,email', new ValidEmail()],
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => ['required', 'string', 'max:20'],
            
            // Dados da empresa
            'company_name' => ['required', 'string', 'max:255'],
            'trading_name' => ['nullable', 'string', 'max:255'],
            'nif' => ['required', 'string', 'max:25', 'unique:companies,nif'],
            'sector' => ['required', 'string', 'max:100'],
            'company_size' => ['nullable', 'string', 'max:50'],
            'foundation_year' => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:2000'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'terms' => ['required', 'accepted'],
        ]);


        // Cria o usuário
        $user = User::create([
            'name' => $data['company_name'], // Alterado para user_name
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'empresa',
        ]);

        // Upload do logo
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('companies/logos', 'public');
        }

        // Cria a empresa associada ao usuário
        try {
            $company = Company::create([
                'user_id' => $user->id,
                'company_name' => $data['company_name'],
                'trading_name' => $data['trading_name'] ?? null,
                'nif' => $data['nif'],
                'sector' => $data['sector'],
                'company_size' => $data['company_size'] ?? null,
                'foundation_year' => $data['foundation_year'] ?? null,
                'phone' => $data['phone'],
                'email' => $data['email'],
                'website' => $data['website'] ?? null,
                'address' => $data['address'],
                'city' => $data['city'],
                'province' => $data['province'],
                'country' => 'Angola',
                'logo_path' => $logoPath,
                'description' => $data['description'] ?? null,
                'is_active' => true,
                'is_verified' => false,
            ]);

            Auth::login($user);

            return redirect()->route('partner.dashboard')
                ->with('success', 'Conta da empresa criada com sucesso! Bem-vindo ao Portal HSE.');

        } catch (\Exception $e) {
            // Se der erro ao criar a empresa, deleta o usuário criado
            if (isset($user)) {
                $user->delete();
            }
            
            // Log do erro
            \Log::error('Erro ao criar empresa: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Erro ao criar a conta da empresa. Por favor, tente novamente.');
        }
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $email = $request->email;
        
        // Verificar se email já está registrado
        $exists = User::where('email', $email)->exists();
        if ($exists) {
            return response()->json([
                'available' => false,
                'message' => 'Este email já está registrado.'
            ]);
        }
        
        // Validar email com API
        try {
            $emailValidationService = new EmailValidationService();
            $details = $emailValidationService->validateEmail($email);
            
            $response = [
                'available' => true,
                'valid' => $details['valid'],
                'disposable' => $details['disposable'],
                'free' => $details['free'],
                'deliverable' => $details['deliverable'],
                'score' => $details['score'],
                'provider' => $details['provider'],
                'message' => $this->getValidationMessage($details),
            ];
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            \Log::error('Erro na validação AJAX do email: ' . $e->getMessage());
            
            return response()->json([
                'available' => true,
                'valid' => filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
                'message' => 'Não foi possível validar o email completamente.'
            ]);
        }
    }
    
    private function getValidationMessage(array $details): string
    {
        if (!$details['valid']) {
            return 'Formato de email inválido.';
        }
        
        if ($details['disposable']) {
            return '⚠️ Emails temporários não são recomendados para empresas.';
        }
        
        if ($details['free'] && $details['score'] < 60) {
            return '✓ Email válido. Recomendamos email corporativo para empresas.';
        }
        
        if ($details['deliverable'] && $details['score'] > 70) {
            return '✓ Email válido e verificável.';
        }
        
        return '✓ Email válido.';
    }


    // Verifica se NIF está disponível
    public function checkNif(Request $request)
    {
        $exists = Company::where('nif', $request->nif)->exists(); // Alterado para Company
        return response()->json(['available' => !$exists]);
    }
}