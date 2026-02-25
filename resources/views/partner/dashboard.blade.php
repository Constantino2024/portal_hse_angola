@extends('layouts.partner')

@section('title', 'Dashboard · Parceiro')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
        <div>
            <h1 class="section-title mb-2">Dashboard</h1>
            <div class="text-muted">
                <i class="fa-solid fa-circle-info me-1"></i>
                Resumo das tuas publicações (vagas, ESG e projectos)
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-light btn-sm d-flex align-items-center">
                <i class="fa-solid fa-calendar-days me-2"></i>
                <span>{{ now()->format('d/m/Y') }}</span>
            </button>
            <button class="btn btn-primary btn-sm d-flex align-items-center">
                <i class="fa-solid fa-plus me-2"></i>
                <span>Novo Conteúdo</span>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-md-4">
            <div class="card stats-card border-0 shadow-lg text-white animate__animated animate__fadeInUp">
                <div class="card-body d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="small opacity-90 fw-semibold">Vagas Publicadas</div>
                            <div class="badge bg-white text-primary-dark rounded-pill">{{ $jobsActiveCount }} ativas</div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between">
                            <div class="fs-1 fw-bold">{{ $jobsCount }}</div>
                            <div class="icon-wrapper">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between small mb-2">
                                <span>Progresso</span>
                                <span>{{ $jobsCount > 0 ? round(($jobsActiveCount / $jobsCount * 100), 1) : 0 }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $jobsCount > 0 ? ($jobsActiveCount / $jobsCount * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('partner.jobs.index') }}" class="btn btn-light btn-sm w-100 d-flex align-items-center justify-content-center">
                            <span>Gerir Vagas</span>
                            <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card stats-card border-0 shadow-lg text-white animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <div class="card-body d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="small opacity-90 fw-semibold">Iniciativas ESG</div>
                            <div class="badge bg-white text-accent-orange rounded-pill">{{ $esgActiveCount }} ativas</div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between">
                            <div class="fs-1 fw-bold">{{ $esgCount }}</div>
                            <div class="icon-wrapper">
                                <i class="fa-solid fa-leaf"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between small mb-2">
                                <span>Progresso</span>
                                <span>{{ $esgCount > 0 ? round(($esgActiveCount / $esgCount * 100), 1) : 0 }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $esgCount > 0 ? ($esgActiveCount / $esgCount * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('partner.esg.index') }}" class="btn btn-light btn-sm w-100 d-flex align-items-center justify-content-center">
                            <span>Gerir ESG</span>
                            <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card stats-card border-0 shadow-lg text-white animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <div class="card-body d-flex flex-column justify-content-between h-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="small opacity-90 fw-semibold">Projectos</div>
                            <div class="badge bg-white text-primary-dark rounded-pill">{{ $projectsActiveCount }} ativos</div>
                        </div>
                        <div class="d-flex align-items-end justify-content-between">
                            <div class="fs-1 fw-bold">{{ $projectsCount }}</div>
                            <div class="icon-wrapper">
                                <i class="fa-solid fa-diagram-project"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between small mb-2">
                                <span>Progresso</span>
                                <span>{{ $projectsCount > 0 ? round(($projectsActiveCount / $projectsCount * 100), 1) : 0 }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $projectsCount > 0 ? ($projectsActiveCount / $projectsCount * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('partner.projects.index') }}" class="btn btn-light btn-sm w-100 d-flex align-items-center justify-content-center">
                            <span>Gerir Projectos</span>
                            <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Ações Rápidas</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('partner.jobs.create') }}" class="btn btn-outline-primary w-100 d-flex flex-column align-items-center justify-content-center p-3 rounded-lg">
                                <i class="fa-solid fa-plus-circle fs-2 mb-2"></i>
                                <span class="fw-semibold">Nova Vaga</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('partner.esg.create') }}" class="btn btn-outline-accent-orange w-100 d-flex flex-column align-items-center justify-content-center p-3 rounded-lg">
                                <i class="fa-solid fa-leaf fs-2 mb-2"></i>
                                <span class="fw-semibold">Nova ESG</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('partner.projects.create') }}" class="btn btn-outline-primary w-100 d-flex flex-column align-items-center justify-content-center p-3 rounded-lg">
                                <i class="fa-solid fa-diagram-project fs-2 mb-2"></i>
                                <span class="fw-semibold">Novo Projecto</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('partner.talents.index') }}" class="btn btn-outline-accent-orange w-100 d-flex flex-column align-items-center justify-content-center p-3 rounded-lg">
                                <i class="fa-solid fa-users fs-2 mb-2"></i>
                                <span class="fw-semibold">Ver Talentos</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-outline-accent-orange {
        border-color: var(--accent-orange);
        color: var(--accent-orange);
    }
    
    .btn-outline-accent-orange:hover {
        background: var(--accent-orange);
        border-color: var(--accent-orange);
        color: white;
    }
    
    .badge.text-accent-orange {
        color: var(--accent-orange) !important;
    }
    
    .badge.text-primary-dark {
        color: var(--primary-dark) !important;
    }
</style>
@endsection