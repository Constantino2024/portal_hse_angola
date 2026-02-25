@extends('layouts.app')

@section('title', 'Meu Perfil HSE · Banco de Talentos')

@section('content')
<div class="container py-5 mt-8">
    @if(!$profile)
        <!-- Caso não tenha perfil -->
        <div class="text-center py-5">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center p-5 mb-4">
                <i class="fa-solid fa-user-plus text-primary fs-1"></i>
            </div>
            <h1 class="display-6 fw-bold mb-3">Você ainda não tem um perfil</h1>
            <p class="lead text-muted mb-4">Crie seu currículo HSE para ser encontrado por empresas</p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="{{ route('talent.profile.edit') }}" class="btn btn-primary btn-lg px-5 py-3">
                    <i class="fa-solid fa-plus me-2"></i>Criar Perfil
                </a>
                <a href="{{ route('talent.index') }}" class="btn btn-outline-primary btn-lg px-5 py-3">
                    <i class="fa-solid fa-arrow-left me-2"></i>Voltar ao Início
                </a>
            </div>
        </div>
    @else
        <!-- Header com botão de editar -->
        <div class="row align-items-center mb-5">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <div class="position-relative me-4">
                        @if($profile->profile_image)
                            <img src="{{ asset('storage/'.$profile->profile_image) }}" 
                                 alt="Foto de perfil" 
                                 class="rounded-circle border border-4 border-primary border-opacity-25"
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle border border-4 border-primary border-opacity-25 d-flex align-items-center justify-content-center bg-primary bg-opacity-10"
                                 style="width: 120px; height: 120px;">
                                <i class="fa-solid fa-user text-primary fs-1"></i>
                            </div>
                        @endif
                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-3 border-white"
                              style="width: 20px; height: 20px;"></span>
                    </div>
                    <div>
                        <h1 class="display-6 fw-bold mb-1">{{ $profile->full_name ?? auth()->user()->name ?? 'Nome não definido' }}</h1>
                        <p class="lead text-primary mb-2">{{ $profile->headline ?? 'Profissional de HSE' }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            @if($profile->level && isset(\App\Http\Controllers\TalentBankController::levels()[$profile->level]))
                            <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3">
                                <i class="fa-solid fa-chart-line me-2"></i>
                                {{ \App\Http\Controllers\TalentBankController::levels()[$profile->level] }}
                            </span>
                            @endif
                            
                            @if($profile->area && isset(\App\Http\Controllers\TalentBankController::areas()[$profile->area]))
                            <span class="badge bg-accent bg-opacity-10 text-white py-2 px-3">
                                <i class="fa-solid fa-helmet-safety me-2"></i>
                                {{ \App\Http\Controllers\TalentBankController::areas()[$profile->area] }}
                            </span>
                            @endif
                            
                            @if($profile->availability && isset(\App\Http\Controllers\TalentBankController::availabilities()[$profile->availability]))
                            <span class="badge bg-success bg-opacity-10 text-success py-2 px-3">
                                <i class="fa-solid fa-clock me-2"></i>
                                {{ \App\Http\Controllers\TalentBankController::availabilities()[$profile->availability] }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="{{ route('talent.profile.edit') }}" class="btn btn-primary px-4 py-3 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-pen-to-square me-3 fs-5"></i>
                        <div class="text-start">
                            <div class="fw-bold">Editar Perfil</div>
                        </div>
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary px-4 py-3 d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-arrow-left me-3 fs-5"></i>
                        <div class="text-start">
                            <div class="fw-bold">Voltar</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Status do perfil -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-3 text-center">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fa-solid fa-eye text-primary fs-2"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Visibilidade</h6>
                                <span class="badge bg-{{ $profile->is_public ? 'success' : 'secondary' }}">
                                    {{ $profile->is_public ? 'Público' : 'Privado' }}
                                </span>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="bg-accent bg-opacity-10 p-3 rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fa-solid fa-briefcase text-white fs-2"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Experiência</h6>
                                <p class="mb-0">{{ $profile->years_experience ?? 0 }} anos</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fa-solid fa-map-marker-alt text-warning fs-2"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Localização</h6>
                                <p class="mb-0">{{ $profile->province ?? 'Não definida' }}</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="bg-info bg-opacity-10 p-3 rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fa-solid fa-calendar-check text-info fs-2"></i>
                                </div>
                                <h6 class="fw-bold mb-1">Disponível</h6>
                                <p class="mb-0">
                                    @if($profile->availability && isset(\App\Http\Controllers\TalentBankController::availabilities()[$profile->availability]))
                                        {{ \App\Http\Controllers\TalentBankController::availabilities()[$profile->availability] }}
                                    @else
                                        Não definido
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteúdo do perfil em tabs -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs bg-light border-0" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                <i class="fa-solid fa-user me-2"></i>Visão Geral
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="experience-tab" data-bs-toggle="tab" data-bs-target="#experience" type="button" role="tab">
                                <i class="fa-solid fa-briefcase me-2"></i>Experiência
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="education-tab" data-bs-toggle="tab" data-bs-target="#education" type="button" role="tab">
                                <i class="fa-solid fa-graduation-cap me-2"></i>Formação
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="skills-tab" data-bs-toggle="tab" data-bs-target="#skills" type="button" role="tab">
                                <i class="fa-solid fa-star me-2"></i>Competências
                            </button>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content p-4 p-lg-5" id="profileTabContent">
                        <!-- Tab 1: Visão Geral -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h4 class="fw-bold mb-4">Sobre Mim</h4>
                                    <p class="text-muted mb-5">{{ $profile->bio ?? 'Nenhuma biografia fornecida.' }}</p>

                                    <h5 class="fw-bold mb-3">Informações Pessoais</h5>
                                    <div class="row g-3 mb-5">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center p-3 rounded-3 border border-opacity-25 bg-light">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                                    <i class="fa-solid fa-phone text-primary"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Telefone</small>
                                                    <strong>{{ $profile->phone ?? 'Não definido' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center p-3 rounded-3 border border-opacity-25 bg-light">
                                                <div class="bg-accent bg-opacity-10 p-2 rounded-circle me-3">
                                                    <i class="fa-solid fa-envelope text-white"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Email</small>
                                                    <strong>{{ $profile->email ?? auth()->user()->email ?? 'Não definido' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center p-3 rounded-3 border border-opacity-25 bg-light">
                                                <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                                    <i class="fa-solid fa-cake-candles text-success"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Idade</small>
                                                    <strong>
                                                        @if($profile->birth_date)
                                                            {{ \Carbon\Carbon::parse($profile->birth_date)->age }} anos
                                                        @else
                                                            Não definida
                                                        @endif
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center p-3 rounded-3 border border-opacity-25 bg-light">
                                                <div class="bg-warning bg-opacity-10 p-2 rounded-circle me-3">
                                                    <i class="fa-solid fa-location-dot text-warning"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Localização</small>
                                                    <strong>{{ $profile->province ?? 'Não definida' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($profile->cv_path)
                                    <div class="mt-4">
                                        <h5 class="fw-bold mb-3">Curriculum Vitae</h5>
                                        <div class="alert alert-primary border-primary border-opacity-25 bg-primary bg-opacity-5 p-3 rounded-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fa-solid fa-file-pdf text-primary fs-3 me-3"></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">CV disponível para download</h6>
                                                    <p class="mb-0 small">Os recrutadores podem visualizar seu currículo completo</p>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ asset('storage/'.$profile->cv_path) }}" target="_blank" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fa-solid fa-eye me-2"></i>Visualizar
                                                    </a>
                                                    <a href="{{ asset('storage/'.$profile->cv_path) }}" download 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fa-solid fa-download me-2"></i>Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="col-lg-4">
                                    <div class="card border-primary border-opacity-25 bg-primary bg-opacity-5">
                                        <div class="card-body">
                                            <h5 class="fw-bold mb-4">Pré-requisitos para Match</h5>
                                            
                                            @if($profile->preferred_location)
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                                    <i class="fa-solid fa-map-pin text-primary"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Localização preferida</small>
                                                    <strong>{{ $profile->preferred_location }}</strong>
                                                </div>
                                            </div>
                                            @endif

                                            @if($profile->expected_salary)
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-accent bg-opacity-10 p-2 rounded-circle me-3">
                                                    <i class="fa-solid fa-money-bill-wave text-whitte"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Pretensão salarial</small>
                                                    <strong>{{ number_format($profile->expected_salary, 2, ',', '.') }} AKZ</strong>
                                                </div>
                                            </div>
                                            @endif

                                            @if($profile->drivers_license)
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                                    <i class="fa-solid fa-car text-success"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block">Carta de condução</small>
                                                    <strong>{{ $profile->drivers_license }}</strong>
                                                </div>
                                            </div>
                                            @endif

                                            @if(!empty($profile->preferred_company_types) && is_array($profile->preferred_company_types) && count($profile->preferred_company_types) > 0)
                                            <div class="mt-4">
                                                <h6 class="fw-bold mb-2">Tipos de empresa preferidos</h6>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($profile->preferred_company_types as $type)
                                                    <span class="badge bg-light text-dark border py-1 px-3">
                                                        {{ $type }}
                                                    </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Experiência -->
                        <div class="tab-pane fade" id="experience" role="tabpanel">
                            <h4 class="fw-bold mb-4">Experiência Profissional</h4>
                            
                            @if(!empty($profile->work_experience) && is_array($profile->work_experience) && count($profile->work_experience) > 0)
                                @foreach($profile->work_experience as $exp)
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <h5 class="fw-bold mb-1">{{ $exp['position'] ?? 'Cargo não especificado' }}</h5>
                                                <p class="text-primary mb-2">
                                                    <i class="fa-solid fa-building me-2"></i>
                                                    {{ $exp['company'] ?? 'Empresa não especificada' }}
                                                </p>
                                                <p class="text-muted mb-3">{{ $exp['description'] ?? '' }}</p>
                                            </div>
                                            <div class="col-md-3 text-md-end">
                                                <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3">
                                                    @if($exp['current'] ?? false)
                                                        Atual
                                                    @else
                                                        @php
                                                            $startYear = isset($exp['start_date']) ? date('Y', strtotime($exp['start_date'])) : '';
                                                            $endYear = isset($exp['end_date']) ? date('Y', strtotime($exp['end_date'])) : '';
                                                        @endphp
                                                        {{ $startYear }} @if($endYear)- {{ $endYear }}@endif
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center p-4 mb-3">
                                        <i class="fa-solid fa-briefcase text-muted fs-1"></i>
                                    </div>
                                    <h5 class="fw-bold text-muted mb-2">Nenhuma experiência registada</h5>
                                    <p class="text-muted">Adicione sua experiência profissional para melhorar seu perfil</p>
                                    <a href="{{ route('talent.profile.edit') }}" class="btn btn-primary">
                                        <i class="fa-solid fa-plus me-2"></i>Adicionar Experiência
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Tab 3: Formação -->
                        <div class="tab-pane fade" id="education" role="tabpanel">
                            <h4 class="fw-bold mb-4">Formação Académica & Certificações</h4>
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5 class="fw-bold mb-3">Formação</h5>
                                    @if(!empty($profile->education) && is_array($profile->education) && count($profile->education) > 0)
                                        @foreach($profile->education as $edu)
                                        <div class="card border-0 shadow-sm mb-3">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-1">{{ $edu['degree'] ?? 'Grau não especificado' }}</h6>
                                                <p class="text-primary mb-2">
                                                    <i class="fa-solid fa-school me-2"></i>
                                                    {{ $edu['institution'] ?? 'Instituição não especificada' }}
                                                </p>
                                                @if(!empty($edu['field']))
                                                <p class="text-muted mb-2">{{ $edu['field'] }}</p>
                                                @endif
                                                <small class="text-muted">
                                                    @php
                                                        $startYear = isset($edu['start_date']) ? date('Y', strtotime($edu['start_date'])) : '';
                                                        $endYear = isset($edu['end_date']) ? date('Y', strtotime($edu['end_date'])) : '';
                                                    @endphp
                                                    {{ $startYear }} - 
                                                    @if($edu['current'] ?? false)
                                                        Atual
                                                    @else
                                                        {{ $endYear }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">Nenhuma formação registada</p>
                                    @endif
                                </div>

                                <div class="col-lg-6">
                                    <h5 class="fw-bold mb-3">Certificações</h5>
                                    @if(!empty($profile->certifications) && is_array($profile->certifications) && count($profile->certifications) > 0)
                                        @foreach($profile->certifications as $cert)
                                        <div class="card border-0 shadow-sm mb-3">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-1">{{ $cert['name'] ?? 'Certificação não especificada' }}</h6>
                                                <p class="text-primary mb-2">
                                                    <i class="fa-solid fa-award me-2"></i>
                                                    {{ $cert['issuer'] ?? 'Emissor não especificado' }}
                                                </p>
                                                @if(!empty($cert['issue_date']))
                                                <small class="text-muted">
                                                    Emitido em: {{ date('d/m/Y', strtotime($cert['issue_date'])) }}
                                                    @if(!empty($cert['expiry_date']))
                                                        | Válido até: {{ date('d/m/Y', strtotime($cert['expiry_date'])) }}
                                                    @endif
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">Nenhuma certificação registada</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tab 4: Competências -->
                        <div class="tab-pane fade" id="skills" role="tabpanel">
                            <h4 class="fw-bold mb-4">Competências & Idiomas</h4>
                            
                            <div class="row">
                                <div class="col-lg-8">
                                    <h5 class="fw-bold mb-3">Competências Técnicas</h5>
                                    @if(!empty($profile->skills) && is_array($profile->skills) && count($profile->skills) > 0)
                                        <div class="d-flex flex-wrap gap-3 mb-5">
                                            @foreach($profile->skills as $skill)
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 py-3 px-4">
                                                <i class="fa-solid fa-check-circle me-2"></i>
                                                {{ $skill }}
                                            </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">Nenhuma competência registada</p>
                                    @endif

                                    <h5 class="fw-bold mb-3">Áreas de Preferência</h5>
                                    @if(!empty($profile->preferred_areas) && is_array($profile->preferred_areas) && count($profile->preferred_areas) > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($profile->preferred_areas as $area)
                                            <span class="badge bg-accent bg-opacity-10 text-white border border-accent border-opacity-25 py-2 px-3">
                                                {{ $area }}
                                            </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">Nenhuma área de preferência definida</p>
                                    @endif
                                </div>

                                <div class="col-lg-4">
                                    <h5 class="fw-bold mb-3">Idiomas</h5>
                                    @if(!empty($profile->languages) && is_array($profile->languages) && count($profile->languages) > 0)
                                        <div class="card border-primary border-opacity-25">
                                            <div class="card-body">
                                                @foreach($profile->languages as $lang)
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <h6 class="fw-bold mb-0">{{ $lang['language'] ?? 'Idioma não especificado' }}</h6>
                                                        <small class="text-muted">{{ ucfirst($lang['level'] ?? 'não definido') }}</small>
                                                    </div>
                                                    <div class="d-flex">
                                                        @php
                                                            $levels = ['basico' => 1, 'intermedio' => 2, 'avancado' => 3, 'fluente' => 4, 'nativo' => 5];
                                                            $currentLevel = $levels[$lang['level'] ?? 'basico'] ?? 0;
                                                        @endphp
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <div class="mx-1">
                                                                <i class="fa-solid fa-circle{{ $i <= $currentLevel ? ' text-primary' : ' text-light' }}"></i>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">Nenhum idioma registado</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aviso de perfil incompleto -->
        @php
            $completionScore = 0;
            $totalFields = 15;
            $filledFields = 0;
            
            $fields = ['full_name', 'email', 'phone', 'level', 'area', 'availability', 'province', 
                      'headline', 'bio', 'cv_path', 'years_experience', 'current_position', 
                      'skills', 'work_experience', 'education'];
            
            foreach($fields as $field) {
                if (!empty($profile->$field)) $filledFields++;
            }
            
            $completionScore = $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;
        @endphp

        @if($completionScore < 70)
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-warning border-warning border-opacity-25 bg-warning bg-opacity-10 p-4 rounded-4">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-exclamation-triangle text-warning fs-3 me-3"></i>
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">Perfil incompleto ({{ $completionScore }}%)</h5>
                            <p class="mb-0">Complete seu perfil para aumentar suas chances de ser encontrado por recrutadores.</p>
                        </div>
                        <a href="{{ route('talent.profile.edit') }}" class="btn btn-warning">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Completar Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
</div>

<style>
.nav-tabs .nav-link {
    border: none;
    padding: 1rem 1.5rem;
    color: var(--bs-secondary);
    font-weight: 600;
    border-bottom: 3px solid transparent;
}

.nav-tabs .nav-link.active {
    color: var(--bs-primary);
    background: transparent;
    border-bottom: 3px solid var(--bs-primary);
}

.nav-tabs .nav-link:hover {
    color: var(--bs-primary);
    border-bottom: 3px solid var(--bs-primary);
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

.profile-image-container {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid rgba(var(--bs-primary-rgb), 0.25);
}

.profile-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.badge {
    font-size: 0.9rem;
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips do Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection