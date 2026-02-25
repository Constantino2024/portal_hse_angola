<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('company.access');
    }

    /**
     * Show the company profile page
     */
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('partner.dashboard')
                ->with('error', 'Perfil da empresa não encontrado.');
        }

        // Carregar contagens com tratamento de null e verificações
        $stats = [
            'esg_initiatives_count' => $this->safeCount($company->esgInitiatives()),
            'projects_active_count' => $this->safeCount($company->companyProjects()->where('status', 'active')),
            'needs_open_count' => $this->safeCount($company->companyNeeds()->where('status', 'open')),
        ];

        return view('partner.profile.index', compact('company', 'user', 'stats'));
    }

    /**
     * Safe count that returns 0 if relation doesn't exist or query fails
     */
    private function safeCount($query)
    {
        try {
            return $query->count();
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::warning('Error counting relation: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Show the edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('partner.dashboard')
                ->with('error', 'Perfil da empresa não encontrado.');
        }

        // Dados para selects
        $sectors = [
            'oil_gas' => 'Óleo & Gás',
            'mining' => 'Mineração',
            'construction' => 'Construção Civil',
            'industry' => 'Indústria',
            'services' => 'Serviços',
            'consulting' => 'Consultoria',
            'energy' => 'Energia',
            'transport' => 'Transporte',
            'logistics' => 'Logística',
            'health' => 'Saúde',
            'education' => 'Educação',
            'agriculture' => 'Agropecuária',
            'technology' => 'Tecnologia',
            'finance' => 'Finanças',
            'other' => 'Outro',
        ];

        $companySizes = [
            'micro' => 'Micro (1-10 funcionários)',
            'small' => 'Pequena (11-50 funcionários)',
            'medium' => 'Média (51-200 funcionários)',
            'large' => 'Grande (201-500 funcionários)',
            'enterprise' => 'Empresarial (501+ funcionários)',
        ];

        $provinces = [
            'luanda' => 'Luanda',
            'benguela' => 'Benguela',
            'huila' => 'Huíla',
            'huambo' => 'Huambo',
            'cabinda' => 'Cabinda',
            'malanje' => 'Malanje',
            'kwanza_norte' => 'Cuanza Norte',
            'kwanza_sul' => 'Cuanza Sul',
            'bengo' => 'Bengo',
            'bie' => 'Bié',
            'uige' => 'Uíge',
            'lunda_norte' => 'Lunda Norte',
            'lunda_sul' => 'Lunda Sul',
            'moxico' => 'Moxico',
            'cunene' => 'Cunene',
            'kuando_kubango' => 'Cuando Cubango',
            'namibe' => 'Namibe',
            'zaire' => 'Zaire',
        ];

        return view('partner.profile.edit', compact('company', 'user', 'sectors', 'companySizes', 'provinces'));
    }

    /**
     * Update the company profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('partner.dashboard')
                ->with('error', 'Perfil da empresa não encontrado.');
        }

        $validated = $request->validate([
            // Dados da empresa
            'company_name' => 'required|string|max:255',
            'trading_name' => 'nullable|string|max:255',
            'nif' => 'required|string|max:50|unique:companies,nif,' . $company->id,
            'sector' => 'required|string|max:100',
            'company_size' => 'required|string|max:50',
            'foundation_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            
            // Contactos
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:companies,email,' . $company->id,
            'website' => 'nullable|url|max:255',
            
            // Pessoa de contacto
            'contact_person' => 'required|string|max:255',
            'contact_position' => 'nullable|string|max:255',
            
            // Localização
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            
            // Informações da empresa
            'description' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'values' => 'nullable|string',
            
            // Redes sociais
            'facebook' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            
            // HSE
            'has_hse_department' => 'boolean',
            'hse_manager_name' => 'nullable|string|max:255',
            'hse_manager_contact' => 'nullable|string|max:255',
            
            // Ficheiros
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Processar upload do logo
        if ($request->hasFile('logo')) {
            // Remover logo antigo se existir
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('companies/logos', 'public');
            $validated['logo_path'] = $logoPath;
        }

        // Processar upload do banner
        if ($request->hasFile('banner')) {
            // Remover banner antigo se existir
            if ($company->banner_path) {
                Storage::disk('public')->delete($company->banner_path);
            }
            
            $bannerPath = $request->file('banner')->store('companies/banners', 'public');
            $validated['banner_path'] = $bannerPath;
        }

        // Processar campos booleanos
        $validated['has_hse_department'] = $request->has('has_hse_department');
        $validated['has_esg_policy'] = $request->has('has_esg_policy');

        // Atualizar empresa
        $company->update($validated);

        // Atualizar também o usuário se o email foi alterado
        if ($user->email !== $validated['email']) {
            $user->update(['email' => $validated['email']]);
        }

        return redirect()->route('partner.profile.index')
            ->with('success', 'Perfil da empresa atualizado com sucesso!');
    }

    /**
     * Update user account settings
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'current_password' => 'required_with:new_password|current_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['new_password'])) {
            $user->password = bcrypt($validated['new_password']);
        }

        $user->save();

        return redirect()->route('partner.profile')
            ->with('success', 'Dados da conta atualizados com sucesso!');
    }

    /**
     * Remove the logo
     */
    public function removeLogo()
    {
        $user = Auth::user();
        $company = $user->company;

        if ($company && $company->logo_path) {
            Storage::disk('public')->delete($company->logo_path);
            $company->update(['logo_path' => null]);
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Remove the banner
     */
    public function removeBanner()
    {
        $user = Auth::user();
        $company = $user->company;

        if ($company && $company->banner_path) {
            Storage::disk('public')->delete($company->banner_path);
            $company->update(['banner_path' => null]);
            
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}