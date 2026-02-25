@extends('layouts.app')

@section('title', 'Banco de Talentos HSE')

@section('content')
<div class="container py-5 mt-8">
    <div class="row align-items-center g-5">
        <!-- Hero Section -->
        <div class="col-lg-7">
            <div class="mb-4">
                <span class="badge bg-primary bg-opacity-10 text-primary fw-semibold px-3 py-2 mb-3 d-inline-flex align-items-center">
                    <i class="fa-solid fa-briefcase me-2"></i>Recrutamento Inteligente
                </span>
                <h1 class="display-5 fw-bold mb-3">Conectamos <span class="text-primary">Talentos</span> a <span class="text-accent">Oportunidades</span> em HSE</h1>
                <p class="lead text-secondary mb-4 fs-5">Uma plataforma onde profissionais de HSE criam perfis detalhados e empresas encontram os candidatos ideais através de um sistema inteligente de matching.</p>
            </div>

            <!-- Stats -->
            <div class="row g-3 mb-5">
                <div class="col-md-4">
                    <div class="d-flex align-items-center p-3 rounded-3 border border-opacity-25 bg-light">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="fa-solid fa-user-pen text-primary fs-5"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Perfil</h4>
                            <small class="text-muted">Detalhado</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center p-3 rounded-3 border border-opacity-25 bg-light">
                        <div class="bg-accent bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="fa-solid fa-bolt text-white fs-5"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Match</h4>
                            <small class="text-muted">Automático</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex align-items-center p-3 rounded-3 border border-opacity-25 bg-light">
                        <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="fa-solid fa-handshake text-success fs-5"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0">Conexão</h4>
                            <small class="text-muted">Direta</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="d-flex flex-column flex-sm-row gap-3 mb-5">
                @auth
                    @php
                        $hasProfile = \App\Models\HseTalentProfile::where('user_id', auth()->id())->exists();
                    @endphp
                    <a href="{{ $hasProfile ? route('talent.profile.show') : route('talent.profile.edit') }}" class="btn btn-primary btn-lg px-4 py-3 shadow-sm d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-user-pen me-3 fs-5"></i>
                        <div class="text-start">
                            <div class="fw-bold">{{ $hasProfile ? 'Ver Meu Perfil' : 'Criar Perfil' }}</div>
                            <small class="opacity-75">{{ $hasProfile ? 'Visualizar e editar' : 'Candidate-se gratuitamente' }}</small>
                        </div>
                    </a>
                @else
                    <a href="{{ route('talent.profile.edit') }}" class="btn btn-primary btn-lg px-4 py-3 shadow-sm d-flex align-items-center justify-content-center">
                        <i class="fa-solid fa-user-pen me-3 fs-5"></i>
                        <div class="text-start">
                            <div class="fw-bold">Criar Perfil</div>
                            <small class="opacity-75">Candidate-se gratuitamente</small>
                        </div>
                    </a>
                @endauth
                <a href="{{ route('partner.talents.index') }}" class="btn btn-outline-primary btn-lg px-4 py-3 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-building me-3 fs-5"></i>
                    <div class="text-start">
                        <div class="fw-bold">Área Empresas</div>
                        <small class="opacity-75">Encontre talentos</small>
                    </div>
                </a>
            </div>

            <!-- Tags -->
            <div class="mb-5">
                <h6 class="fw-semibold mb-3 text-muted">Filtros disponíveis:</h6>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark border py-2 px-3 d-flex align-items-center">
                        <i class="fa-solid fa-chart-line text-primary me-2"></i>
                        <span>Nível: Júnior · Pleno · Sénior</span>
                    </span>
                    <span class="badge bg-light text-dark border py-2 px-3 d-flex align-items-center">
                        <i class="fa-solid fa-helmet-safety text-accent me-2"></i>
                        <span>Áreas: HST · Ambiente · ESG · QHSE</span>
                    </span>
                    <span class="badge bg-light text-dark border py-2 px-3 d-flex align-items-center">
                        <i class="fa-solid fa-clock text-success me-2"></i>
                        <span>Disponibilidade: Obra · Projecto · Permanente</span>
                    </span>
                </div>
            </div>

            <!-- Alert -->
            <div class="alert alert-primary shadow-lg border-primary border-opacity-25 bg-primary bg-opacity-5 py-3 px-4 rounded-3 d-flex align-items-start">
                <i class="fa-solid fa-crown text-white mt-1 me-3 fs-5"></i>
                <div>
                    <h6 class="fw-bold mb-1 text-white">Potencial de Evolução</h6>
                    <p class="mb-0 small text-white">Este módulo pode evoluir para um serviço premium com desbloqueio de CV completo, contacto direto e matches ilimitados para empresas.</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Cards -->
        <div class="col-lg-5">
            <!-- How It Works -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-primary bg-opacity-10 border-0 py-4">
                    <h4 class="fw-bold mb-0 d-flex align-items-center">
                        <i class="fa-solid fa-wand-magic-sparkles text-primary me-3"></i>
                        Como funciona o match
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">1</div>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-bold mb-1">Perfil Completo</h6>
                            <p class="text-muted mb-0">Profissionais criam perfil detalhado e anexam CV.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">2</div>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-bold mb-1">Publicação de Vagas</h6>
                            <p class="text-muted mb-0">Empresas publicam necessidades específicas.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">3</div>
                        </div>
                        <div class="ms-3">
                            <h6 class="fw-bold mb-1">Sugestões Automáticas</h6>
                            <p class="text-muted mb-0">O sistema sugere perfis compatíveis instantaneamente.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="card border-0 shadow-sm rounded-4 border border-opacity-25">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 d-flex align-items-center">
                        <i class="fa-solid fa-filter text-accent me-3"></i>
                        Filtros Inteligentes
                    </h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 rounded-3 bg-light border">
                                <i class="fa-solid fa-chart-line text-primary fs-4 mb-2"></i>
                                <h6 class="fw-bold mb-1">Nível</h6>
                                <small class="text-muted">Experiência</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 rounded-3 bg-light border">
                                <i class="fa-solid fa-helmet-safety text-accent fs-4 mb-2"></i>
                                <h6 class="fw-bold mb-1">Área</h6>
                                <small class="text-muted">Especialização</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 rounded-3 bg-light border">
                                <i class="fa-solid fa-clock text-success fs-4 mb-2"></i>
                                <h6 class="fw-bold mb-1">Disponibilidade</h6>
                                <small class="text-muted">Tempo</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 rounded-3 bg-light border">
                                <i class="fa-solid fa-map-marker-alt text-warning fs-4 mb-2"></i>
                                <h6 class="fw-bold mb-1">Província</h6>
                                <small class="text-muted">Localização</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.btn-primary {
    background: linear-gradient(135deg, var(--bs-primary) 0%, #0056b3 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 86, 179, 0.3);
}

.btn-outline-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 10px rgba(0, 86, 179, 0.1);
}

.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.badge {
    transition: all 0.2s ease;
}

.badge:hover {
    transform: scale(1.05);
}

.rounded-3 {
    border-radius: 1rem !important;
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
</style>