@extends('layouts.partner')

@section('title', 'Pesquisar Talentos · Banco de Talentos')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5">
        <div class="mb-3 mb-md-0">
            <h1 class="section-title mb-2">
                <i class="fa-solid fa-users text-accent-orange me-2"></i>
                Pesquisar Talentos
            </h1>
            <div class="text-muted d-flex align-items-center gap-2">
                <i class="fa-solid fa-filter text-primary-dark"></i>
                <span>Filtre profissionais por nível, área, disponibilidade e localização</span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('partner.needs.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Ver Necessidades</span>
            </a>
            <button class="btn btn-primary d-flex align-items-center gap-2" id="saveSearchBtn">
                <i class="fa-solid fa-bookmark"></i>
                <span>Salvar Busca</span>
            </button>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 48px; height: 48px; background: rgba(26, 54, 93, 0.1);">
                        <i class="fa-solid fa-users text-primary-dark fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total de Talentos</div>
                        <div class="fw-bold fs-4">{{ $profiles->total() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 48px; height: 48px; background: rgba(211, 84, 0, 0.1);">
                        <i class="fa-solid fa-briefcase text-accent-orange fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Disponíveis Agora</div>
                        <div class="fw-bold fs-4">{{ $profiles->where('availability', 'immediately')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 48px; height: 48px; background: rgba(45, 206, 137, 0.1);">
                        <i class="fa-solid fa-graduation-cap text-success fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Com Currículo</div>
                        <div class="fw-bold fs-4">{{ $profiles->where('cv_path', '!=', null)->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" 
                         style="width: 48px; height: 48px; background: rgba(108, 117, 125, 0.1);">
                        <i class="fa-solid fa-location-dot text-secondary fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Províncias Diferentes</div>
                        <div class="fw-bold fs-4">{{ $profiles->pluck('province')->unique()->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-lg rounded-4 mb-5">
        <div class="card-header bg-white border-0 py-3 px-4">
            <h5 class="fw-semibold mb-0 d-flex align-items-center gap-2">
                <i class="fa-solid fa-sliders text-accent-orange"></i>
                Filtros de Pesquisa
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('partner.talents.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-medium small text-muted mb-2">
                        <i class="fa-solid fa-graduation-cap me-1"></i>
                        Nível Profissional
                    </label>
                    <select class="form-select form-select-sm" name="level">
                        <option value="">Todos os níveis</option>
                        @foreach($levels as $k => $label)
                            <option value="{{ $k }}" @selected(request('level') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-medium small text-muted mb-2">
                        <i class="fa-solid fa-layer-group me-1"></i>
                        Área de Atuação
                    </label>
                    <select class="form-select form-select-sm" name="area">
                        <option value="">Todas as áreas</option>
                        @foreach($areas as $k => $label)
                            <option value="{{ $k }}" @selected(request('area') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-medium small text-muted mb-2">
                        <i class="fa-solid fa-calendar-check me-1"></i>
                        Disponibilidade
                    </label>
                    <select class="form-select form-select-sm" name="availability">
                        <option value="">Todas as disponibilidades</option>
                        @foreach($availabilities as $k => $label)
                            <option value="{{ $k }}" @selected(request('availability') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label fw-medium small text-muted mb-2">
                        <i class="fa-solid fa-location-dot me-1"></i>
                        Província
                    </label>
                    <input type="text" 
                           name="province" 
                           class="form-control form-control-sm" 
                           value="{{ request('province') }}" 
                           placeholder="Ex: Luanda, Huíla...">
                </div>
                
                <div class="col-md-9">
                    <label class="form-label fw-medium small text-muted mb-2">
                        <i class="fa-solid fa-magnifying-glass me-1"></i>
                        Pesquisa Avançada
                    </label>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           value="{{ request('search') }}" 
                           placeholder="Pesquise por habilidades, certificações, experiência (Ex: NEBOSH, ISO 45001, auditoria)...">
                </div>
                
                <div class="col-md-3 d-flex gap-2 align-items-end">
                    <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2" type="submit">
                        <i class="fa-solid fa-filter"></i>
                        Aplicar Filtros
                    </button>
                    <a href="{{ route('partner.talents.index') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" 
                       style="width: 44px; height: 38px;" title="Limpar filtros">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                </div>
            </form>
            
            <!-- Active Filters -->
            @if(request()->hasAny(['level', 'area', 'availability', 'province', 'search']))
            <div class="mt-4 pt-3 border-top">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="fa-solid fa-tags text-primary-dark"></i>
                    <span class="small fw-medium">Filtros Ativos:</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @if(request('level'))
                    <span class="badge d-flex align-items-center gap-1 bg-primary-dark bg-opacity-10 text-primary-dark px-3 py-1">
                        <span>Nível:</span>
                        <strong>{{ $levels[request('level')] ?? request('level') }}</strong>
                        <a href="?{{ http_build_query(request()->except('level')) }}" class="text-muted ms-1">
                            <i class="fa-solid fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('area'))
                    <span class="badge d-flex align-items-center gap-1 bg-primary-dark bg-opacity-10 text-primary-dark px-3 py-1">
                        <span>Área:</span>
                        <strong>{{ $areas[request('area')] ?? request('area') }}</strong>
                        <a href="?{{ http_build_query(request()->except('area')) }}" class="text-muted ms-1">
                            <i class="fa-solid fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('availability'))
                    <span class="badge d-flex align-items-center gap-1 bg-primary-dark bg-opacity-10 text-primary-dark px-3 py-1">
                        <span>Disponibilidade:</span>
                        <strong>{{ $availabilities[request('availability')] ?? request('availability') }}</strong>
                        <a href="?{{ http_build_query(request()->except('availability')) }}" class="text-muted ms-1">
                            <i class="fa-solid fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('province'))
                    <span class="badge d-flex align-items-center gap-1 bg-primary-dark bg-opacity-10 text-primary-dark px-3 py-1">
                        <span>Província:</span>
                        <strong>{{ request('province') }}</strong>
                        <a href="?{{ http_build_query(request()->except('province')) }}" class="text-muted ms-1">
                            <i class="fa-solid fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request('search'))
                    <span class="badge d-flex align-items-center gap-1 bg-primary-dark bg-opacity-10 text-primary-dark px-3 py-1">
                        <span>Pesquisa:</span>
                        <strong>"{{ request('search') }}"</strong>
                        <a href="?{{ http_build_query(request()->except('search')) }}" class="text-muted ms-1">
                            <i class="fa-solid fa-times"></i>
                        </a>
                    </span>
                    @endif
                    
                    @if(request()->hasAny(['level', 'area', 'availability', 'province', 'search']))
                    <a href="{{ route('partner.talents.index') }}" class="badge d-flex align-items-center gap-1 bg-accent-orange bg-opacity-10 text-accent-orange px-3 py-1">
                        <i class="fa-solid fa-broom"></i>
                        Limpar Todos
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Talent Cards Grid -->
    <div class="row g-4" id="talentsGrid">
        @forelse($profiles as $profile)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 talent-card hover-lift">
                <div class="card-body">
                    <!-- Header with Profile Info -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #1a365d 0%, #2d5282 100%); color: white;">
                                    <span class="fw-bold">{{ substr($profile->user->name ?? 'P', 0, 1) }}</span>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $profile->user->name ?? 'Profissional' }}</h6>
                                    <div class="text-muted small d-flex align-items-center gap-1">
                                        <i class="fa-solid fa-graduation-cap" style="font-size: 0.75rem;"></i>
                                        <span>{{ $levels[$profile->level] ?? $profile->level }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="badge d-flex align-items-center gap-1" 
                              style="background: rgba(26, 54, 93, 0.1); color: var(--primary-dark); padding: 0.35rem 0.75rem; border-radius: 20px;">
                            <i class="fa-solid fa-location-dot" style="font-size: 0.75rem;"></i>
                            {{ $profile->province }}
                        </span>
                    </div>

                    <!-- Headline -->
                    @if($profile->headline)
                    <div class="mb-3">
                        <p class="fw-medium text-dark mb-0">{{ $profile->headline }}</p>
                    </div>
                    @endif

                    <!-- Area Badge -->
                    <div class="mb-3">
                        <span class="badge d-flex align-items-center gap-1 w-fit-content" 
                              style="background: rgba(211, 84, 0, 0.1); color: var(--accent-orange); padding: 0.5rem 1rem; border-radius: 20px;">
                            <i class="fa-solid fa-layer-group"></i>
                            {{ $areas[$profile->area] ?? $profile->area }}
                        </span>
                    </div>

                    <!-- Bio Preview -->
                    @if($profile->bio)
                    <div class="mb-4">
                        <p class="text-muted small" style="line-height: 1.5;">
                            {{ \Illuminate\Support\Str::limit($profile->bio, 160) }}
                        </p>
                    </div>
                    @endif

                    <!-- Footer with Actions -->
                    <div class="mt-auto pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge d-flex align-items-center gap-1" 
                                      style="background: rgba(45, 206, 137, 0.1); color: #2dce89; padding: 0.35rem 0.75rem; border-radius: 20px;">
                                    <i class="fa-solid fa-clock" style="font-size: 0.75rem;"></i>
                                    {{ $availabilities[$profile->availability] ?? $profile->availability }}
                                </span>
                            </div>
                            <div class="d-flex gap-2">
                                @if($profile->cv_path)
                                <a href="{{ asset('storage/'.$profile->cv_path) }}" 
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary rounded-pill d-flex align-items-center gap-1"
                                   title="Baixar Currículo">
                                    <i class="fa-solid fa-file-arrow-down"></i>
                                    <span class="d-none d-sm-inline">CV</span>
                                </a>
                                @endif
                                <button class="btn btn-sm btn-primary rounded-pill d-flex align-items-center gap-1 view-talent-btn"
                                        data-profile-id="{{ $profile->id }}"
                                        title="Ver Perfil Completo">
                                    <i class="fa-solid fa-eye"></i>
                                    <span class="d-none d-sm-inline">Ver</span>
                                </button>

                                <form method="POST" action="{{ route('chat.start') }}" style="display:inline">
                                    @csrf
                                    <input type="hidden" name="recipient_id" value="{{ $profile->user_id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill d-flex align-items-center gap-1" title="Enviar Mensagem">
                                        <i class="fa-solid fa-comments"></i>
                                        <span class="d-none d-sm-inline">Chat</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body py-5 text-center">
                    <div class="mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; background: var(--light-gray);">
                            <i class="fa-solid fa-user-slash text-muted fs-3"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">Nenhum talento encontrado</h5>
                        <p class="text-muted mb-4">Não encontramos profissionais com os critérios de pesquisa selecionados</p>
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <a href="{{ route('partner.talents.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                                <i class="fa-solid fa-rotate-right"></i>
                                Limpar Filtros
                            </a>
                            <button class="btn btn-primary d-flex align-items-center gap-2" id="expandSearchBtn">
                                <i class="fa-solid fa-expand"></i>
                                Expandir Busca
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($profiles->hasPages())
    <div class="mt-5">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Mostrando {{ $profiles->firstItem() ?? 0 }}-{{ $profiles->lastItem() ?? 0 }} de {{ $profiles->total() }} talentos
                    </div>
                    <div>
                        {{ $profiles->links() }}
                    </div>
                    <div class="text-muted small">
                        Página {{ $profiles->currentPage() }} de {{ $profiles->lastPage() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    /* Custom styles for talents page */
    .talent-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
    }
    
    .talent-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
        border-color: rgba(26, 54, 93, 0.1);
    }
    
    .avatar-placeholder {
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .w-fit-content {
        width: fit-content;
    }
    
    .btn-outline-accent-orange {
        border-color: var(--accent-orange);
        color: var(--accent-orange);
    }
    
    .btn-outline-accent-orange:hover {
        background-color: var(--accent-orange);
        border-color: var(--accent-orange);
        color: white;
    }
    
    .badge.bg-primary-dark.bg-opacity-10 {
        background-color: rgba(26, 54, 93, 0.1) !important;
    }
    
    .badge.bg-accent-orange.bg-opacity-10 {
        background-color: rgba(211, 84, 0, 0.1) !important;
    }
    
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
    }
    
    /* Talent card animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .talent-card {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    
    .talent-card:nth-child(1) { animation-delay: 0.1s; }
    .talent-card:nth-child(2) { animation-delay: 0.2s; }
    .talent-card:nth-child(3) { animation-delay: 0.3s; }
    .talent-card:nth-child(4) { animation-delay: 0.4s; }
    .talent-card:nth-child(5) { animation-delay: 0.5s; }
    .talent-card:nth-child(6) { animation-delay: 0.6s; }
    
    /* Form select styling */
    .form-select {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.2s ease;
    }
    
    .form-select:focus {
        border-color: var(--primary-dark);
        box-shadow: 0 0 0 0.2rem rgba(26, 54, 93, 0.1);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start !important;
        }
        
        .form-row {
            flex-direction: column;
        }
        
        .btn-group {
            width: 100%;
        }
        
        .btn-group .btn {
            flex: 1;
        }
    }
    
    /* Active filters badges */
    .badge a.text-muted:hover {
        color: #dc3545 !important;
        text-decoration: none;
    }
    
    /* Loading skeleton */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 4px;
    }
    
    @keyframes loading {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Save search functionality
    
    
    // View talent profile
    document.querySelectorAll('.view-talent-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const profileId = this.dataset.profileId;
            // Here you would typically open a modal or redirect to profile page
            window.open(`/talents/${profileId}`, '_blank');
        });
    });
    
    // Contact talent
    document.querySelectorAll('.contact-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const profileId = this.dataset.profileId;
            // Here you would typically open a contact modal
            showContactModal(profileId);
        });
    });
    
    // Expand search button
    const expandSearchBtn = document.getElementById('expandSearchBtn');
    if (expandSearchBtn) {
        expandSearchBtn.addEventListener('click', function() {
            // Clear all filters except maybe some basic ones
            window.location.href = '{{ route("partner.talents.index") }}';
        });
    }
    
    // Filter form auto-submit on select change (optional)
    document.querySelectorAll('select[name="level"], select[name="area"], select[name="availability"]').forEach(select => {
        select.addEventListener('change', function() {
            if (this.value) {
                this.closest('form').submit();
            }
        });
    });
    
    // Show loading skeleton on filter submit
    const filterForm = document.querySelector('form[method="GET"]');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            const talentsGrid = document.getElementById('talentsGrid');
            if (talentsGrid) {
                talentsGrid.innerHTML = `
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body py-5 text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                                <p class="mt-3 text-muted">Aplicando filtros...</p>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    }
    
    // Helper functions
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        const container = document.getElementById('toastContainer') || createToastContainer();
        container.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
    }
    
    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toastContainer';
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
        return container;
    }
    
    function showContactModal(profileId) {
        // Create and show a contact modal
        const modal = new bootstrap.Modal(document.getElementById('contactModal') || createContactModal());
        modal.show();
        
        // Here you would load contact form for the specific profile
    }
    
    function createContactModal() {
        const modal = document.createElement('div');
        modal.id = 'contactModal';
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Entrar em Contato</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="contactForm">
                            <div class="mb-3">
                                <label for="message" class="form-label">Mensagem</label>
                                <textarea class="form-control" id="message" rows="4" 
                                          placeholder="Digite sua mensagem para este profissional..."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="sendMessageBtn">Enviar Mensagem</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        return modal;
    }
    
    // Initialize animations
    const talentCards = document.querySelectorAll('.talent-card');
    talentCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>
@endsection