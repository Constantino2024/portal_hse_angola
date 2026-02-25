@extends('layouts.app')

@section('title', 'Agenda Nacional HSE & ESG')

@section('content')
<div class="container py-4 mt-9">
    <!-- Header com gradiente e sombra -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient-primary shadow-lg rounded-4 p-4 p-md-5">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <div>
                        <h1 class="h2 text-white mb-2">
                            <i class="fas fa-calendar-alt me-3"></i>Agenda Nacional de HSE & ESG
                        </h1>
                        <p class="text-white mb-0 fs-5">
                            Eventos, workshops, formações, datas internacionais e webinars
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros em card separado -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4">
                    <h2 class="h5 mb-0 text-dark">
                        <i class="fa-solid fa-filter me-2 text-primary"></i>Filtrar Eventos
                    </h2>
                </div>
                <div class="card-body p-4">
                    <form class="row g-3 align-items-end" method="GET" action="{{ route('agenda.index') }}">
                        <div class="col-12 col-lg-4 col-xl-4">
                            <label class="form-label small text-muted mb-1">Pesquisar Eventos</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                                </span>
                                <input type="search" name="q" value="{{ request('q') }}" 
                                       class="form-control border-start-0 ps-0" 
                                       placeholder="Digite o nome do evento, local, etc...">
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                            <label class="form-label small text-muted mb-1">Tipo de Evento</label>
                            <select name="type" class="form-select">
                                <option value="">Todos os tipos</option>
                                @foreach($types as $k => $label)
                                    <option value="{{ $k }}" @selected(request('type')===$k)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                            <label class="form-label small text-muted mb-1">Período</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa-solid fa-calendar-days text-muted"></i>
                                </span>
                                <input type="month" name="month" value="{{ request('month') }}" 
                                       class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-12 col-lg-2 col-xl-2 d-grid">
                            <button class="btn btn-primary btn-lg px-4 rounded-3">
                                <i class="fa-solid fa-filter me-2"></i> Filtrar
                            </button>
                        </div>
                        
                        @if(request()->has('q') || request()->has('type') || request()->has('month'))
                        <div class="col-12 col-lg-2 col-xl-2 d-grid">
                            <a href="{{ route('agenda.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fa-solid fa-times me-2"></i>Limpar
                            </a>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check me-3 fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation me-3 fs-4"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Cards de Eventos -->
    @if($items->count() > 0)
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        @foreach($items as $item)
        <div class="col">
            <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden transition-all">
                <!-- Badge de Tipo no topo -->
                <div class="position-absolute top-0 end-0 m-3 z-1">
                    <span class="badge rounded-pill px-3 py-2 shadow-sm 
                        @if($item->type === 'webinar') bg-info
                        @elseif($item->type === 'workshop') bg-warning text-dark
                        @elseif($item->type === 'formacao') bg-success
                        @elseif($item->type === 'evento') bg-primary
                        @else bg-secondary @endif">
                        {{ $types[$item->type] ?? ucfirst($item->type) }}
                    </span>
                </div>
                
                <!-- Imagem representativa do tipo -->
                <div class="card-img-top 
                    @if($item->type === 'webinar') bg-gradient-info
                    @elseif($item->type === 'workshop') bg-gradient-warning
                    @elseif($item->type === 'formacao') bg-gradient-success
                    @elseif($item->type === 'evento') bg-gradient-primary
                    @else bg-gradient-secondary @endif" 
                    style="height: 180px;">
                    <div class="h-100 d-flex align-items-center justify-content-center text-white">
                        <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}" style="max-height: 100%; max-width: 100%; object-fit: cover;">
                    </div>
                </div>
                
                <!-- Corpo do Card -->
                <div class="card-body p-4">
                    <h3 class="h5 card-title mb-3">
                        <a href="{{ route('agenda.show', $item->slug) }}" 
                           class="text-decoration-none text-dark fw-bold">
                            {{ $item->title }}
                        </a>
                    </h3>
                    
                    <!-- Informações do evento -->
                    <div class="mb-3">
                        <!-- Data e Hora -->
                        <div class="d-flex align-items-start mb-2">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fa-regular fa-clock text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-medium">{{ $item->starts_at?->format('d/m/Y') }}</div>
                                <small class="text-muted">
                                    {{ $item->starts_at?->format('H:i') }}
                                    @if($item->ends_at)
                                    - {{ $item->ends_at->format('H:i') }}
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <!-- Localização -->
                        <div class="d-flex align-items-start mb-2">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fa-solid fa-location-dot text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-medium">
                                    {{ $item->is_online ? 'Evento Online' : ($item->location ?? 'Local a confirmar') }}
                                </div>
                                @if(!$item->is_online && $item->location)
                                <small class="text-muted">Presencial</small>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Vagas (apenas informativo) -->
                        @if($item->capacity !== null)
                        <div class="d-flex align-items-start">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="fa-solid fa-users text-primary"></i>
                            </div>
                            <div class="w-100">
                                <div class="fw-medium">
                                    Vagas: {{ $item->available_spots ?? 0 }} / {{ $item->capacity }}
                                </div>
                                @php
                                    $vagasDisponiveis = $item->available_spots ?? $item->capacity;
                                    $vagasTotais = $item->capacity;
                                    $porcentagem = $vagasTotais > 0 
                                        ? (($vagasTotais - $vagasDisponiveis) / $vagasTotais) * 100 
                                        : 0;
                                @endphp
                                @if($vagasDisponiveis == 0)
                                    <small class="text-danger fw-medium">
                                        <i class="fa-solid fa-times-circle me-1"></i>Esgotado
                                    </small>
                                @elseif($vagasDisponiveis < $vagasTotais * 0.3)
                                    <small class="text-warning fw-medium">
                                        <i class="fa-solid fa-exclamation-circle me-1"></i>Últimas vagas
                                    </small>
                                @else
                                    <small class="text-success fw-medium">
                                        <i class="fa-solid fa-check-circle me-1"></i>Vagas disponíveis
                                    </small>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Resumo -->
                    @if($item->excerpt)
                    <p class="card-text text-muted mb-0 border-top pt-3">
                        <small>{{ Str::limit($item->excerpt, 120) }}</small>
                    </p>
                    @endif
                </div>
                
                <!-- Rodapé do Card (apenas botão de detalhes) -->
                <div class="card-footer bg-white border-0 p-4 pt-0">
                    <div class="d-grid">
                        <a href="{{ route('agenda.show', $item->slug) }}" 
                           class="btn btn-outline-primary btn-lg rounded-3">
                            <i class="fa-solid fa-circle-info me-2"></i>Ver Detalhes Completos
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <!-- Estado vazio -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fa-solid fa-calendar-xmark text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="h4 text-dark mb-3">Nenhum evento encontrado</h3>
                    <p class="text-muted mb-4">
                        Não foram encontrados eventos correspondentes aos filtros aplicados.
                        Tente ajustar os critérios de busca ou verifique nossa agenda completa.
                    </p>
                    <a href="{{ route('agenda.index') }}" class="btn btn-primary btn-lg px-4 rounded-3">
                        <i class="fa-solid fa-calendar-alt me-2"></i>Ver Todos os Eventos
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Paginação -->
    @if($items->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <nav aria-label="Navegação de eventos">
                        {{ $items->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Estatísticas de Visualização -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-4">
                    <h3 class="h5 mb-0 text-dark">
                        <i class="fa-solid fa-chart-bar me-2 text-primary"></i>Resumo da Agenda
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-6 col-md-3 text-center">
                            <div class="p-3">
                                <div class="text-primary mb-2">
                                    <i class="fa-solid fa-calendar-alt fa-2x"></i>
                                </div>
                                <h3 class="h2 fw-bold text-dark">{{ $items->total() ?? 0 }}</h3>
                                <p class="text-muted mb-0">Eventos</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="p-3">
                                <div class="text-success mb-2">
                                    <i class="fa-solid fa-laptop fa-2x"></i>
                                </div>
                                <h3 class="h2 fw-bold text-dark">
                                    {{ $items->where('is_online', true)->count() ?? 0 }}
                                </h3>
                                <p class="text-muted mb-0">Online</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="p-3">
                                <div class="text-info mb-2">
                                    <i class="fa-solid fa-building fa-2x"></i>
                                </div>
                                <h3 class="h2 fw-bold text-dark">
                                    {{ $items->where('is_online', false)->count() ?? 0 }}
                                </h3>
                                <p class="text-muted mb-0">Presencial</p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="p-3">
                                <div class="text-warning mb-2">
                                    <i class="fa-solid fa-fire fa-2x"></i>
                                </div>
                                <h3 class="h2 fw-bold text-dark">
                                    {{ $items->where('starts_at', '>=', now())->count() ?? 0 }}
                                </h3>
                                <p class="text-muted mb-0">Próximos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos customizados -->
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .mt-9{
        margin-top: 120px;
    }
    
    .bg-gradient-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }
    
    .transition-all {
        transition: all 0.3s ease;
    }
    
    .transition-all:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    
    .rounded-4 {
        border-radius: 1rem !important;
    }
    
    .card-img-top {
        border-radius: 1rem 1rem 0 0 !important;
    }
    
    .badge.bg-info {
        background-color: #36b9cc !important;
    }
    
    .badge.bg-warning {
        background-color: #f6c23e !important;
    }
    
    .badge.bg-success {
        background-color: #1cc88a !important;
    }
    
    .text-white-75 {
        opacity: 0.85;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
</style>

<!-- Script para melhorar experiência -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adiciona efeito de tooltip nos badges de tipo
    const badges = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    badges.forEach(badge => {
        new bootstrap.Tooltip(badge);
    });
    
    // Foca no campo de pesquisa se houver busca anterior
    const searchParam = new URLSearchParams(window.location.search).get('q');
    if (searchParam) {
        const searchInput = document.querySelector('input[name="q"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }
    
    // Efeito de loading ao aplicar filtros
    const filterForm = document.querySelector('form[method="GET"]');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Aplicando...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
@endsection