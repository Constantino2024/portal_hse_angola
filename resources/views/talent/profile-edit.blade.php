@extends('layouts.app')

@section('title', 'Editar Perfil HSE · Banco de Talentos')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                    <i class="fa-solid fa-user-pen text-primary fs-3"></i>
                </div>
                <div>
                    <h1 class="display-6 fw-bold mb-1">Editar Perfil HSE</h1>
                    <p class="text-muted mb-0">Preencha todos os campos para criar um currículo completo.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 text-lg-end">
            <div class="d-flex flex-column flex-sm-row gap-3">
                <a href="{{ route('talent.profile.show') }}" class="btn btn-outline-primary px-4 py-2 d-inline-flex align-items-center">
                    <i class="fa-solid fa-eye me-2"></i>
                    Ver Perfil
                </a>
                <a href="{{ route('talent.index') }}" class="btn btn-outline-secondary px-4 py-2 d-inline-flex align-items-center">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Progresso -->
    @php
        $profile = \App\Models\HseTalentProfile::firstOrNew(['user_id' => auth()->id()]);
        $filledFields = 0;
        $totalFields = 10;
        $fieldsToCheck = ['full_name', 'email', 'phone', 'level', 'area', 'availability', 'province', 'headline', 'bio', 'cv_path'];
        
        foreach($fieldsToCheck as $field) {
            if (!empty($profile->$field)) $filledFields++;
        }
        
        $completionPercentage = round(($filledFields / $totalFields) * 100);
    @endphp

    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-semibold text-muted mb-0">Progresso do Currículo</h6>
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                <i class="fa-solid fa-percent me-2"></i>
                {{ $completionPercentage }}% Completo
            </span>
        </div>
        <div class="progress rounded-pill" style="height: 10px;">
            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $completionPercentage }}%">
                <span class="visually-hidden">{{ $completionPercentage }}% completo</span>
            </div>
        </div>
    </div>

    <!-- Formulário Principal -->
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
        <div class="card-header bg-primary bg-gradient bg-opacity-10 border-0 py-4">
            <h4 class="fw-bold mb-0 d-flex align-items-center">
                <i class="fa-solid fa-id-card text-primary me-3"></i>
                Currículo Profissional HSE
            </h4>
        </div>
        
        <form method="POST" action="{{ route('talent.profile.update') }}" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('POST')
            
            <div class="card-body p-4 p-lg-5">
                <!-- Mensagens de erro/sucesso -->
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                <!-- Erros de validação -->
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    <strong>Erros encontrados:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Tabs de navegação -->
                <ul class="nav nav-pills mb-5" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="personal-tab" data-bs-toggle="pill" data-bs-target="#personal" type="button">
                            <i class="fa-solid fa-user me-2"></i>Pessoal
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="professional-tab" data-bs-toggle="pill" data-bs-target="#professional" type="button">
                            <i class="fa-solid fa-briefcase me-2"></i>Profissional
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="experience-tab" data-bs-toggle="pill" data-bs-target="#experience" type="button">
                            <i class="fa-solid fa-history me-2"></i>Experiência (Opcional)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documents-tab" data-bs-toggle="pill" data-bs-target="#documents" type="button">
                            <i class="fa-solid fa-file me-2"></i>Documentos
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="profileTabsContent">
                    <!-- Tab 1: Informações Pessoais -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <h5 class="fw-bold text-primary mb-4">Informações Pessoais *</h5>
                        <p class="text-muted mb-4"><small>Campos com * são obrigatórios</small></p>
                        
                        <div class="row g-4 mb-4">
                            <!-- Foto de Perfil -->
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="position-relative d-inline-block mb-3">
                                        @if($profile->profile_image)
                                            <img src="{{ asset('storage/' . $profile->profile_image) }}" 
                                                 id="profileImagePreview"
                                                 class="rounded-circle border border-4 border-primary border-opacity-25"
                                                 style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;"
                                                 onclick="document.getElementById('profile_image').click()">
                                        @else
                                            <div id="profileImagePreview" 
                                                 class="rounded-circle border border-4 border-primary border-opacity-25 d-flex align-items-center justify-content-center bg-primary bg-opacity-10"
                                                 style="width: 150px; height: 150px; cursor: pointer;"
                                                 onclick="document.getElementById('profile_image').click()">
                                                <i class="fa-solid fa-camera text-primary fs-1"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 border border-3 border-white">
                                            <i class="fa-solid fa-camera text-white"></i>
                                        </div>
                                    </div>
                                    <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/*">
                                    <div class="form-text">Clique na imagem para alterar (JPEG, PNG, max 2MB)</div>
                                    @error('profile_image')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nome Completo *</label>
                                        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                                               value="{{ old('full_name', $profile->full_name) }}" required>
                                        @error('full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email *</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $profile->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Telefone *</label>
                                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                               value="{{ old('phone', $profile->phone) }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Data de Nascimento</label>
                                        <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
                                               value="{{ old('birth_date', $profile->birth_date ? $profile->birth_date->format('Y-m-d') : '') }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nacionalidade</label>
                                        <input type="text" name="nationality" class="form-control @error('nationality') is-invalid @enderror" 
                                               value="{{ old('nationality', $profile->nationality) }}" placeholder="ex: Angolana">
                                        @error('nationality')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Estado Civil</label>
                                        <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror">
                                            <option value="">-- Selecionar --</option>
                                            @foreach($maritalStatuses as $key => $status)
                                                <option value="{{ $key }}" @selected(old('marital_status', $profile->marital_status) === $key)>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('marital_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Província *</label>
                                        <select name="province" class="form-select @error('province') is-invalid @enderror" required>
                                            <option value="">-- Selecione --</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province }}" @selected(old('province', $profile->province) === $province)>{{ $province }}</option>
                                            @endforeach
                                        </select>
                                        @error('province')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Cidade</label>
                                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                               value="{{ old('city', $profile->city ?? '') }}" placeholder="ex: Luanda">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Morada</label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address', $profile->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Informações Profissionais -->
                    <div class="tab-pane fade" id="professional" role="tabpanel">
                        <h5 class="fw-bold text-primary mb-4">Informações Profissionais *</h5>
                        <p class="text-muted mb-4"><small>Campos com * são obrigatórios</small></p>
                        
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Nível Profissional *</label>
                                <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                                    <option value="" disabled selected>-- Selecione --</option>
                                    @foreach($levels as $k => $label)
                                        <option value="{{ $k }}" @selected(old('level', $profile->level) === $k)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Área de Atuação *</label>
                                <select name="area" class="form-select @error('area') is-invalid @enderror" required>
                                    <option value="" disabled selected>-- Selecione --</option>
                                    @foreach($areas as $k => $label)
                                        <option value="{{ $k }}" @selected(old('area', $profile->area) === $k)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Disponibilidade *</label>
                                <select name="availability" class="form-select @error('availability') is-invalid @enderror" required>
                                    <option value="" disabled selected>-- Selecione --</option>
                                    @foreach($availabilities as $k => $label)
                                        <option value="{{ $k }}" @selected(old('availability', $profile->availability) === $k)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('availability')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cargo Atual</label>
                                <input type="text" name="current_position" class="form-control @error('current_position') is-invalid @enderror" 
                                       value="{{ old('current_position', $profile->current_position) }}" 
                                       placeholder="ex: Técnico de Segurança Sénior">
                                @error('current_position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Anos de Experiência</label>
                                <input type="number" name="years_experience" class="form-control @error('years_experience') is-invalid @enderror" 
                                       value="{{ old('years_experience', $profile->years_experience) }}" 
                                       min="0" max="50" placeholder="ex: 5">
                                @error('years_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pretensão Salarial (AKZ)</label>
                                <input type="number" name="expected_salary" class="form-control @error('expected_salary') is-invalid @enderror" 
                                       value="{{ old('expected_salary', $profile->expected_salary) }}" 
                                       step="0.01" placeholder="ex: 250000.00">
                                @error('expected_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Carta de Condução</label>
                                <input type="text" name="drivers_license" class="form-control @error('drivers_license') is-invalid @enderror" 
                                       value="{{ old('drivers_license', $profile->drivers_license) }}" 
                                       placeholder="ex: Categoria B">
                                @error('drivers_license')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Localização Preferida</label>
                                <input type="text" name="preferred_location" class="form-control @error('preferred_location') is-invalid @enderror" 
                                       value="{{ old('preferred_location', $profile->preferred_location) }}" 
                                       placeholder="ex: Luanda, Benguela">
                                @error('preferred_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">LinkedIn (URL)</label>
                                <input type="url" name="linkedin" class="form-control @error('linkedin') is-invalid @enderror" 
                                       value="{{ old('linkedin', $profile->linkedin ?? '') }}" 
                                       placeholder="https://linkedin.com/in/seu-perfil">
                                @error('linkedin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Headline Profissional</label>
                                <input type="text" name="headline" class="form-control @error('headline') is-invalid @enderror" 
                                       value="{{ old('headline', $profile->headline) }}" 
                                       placeholder="Breve descrição do seu perfil profissional">
                                @error('headline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Biografia Profissional</label>
                                <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="6" 
                                          placeholder="Descreva sua experiência, objetivos, conquistas...">{{ old('bio', $profile->bio) }}</textarea>
                                <div class="form-text">Mínimo 50 caracteres recomendado</div>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Áreas de preferência -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Áreas de Interesse (Opcional)</label>
                                <div class="row g-2">
                                    @php
                                        $allAreas = ['Construção Civil', 'Mineração', 'Petróleo & Gás', 'Indústria', 
                                                    'Energia', 'Consultoria', 'Saúde', 'Transportes', 'Agricultura'];
                                        $selectedAreas = old('preferred_areas', $profile->preferred_areas ?? []);
                                        if (is_string($selectedAreas)) {
                                            $selectedAreas = json_decode($selectedAreas, true) ?: [];
                                        }
                                    @endphp
                                    @foreach($allAreas as $area)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="preferred_areas[]" 
                                                   value="{{ $area }}" id="area_{{ $loop->index }}"
                                                   @checked(in_array($area, $selectedAreas))>
                                            <label class="form-check-label" for="area_{{ $loop->index }}">
                                                {{ $area }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error('preferred_areas')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipos de empresa preferidos -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Tipos de Empresa Preferidos (Opcional)</label>
                                <div class="row g-2">
                                    @php
                                        $selectedCompanyTypes = old('preferred_company_types', $profile->preferred_company_types ?? []);
                                        if (is_string($selectedCompanyTypes)) {
                                            $selectedCompanyTypes = json_decode($selectedCompanyTypes, true) ?: [];
                                        }
                                    @endphp
                                    @foreach($companyTypes as $key => $type)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="preferred_company_types[]" 
                                                   value="{{ $type }}" id="type_{{ $key }}"
                                                   @checked(in_array($type, $selectedCompanyTypes))>
                                            <label class="form-check-label" for="type_{{ $key }}">
                                                {{ $type }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error('preferred_company_types')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Experiência Profissional (OPCIONAL) -->
                    <div class="tab-pane fade" id="experience" role="tabpanel">
                        <h5 class="fw-bold text-primary mb-4">Experiência Profissional (Opcional)</h5>
                        <p class="text-muted mb-4"><small>Preencha apenas se tiver experiência relevante</small></p>
                        
                        <!-- Experiência Profissional -->
                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold">Histórico Profissional</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addExperience()">
                                    <i class="fa-solid fa-plus me-2"></i>Adicionar Experiência
                                </button>
                            </div>
                            
                            <div id="experience-container">
                                @php
                                    $experienceData = old('work_experience', $profile->work_experience ?? []);
                                    if (is_string($experienceData)) {
                                        $experienceData = json_decode($experienceData, true) ?: [];
                                    }
                                    // Começar vazio - o usuário adiciona se quiser
                                    $experienceData = array_filter($experienceData, function($item) {
                                        return !empty(array_filter($item));
                                    });
                                @endphp
                                
                                @if(count($experienceData) > 0)
                                    @foreach($experienceData as $index => $exp)
                                    <div class="card border mb-3 experience-entry">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Empresa</label>
                                                    <input type="text" name="work_experience[{{ $index }}][company]" 
                                                           class="form-control" value="{{ $exp['company'] ?? '' }}" 
                                                           placeholder="Nome da empresa">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Cargo</label>
                                                    <input type="text" name="work_experience[{{ $index }}][position]" 
                                                           class="form-control" value="{{ $exp['position'] ?? '' }}" 
                                                           placeholder="ex: Técnico de Segurança">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Data Início</label>
                                                    <input type="date" name="work_experience[{{ $index }}][start_date]" 
                                                           class="form-control" value="{{ $exp['start_date'] ?? '' }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Data Término</label>
                                                    <input type="date" name="work_experience[{{ $index }}][end_date]" 
                                                           class="form-control" value="{{ $exp['end_date'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check pt-4">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="work_experience[{{ $index }}][current]" value="1"
                                                               @checked($exp['current'] ?? false) 
                                                               onclick="toggleEndDate(this)">
                                                        <label class="form-check-label">Trabalho Atual</label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Descrição das Funções</label>
                                                    <textarea name="work_experience[{{ $index }}][description]" 
                                                              class="form-control" rows="3" 
                                                              placeholder="Descreva suas responsabilidades e conquistas...">{{ $exp['description'] ?? '' }}</textarea>
                                                </div>
                                                <div class="col-12 text-end">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeEntry(this)">
                                                        <i class="fa-solid fa-trash me-2"></i>Remover
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-5 border rounded bg-light">
                                        <i class="fa-solid fa-briefcase text-muted fs-1 mb-3"></i>
                                        <p class="text-muted mb-4">Nenhuma experiência adicionada</p>
                                        <button type="button" class="btn btn-primary" onclick="addExperience()">
                                            <i class="fa-solid fa-plus me-2"></i>Adicionar Primeira Experiência
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Formação -->
                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold">Formação Académica (Opcional)</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addEducation()">
                                    <i class="fa-solid fa-plus me-2"></i>Adicionar Formação
                                </button>
                            </div>
                            
                            <div id="education-container">
                                @php
                                    $educationData = old('education', $profile->education ?? []);
                                    if (is_string($educationData)) {
                                        $educationData = json_decode($educationData, true) ?: [];
                                    }
                                    // Começar vazio - o usuário adiciona se quiser
                                    $educationData = array_filter($educationData, function($item) {
                                        return !empty(array_filter($item));
                                    });
                                @endphp
                                
                                @if(count($educationData) > 0)
                                    @foreach($educationData as $index => $edu)
                                    <div class="card border mb-3 education-entry">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Instituição</label>
                                                    <input type="text" name="education[{{ $index }}][institution]" 
                                                           class="form-control" value="{{ $edu['institution'] ?? '' }}" 
                                                           placeholder="Nome da instituição">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Grau/Certificado</label>
                                                    <input type="text" name="education[{{ $index }}][degree]" 
                                                           class="form-control" value="{{ $edu['degree'] ?? '' }}" 
                                                           placeholder="ex: Licenciatura, Mestrado">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Área de Estudo</label>
                                                    <input type="text" name="education[{{ $index }}][field]" 
                                                           class="form-control" value="{{ $edu['field'] ?? '' }}"
                                                           placeholder="ex: Engenharia de Segurança">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Data Início</label>
                                                    <input type="date" name="education[{{ $index }}][start_date]" 
                                                           class="form-control" value="{{ $edu['start_date'] ?? '' }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Data Conclusão</label>
                                                    <input type="date" name="education[{{ $index }}][end_date]" 
                                                           class="form-control" value="{{ $edu['end_date'] ?? '' }}">
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="education[{{ $index }}][current]" value="1"
                                                               @checked($edu['current'] ?? false)
                                                               onclick="toggleEducationEndDate(this)">
                                                        <label class="form-check-label">Ainda estou a estudar</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 text-end">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeEntry(this)">
                                                        <i class="fa-solid fa-trash me-2"></i>Remover
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-5 border rounded bg-light">
                                        <i class="fa-solid fa-graduation-cap text-muted fs-1 mb-3"></i>
                                        <p class="text-muted mb-4">Nenhuma formação adicionada</p>
                                        <button type="button" class="btn btn-primary" onclick="addEducation()">
                                            <i class="fa-solid fa-plus me-2"></i>Adicionar Primeira Formação
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Certificações -->
                        <div class="mb-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold">Certificações (Opcional)</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCertification()">
                                    <i class="fa-solid fa-plus me-2"></i>Adicionar Certificação
                                </button>
                            </div>
                            
                            <div id="certifications-container">
                                @php
                                    $certificationsData = old('certifications', $profile->certifications ?? []);
                                    if (is_string($certificationsData)) {
                                        $certificationsData = json_decode($certificationsData, true) ?: [];
                                    }
                                    // Começar vazio - o usuário adiciona se quiser
                                    $certificationsData = array_filter($certificationsData, function($item) {
                                        return !empty(array_filter($item));
                                    });
                                @endphp
                                
                                @if(count($certificationsData) > 0)
                                    @foreach($certificationsData as $index => $cert)
                                    <div class="card border mb-3 certification-entry">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nome da Certificação</label>
                                                    <input type="text" name="certifications[{{ $index }}][name]" 
                                                           class="form-control" value="{{ $cert['name'] ?? '' }}" 
                                                           placeholder="ex: NEBOSH, OSHA 30">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Emissor</label>
                                                    <input type="text" name="certifications[{{ $index }}][issuer]" 
                                                           class="form-control" value="{{ $cert['issuer'] ?? '' }}"
                                                           placeholder="Organização emissora">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Data Emissão</label>
                                                    <input type="date" name="certifications[{{ $index }}][issue_date]" 
                                                           class="form-control" value="{{ $cert['issue_date'] ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Data Validade</label>
                                                    <input type="date" name="certifications[{{ $index }}][expiry_date]" 
                                                           class="form-control" value="{{ $cert['expiry_date'] ?? '' }}">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeEntry(this)">
                                                        <i class="fa-solid fa-trash me-2"></i>Remover
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-5 border rounded bg-light">
                                        <i class="fa-solid fa-certificate text-muted fs-1 mb-3"></i>
                                        <p class="text-muted mb-4">Nenhuma certificação adicionada</p>
                                        <button type="button" class="btn btn-primary" onclick="addCertification()">
                                            <i class="fa-solid fa-plus me-2"></i>Adicionar Primeira Certificação
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Habilidades -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Habilidades Técnicas (Opcional)</h6>
                            <div id="skills-container">
                                @php
                                    $profileSkills = old('skills', $profile->skills ?? []);
                                    if (is_string($profileSkills)) {
                                        $profileSkills = json_decode($profileSkills, true) ?: [];
                                    }
                                    // Começar vazio - o usuário adiciona se quiser
                                    $profileSkills = array_filter($profileSkills, function($skill) {
                                        return !empty(trim($skill));
                                    });
                                @endphp
                                
                                @if(count($profileSkills) > 0)
                                    @foreach($profileSkills as $index => $skill)
                                    <div class="input-group mb-2 skill-entry">
                                        <input type="text" name="skills[]" class="form-control" 
                                               value="{{ is_string($skill) ? $skill : '' }}" 
                                               placeholder="ex: Auditorias HSE, Gestão de Riscos">
                                        <button type="button" class="btn btn-outline-danger" onclick="removeSkill(this)">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addSkill()">
                                <i class="fa-solid fa-plus me-2"></i>Adicionar Habilidade
                            </button>
                        </div>

                        <!-- Idiomas -->
                        <div>
                            <h6 class="fw-bold mb-3">Idiomas (Opcional)</h6>
                            <div id="languages-container">
                                @php
                                    $languagesData = old('languages', $profile->languages ?? []);
                                    if (is_string($languagesData)) {
                                        $languagesData = json_decode($languagesData, true) ?: [];
                                    }
                                    // Começar vazio - o usuário adiciona se quiser
                                    $languagesData = array_filter($languagesData, function($item) {
                                        return !empty(array_filter($item));
                                    });
                                @endphp
                                
                                @if(count($languagesData) > 0)
                                    @foreach($languagesData as $index => $lang)
                                    <div class="card border mb-3 language-entry">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Idioma</label>
                                                    <input type="text" name="languages[{{ $index }}][language]" 
                                                           class="form-control" value="{{ $lang['language'] ?? '' }}"
                                                           placeholder="ex: Português, Inglês">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Nível</label>
                                                    <select name="languages[{{ $index }}][level]" class="form-select">
                                                        <option value="">-- Selecionar --</option>
                                                        <option value="basico" @selected(($lang['level'] ?? '') === 'basico')>Básico</option>
                                                        <option value="intermedio" @selected(($lang['level'] ?? '') === 'intermedio')>Intermediário</option>
                                                        <option value="avancado" @selected(($lang['level'] ?? '') === 'avancado')>Avançado</option>
                                                        <option value="fluente" @selected(($lang['level'] ?? '') === 'fluente')>Fluente</option>
                                                        <option value="nativo" @selected(($lang['level'] ?? '') === 'nativo')>Nativo</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeEntry(this)">
                                                        <i class="fa-solid fa-trash me-2"></i>Remover
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addLanguage()">
                                <i class="fa-solid fa-plus me-2"></i>Adicionar Idioma
                            </button>
                        </div>
                    </div>

                    <!-- Tab 4: Documentos -->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <h5 class="fw-bold text-primary mb-4">Documentos *</h5>
                        <p class="text-muted mb-4"><small>Campos com * são obrigatórios</small></p>
                        
                        <div class="row g-4">
                            <!-- Upload de CV -->
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Curriculum Vitae (PDF/DOC/DOCX) *</label>
                                <div class="input-group">
                                    <input type="file" name="cv" class="form-control @error('cv') is-invalid @enderror" 
                                           accept=".pdf,.doc,.docx" id="cvUpload" {{ !$profile->cv_path ? 'required' : '' }}>
                                    <button class="btn btn-outline-primary" type="button" onclick="document.getElementById('cvUpload').click()">
                                        <i class="fa-solid fa-upload me-2"></i>Escolher Arquivo
                                    </button>
                                </div>
                                <div class="form-text">Tamanho máximo: 5MB. Formatos aceites: PDF, DOC, DOCX</div>
                                @error('cv')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                
                                @if($profile->cv_path)
                                <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25 mt-3 p-3 rounded-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-check-circle text-success fs-4 me-3"></i>
                                        <div>
                                            <div class="fw-bold mb-1">CV já carregado</div>
                                            <a href="{{ asset('storage/'.$profile->cv_path) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                <i class="fa-solid fa-eye me-2"></i>Visualizar
                                            </a>
                                            <a href="{{ asset('storage/'.$profile->cv_path) }}" download class="btn btn-sm btn-outline-primary ms-2">
                                                <i class="fa-solid fa-download me-2"></i>Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="keep_existing_cv" value="1" id="keep_existing_cv" checked>
                                    <label class="form-check-label" for="keep_existing_cv">
                                        Manter o CV atual
                                    </label>
                                </div>
                                @endif
                            </div>

                            <!-- Visibilidade do perfil -->
                            <div class="col-md-4">
                                <div class="card border-primary border-opacity-25 bg-primary bg-opacity-5 h-100">
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input type="hidden" name="is_public" value="0">
                                            <input class="form-check-input" type="checkbox" role="switch" id="is_public" 
                                                   name="is_public" value="1" @checked(old('is_public', $profile->is_public ?? true)) 
                                                   style="width: 3.5em; height: 1.8em;">
                                            <label class="form-check-label fw-bold" for="is_public">
                                                <i class="fa-solid fa-eye text-primary me-2"></i>
                                                Perfil Visível
                                            </label>
                                        </div>
                                        <p class="text-muted small mb-0">
                                            <i class="fa-solid fa-lightbulb me-1"></i>
                                            Desative para manter seu perfil privado. Apenas você poderá visualizá-lo.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botões de ação -->
            <div class="card-footer bg-light border-0 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-muted mb-1">Pronto para ser encontrado?</h6>
                        <p class="text-muted small mb-0">Seu perfil será incluído nas buscas automáticas das empresas.</p>
                    </div>
                    <div class="d-flex gap-3">
                        <button type="reset" class="btn btn-outline-secondary px-4 py-3" onclick="return confirm('Tem certeza que deseja limpar todos os campos?')">
                            <i class="fa-solid fa-rotate-left me-2"></i>Limpar
                        </button>
                        <button type="submit" class="btn btn-primary px-5 py-3 fw-bold shadow-sm" id="submitBtn">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Salvar Perfil
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Dicas -->
    <div class="card border-0 shadow-sm border-start-4 border-accent rounded-4">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="fw-bold d-flex align-items-center mb-3">
                        <i class="fa-solid fa-lightbulb text-accent fs-3 me-3"></i>
                        Dicas para um currículo atrativo
                    </h5>
                    <ul class="text-muted mb-0">
                        <li class="mb-2">Use uma foto profissional e atualizada</li>
                        <li class="mb-2">Descreva suas experiências com detalhes e resultados alcançados</li>
                        <li class="mb-2">Mantenha seu CV atualizado com certificações recentes</li>
                        <li class="mb-0">Selecione filtros precisos para receber matches relevantes</li>
                    </ul>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="badge bg-accent bg-opacity-10 text-accent border border-accent border-opacity-25 fs-6 px-4 py-3">
                        <i class="fa-solid fa-chart-line me-2"></i>
                        +80% de matches
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.nav-pills .nav-link {
    padding: 0.75rem 1.5rem;
    color: var(--bs-secondary);
    font-weight: 600;
    border-radius: 0.5rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    border: 1px solid transparent;
}

.nav-pills .nav-link.active {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
    color: var(--bs-primary);
    border: 1px solid rgba(var(--bs-primary-rgb), 0.25);
}

.form-control:focus, .form-select:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, var(--bs-primary) 0%, #0056b3 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 86, 179, 0.3);
}

.rounded-4 {
    border-radius: 1.5rem !important;
}

.text-accent {
    color: #20c997 !important;
}

.bg-accent {
    background-color: #20c997 !important;
}

.border-accent {
    border-color: #20c997 !important;
}

.form-switch .form-check-input:checked {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

#submitBtn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.optional-section {
    border-left: 4px solid #6c757d;
    padding-left: 15px;
    margin-bottom: 20px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview da imagem de perfil
    const profileImageInput = document.getElementById('profile_image');
    const profileImagePreview = document.getElementById('profileImagePreview');
    
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (profileImagePreview.tagName === 'IMG') {
                        profileImagePreview.src = e.target.result;
                    } else {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'rounded-circle border border-4 border-primary border-opacity-25';
                        img.style.width = '150px';
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';
                        img.style.cursor = 'pointer';
                        img.onclick = () => profileImageInput.click();
                        
                        profileImagePreview.parentNode.replaceChild(img, profileImagePreview);
                        img.id = 'profileImagePreview';
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Prevenir envio duplo do formulário
    const form = document.getElementById('profileForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Desabilitar botão para prevenir envio duplo
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Salvando...';
        });
    }

    // Se tem CV existente, não é obrigatório upload
    const cvInput = document.getElementById('cvUpload');
    const keepCvCheckbox = document.querySelector('input[name="keep_existing_cv"]');
    
    if (cvInput && keepCvCheckbox) {
        keepCvCheckbox.addEventListener('change', function() {
            if (this.checked) {
                cvInput.removeAttribute('required');
            } else {
                cvInput.setAttribute('required', 'required');
            }
        });
    }
});

// Contadores dinâmicos
let educationIndex = {{ isset($educationData) && is_array($educationData) ? count($educationData) : 0 }};
let certificationIndex = {{ isset($certificationsData) && is_array($certificationsData) ? count($certificationsData) : 0 }};
let experienceIndex = {{ isset($experienceData) && is_array($experienceData) ? count($experienceData) : 0 }};
let languageIndex = {{ isset($languagesData) && is_array($languagesData) ? count($languagesData) : 0 }};
let skillIndex = {{ isset($profileSkills) && is_array($profileSkills) ? count($profileSkills) : 0 }};

function addExperience() {
    const container = document.getElementById('experience-container');
    
    // Se estiver vazio, remove a mensagem de "vazio"
    if (container.querySelector('.text-center')) {
        container.innerHTML = '';
    }
    
    const template = `
        <div class="card border mb-3 experience-entry">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Empresa</label>
                        <input type="text" name="work_experience[${experienceIndex}][company]" class="form-control" placeholder="Nome da empresa">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cargo</label>
                        <input type="text" name="work_experience[${experienceIndex}][position]" class="form-control" placeholder="ex: Técnico de Segurança">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Início</label>
                        <input type="date" name="work_experience[${experienceIndex}][start_date]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Término</label>
                        <input type="date" name="work_experience[${experienceIndex}][end_date]" class="form-control" id="experience_end_${experienceIndex}">
                    </div>
                    <div class="col-md-6">
                        <div class="form-check pt-4">
                            <input class="form-check-input" type="checkbox" name="work_experience[${experienceIndex}][current]" value="1" onclick="toggleEndDate(this)">
                            <label class="form-check-label">Trabalho Atual</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Descrição das Funções</label>
                        <textarea name="work_experience[${experienceIndex}][description]" class="form-control" rows="3" placeholder="Descreva suas responsabilidades e conquistas..."></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeEntry(this)">
                            <i class="fa-solid fa-trash me-2"></i>Remover
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    experienceIndex++;
}

function addEducation() {
    const container = document.getElementById('education-container');
    
    // Se estiver vazio, remove a mensagem de "vazio"
    if (container.querySelector('.text-center')) {
        container.innerHTML = '';
    }
    
    const template = `
        <div class="card border mb-3 education-entry">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Instituição</label>
                        <input type="text" name="education[${educationIndex}][institution]" class="form-control" placeholder="Nome da instituição">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Grau/Certificado</label>
                        <input type="text" name="education[${educationIndex}][degree]" class="form-control" placeholder="ex: Licenciatura, Mestrado">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Área de Estudo</label>
                        <input type="text" name="education[${educationIndex}][field]" class="form-control" placeholder="ex: Engenharia de Segurança">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Início</label>
                        <input type="date" name="education[${educationIndex}][start_date]" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Conclusão</label>
                        <input type="date" name="education[${educationIndex}][end_date]" class="form-control" id="education_end_${educationIndex}">
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="education[${educationIndex}][current]" value="1" onclick="toggleEducationEndDate(this)">
                            <label class="form-check-label">Ainda estou a estudar</label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeEntry(this)">
                            <i class="fa-solid fa-trash me-2"></i>Remover
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    educationIndex++;
}

function addCertification() {
    const container = document.getElementById('certifications-container');
    
    // Se estiver vazio, remove a mensagem de "vazio"
    if (container.querySelector('.text-center')) {
        container.innerHTML = '';
    }
    
    const template = `
        <div class="card border mb-3 certification-entry">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome da Certificação</label>
                        <input type="text" name="certifications[${certificationIndex}][name]" class="form-control" placeholder="ex: NEBOSH, OSHA 30">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Emissor</label>
                        <input type="text" name="certifications[${certificationIndex}][issuer]" class="form-control" placeholder="Organização emissora">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Data Emissão</label>
                        <input type="date" name="certifications[${certificationIndex}][issue_date]" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Data Validade</label>
                        <input type="date" name="certifications[${certificationIndex}][expiry_date]" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeEntry(this)">
                            <i class="fa-solid fa-trash me-2"></i>Remover
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    certificationIndex++;
}

function addLanguage() {
    const container = document.getElementById('languages-container');
    
    // Se estiver vazio, remove a mensagem de "vazio"
    if (container.querySelector('.text-center')) {
        container.innerHTML = '';
    }
    
    const template = `
        <div class="card border mb-3 language-entry">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Idioma</label>
                        <input type="text" name="languages[${languageIndex}][language]" class="form-control" placeholder="ex: Português, Inglês">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nível</label>
                        <select name="languages[${languageIndex}][level]" class="form-select">
                            <option value="">-- Selecionar --</option>
                            <option value="basico">Básico</option>
                            <option value="intermedio">Intermediário</option>
                            <option value="avancado">Avançado</option>
                            <option value="fluente">Fluente</option>
                            <option value="nativo">Nativo</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeEntry(this)">
                            <i class="fa-solid fa-trash me-2"></i>Remover
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    languageIndex++;
}

function addSkill() {
    const container = document.getElementById('skills-container');
    const div = document.createElement('div');
    div.className = 'input-group mb-2 skill-entry';
    div.innerHTML = `
        <input type="text" name="skills[]" class="form-control" placeholder="ex: Auditorias HSE, Gestão de Riscos">
        <button type="button" class="btn btn-outline-danger" onclick="removeSkill(this)">
            <i class="fa-solid fa-times"></i>
        </button>
    `;
    container.appendChild(div);
    skillIndex++;
}

function removeEntry(button) {
    const entry = button.closest('.education-entry, .certification-entry, .experience-entry, .language-entry');
    const container = entry.closest('#education-container, #certifications-container, #experience-container, #languages-container');
    
    if (entry) {
        entry.remove();
        
        // Se container ficou vazio, adiciona mensagem
        if (container.children.length === 0) {
            const message = container.id === 'experience-container' ? 
                '<div class="text-center py-5 border rounded bg-light"><i class="fa-solid fa-briefcase text-muted fs-1 mb-3"></i><p class="text-muted mb-4">Nenhuma experiência adicionada</p><button type="button" class="btn btn-primary" onclick="addExperience()"><i class="fa-solid fa-plus me-2"></i>Adicionar Primeira Experiência</button></div>' :
                container.id === 'education-container' ?
                '<div class="text-center py-5 border rounded bg-light"><i class="fa-solid fa-graduation-cap text-muted fs-1 mb-3"></i><p class="text-muted mb-4">Nenhuma formação adicionada</p><button type="button" class="btn btn-primary" onclick="addEducation()"><i class="fa-solid fa-plus me-2"></i>Adicionar Primeira Formação</button></div>' :
                container.id === 'certifications-container' ?
                '<div class="text-center py-5 border rounded bg-light"><i class="fa-solid fa-certificate text-muted fs-1 mb-3"></i><p class="text-muted mb-4">Nenhuma certificação adicionada</p><button type="button" class="btn btn-primary" onclick="addCertification()"><i class="fa-solid fa-plus me-2"></i>Adicionar Primeira Certificação</button></div>' :
                '';
            
            if (message) {
                container.innerHTML = message;
            }
        }
    }
}

function removeSkill(button) {
    const skillEntry = button.closest('.skill-entry');
    if (skillEntry) {
        skillEntry.remove();
    }
}

function toggleEndDate(checkbox) {
    const card = checkbox.closest('.card');
    const endDateInput = card.querySelector('input[name$="[end_date]"]');
    if (checkbox.checked) {
        endDateInput.value = '';
        endDateInput.disabled = true;
        endDateInput.style.backgroundColor = '#f8f9fa';
    } else {
        endDateInput.disabled = false;
        endDateInput.style.backgroundColor = '';
    }
}

function toggleEducationEndDate(checkbox) {
    const card = checkbox.closest('.card');
    const endDateInput = card.querySelector('input[name$="[end_date]"]');
    if (checkbox.checked) {
        endDateInput.value = '';
        endDateInput.disabled = true;
        endDateInput.style.backgroundColor = '#f8f9fa';
    } else {
        endDateInput.disabled = false;
        endDateInput.style.backgroundColor = '';
    }
}

// Inicializar toggles para checkboxes existentes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name$="[current]"]').forEach(checkbox => {
        const card = checkbox.closest('.card');
        const endDateInput = card.querySelector('input[name$="[end_date]"]');
        
        if (checkbox.checked && endDateInput) {
            endDateInput.disabled = true;
            endDateInput.style.backgroundColor = '#f8f9fa';
        }
    });
});
</script>
@endsection