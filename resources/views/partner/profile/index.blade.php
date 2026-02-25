@extends('layouts.partner')

@section('title', 'Perfil da Empresa')



@section('content')
<div class="container-fluid px-0">
    <!-- Header Moderno -->
    <div class="profile-header-modern fade-in-up">
        <div class="row align-items-center g-4">
            <div class="col-lg-auto text-center text-lg-start">
                <div class="avatar-modern mx-auto mx-lg-0">
                    @if($company->logo_path)
                        <img src="{{ $company->logo_url }}" alt="{{ $company->company_name }}">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr($company->company_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-lg">
                <div class="text-center text-lg-start">
                    <h1 class="h2 fw-bold mb-3">{{ $company->company_name }}</h1>
                    
                    <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-start mb-3">
                        @if($company->trading_name)
                            <span class="badge-modern">
                                <i class="fa-regular fa-building"></i>
                                {{ $company->trading_name }}
                            </span>
                        @endif
                        
                        @if($company->nif)
                            <span class="badge-modern">
                                <i class="fa-regular fa-id-card"></i>
                                NIF: {{ $company->nif }}
                            </span>
                        @endif

                        <span class="badge-modern">
                            <i class="fa-regular fa-calendar"></i>
                            Membro desde {{ $company->created_at ? $company->created_at->format('M Y') : 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-auto">
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-lg-end">
                    @if($company->is_verified)
                        <span class="status-badge-modern verified" data-tooltip="Empresa verificada">
                            <i class="fa-solid fa-circle-check"></i>
                            Verificada
                        </span>
                    @endif
                    
                    @if($company->is_premium)
                        <span class="status-badge-modern premium" data-tooltip="Empresa Premium">
                            <i class="fa-solid fa-crown"></i>
                            Premium
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Modernos -->
    <div class="row g-4 mb-5">
        <div class="col-sm-6 col-xl-3 fade-in-up delay-1">
            <div class="stat-card-modern">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Vagas Publicadas</span>
                        <div class="h2 fw-bold mt-2 mb-0 {{ ($company->total_jobs_posted ?? 0) == 0 ? 'stat-value-zero' : '' }}">
                            {{ $company->total_jobs_posted ?? 0 }}
                        </div>
                    </div>
                    <div class="stat-icon-modern">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>
                </div>
                <div class="progress-modern mt-3">
                    <div class="progress-bar-modern" style="width: {{ min(100, ($company->total_jobs_posted ?? 0) * 10) }}%; height: 100%;"></div>
                </div>
                <small class="text-muted mt-2 d-block">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    Últimos 30 dias
                                </small>
                            </div>
                        </div>
        
        <div class="col-sm-6 col-xl-3 fade-in-up delay-2">
            <div class="stat-card-modern">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Visualizações</span>
                        <div class="h2 fw-bold mt-2 mb-0 {{ ($company->total_views ?? 0) == 0 ? 'stat-value-zero' : '' }}">
                            {{ number_format($company->total_views ?? 0) }}
                        </div>
                    </div>
                    <div class="stat-icon-modern">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                </div>
                <div class="progress-modern mt-3">
                    <div class="progress-bar-modern" style="width: {{ min(100, ($company->total_views ?? 0) / 100) }}%; height: 100%;"></div>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fa-regular fa-clock me-1"></i>
                    Total acumulado
                </small>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-3 fade-in-up delay-3">
            <div class="stat-card-modern">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Candidaturas</span>
                        <div class="h2 fw-bold mt-2 mb-0 {{ ($company->total_applications ?? 0) == 0 ? 'stat-value-zero' : '' }}">
                            {{ number_format($company->total_applications ?? 0) }}
                        </div>
                    </div>
                    <div class="stat-icon-modern">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                </div>
                <div class="progress-modern mt-3">
                    <div class="progress-bar-modern" style="width: {{ min(100, ($company->total_applications ?? 0) * 5) }}%; height: 100%;"></div>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fa-regular fa-clock me-1"></i>
                    Taxa de conversão: {{ $company->total_jobs_posted > 0 ? round(($company->total_applications / $company->total_jobs_posted) * 100, 1) : 0 }}%
                </small>
            </div>
        </div>
        
        <div class="col-sm-6 col-xl-3 fade-in-up delay-4">
            <div class="stat-card-modern">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <span class="text-muted small text-uppercase fw-semibold">Anos de Operação</span>
                        <div class="h2 fw-bold mt-2 mb-0">
                            {{ $company->years_in_operation ?? 0 }}
                        </div>
                    </div>
                    <div class="stat-icon-modern">
                        <i class="fa-solid fa-calendar-alt"></i>
                    </div>
                </div>
                <div class="progress-modern mt-3">
                    <div class="progress-bar-modern" style="width: {{ min(100, ($company->years_in_operation ?? 0) * 10) }}%; height: 100%;"></div>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fa-regular fa-building me-1"></i>
                    Experiência no mercado
                </small>
            </div>
        </div>
    </div>

    <!-- Action Buttons Modernos -->
    <div class="action-buttons-modern fade-in-up">
        <a href="{{ route('partner.profile.edit') }}" class="btn-modern btn-modern-primary">
            <i class="fa-solid fa-pen-to-square"></i>
            Editar Perfil
        </a>
        <button type="button" class="btn-modern btn-modern-outline" data-bs-toggle="modal" data-bs-target="#accountModal">
            <i class="fa-solid fa-user-gear"></i>
            Configurações da Conta
        </button>
        <a href="#" class="btn-modern btn-modern-outline" onclick="window.print()">
            <i class="fa-solid fa-download"></i>
            Exportar Dados
        </a>
    </div>

    <!-- Banner Section -->
    @if($company->banner_path)
    <div class="info-section-modern p-0 overflow-hidden mb-4 fade-in-up">
        <img src="{{ $company->banner_url }}" alt="Banner" class="w-100" style="max-height: 300px; object-fit: cover;">
    </div>
    @endif

    <!-- Conteúdo Principal com Grid Responsivo -->
    <div class="row g-4">
        <!-- Coluna Principal -->
        <div class="col-lg-8">
            <!-- Sobre a Empresa -->
            <div class="info-section-modern fade-in-up">
                <div class="info-title-modern">
                    <i class="fa-solid fa-building"></i>
                    Sobre a Empresa
                </div>
                
                @if($company->description)
                    <p class="mb-4" style="line-height: 1.8; color: #4a5568;">{{ $company->description }}</p>
                @else
                    <p class="text-muted fst-italic mb-4">
                        <i class="fa-regular fa-pen-to-square me-2"></i>
                        Nenhuma descrição fornecida. <a href="{{ route('partner.profile.edit') }}" class="text-primary">Adicionar descrição</a>
                    </p>
                @endif

                @if($company->mission || $company->vision || $company->values)
                <div class="row g-4 mt-2">
                    @if($company->mission)
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="me-3">
                                <div class="stat-icon-modern" style="width: 45px; height: 45px;">
                                    <i class="fa-solid fa-bullseye"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-2">Missão</h6>
                                <p class="text-muted small mb-0">{{ $company->mission }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($company->vision)
                    <div class="col-md-6">
                        <div class="d-flex">
                            <div class="me-3">
                                <div class="stat-icon-modern" style="width: 45px; height: 45px;">
                                    <i class="fa-solid fa-eye"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-2">Visão</h6>
                                <p class="text-muted small mb-0">{{ $company->vision }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($company->values)
                    <div class="col-12">
                        <div class="d-flex">
                            <div class="me-3">
                                <div class="stat-icon-modern" style="width: 45px; height: 45px;">
                                    <i class="fa-solid fa-handshake"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-2">Valores</h6>
                                <p class="text-muted small mb-0">{{ $company->values }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Contactos e Localização -->
            <div class="info-section-modern fade-in-up">
                <div class="info-title-modern">
                    <i class="fa-solid fa-address-card"></i>
                    Contactos e Localização
                </div>

                <div class="info-grid-modern">
                    <div class="info-item-modern">
                        <span class="info-label-modern">
                            <i class="fa-solid fa-phone"></i>
                            Telefone
                        </span>
                        <span class="info-value-modern">
                            {{ $company->phone ?? 'Não informado' }}
                        </span>
                    </div>

                    <div class="info-item-modern">
                        <span class="info-label-modern">
                            <i class="fa-solid fa-envelope"></i>
                            Email
                        </span>
                        <span class="info-value-modern">
                            {{ $company->email ?? 'Não informado' }}
                        </span>
                    </div>

                    <div class="info-item-modern">
                        <span class="info-label-modern">
                            <i class="fa-solid fa-globe"></i>
                            Website
                        </span>
                        @if($company->website)
                            <a href="{{ $company->website }}" target="_blank" class="info-value-modern text-decoration-none text-primary">
                                {{ Str::limit($company->website, 30) }}
                                <i class="fa-solid fa-external-link-alt ms-1" style="font-size: 0.8rem;"></i>
                            </a>
                        @else
                            <span class="info-value-modern empty">Não informado</span>
                        @endif
                    </div>

                    <div class="info-item-modern">
                        <span class="info-label-modern">
                            <i class="fa-solid fa-location-dot"></i>
                            Endereço
                        </span>
                        <span class="info-value-modern">
                            {{ $company->full_address ?? 'Não informado' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <div class="info-section-modern fade-in-up">
                <div class="info-title-modern">
                    <i class="fa-solid fa-circle-info"></i>
                    Informações Adicionais
                </div>

                <div class="info-grid-modern">
                    <div class="info-item-modern">
                        <span class="info-label-modern">Setor de Atividade</span>
                        <span class="info-value-modern">{{ $company->sector_name ?? 'Não informado' }}</span>
                    </div>

                    <div class="info-item-modern">
                        <span class="info-label-modern">Porte da Empresa</span>
                        <span class="info-value-modern">{{ $company->company_size_name ?? 'Não informado' }}</span>
                    </div>

                    <div class="info-item-modern">
                        <span class="info-label-modern">Ano de Fundação</span>
                        <span class="info-value-modern">{{ $company->foundation_year ?? 'Não informado' }}</span>
                    </div>

                    <div class="info-item-modern">
                        <span class="info-label-modern">Pessoa de Contacto</span>
                        <span class="info-value-modern">
                            {{ $company->contact_person ?? 'Não informado' }}
                            @if($company->contact_position)
                                <br><small class="text-muted">{{ $company->contact_position }}</small>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Redes Sociais -->
            <div class="info-section-modern fade-in-up">
                <div class="info-title-modern">
                    <i class="fa-solid fa-share-nodes"></i>
                    Redes Sociais
                </div>

                @php
                    $hasSocialLinks = false;
                    $socialLinks = $company->social_links ?? [];
                    $socialIcons = [
                        'facebook' => 'fa-brands fa-facebook-f',
                        'linkedin' => 'fa-brands fa-linkedin-in',
                        'twitter' => 'fa-brands fa-x-twitter',
                        'instagram' => 'fa-brands fa-instagram',
                        'website' => 'fa-solid fa-globe',
                    ];
                @endphp

                <div class="social-links-modern">
                    @foreach(['facebook', 'linkedin', 'twitter', 'instagram', 'website'] as $social)
                        @if(!empty($socialLinks[$social]))
                            @php $hasSocialLinks = true; @endphp
                            <a href="{{ $socialLinks[$social] }}" target="_blank" class="social-link-modern">
                                <i class="{{ $socialIcons[$social] }}"></i>
                                {{ ucfirst($social) }}
                            </a>
                        @endif
                    @endforeach
                </div>

                @if(!$hasSocialLinks)
                    <p class="text-muted fst-italic small mb-0 text-center py-3">
                        <i class="fa-regular fa-face-frown me-2"></i>
                        Nenhuma rede social adicionada
                    </p>
                @endif
            </div>

            <!-- HSE & ESG -->
            <div class="info-section-modern fade-in-up">
                <div class="info-title-modern">
                    <i class="fa-solid fa-leaf"></i>
                    HSE & ESG
                </div>

                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            @if($company->has_hse_department)
                                <span class="badge bg-success rounded-pill p-2" style="font-size: 1rem;">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                            @else
                                <span class="badge bg-secondary rounded-pill p-2" style="font-size: 1rem;">
                                    <i class="fa-solid fa-xmark"></i>
                                </span>
                            @endif
                        </div>
                        <div>
                            <strong class="d-block">Departamento de HSE</strong>
                            <small class="text-muted">{{ $company->has_hse_department ? 'Implementado' : 'Não implementado' }}</small>
                        </div>
                    </div>

                    @if($company->has_hse_department && ($company->hse_manager_name || $company->hse_manager_contact))
                        <div class="ms-4 ps-2 mb-3 p-3 bg-light rounded-3 border-start border-4 border-warning">
                            @if($company->hse_manager_name)
                                <div class="small mb-2">
                                    <i class="fa-regular fa-user me-2 text-warning"></i>
                                    <strong>Gestor HSE:</strong> {{ $company->hse_manager_name }}
                                </div>
                            @endif
                            @if($company->hse_manager_contact)
                                <div class="small">
                                    <i class="fa-regular fa-address-book me-2 text-warning"></i>
                                    <strong>Contacto:</strong> {{ $company->hse_manager_contact }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            @if($company->has_esg_policy)
                                <span class="badge bg-success rounded-pill p-2" style="font-size: 1rem;">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                            @else
                                <span class="badge bg-secondary rounded-pill p-2" style="font-size: 1rem;">
                                    <i class="fa-solid fa-xmark"></i>
                                </span>
                            @endif
                        </div>
                        <div>
                            <strong class="d-block">Política ESG</strong>
                            <small class="text-muted">{{ $company->has_esg_policy ? 'Implementada' : 'Não implementada' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estatísticas Rápidas -->
            <div class="info-section-modern fade-in-up">
                <div class="info-title-modern">
                    <i class="fa-solid fa-chart-simple"></i>
                    Estatísticas Rápidas
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">
                            <i class="fa-regular fa-file-lines me-2"></i>
                            Iniciativas ESG
                        </span>
                        <span class="fw-bold badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                            {{ $stats['esg_initiatives_count'] ?? 0 }}
                        </span>
                    </div>
                    <div class="progress-modern">
                        <div class="progress-bar-modern" style="width: {{ min(100, ($stats['esg_initiatives_count'] ?? 0) * 20) }}%;"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">
                            <i class="fa-regular fa-diagram-project me-2"></i>
                            Projectos Ativos
                        </span>
                        <span class="fw-bold badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                            {{ $stats['projects_active_count'] ?? 0 }}
                        </span>
                    </div>
                    <div class="progress-modern">
                        <div class="progress-bar-modern" style="width: {{ min(100, ($stats['projects_active_count'] ?? 0) * 20) }}%;"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">
                            <i class="fa-regular fa-clipboard-list me-2"></i>
                            Necessidades Ativas
                        </span>
                        <span class="fw-bold badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                            {{ $stats['needs_open_count'] ?? 0 }}
                        </span>
                    </div>
                    <div class="progress-modern">
                        <div class="progress-bar-modern" style="width: {{ min(100, ($stats['needs_open_count'] ?? 0) * 20) }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Configurações da Conta (mantém o mesmo) -->
@include('partner.profile.partials.account-modal')

<style>
    :root {
        --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --gradient-4: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        --gradient-5: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    /* Profile Header Moderno */
    .profile-header-modern {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #2c3e50 100%);
        border-radius: 24px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .profile-header-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 30s linear infinite;
    }

    .profile-header-modern::after {
        content: '';
        position: absolute;
        bottom: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(211,84,0,0.1) 0%, transparent 70%);
        animation: rotate 25s linear infinite reverse;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Avatar Moderno */
    .avatar-modern {
        width: 140px;
        height: 140px;
        border-radius: 30px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid rgba(255,255,255,0.3);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .avatar-modern:hover {
        transform: scale(1.05) rotate(2deg);
        border-color: var(--accent-orange);
    }

    .avatar-modern img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-modern .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--accent-orange) 0%, #e67e22 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        font-weight: bold;
        color: white;
    }

    /* Badges Modernos */
    .badge-modern {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        border: 1px solid rgba(255,255,255,0.2);
        transition: all 0.3s ease;
        color: white;
    }

    .badge-modern:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
    }

    .badge-modern i {
        font-size: 1rem;
    }

    /* Status Badges */
    .status-badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .status-badge-modern.verified {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-badge-modern.premium {
        background: rgba(211, 84, 0, 0.2);
        color: var(--accent-orange);
        border: 1px solid rgba(211, 84, 0, 0.3);
    }

    .status-badge-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    /* Cards de Estatísticas */
    .stat-card-modern {
        background: white;
        border-radius: 20px;
        padding: 1.8rem 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    .stat-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-orange), #f39c12);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .stat-card-modern:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .stat-card-modern:hover::before {
        transform: scaleX(1);
    }

    .stat-icon-modern {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(211, 84, 0, 0.1) 0%, rgba(230, 126, 34, 0.1) 100%);
        color: var(--accent-orange);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        transition: all 0.3s ease;
    }

    .stat-card-modern:hover .stat-icon-modern {
        background: var(--accent-orange);
        color: white;
        transform: scale(1.1) rotate(5deg);
    }

    /* Progress Bars Modernas */
    .progress-modern {
        height: 8px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 1rem;
    }

    .progress-bar-modern {
        background: linear-gradient(90deg, var(--accent-orange), #f39c12);
        border-radius: 10px;
        position: relative;
        overflow: hidden;
    }

    .progress-bar-modern::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* Seções de Informação */
    .info-section-modern {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .info-section-modern:hover {
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }

    .info-title-modern {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 1.8rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .info-title-modern i {
        color: var(--accent-orange);
        font-size: 1.5rem;
        background: rgba(211, 84, 0, 0.1);
        padding: 0.8rem;
        border-radius: 14px;
    }

    /* Grid de Informações */
    .info-grid-modern {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.8rem;
    }

    .info-item-modern {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .info-label-modern {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-label-modern i {
        color: var(--accent-orange);
        font-size: 0.9rem;
    }

    .info-value-modern {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-dark);
        word-break: break-word;
        line-height: 1.4;
    }

    .info-value-modern.empty {
        color: #adb5bd;
        font-style: italic;
        font-weight: 400;
    }

    /* Links Sociais */
    .social-links-modern {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .social-link-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        padding: 0.7rem 1.4rem;
        border-radius: 50px;
        background: #f8f9fa;
        color: var(--text-dark);
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        font-weight: 500;
    }

    .social-link-modern:hover {
        background: var(--primary-dark);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: var(--primary-dark);
    }

    .social-link-modern i {
        font-size: 1.2rem;
    }

    /* Action Buttons */
    .action-buttons-modern {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .btn-modern {
        padding: 0.9rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        border: none;
        cursor: pointer;
    }

    .btn-modern-primary {
        background: linear-gradient(135deg, var(--accent-orange), #e67e22);
        color: white;
        box-shadow: 0 10px 20px rgba(211, 84, 0, 0.2);
    }

    .btn-modern-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(211, 84, 0, 0.3);
    }

    .btn-modern-outline {
        background: transparent;
        color: var(--primary-dark);
        border: 2px solid var(--primary-dark);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .btn-modern-outline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--primary-dark);
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
        z-index: -1;
    }

    .btn-modern-outline:hover {
        color: white;
    }

    .btn-modern-outline:hover::before {
        transform: scaleX(1);
        transform-origin: left;
    }

    .btn-modern-outline i {
        transition: transform 0.3s ease;
    }

    .btn-modern-outline:hover i {
        transform: translateX(5px);
    }

    /* Responsive Grid */
    .responsive-grid {
        display: grid;
        grid-template-columns: repeat(12, 1fr);
        gap: 1.5rem;
    }

    /* Media Queries Avançadas */
    @media (max-width: 1400px) {
        .container-fluid {
            padding-left: 2rem;
            padding-right: 2rem;
        }
        
        .info-grid-modern {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }
    }

    @media (max-width: 1200px) {
        .profile-header-modern {
            padding: 1.8rem;
        }
        
        .avatar-modern {
            width: 120px;
            height: 120px;
        }
        
        .stat-card-modern {
            padding: 1.5rem;
        }
        
        .stat-icon-modern {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
    }

    @media (max-width: 992px) {
        .container-fluid {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
        
        .profile-header-modern .row {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-header-modern .col-auto {
            margin-bottom: 1.5rem;
        }
        
        .avatar-modern {
            margin: 0 auto;
        }
        
        .badge-modern {
            justify-content: center;
        }
        
        .status-badge-modern {
            margin: 0.5rem;
        }
        
        .info-section-modern {
            padding: 1.5rem;
        }
        
        .info-title-modern {
            font-size: 1.2rem;
        }
        
        .info-title-modern i {
            padding: 0.6rem;
            font-size: 1.2rem;
        }
        
        .info-grid-modern {
            grid-template-columns: 1fr;
            gap: 1.2rem;
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .profile-header-modern {
            padding: 1.5rem;
            border-radius: 20px;
        }
        
        .avatar-modern {
            width: 100px;
            height: 100px;
            border-radius: 20px;
        }
        
        .avatar-modern .avatar-placeholder {
            font-size: 2.5rem;
        }
        
        .badge-modern {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
        
        .stat-card-modern {
            padding: 1.2rem;
        }
        
        .stat-icon-modern {
            width: 45px;
            height: 45px;
            font-size: 1.2rem;
            border-radius: 12px;
        }
        
        .stat-card-modern .h3 {
            font-size: 1.5rem;
        }
        
        .action-buttons-modern {
            flex-direction: column;
        }
        
        .btn-modern {
            width: 100%;
            justify-content: center;
        }
        
        .info-section-modern {
            padding: 1.2rem;
            border-radius: 20px;
        }
        
        .info-title-modern {
            font-size: 1.1rem;
            margin-bottom: 1.2rem;
            padding-bottom: 0.8rem;
        }
        
        .info-value-modern {
            font-size: 1rem;
        }
        
        .social-link-modern {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .profile-header-modern {
            padding: 1.2rem;
        }
        
        .avatar-modern {
            width: 90px;
            height: 90px;
            border-radius: 18px;
            border-width: 3px;
        }
        
        .profile-header-modern h1 {
            font-size: 1.3rem;
        }
        
        .badge-modern {
            width: 100%;
            justify-content: center;
            margin: 0.25rem 0;
        }
        
        .status-badge-modern {
            width: 100%;
            justify-content: center;
        }
        
        .stat-card-modern {
            padding: 1rem;
        }
        
        .stat-icon-modern {
            width: 40px;
            height: 40px;
            font-size: 1rem;
            border-radius: 10px;
        }
        
        .stat-card-modern .h3 {
            font-size: 1.3rem;
        }
        
        .stat-card-modern .small {
            font-size: 0.7rem;
        }
        
        .info-section-modern {
            padding: 1rem;
        }
        
        .info-title-modern {
            font-size: 1rem;
        }
        
        .info-title-modern i {
            padding: 0.5rem;
            font-size: 1rem;
        }
        
        .info-label-modern {
            font-size: 0.7rem;
        }
        
        .info-value-modern {
            font-size: 0.9rem;
        }
    }

    /* Animações */
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

    .fade-in-up {
        animation: fadeInUp 0.6s ease forwards;
    }

    .delay-1 {
        animation-delay: 0.1s;
    }

    .delay-2 {
        animation-delay: 0.2s;
    }

    .delay-3 {
        animation-delay: 0.3s;
    }

    .delay-4 {
        animation-delay: 0.4s;
    }

    /* Tooltips Modernos */
    [data-tooltip] {
        position: relative;
        cursor: help;
    }

    [data-tooltip]:before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(-10px);
        padding: 0.5rem 1rem;
        background: var(--primary-dark);
        color: white;
        font-size: 0.8rem;
        white-space: nowrap;
        border-radius: 8px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 100;
    }

    [data-tooltip]:hover:before {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(-5px);
    }
</style>
@endsection