@extends('layouts.admin')

@section('title', 'Detalhes do Utilizador - ' . $user->display_name)

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="section-title">Detalhes do Utilizador</h1>
            <p class="text-muted">Informações detalhadas e atividades do utilizador</p>
        </div>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary me-2">
                <i class="fa-solid fa-pen me-2"></i>Editar
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Perfil do Utilizador --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-4">
                        @if($user->profile_image)
                            <img src="{{ $user->profile_image }}" 
                                 alt="Profile" 
                                 class="rounded-circle" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 120px; height: 120px;">
                                <span class="display-4 fw-bold text-primary">
                                    {{ substr($user->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <span class="position-absolute bottom-0 end-0 p-2">
                            <span class="badge rounded-pill bg-{{ $user->is_active ? 'success' : 'secondary' }} p-2">
                                <i class="fa-solid fa-circle fs-10"></i>
                            </span>
                        </span>
                    </div>

                    <h4 class="mb-1">{{ $user->display_name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <span class="badge bg-{{ 
                        $user->role === 'admin' ? 'danger' : 
                        ($user->role === 'empresa' ? 'primary' : 'success') 
                    }} px-4 py-2 mb-3">
                        <i class="fa-solid fa-{{ 
                            $user->role === 'admin' ? 'crown' : 
                            ($user->role === 'empresa' ? 'building' : 'user-tie') 
                        }} me-2"></i>
                        {{ ucfirst($user->role) }}
                    </span>

                    <hr>

                    <div class="text-start">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Telefone</small>
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-phone text-primary me-3" style="width: 20px;"></i>
                                <span>{{ $user->phone ?? 'Não fornecido' }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Registado em</small>
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-calendar text-primary me-3" style="width: 20px;"></i>
                                <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Última atualização</small>
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-rotate text-primary me-3" style="width: 20px;"></i>
                                <span>{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($user->id !== auth()->id())
                        <hr>
                        <div class="d-grid gap-2">
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-{{ $user->is_active ? 'warning' : 'success' }} w-100">
                                    <i class="fa-solid fa-{{ $user->is_active ? 'ban' : 'check-circle' }} me-2"></i>
                                    {{ $user->is_active ? 'Desativar Utilizador' : 'Ativar Utilizador' }}
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.users.destroy', $user) }}" 
                                method="POST" 
                                onsubmit="return confirm('Tem certeza que deseja eliminar este utilizador? Esta ação é irreversível.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fa-solid fa-trash me-2"></i>
                                    Eliminar Utilizador
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Atividades e Detalhes --}}
        <div class="col-lg-8">
            {{-- Estatísticas Rápidas --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary bg-opacity-10 border-0">
                        <div class="card-body text-center">
                            <h3 class="mb-2">{{ $user->trabalhos->count() }}</h3>
                            <small class="text-muted">Trabalhos Publicados</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success bg-opacity-10 border-0">
                        <div class="card-body text-center">
                            <h3 class="mb-2">{{ $user->companyProjects->count() + $user->esgInitiatives->count() }}</h3>
                            <small class="text-muted">Projetos/Iniciativas</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info bg-opacity-10 border-0">
                        <div class="card-body text-center">
                            <h3 class="mb-2">{{ $user->jobPosts->count() + $user->companyNeeds->count() }}</h3>
                            <small class="text-muted">Vagas/Necessidades</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Perfil da Empresa (se aplicável) --}}
            @if($user->isCompany && $user->company)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-building me-2 text-primary"></i>
                        Perfil da Empresa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nome da Empresa:</strong> {{ $user->company->company_name }}</p>
                            <p><strong>NIF:</strong> {{ $user->company->nif ?? 'Não fornecido' }}</p>
                            <p><strong>Setor:</strong> {{ $user->company->sector ?? 'Não especificado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Localização:</strong> {{ $user->company->location ?? 'Não fornecida' }}</p>
                            <p><strong>Website:</strong> 
                                @if($user->company->website)
                                    <a href="{{ $user->company->website }}" target="_blank">{{ $user->company->website }}</a>
                                @else
                                    Não fornecido
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Perfil Profissional (se aplicável) --}}
            @if($user->isProfessional && $user->talentProfile)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-user-tie me-2 text-primary"></i>
                        Perfil Profissional
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Área de Especialização:</strong> {{ $user->talentProfile->specialization ?? 'Não especificada' }}</p>
                            <p><strong>Experiência:</strong> {{ $user->talentProfile->experience_years ?? 0 }} anos</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Localização:</strong> {{ $user->talentProfile->location ?? 'Não fornecida' }}</p>
                            <p><strong>Disponibilidade:</strong> {{ $user->talentProfile->availability ?? 'Não especificada' }}</p>
                        </div>
                    </div>
                    @if($user->talentProfile->bio)
                        <hr>
                        <p><strong>Bio:</strong></p>
                        <p class="text-muted">{{ $user->talentProfile->bio }}</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Últimos Trabalhos --}}
            @if($user->trabalhos->isNotEmpty())
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fa-solid fa-newspaper me-2 text-primary"></i>
                        Últimos Trabalhos Publicados
                    </h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($user->trabalhos as $trabalho)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $trabalho->title }}</h6>
                                <small class="text-muted">
                                    <i class="fa-regular fa-calendar me-1"></i>
                                    {{ $trabalho->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                            <span class="badge bg-{{ $trabalho->status === 'published' ? 'success' : 'secondary' }}">
                                {{ $trabalho->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection