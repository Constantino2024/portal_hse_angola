@extends('layouts.app')

@section('title', $item->title)

@section('content')
<div class="container py-5 mt-9">
    <!-- Breadcrumb e Voltar -->
    <div class="row mb-5">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('agenda.index') }}" class="text-decoration-none d-flex align-items-center">
                            <i class="fa-solid fa-chevron-left me-2 fs-6"></i>
                            <i class="fa-solid fa-calendar-alt me-1"></i>Agenda
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-truncate" aria-current="page" style="max-width: 300px;">
                        {{ Str::limit($item->title, 50) }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row g-5">
        <!-- Conteúdo Principal -->
        <div class="col-lg-8">
            <!-- Card de Cabeçalho -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5 position-relative">
                <div class="card-header position-relative overflow-hidden" style="min-height: 300px;">
                    <!-- Overlay gradiente -->
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient-dark"></div>
                    
                    <!-- Imagem de fundo -->
                    <div class="position-absolute top-0 start-0 w-100 h-100">
                        <img src="{{ asset('storage/'.$item->image_path) }}" 
                             alt="{{ $item->title }}" 
                             class="w-100 h-100 object-fit-cover"
                             style="filter: brightness(0.7);">
                    </div>
                    
                    <!-- Conteúdo do cabeçalho -->
                    <div class="position-relative z-2 p-4 p-lg-5 text-white d-flex flex-column h-100">
                        <!-- Badges flutuantes -->
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="badge bg-white text-dark px-3 py-2 rounded-3 fw-semibold shadow-sm">
                                {{ ucfirst(str_replace('_',' ', $item->type)) }}
                            </span>
                            @if($item->is_online)
                                <span class="badge bg-light text-dark px-3 py-2 rounded-3 fw-semibold shadow-sm">
                                    <i class="fa-solid fa-video me-1"></i>Online
                                </span>
                            @endif
                            @if(!$item->is_active)
                                <span class="badge bg-dark bg-opacity-75 px-3 py-2 rounded-3 fw-semibold shadow-sm">
                                    <i class="fa-solid fa-eye-slash me-1"></i>Inativo
                                </span>
                            @endif
                        </div>
                        
                        <!-- Título -->
                        <h1 class="display-6 fw-bold mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                            {{ $item->title }}
                        </h1>
                        
                        <!-- Data principal -->
                        @if($item->starts_at)
                        <div class="mt-auto">
                            <div class="d-flex align-items-center">
                                <div class="bg-white bg-opacity-25 rounded-3 p-3 me-3">
                                    <i class="fa-regular fa-calendar fa-xl"></i>
                                </div>
                                <div>
                                    <p class="h5 mb-1">{{ $item->starts_at?->format('d') }}</p>
                                    <p class="mb-0 text-white-75">{{ $item->starts_at?->format('M Y') }}</p>
                                </div>
                                <div class="ms-4">
                                    <p class="h5 mb-1">{{ $item->starts_at?->format('H:i') }}</p>
                                    <p class="mb-0 text-white-75">Horário de início</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Aviso de esgotado -->
                @if($item->capacity !== null && ($item->available_spots ?? 0) <= 0)
                <div class="position-absolute top-4 end-4">
                    <span class="badge bg-danger px-3 py-2 rounded-3 fw-bold shadow-lg">
                        <i class="fa-solid fa-fire me-1"></i>Esgotado
                    </span>
                </div>
                @endif
            </div>

            <!-- Informações do Evento -->
            <div class="card border-0 shadow-sm rounded-4 mb-5">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="h4 text-dark mb-4 pb-3 border-bottom">
                        <i class="fa-solid fa-circle-info me-2 text-primary"></i>Informações do Evento
                    </h2>
                    
                    <div class="row g-4">
                        <!-- Data e Hora -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 bg-light rounded-4 h-100">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3 flex-shrink-0">
                                    <i class="fa-regular fa-clock fa-lg text-primary"></i>
                                </div>
                                <div>
                                    <h3 class="h6 text-muted mb-2">Data e Hora</h3>
                                    <p class="mb-1 fw-bold fs-5 text-dark">{{ $item->starts_at?->format('d/m/Y') }}</p>
                                    <p class="text-muted mb-0">
                                        <i class="fa-solid fa-clock me-1"></i>
                                        {{ $item->starts_at?->format('H:i') }}
                                        @if($item->ends_at)
                                        - {{ $item->ends_at->format('H:i') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Localização -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 bg-light rounded-4 h-100">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3 flex-shrink-0">
                                    <i class="fa-solid fa-location-dot fa-lg text-primary"></i>
                                </div>
                                <div>
                                    <h3 class="h6 text-muted mb-2">Localização</h3>
                                    <p class="mb-1 fw-bold fs-5 text-dark">
                                        {{ $item->is_online ? 'Evento Online' : ($item->location ?? 'Local a confirmar') }}
                                    </p>
                                    @if(!$item->is_online && $item->location)
                                    <p class="text-muted mb-0">
                                        <i class="fa-solid fa-map-marker-alt me-1"></i>Presencial
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vagas -->
                        @if($item->capacity !== null)
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 bg-light rounded-4 h-100">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3 flex-shrink-0">
                                    <i class="fa-solid fa-users fa-lg text-primary"></i>
                                </div>
                                <div class="w-100">
                                    <h3 class="h6 text-muted mb-2">Disponibilidade</h3>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold fs-4 text-dark">
                                            {{ $item->available_spots ?? 0 }}
                                            <small class="fs-6 text-muted">/ {{ $item->capacity }}</small>
                                        </span>
                                        @if(($item->available_spots ?? 0) <= 0)
                                            <span class="badge bg-danger px-3 py-1">Esgotado</span>
                                        @elseif(($item->available_spots ?? 0) < $item->capacity * 0.3)
                                            <span class="badge bg-warning px-3 py-1">Últimas vagas</span>
                                        @else
                                            <span class="badge bg-success px-3 py-1">Disponível</span>
                                        @endif
                                    </div>
                                    @php
                                        $vagasDisponiveis = $item->available_spots ?? $item->capacity;
                                        $vagasTotais = $item->capacity;
                                        $porcentagem = $vagasTotais > 0 
                                            ? (($vagasTotais - $vagasDisponiveis) / $vagasTotais) * 100 
                                            : 0;
                                    @endphp
                                    <div class="progress mt-2" style="height: 10px; border-radius: 10px;">
                                        <div class="progress-bar 
                                            @if($vagasDisponiveis == 0) bg-danger
                                            @elseif($vagasDisponiveis < $vagasTotais * 0.3) bg-warning
                                            @else bg-success @endif" 
                                            role="progressbar" 
                                            style="width: {{ $porcentagem }}%; border-radius: 10px;"
                                            aria-valuenow="{{ $porcentagem }}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        {{ $vagasTotais - $vagasDisponiveis }} de {{ $vagasTotais }} vagas preenchidas
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Status da Inscrição -->
                        <div class="col-md-6">
                            <div class="d-flex align-items-start p-3 bg-light rounded-4 h-100">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3 flex-shrink-0">
                                    <i class="fa-solid fa-clipboard-check fa-lg text-primary"></i>
                                </div>
                                <div class="w-100">
                                    <h3 class="h6 text-muted mb-2">Status da Inscrição</h3>
                                    @if($item->registration_enabled)
                                        @if($item->external_registration_url)
                                            <div class="mb-2">
                                                <span class="badge bg-info px-3 py-2 mb-2">
                                                    <i class="fa-solid fa-link me-1"></i>Inscrição Externa
                                                </span>
                                                <a href="{{ $item->external_registration_url }}" 
                                                   target="_blank"
                                                   class="btn btn-sm btn-info w-100 mt-2">
                                                    <i class="fa-solid fa-external-link me-1"></i>Acessar Plataforma
                                                </a>
                                            </div>
                                        @else
                                            <p class="fw-bold fs-5 text-success mb-2">
                                                <i class="fa-solid fa-check-circle me-1"></i>Inscrições Abertas
                                            </p>
                                        @endif
                                    @else
                                        <p class="fw-bold fs-5 text-secondary mb-0">
                                            <i class="fa-solid fa-ban me-1"></i>Inscrições Encerradas
                                        </p>
                                    @endif
                                    <small class="text-muted d-block mt-1">
                                        {{ $item->registration_enabled ? 'Inscrições disponíveis' : 'Período de inscrições finalizado' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descrição do Evento -->
            @if($item->excerpt || $item->description)
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    @if($item->excerpt)
                    <div class="mb-5">
                        <h2 class="h4 text-dark mb-3 pb-3 border-bottom">
                            <i class="fa-solid fa-quote-left me-2 text-primary"></i>Resumo do Evento
                        </h2>
                        <div class="p-4 bg-light rounded-4">
                            <p class="lead text-muted mb-0">{{ $item->excerpt }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($item->description)
                    <div class="@if($item->excerpt) pt-4 @endif">
                        <h2 class="h4 text-dark mb-4">
                            <i class="fa-solid fa-align-left me-2 text-primary"></i>Descrição Detalhada
                        </h2>
                        <div class="prose p-4 bg-white rounded-4 border">
                            {!! nl2br(e($item->description)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Card de Status -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-4 border-bottom">
                    <h3 class="h5 mb-0 text-dark d-flex align-items-center">
                        <i class="fa-solid fa-eye me-2 text-primary"></i>Modo Visualização
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 rounded-4 mb-0">
                        <div class="d-flex align-items-start">
                            <i class="fa-solid fa-info-circle fa-lg me-3 mt-1 flex-shrink-0"></i>
                            <div>
                                <h4 class="alert-heading h6 mb-2">Apenas para Visualização</h4>
                                <p class="mb-2 small">As funcionalidades de inscrição foram desabilitadas conforme requisitos do sistema.</p>
                                <a href="{{ route('agenda.index') }}" class="btn btn-sm btn-outline-info mt-2">
                                    <i class="fa-solid fa-calendar me-1"></i>Voltar para Agenda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Ações Rápidas -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-4 border-bottom">
                    <h3 class="h5 mb-0 text-dark d-flex align-items-center">
                        <i class="fa-solid fa-bolt me-2 text-primary"></i>Ações Rápidas
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="vstack gap-3">
                        @if($item->external_registration_url)
                        <a href="{{ $item->external_registration_url }}" 
                           target="_blank"
                           class="btn btn-primary btn-lg d-flex align-items-center justify-content-center py-3 rounded-3">
                            <i class="fa-solid fa-external-link-alt me-3 fa-lg"></i>
                            <div class="text-start">
                                <div class="fw-bold">Inscrição Externa</div>
                                <small class="opacity-75">Acessar plataforma</small>
                            </div>
                        </a>
                        @endif
                        
                        @if($item->website_url)
                        <a href="{{ $item->website_url }}" 
                           target="_blank"
                           class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center py-3 rounded-3">
                            <i class="fa-solid fa-globe me-3 fa-lg"></i>
                            <div class="text-start">
                                <div class="fw-bold">Site Oficial</div>
                                <small class="opacity-75">Visitar site do evento</small>
                            </div>
                        </a>
                        @endif
                        
                        <div class="d-grid gap-2 mt-2">
                            <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center py-2">
                                <i class="fa-regular fa-calendar-plus me-2"></i>Adicionar ao Calendário
                            </button>
                            <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center py-2">
                                <i class="fa-solid fa-share-alt me-2"></i>Compartilhar Evento
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Eventos Relacionados -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-4 border-bottom">
                    <h3 class="h5 mb-0 text-dark d-flex align-items-center">
                        <i class="fa-solid fa-calendar-day me-2 text-primary"></i>Explorar Mais
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="vstack gap-3">
                        <a href="{{ route('agenda.index') }}" 
                           class="btn btn-outline-dark d-flex align-items-center justify-content-between py-3 rounded-3">
                            <span class="d-flex align-items-center">
                                <i class="fa-solid fa-calendar-alt me-3"></i>
                                Todos os Eventos
                            </span>
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        
                        <a href="{{ route('agenda.index') }}?type={{ $item->type }}" 
                           class="btn btn-outline-dark d-flex align-items-center justify-content-between py-3 rounded-3">
                            <span class="d-flex align-items-center">
                                <i class="fa-solid fa-filter me-3"></i>
                                Mais {{ ucfirst(str_replace('_',' ', $item->type)) }}s
                            </span>
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        
                        @if($item->starts_at)
                        <a href="{{ route('agenda.index') }}?month={{ $item->starts_at->format('Y-m') }}" 
                           class="btn btn-outline-dark d-flex align-items-center justify-content-between py-3 rounded-3">
                            <span class="d-flex align-items-center">
                                <i class="fa-solid fa-calendar me-3"></i>
                                {{ $item->starts_at->format('F Y') }}
                            </span>
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos customizados -->
<style>
    .mt-9 {
        margin-top: 120px;
    }
    
    .bg-gradient-dark {
        background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 100%);
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    .rounded-3 {
        border-radius: 0.75rem !important;
    }
    
    .object-fit-cover {
        object-fit: cover;
    }
    
    .breadcrumb {
        background-color: transparent;
        padding: 0;
    }
    
    .breadcrumb-item a {
        color: #6c757d;
        transition: color 0.2s;
    }
    
    .breadcrumb-item a:hover {
        color: #0d6efd;
    }
    
    .breadcrumb-item.active {
        color: #495057;
        font-weight: 500;
    }
    
    .prose {
        line-height: 1.8;
        color: #495057;
        font-size: 1.05rem;
    }
    
    .prose p {
        margin-bottom: 1.5rem;
    }
    
    .prose p:last-child {
        margin-bottom: 0;
    }
    
    .prose ul, .prose ol {
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .prose li {
        margin-bottom: 0.5rem;
    }
    
    .shadow-sm {
        box-shadow: 0 0.125rem 0.5rem rgba(0,0,0,0.08) !important;
    }
    
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.175) !important;
    }
    
    .vstack > *:not(:last-child) {
        margin-bottom: 1rem;
    }
    
    .btn {
        transition: all 0.3s ease;
        border-width: 2px;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .btn-lg {
        padding: 1rem 1.5rem;
    }
    
    .border-bottom {
        border-bottom: 2px solid #f8f9fa !important;
    }
    
    .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }
    
    .bg-opacity-25 {
        background-color: rgba(255, 255, 255, 0.25) !important;
    }
    
    .display-6 {
        font-size: calc(1.375rem + 1.5vw);
        font-weight: 700;
        line-height: 1.2;
    }
    
    @media (min-width: 1200px) {
        .display-6 {
            font-size: 2.5rem;
        }
    }
</style>

<!-- Scripts para melhorar experiência -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adiciona tooltips
    const tooltips = document.querySelectorAll('[title]');
    tooltips.forEach(el => {
        new bootstrap.Tooltip(el);
    });
    
    // Melhora links externos
    document.querySelectorAll('a[target="_blank"]').forEach(link => {
        if (!link.querySelector('.fa-external-link')) {
            const icon = document.createElement('i');
            icon.className = 'fa-solid fa-external-link-alt ms-2 small opacity-75';
            link.appendChild(icon);
        }
    });
    
    // Efeito de hover nos cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.style.transition = 'all 0.3s ease';
        
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
            this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.12)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 0.125rem 0.5rem rgba(0,0,0,0.08)';
        });
    });
    
    // Animações suaves
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeInUp');
            }
        });
    }, observerOptions);
    
    // Observar elementos para animação
    document.querySelectorAll('.card, .h1, .h2').forEach(el => {
        observer.observe(el);
    });
    
    // Compartilhar evento
    const shareBtn = document.querySelector('button:contains("Compartilhar Evento")');
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $item->title }}',
                    text: '{{ $item->excerpt }}',
                    url: window.location.href
                });
            } else {
                // Fallback para copiar link
                navigator.clipboard.writeText(window.location.href);
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show position-fixed bottom-0 end-0 m-3';
                alert.innerHTML = `
                    <i class="fa-solid fa-check me-2"></i>
                    Link copiado para a área de transferência!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                setTimeout(() => alert.remove(), 3000);
            }
        });
    }
});
</script>
@endsection