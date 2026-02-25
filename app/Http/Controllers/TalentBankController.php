<?php

namespace App\Http\Controllers;

use App\Models\HseTalentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TalentBankController extends Controller
{
    public function index()
    {
        return view('talent.index');
    }

    public function show()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $profile = HseTalentProfile::where('user_id', auth()->id())->first();

        if (!$profile) {
            return redirect()->route('talent.profile.edit')->with('info', 'Crie seu perfil primeiro!');
        }

        $profile->load('user');

        return view('talent.profile-show', compact('profile'));
    }

    public function edit()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $profile = HseTalentProfile::firstOrNew(['user_id' => auth()->id()]);

        if (!$profile->exists) {
            $user = auth()->user();
            $profile->full_name = $user->name;
            $profile->email = $user->email;
        }

        return view('talent.profile-edit', [
            'profile' => $profile,
            'levels' => self::levels(),
            'areas' => self::areas(),
            'availabilities' => self::availabilities(),
            'maritalStatuses' => self::maritalStatuses(),
            'companyTypes' => self::companyTypes(),
            'provinces' => self::provinces(),
        ]);
    }

    public function update(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Não autorizado');
        }

        \Log::info('Profile update request:', [
            'user_id' => auth()->id(),
            'data_keys' => array_keys($request->all()),
            'has_files' => $request->hasFile('cv') || $request->hasFile('profile_image')
        ]);

        try {
            // Validação com valores corretos do ENUM
            $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'level' => 'required|string|in:junior,pleno,senior',
                'area' => 'required|string|in:tecnico_trabalho,ambientalistas,psicologos,medico_trabalho,higienistas',
                'availability' => 'required|string|in:obra,projecto,permanente',
                'province' => 'required|string|max:60',
                'profile_image' => 'nullable|image|max:2048',
                'cv' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            ], [
                'full_name.required' => 'O nome completo é obrigatório.',
                'email.required' => 'O email é obrigatório.',
                'phone.required' => 'O telefone é obrigatório.',
                'level.required' => 'O nível profissional é obrigatório.',
                'level.in' => 'Nível profissional inválido.',
                'area.required' => 'A área de atuação é obrigatória.',
                'area.in' => 'Área de atuação inválida.',
                'availability.required' => 'A disponibilidade é obrigatória.',
                'availability.in' => 'Disponibilidade inválida.',
                'province.required' => 'A província é obrigatória.',
            ]);

            // Buscar ou criar perfil
            $profile = HseTalentProfile::firstOrNew(['user_id' => auth()->id()]);
            
            // Preparar dados básicos
            $data = $request->only([
                'full_name', 'email', 'phone', 'birth_date', 'nationality',
                'marital_status', 'address', 'city', 'province',
                'headline', 'bio', 'current_position', 'years_experience',
                'level', 'area', 'availability', 'expected_salary',
                'preferred_location', 'drivers_license', 'linkedin'
            ]);
            
            // Converter valores para os valores corretos do ENUM
            if ($request->has('level')) {
                $data['level'] = $this->mapLevelValue($request->level);
            }
            
            if ($request->has('area')) {
                $data['area'] = $this->mapAreaValue($request->area);
            }
            
            if ($request->has('availability')) {
                $data['availability'] = $this->mapAvailabilityValue($request->availability);
            }
            
            // Processar campos opcionais (arrays) - aceitar vazios
            $arrayFields = ['skills', 'preferred_areas', 'preferred_company_types'];
            foreach ($arrayFields as $field) {
                if ($request->has($field) && is_array($request->$field)) {
                    // Filtrar valores vazios
                    $filtered = array_filter($request->$field, function($value) {
                        return !empty(trim($value));
                    });
                    $data[$field] = !empty($filtered) ? json_encode(array_values($filtered), JSON_UNESCAPED_UNICODE) : null;
                } else {
                    $data[$field] = null;
                }
            }
            
            // Processar arrays complexos opcionais
            $complexArrays = ['education', 'certifications', 'languages', 'work_experience'];
            foreach ($complexArrays as $field) {
                if ($request->has($field) && is_array($request->$field)) {
                    // Filtrar entradas completamente vazias
                    $filtered = array_filter($request->$field, function($item) {
                        if (!is_array($item)) return false;
                        
                        // Verificar se pelo menos um campo tem valor
                        foreach ($item as $value) {
                            if (!empty(trim($value))) {
                                return true;
                            }
                        }
                        return false;
                    });
                    
                    $data[$field] = !empty($filtered) ? json_encode(array_values($filtered), JSON_UNESCAPED_UNICODE) : null;
                } else {
                    $data[$field] = null;
                }
            }
            
            // Processar checkbox is_public
            $data['is_public'] = $request->has('is_public') ? 1 : 0;
            
            // Garantir user_id
            $data['user_id'] = auth()->id();
            
            \Log::info('Processed data for save:', [
                'level' => $data['level'] ?? null,
                'area' => $data['area'] ?? null,
                'availability' => $data['availability'] ?? null,
                'user_id' => $data['user_id']
            ]);
            
            // Atualizar perfil com dados básicos
            $profile->fill($data);
            
            // Processar uploads
            if ($request->hasFile('profile_image')) {
                \Log::info('Processing profile image upload');
                if ($profile->profile_image) {
                    Storage::disk('public')->delete($profile->profile_image);
                }
                $profile->profile_image = $request->file('profile_image')->store('profile_images', 'public');
            }
            
            // Processar CV - opcional se já tiver um
            $hasExistingCv = $profile->cv_path && Storage::disk('public')->exists($profile->cv_path);
            $keepExistingCv = $request->has('keep_existing_cv') && $hasExistingCv;
            
            if ($request->hasFile('cv')) {
                \Log::info('Processing CV upload');
                if ($profile->cv_path) {
                    Storage::disk('public')->delete($profile->cv_path);
                }
                $profile->cv_path = $request->file('cv')->store('cv', 'public');
            } elseif (!$hasExistingCv && !$keepExistingCv) {
                // Se não tem CV existente e não enviou novo, manter null
                $profile->cv_path = null;
            }
            // Se tem CV existente e marcou para manter, não faz nada
            
            \Log::info('Saving profile:', [
                'user_id' => $profile->user_id,
                'has_image' => !empty($profile->profile_image),
                'has_cv' => !empty($profile->cv_path),
                'level' => $profile->level,
                'area' => $profile->area,
                'availability' => $profile->availability
            ]);
            
            // Salvar perfil
            $profile->save();
            
            \Log::info('Profile saved successfully', ['profile_id' => $profile->id]);
            
            return redirect()
                ->route('talent.profile.show')
                ->with('success', 'Perfil atualizado com sucesso!');
                
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar perfil: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'error' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao salvar perfil: ' . $e->getMessage());
        }
    }

    /**
     * Mapear valores do front-end para os valores do ENUM
     */
    private function mapLevelValue($value)
    {
        $mapping = [
            'junior' => 'junior',
            'pleno' => 'pleno',
            'senior' => 'senior',
            'especialista' => 'senior', // Mapear para senior
            'coordenador' => 'senior',   // Mapear para senior
            'gestor' => 'senior',        // Mapear para senior
            'director' => 'senior',      // Mapear para senior
        ];
        
        return $mapping[$value] ?? 'junior'; // Default para junior
    }

    private function mapAreaValue($value)
    {
        $mapping = [
            'hst' => 'tecnico_trabalho',
            'ambiente' => 'ambientalistas',
            'esg' => 'ambientalistas',
            'medico_trabalho' => 'medico_trabalho',
            'qhse' => 'tecnico_trabalho',
            'seguranca' => 'tecnico_trabalho',
            'higiene' => 'higienistas',
            'saude' => 'medico_trabalho',
            'tecnico_trabalho' => 'tecnico_trabalho',
            'ambientalistas' => 'ambientalistas',
            'psicologos' => 'psicologos',
            'medico_trabalho' => 'medico_trabalho',
            'higienistas' => 'higienistas',
        ];
        
        return $mapping[$value] ?? 'tecnico_trabalho'; // Default
    }

    private function mapAvailabilityValue($value)
    {
        $mapping = [
            'obra' => 'obra',
            'projecto' => 'projecto',
            'permanente' => 'permanente',
            'freelance' => 'projecto', // Mapear para projecto
            'remoto' => 'permanente',  // Mapear para permanente
            'hibrido' => 'permanente', // Mapear para permanente
        ];
        
        return $mapping[$value] ?? 'permanente'; // Default
    }

    // Métodos auxiliares estáticos
    public static function levels(): array
    {
        return [
            'junior' => 'Júnior',
            'pleno' => 'Pleno',
            'senior' => 'Sénior',
            'especialista' => 'Especialista',
            'coordenador' => 'Coordenador',
            'gestor' => 'Gestor',
            'director' => 'Director',
        ];
    }

    public static function areas(): array
    {
        // Mostrar opções amigáveis no front-end
        return [
            'tecnico_trabalho' => 'Técnico de Trabalho',
            'ambientalistas' => 'Ambientalista',
            'psicologos' => 'Psicólogo',
            'medico_trabalho' => 'Médico do Trabalho',
            'higienistas' => 'Higienista',
        ];
    }

    public static function availabilities(): array
    {
        return [
            'obra' => 'Obra',
            'projecto' => 'Projecto',
            'permanente' => 'Permanente',
        ];
    }

    public static function maritalStatuses(): array
    {
        return [
            'solteiro' => 'Solteiro(a)',
            'casado' => 'Casado(a)',
            'divorciado' => 'Divorciado(a)',
            'viuvo' => 'Viúvo(a)',
            'uniao_facto' => 'União de Facto',
        ];
    }

    public static function companyTypes(): array
    {
        return [
            'construcao' => 'Construção Civil',
            'minas' => 'Mineração',
            'petroleo_gas' => 'Petróleo & Gás',
            'industrial' => 'Indústria',
            'consultoria' => 'Consultoria',
            'energia' => 'Energia',
            'quimica' => 'Química',
            'saude' => 'Saúde',
            'agricultura' => 'Agricultura',
            'outro' => 'Outro',
        ];
    }

    public static function provinces(): array
    {
        return [
            'Bengo', 'Benguela', 'Bié', 'Cabinda', 'Cuando-Cubango',
            'Cuanza Norte', 'Cuanza Sul', 'Cunene', 'Huambo', 'Huíla',
            'Luanda', 'Lunda Norte', 'Lunda Sul', 'Malanje', 'Moxico',
            'Namibe', 'Uíge', 'Zaire'
        ];
    }
}