@extends('layouts.app')

@section('title', 'Vagas HSE - Oportunidades em Saúde, Segurança e Ambiente')

@section('meta_description', 'Encontre as melhores vagas de emprego em HSE (Saúde, Segurança e Ambiente). Oportunidades para técnicos, engenheiros, auditores e profissionais do setor.')

@section('content')

<section class="jobs-index-section mt-8">
  <div class="container">
    {{-- Hero Section --}}
    <div class="jobs-hero">
      <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="hero-gradient"></div>
      </div>
      <div class="hero-content">
        <div class="hero-text">
          <div class="hero-badge">
            <span class="badge-icon">🚀</span>
            <span class="badge-text">Encontre sua próxima oportunidade</span>
          </div>
          <h1 class="hero-title">Vagas de Emprego em <span class="highlight">HSE</span></h1>
          <p class="hero-subtitle">Oportunidades para técnicos, engenheiros, auditores e profissionais do setor de Saúde, Segurança e Ambiente.</p>
        </div>
        <div class="hero-illustration">
          <div class="illustration-container">
            <div class="illustration-icon">
              <i class="fas fa-hard-hat"></i>
            </div>
            <div class="illustration-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <div class="illustration-icon">
              <i class="fas fa-leaf"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Search Section --}}
    <div class="search-wrapper">
      <div class="search-container">
        <form method="GET" action="{{ route('jobs.index') }}" class="search-form">
          <div class="search-main">
            <div class="search-input-wrapper">
              <i class="search-icon fas fa-search"></i>
              <input type="text" 
                     name="q" 
                     value="{{ request('q') }}"
                     class="search-input"
                     placeholder="Cargo, empresa, palavra-chave...">
              <button class="search-button btn-primary" type="submit">
                <i class="fas fa-search me-2"></i>Buscar
              </button>
            </div>
          </div>

          {{-- Filtros rápidos --}}
          <div class="quick-filters">
            <div class="filters-header">
              <span class="filters-title">Filtrar por:</span>
              @if(request()->hasAny(['location', 'type', 'level']))
                <a href="{{ route('jobs.index', request()->except(['location', 'type', 'level', 'page'])) }}" 
                   class="clear-filters">
                  <i class="fas fa-times-circle"></i> Limpar filtros
                </a>
              @endif
            </div>
            
            <div class="filters-grid">
              <div class="filter-group">
                <select name="location" class="filter-select" onchange="this.form.submit()">
                  <option value="">📍 Localização</option>
                  <option value="Luanda" {{ request('location') == 'Luanda' ? 'selected' : '' }}>Luanda</option>
                  <option value="Benguela" {{ request('location') == 'Benguela' ? 'selected' : '' }}>Benguela</option>
                  <option value="Huíla" {{ request('location') == 'Huíla' ? 'selected' : '' }}>Huíla</option>
                  <option value="Remoto" {{ request('location') == 'Remoto' ? 'selected' : '' }}>Remoto</option>
                  <option value="Híbrido" {{ request('location') == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
                </select>
              </div>
              
              <div class="filter-group">
                <select name="type" class="filter-select" onchange="this.form.submit()">
                  <option value="">⏱️ Tipo de emprego</option>
                  <option value="Tempo Integral" {{ request('type') == 'Tempo Integral' ? 'selected' : '' }}>Tempo Integral</option>
                  <option value="Meio Período" {{ request('type') == 'Meio Período' ? 'selected' : '' }}>Meio Período</option>
                  <option value="Contrato" {{ request('type') == 'Contrato' ? 'selected' : '' }}>Contrato</option>
                  <option value="Estágio" {{ request('type') == 'Estágio' ? 'selected' : '' }}>Estágio</option>
                  <option value="Freelance" {{ request('type') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                </select>
              </div>
              
              <div class="filter-group">
                <select name="level" class="filter-select" onchange="this.form.submit()">
                  <option value="">📊 Nível</option>
                  <option value="Estagiário" {{ request('level') == 'Estagiário' ? 'selected' : '' }}>Estagiário</option>
                  <option value="Júnior" {{ request('level') == 'Júnior' ? 'selected' : '' }}>Júnior</option>
                  <option value="Pleno" {{ request('level') == 'Pleno' ? 'selected' : '' }}>Pleno</option>
                  <option value="Sênior" {{ request('level') == 'Sênior' ? 'selected' : '' }}>Sênior</option>
                  <option value="Especialista" {{ request('level') == 'Especialista' ? 'selected' : '' }}>Especialista</option>
                </select>
              </div>
              
              <div class="filter-group">
                <button type="button" class="filter-advanced" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                  <i class="fas fa-sliders-h me-2"></i> Mais filtros
                </button>
              </div>
            </div>
          </div>

          {{-- Filtros avançados --}}
          <div class="collapse advanced-filters-wrapper" id="advancedFilters">
            <div class="advanced-filters">
              <div class="advanced-header">
                <h4><i class="fas fa-filter me-2"></i>Filtros Avançados</h4>
                <button type="button" class="btn-close" data-bs-toggle="collapse" data-bs-target="#advancedFilters"></button>
              </div>
              <div class="filters-grid-advanced">
                <div class="filter-column">
                  <label class="filter-label">Salário</label>
                  <select name="salary" class="form-select">
                    <option value="">💰 Qualquer salário</option>
                    <option value="0-50000" {{ request('salary') == '0-50000' ? 'selected' : '' }}>Até 50.000 Kz</option>
                    <option value="50000-150000" {{ request('salary') == '50000-150000' ? 'selected' : '' }}>50.000 - 150.000 Kz</option>
                    <option value="150000-300000" {{ request('salary') == '150000-300000' ? 'selected' : '' }}>150.000 - 300.000 Kz</option>
                    <option value="300000+" {{ request('salary') == '300000+' ? 'selected' : '' }}>Acima de 300.000 Kz</option>
                  </select>
                </div>
                
                <div class="filter-column">
                  <label class="filter-label">Categoria</label>
                  <select name="category" class="form-select">
                    <option value="">🏷️ Todas categorias</option>
                    <option value="seguranca" {{ request('category') == 'seguranca' ? 'selected' : '' }}>Segurança</option>
                    <option value="saude" {{ request('category') == 'saude' ? 'selected' : '' }}>Saúde</option>
                    <option value="ambiente" {{ request('category') == 'ambiente' ? 'selected' : '' }}>Ambiente</option>
                    <option value="qualidade" {{ request('category') == 'qualidade' ? 'selected' : '' }}>Qualidade</option>
                  </select>
                </div>
                
                <div class="filter-column">
                  <label class="filter-label">Data de publicação</label>
                  <select name="date_posted" class="form-select">
                    <option value="">📅 Qualquer data</option>
                    <option value="1" {{ request('date_posted') == '1' ? 'selected' : '' }}>Últimas 24h</option>
                    <option value="7" {{ request('date_posted') == '7' ? 'selected' : '' }}>Última semana</option>
                    <option value="30" {{ request('date_posted') == '30' ? 'selected' : '' }}>Último mês</option>
                  </select>
                </div>
              </div>
              <div class="advanced-actions">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-check me-2"></i>Aplicar filtros
                </button>
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">
                  <i class="fas fa-times me-2"></i>Limpar tudo
                </a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- Stats Dashboard --}}
    <div class="stats-dashboard">
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon-wrapper">
            <i class="fas fa-briefcase"></i>
          </div>
          <div class="stat-content">
            <div class="stat-number" data-count="{{ $jobs->total() ?? 0 }}">0</div>
            <div class="stat-label">Vagas ativas</div>
          </div>
          <div class="stat-trend">
            <i class="fas fa-arrow-up text-success"></i> +12%
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon-wrapper">
            <i class="fas fa-star"></i>
          </div>
          <div class="stat-content">
            <div class="stat-number" data-count="{{ $sponsoredCount ?? 0 }}">0</div>
            <div class="stat-label">Patrocinadas</div>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon-wrapper">
            <i class="fas fa-laptop-house"></i>
          </div>
          <div class="stat-content">
            <div class="stat-number" data-count="{{ $remoteCount ?? 0 }}">0</div>
            <div class="stat-label">Remotas</div>
          </div>
        </div>
        
        <div class="stat-card">
          <div class="stat-icon-wrapper">
            <i class="fas fa-bolt"></i>
          </div>
          <div class="stat-content">
            <div class="stat-number" data-count="{{ $newToday ?? 0 }}">0</div>
            <div class="stat-label">Novas hoje</div>
          </div>
        </div>
      </div>
    </div>

    <div class="main-content-wrapper">
      <div class="row g-4">
        {{-- MAIN CONTENT --}}
        <div class="col-lg-8">
          {{-- Results Header --}}
          <div class="results-header">
            <div class="results-info">
              <h2 class="results-title">
                @if(request()->hasAny(['q', 'location', 'type', 'level']))
                  <i class="fas fa-search text-primary me-2"></i>Resultados da busca
                @else
                  <i class="fas fa-list text-primary me-2"></i>Todas as vagas
                @endif
              </h2>
              <div class="results-count">
                <span class="badge bg-light text-dark">
                  {{ $jobs->total() }} {{ $jobs->total() == 1 ? 'vaga' : 'vagas' }}
                </span>
              </div>
            </div>
            
            <div class="results-controls">
              <div class="sort-control">
                <select class="sort-select" onchange="window.location.href = this.value">
                  <option value="{{ route('jobs.index', array_merge(request()->except('page'), ['sort' => 'newest'])) }}"
                          {{ request('sort') == 'newest' ? 'selected' : '' }}>
                    Ordenar: Mais recentes
                  </option>
                  <option value="{{ route('jobs.index', array_merge(request()->except('page'), ['sort' => 'sponsored'])) }}"
                          {{ request('sort') == 'sponsored' ? 'selected' : '' }}>
                    Ordenar: Patrocinadas primeiro
                  </option>
                  <option value="{{ route('jobs.index', array_merge(request()->except('page'), ['sort' => 'closing_soon'])) }}"
                          {{ request('sort') == 'closing_soon' ? 'selected' : '' }}>
                    Ordenar: A encerrar breve
                  </option>
                </select>
              </div>
            </div>
          </div>

          @if($jobs->count())
            {{-- Jobs Grid --}}
            <div class="jobs-grid-modern">
              @foreach($jobs as $job)
                <div class="job-card-modern {{ $job->is_sponsored ? 'sponsored' : '' }}">
                  <div class="job-card-header">
                    <div class="job-company-badge">
                      @if($job->company_logo)
                        <img src="{{ asset('storage/'.$job->company_logo) }}" 
                             alt="{{ $job->company }}"
                             class="company-logo-modern">
                      @else
                        <div class="company-logo-placeholder-modern">
                          {{ substr($job->company, 0, 2) }}
                        </div>
                      @endif
                      <div class="company-badge-info">
                        <span class="company-name-modern">{{ $job->company }}</span>
                        <span class="job-location-modern">
                          <i class="fas fa-map-marker-alt"></i>
                          {{ $job->location ?? 'Local não especificado' }}
                        </span>
                      </div>
                    </div>
                    
                    <div class="job-card-badges">
                      @if($job->is_sponsored)
                        <span class="badge sponsored-modern">
                          <i class="fas fa-bolt"></i> Patrocinada
                        </span>
                      @endif
                      @if($job->is_remote)
                        <span class="badge remote-modern">
                          <i class="fas fa-laptop-house"></i> Remoto
                        </span>
                      @endif
                      @if($job->is_urgent)
                        <span class="badge urgent-modern">
                          <i class="fas fa-clock"></i> Urgente
                        </span>
                      @endif
                    </div>
                  </div>

                  <div class="job-card-body">
                    <h3 class="job-title-modern">
                      <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a>
                    </h3>
                    
                    <p class="job-description-modern">
                      {{ \Illuminate\Support\Str::limit(strip_tags($job->description), 180) }}
                    </p>
                    
                    <div class="job-tags-modern">
                      @if($job->level)
                        <span class="tag level-tag-modern">
                          <i class="fas fa-user-graduate"></i> {{ $job->level }}
                        </span>
                      @endif
                      
                      @if($job->type)
                        <span class="tag type-tag-modern">
                          <i class="fas fa-clock"></i> {{ $job->type }}
                        </span>
                      @endif
                      
                      @if($job->salary_range)
                        <span class="tag salary-tag-modern">
                          <i class="fas fa-money-bill-wave"></i> {{ $job->salary_range }}
                        </span>
                      @endif
                    </div>
                  </div>

                  <div class="job-card-footer">
                    <div class="job-meta-modern">
                      <span class="meta-item-modern">
                        <i class="fas fa-calendar-alt"></i>
                        Publicada {{ optional($job->published_at)->diffForHumans() }}
                      </span>
                      @if($job->closing_date)
                        <span class="meta-item-modern closing-date-modern">
                          <i class="fas fa-hourglass-half"></i>
                          Encerra em {{ \Carbon\Carbon::parse($job->closing_date)->format('d/m/Y') }}
                        </span>
                      @endif
                    </div>
                    
                    <div class="job-actions-modern">
                      <a href="{{ route('jobs.show', $job->slug) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i> Detalhes
                      </a>
                      @if($job->apply_link)
                        <a href="{{ $job->apply_link }}" 
                           target="_blank" 
                           class="btn btn-primary btn-sm">
                          <i class="fas fa-paper-plane me-1"></i> Candidatar-se
                        </a>
                      @endif
                      <button class="btn btn-icon save-job-modern" data-job-id="{{ $job->id }}">
                        <i class="far fa-bookmark"></i>
                      </button>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pagination-modern">
              <div class="pagination-info">
                <span>Mostrando {{ $jobs->firstItem() ?? 0 }}–{{ $jobs->lastItem() ?? 0 }} de {{ $jobs->total() }} vagas</span>
              </div>
              <nav>
                <ul class="pagination">
                  <li class="page-item {{ $jobs->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $jobs->previousPageUrl() }}">
                      <i class="fas fa-chevron-left"></i>
                    </a>
                  </li>
                  
                  @php
                    $current = $jobs->currentPage();
                    $last = $jobs->lastPage();
                    $start = max(1, $current - 1);
                    $end = min($last, $current + 1);
                  @endphp

                  @if($start > 1)
                    <li class="page-item">
                      <a class="page-link" href="{{ $jobs->url(1) }}">1</a>
                    </li>
                    @if($start > 2)
                      <li class="page-item disabled">
                        <span class="page-link">...</span>
                      </li>
                    @endif
                  @endif

                  @for($i = $start; $i <= $end; $i++)
                    <li class="page-item {{ $i == $current ? 'active' : '' }}">
                      <a class="page-link" href="{{ $jobs->url($i) }}">{{ $i }}</a>
                    </li>
                  @endfor

                  @if($end < $last)
                    @if($end < $last - 1)
                      <li class="page-item disabled">
                        <span class="page-link">...</span>
                      </li>
                    @endif
                    <li class="page-item">
                      <a class="page-link" href="{{ $jobs->url($last) }}">{{ $last }}</a>
                    </li>
                  @endif

                  <li class="page-item {{ !$jobs->hasMorePages() ? 'disabled' : '' }}">
                    <a class="page-link" href="{{ $jobs->nextPageUrl() }}">
                      <i class="fas fa-chevron-right"></i>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>

          @else
            {{-- No Results --}}
            <div class="no-results-modern">
              <div class="no-results-icon">
                <i class="fas fa-search fa-4x"></i>
              </div>
              <h3>Nenhuma vaga encontrada</h3>
              <p class="no-results-text">
                @if(request()->hasAny(['q', 'location', 'type', 'level']))
                  Não encontramos vagas com os critérios selecionados.
                @else
                  Não há vagas disponíveis no momento.
                @endif
              </p>
              <div class="no-results-actions">
                <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                  <i class="fas fa-sync-alt me-2"></i> Ver todas as vagas
                </a>
                <button class="btn btn-outline-secondary" onclick="document.querySelector('.search-input').focus()">
                  <i class="fas fa-edit me-2"></i> Alterar busca
                </button>
              </div>
            </div>
          @endif
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-4">
          {{-- Categories Widget --}}
          <div class="sidebar-widget-modern categories-widget">
            <div class="widget-header-modern">
              <h3>
                <i class="fas fa-tags me-2"></i>
                Categorias HSE
              </h3>
            </div>
            <div class="widget-content-modern">
              <div class="categories-list-modern">
                @foreach([
                  ['seguranca', 'fas fa-hard-hat', 'Segurança do Trabalho'],
                  ['saude', 'fas fa-heartbeat', 'Saúde Ocupacional'],
                  ['ambiente', 'fas fa-leaf', 'Meio Ambiente'],
                  ['qualidade', 'fas fa-chart-line', 'Qualidade'],
                  ['consultoria', 'fas fa-briefcase', 'Consultoria']
                ] as $category)
                  <a href="{{ route('jobs.index', ['category' => $category[0]]) }}" 
                     class="category-item-modern {{ request('category') == $category[0] ? 'active' : '' }}">
                    <div class="category-icon">
                      <i class="{{ $category[1] }}"></i>
                    </div>
                    <div class="category-name">{{ $category[2] }}</div>
                    <div class="category-count">
                      ({{ $categoryCounts[$category[0]] ?? 0 }})
                    </div>
                  </a>
                @endforeach
              </div>
            </div>
          </div>

          {{-- Tips Widget --}}
          <div class="sidebar-widget-modern tips-widget">
            <div class="widget-header-modern">
              <h3>
                <i class="fas fa-lightbulb me-2"></i>
                Dicas para sua busca
              </h3>
            </div>
            <div class="widget-content-modern">
              <div class="tips-list-modern">
                <div class="tip-item">
                  <div class="tip-icon">
                    <i class="fas fa-check-circle"></i>
                  </div>
                  <div class="tip-content">
                    <strong>Use palavras-chave específicas</strong>
                    <p>Ex: "Engenheiro Ambiental", "Técnico em Segurança"</p>
                  </div>
                </div>
                <div class="tip-item">
                  <div class="tip-icon">
                    <i class="fas fa-check-circle"></i>
                  </div>
                  <div class="tip-content">
                    <strong>Salve vagas interessantes</strong>
                    <p>Clique no ícone de favorito para salvar</p>
                  </div>
                </div>
                <div class="tip-item">
                  <div class="tip-icon">
                    <i class="fas fa-check-circle"></i>
                  </div>
                  <div class="tip-content">
                    <strong>Ative alertas por email</strong>
                    <p>Receba novas vagas automaticamente</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- Newsletter Widget --}}
          <div class="sidebar-widget-modern newsletter-widget">
            <div class="widget-header-modern">
              <h3>
                <i class="fas fa-envelope me-2"></i>
                Receba alertas de vagas
              </h3>
            </div>
            <div class="widget-content-modern">
              <p class="newsletter-text">Cadastre-se para receber as melhores oportunidades em HSE</p>
              <form action="{{ route('subscribers.store') }}" method="POST" class="newsletter-form-modern">
                @csrf
                <input type="hidden" name="source" value="jobs_page">
                
                <div class="form-group-modern">
                  <input type="email" 
                         name="email" 
                         class="form-control-modern" 
                         placeholder="seu@email.com"
                         required>
                </div>
                
                <div class="form-group-modern">
                  <select name="job_alerts_category" class="form-select-modern">
                    <option value="">Área de interesse</option>
                    <option value="seguranca">Segurança do Trabalho</option>
                    <option value="saude">Saúde Ocupacional</option>
                    <option value="ambiente">Meio Ambiente</option>
                  </select>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">
                  <i class="fas fa-paper-plane me-2"></i> Cadastrar
                </button>
              </form>
              <div class="privacy-note">
                <i class="fas fa-lock me-1"></i>
                <small>Seus dados estão seguros. Sem spam.</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


@endsection

@push('styles')
<style>
/* Variáveis de cores mantidas */
:root {
  --primary-color: #1a365d;
  --primary-dark: #004d99;
  --primary-light: #e6f2ff;
  --secondary-color: #00a859;
  --secondary-dark: #008e4c;
  --accent-color: #ff6b35;
  --neutral-light: #f8f9fa;
  --neutral-medium: #dee2e6;
  --neutral-dark: #343a40;
  --neutral-text: #6c757d;
  --danger-color: #dc3545;
  --success-color: #28a745;
  --shadow: 0 4px 12px rgba(0,0,0,0.08);
  --shadow-large: 0 8px 24px rgba(0,0,0,0.12);
}

.jobs-index-section {
  padding-top: 30px;
  background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
  min-height: 100vh;
}

/* Hero Section */
.jobs-hero {
  position: relative;
  background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
  border-radius: 24px;
  padding: 60px 40px;
  margin-bottom: 30px;
  overflow: hidden;
  box-shadow: var(--shadow-large);
}

.hero-background {
  position: absolute;
  inset: 0;
  background: linear-gradient(45deg, rgba(255,255,255,0.05) 0%, transparent 100%);
}

.hero-pattern {
  position: absolute;
  top: -50px;
  right: -50px;
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
  background-size: 30px 30px;
  opacity: 0.3;
}

.hero-content {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 40px;
}

.hero-text {
  flex: 1;
  color: white;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  padding: 8px 16px;
  border-radius: 50px;
  margin-bottom: 20px;
}

.badge-icon {
  font-size: 1.2rem;
}

.badge-text {
  font-size: 0.9rem;
  font-weight: 600;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  line-height: 1.1;
  margin-bottom: 15px;
  color: white;
}

.highlight {
  color: #ffd166;
  position: relative;
}

.highlight::after {
  content: '';
  position: absolute;
  bottom: 5px;
  left: 0;
  width: 100%;
  height: 8px;
  background: rgba(255, 209, 102, 0.2);
  z-index: -1;
}

.hero-subtitle {
  font-size: 1.2rem;
  opacity: 0.9;
  max-width: 600px;
  line-height: 1.6;
}

.hero-illustration {
  display: flex;
  align-items: center;
}

.illustration-container {
  display: flex;
  gap: 15px;
}

.illustration-icon {
  width: 70px;
  height: 70px;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  color: white;
  animation: float 3s ease-in-out infinite;
}

.illustration-icon:nth-child(2) {
  animation-delay: 0.2s;
}

.illustration-icon:nth-child(3) {
  animation-delay: 0.4s;
}

/* Search Section */
.search-wrapper {
  margin-bottom: 40px;
}

.search-container {
  background: white;
  border-radius: 20px;
  padding: 30px;
  box-shadow: var(--shadow-large);
  border: 1px solid rgba(0,0,0,0.05);
}

.search-main {
  margin-bottom: 20px;
}

.search-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  gap: 10px;
}

.search-icon {
  position: absolute;
  left: 20px;
  color: var(--neutral-text);
  font-size: 1.1rem;
  z-index: 2;
}

.search-input {
  flex: 1;
  padding: 18px 20px 18px 50px;
  border: 2px solid var(--neutral-medium);
  border-radius: 12px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: white;
}

.search-input:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
  outline: none;
}

.search-button {
  padding: 18px 30px;
  border-radius: 12px;
  font-weight: 600;
  font-size: 1rem;
  white-space: nowrap;
  background: var(--accent-color);
  border: none;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
}

.search-button:hover {
  background: #ff5a1f;
  transform: translateY(-2px);
}

/* Quick Filters */
.quick-filters {
  border-top: 1px solid var(--neutral-medium);
  padding-top: 20px;
}

.filters-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.filters-title {
  font-weight: 600;
  color: var(--neutral-dark);
}

.clear-filters {
  color: var(--neutral-text);
  text-decoration: none;
  font-size: 0.9rem;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  transition: color 0.3s ease;
}

.clear-filters:hover {
  color: var(--danger-color);
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
}

.filter-group {
  position: relative;
}

.filter-select {
  width: 100%;
  padding: 12px 40px 12px 15px;
  border: 2px solid var(--neutral-medium);
  border-radius: 10px;
  font-size: 0.95rem;
  background: white;
  cursor: pointer;
  appearance: none;
  transition: all 0.3s ease;
}

.filter-select:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
  outline: none;
}

.filter-group::after {
  content: '⌄';
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--neutral-text);
  pointer-events: none;
}

.filter-advanced {
  width: 100%;
  padding: 12px 15px;
  border: 2px dashed var(--neutral-medium);
  border-radius: 10px;
  background: transparent;
  color: var(--neutral-text);
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.filter-advanced:hover {
  border-color: var(--primary-color);
  color: var(--primary-color);
  background: var(--primary-light);
}

/* Advanced Filters */
.advanced-filters-wrapper {
  margin-top: 20px;
}

.advanced-filters {
  background: var(--neutral-light);
  border-radius: 15px;
  padding: 25px;
  border: 1px solid var(--neutral-medium);
}

.advanced-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.advanced-header h4 {
  margin: 0;
  color: var(--neutral-dark);
  font-weight: 600;
  display: flex;
  align-items: center;
}

.btn-close {
  background: none;
  border: none;
  font-size: 1.2rem;
  color: var(--neutral-text);
  cursor: pointer;
  padding: 5px;
  transition: color 0.3s ease;
}

.btn-close:hover {
  color: var(--danger-color);
}

.filters-grid-advanced {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 25px;
}

.filter-column {
  display: flex;
  flex-direction: column;
}

.filter-label {
  font-weight: 600;
  color: var(--neutral-dark);
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.advanced-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
}

/* Stats Dashboard */
.stats-dashboard {
  margin-bottom: 40px;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
}

.stat-card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: var(--shadow);
  border: 2px solid transparent;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 15px;
}

.stat-card:hover {
  transform: translateY(-5px);
  border-color: var(--primary-light);
  box-shadow: var(--shadow-large);
}

.stat-icon-wrapper {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  background: var(--primary-light);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: var(--primary-color);
}

.stat-content {
  flex: 1;
}

.stat-number {
  font-size: 2.2rem;
  font-weight: 800;
  color: var(--neutral-dark);
  line-height: 1;
}

.stat-label {
  font-size: 0.9rem;
  color: var(--neutral-text);
  font-weight: 600;
}

.stat-trend {
  font-size: 0.85rem;
  color: var(--success-color);
  font-weight: 600;
}

/* Results Header */
.results-header {
  background: white;
  border-radius: 15px;
  padding: 20px 25px;
  margin-bottom: 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
  box-shadow: var(--shadow);
}

.results-info {
  display: flex;
  align-items: center;
  gap: 15px;
}

.results-title {
  margin: 0;
  font-size: 1.4rem;
  font-weight: 700;
  color: var(--neutral-dark);
  display: flex;
  align-items: center;
}

.results-count .badge {
  font-size: 0.9rem;
  padding: 8px 15px;
  border-radius: 20px;
}

.sort-control {
  min-width: 220px;
}

.sort-select {
  width: 100%;
  padding: 10px 15px;
  border: 2px solid var(--neutral-medium);
  border-radius: 8px;
  font-size: 0.95rem;
  background: white;
  cursor: pointer;
  transition: all 0.3s ease;
}

.sort-select:focus {
  border-color: var(--primary-color);
  outline: none;
  box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

/* Jobs Grid Modern */
.jobs-grid-modern {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-bottom: 40px;
}

.job-card-modern {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid transparent;
  position: relative;
}

.job-card-modern:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-large);
  border-color: var(--primary-light);
}

.job-card-modern.sponsored {
  border-color: var(--accent-color);
}

.job-card-header {
  padding: 25px 25px 20px;
  background: linear-gradient(90deg, var(--primary-light) 0%, rgba(230, 242, 255, 0.3) 100%);
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  flex-wrap: wrap;
  gap: 15px;
}

.job-company-badge {
  display: flex;
  align-items: center;
  gap: 15px;
}

.company-logo-modern {
  width: 70px;
  height: 70px;
  border-radius: 12px;
  object-fit: contain;
  border: 2px solid white;
  background: white;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.company-logo-placeholder-modern {
  width: 70px;
  height: 70px;
  border-radius: 12px;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.8rem;
  font-weight: 700;
  border: 2px solid white;
}

.company-badge-info {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.company-name-modern {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--neutral-dark);
}

.job-location-modern {
  font-size: 0.9rem;
  color: var(--neutral-text);
  display: flex;
  align-items: center;
  gap: 5px;
}

.job-card-badges {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.job-card-badges .badge {
  padding: 6px 12px;
  font-size: 0.8rem;
  font-weight: 600;
  border-radius: 20px;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.sponsored-modern {
  background: rgba(255, 107, 53, 0.15);
  color: var(--accent-color);
  border: 1px solid rgba(255, 107, 53, 0.3);
}

.remote-modern {
  background: rgba(0, 168, 89, 0.15);
  color: var(--secondary-color);
  border: 1px solid rgba(0, 168, 89, 0.3);
}

.urgent-modern {
  background: rgba(220, 53, 69, 0.15);
  color: var(--danger-color);
  border: 1px solid rgba(220, 53, 69, 0.3);
}

.job-card-body {
  padding: 20px 25px;
}

.job-title-modern {
  font-size: 1.3rem;
  font-weight: 700;
  margin-bottom: 12px;
  line-height: 1.3;
}

.job-title-modern a {
  color: var(--neutral-dark);
  text-decoration: none;
  transition: color 0.3s ease;
}

.job-title-modern a:hover {
  color: var(--primary-color);
}

.job-description-modern {
  color: var(--neutral-text);
  line-height: 1.6;
  margin-bottom: 20px;
  font-size: 0.95rem;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.job-tags-modern {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.tag {
  padding: 8px 14px;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.level-tag-modern {
  background: rgba(0, 102, 204, 0.1);
  color: var(--primary-color);
  border: 1px solid rgba(0, 102, 204, 0.2);
}

.type-tag-modern {
  background: rgba(108, 117, 125, 0.1);
  color: var(--neutral-text);
  border: 1px solid rgba(108, 117, 125, 0.2);
}

.salary-tag-modern {
  background: rgba(0, 168, 89, 0.1);
  color: var(--secondary-color);
  border: 1px solid rgba(0, 168, 89, 0.2);
}

.job-card-footer {
  padding: 20px 25px;
  border-top: 1px solid var(--neutral-medium);
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 15px;
}

.job-meta-modern {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.meta-item-modern {
  font-size: 0.85rem;
  color: var(--neutral-text);
  display: inline-flex;
  align-items: center;
  gap: 5px;
}

.closing-date-modern {
  color: var(--danger-color);
  font-weight: 600;
}

.job-actions-modern {
  display: flex;
  gap: 10px;
  align-items: center;
}

.btn-icon {
  width: 40px;
  height: 40px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  border: 2px solid var(--neutral-medium);
  background: white;
  color: var(--neutral-text);
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-icon:hover {
  border-color: var(--primary-color);
  color: var(--primary-color);
}

/* Pagination Modern */
.pagination-modern {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: var(--shadow);
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}

.pagination-info {
  font-size: 0.95rem;
  color: var(--neutral-text);
  font-weight: 500;
}

.pagination {
  margin: 0;
  gap: 8px;
}

.page-item.active .page-link {
  background: var(--primary-color);
  border-color: var(--primary-color);
  color: white;
}

.page-link {
  padding: 10px 16px;
  border: 2px solid var(--neutral-medium);
  color: var(--neutral-dark);
  font-weight: 600;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.page-link:hover {
  background: var(--primary-light);
  border-color: var(--primary-color);
  color: var(--primary-color);
}

/* No Results Modern */
.no-results-modern {
  background: white;
  border-radius: 20px;
  padding: 60px 40px;
  text-align: center;
  box-shadow: var(--shadow);
}

.no-results-icon {
  color: var(--neutral-medium);
  margin-bottom: 25px;
  opacity: 0.6;
}

.no-results-modern h3 {
  font-size: 1.8rem;
  color: var(--neutral-dark);
  margin-bottom: 15px;
}

.no-results-text {
  color: var(--neutral-text);
  font-size: 1.1rem;
  margin-bottom: 30px;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}

.no-results-actions {
  display: flex;
  gap: 15px;
  justify-content: center;
  flex-wrap: wrap;
}

/* Sidebar Widgets Modern */
.sidebar-widget-modern {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  margin-bottom: 25px;
  box-shadow: var(--shadow);
  border: 2px solid var(--neutral-medium);
}

.widget-header-modern {
  background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
  color: white;
  padding: 20px;
}

.widget-header-modern h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: 700;
  display: flex;
  align-items: center;
}

.widget-content-modern {
  padding: 25px;
}

/* Categories Modern */
.categories-list-modern {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.category-item-modern {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px;
  border-radius: 12px;
  text-decoration: none;
  color: var(--neutral-text);
  transition: all 0.3s ease;
  border: 2px solid transparent;
  background: var(--neutral-light);
}

.category-item-modern:hover {
  background: white;
  border-color: var(--primary-light);
  color: var(--primary-color);
  transform: translateX(5px);
}

.category-item-modern.active {
  background: var(--primary-light);
  border-color: var(--primary-color);
  color: var(--primary-color);
  font-weight: 600;
}

.category-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary-color);
  font-size: 1.1rem;
  border: 2px solid var(--neutral-medium);
}

.category-name {
  flex: 1;
  font-size: 0.95rem;
  font-weight: 500;
}

.category-count {
  font-size: 0.85rem;
  color: var(--neutral-text);
  opacity: 0.7;
}

/* Tips Modern */
.tips-list-modern {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.tip-item {
  display: flex;
  gap: 15px;
}

.tip-icon {
  color: var(--secondary-color);
  font-size: 1.2rem;
  margin-top: 2px;
}

.tip-content {
  flex: 1;
}

.tip-content strong {
  display: block;
  color: var(--neutral-dark);
  margin-bottom: 4px;
  font-size: 0.95rem;
}

.tip-content p {
  margin: 0;
  color: var(--neutral-text);
  font-size: 0.9rem;
  line-height: 1.4;
}

/* Newsletter Modern */
.newsletter-text {
  color: var(--neutral-text);
  font-size: 0.95rem;
  margin-bottom: 20px;
  line-height: 1.5;
}

.newsletter-form-modern .form-group-modern {
  margin-bottom: 15px;
}

.form-control-modern,
.form-select-modern {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid var(--neutral-medium);
  border-radius: 10px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.form-control-modern:focus,
.form-select-modern:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
  outline: none;
}

.privacy-note {
  margin-top: 15px;
  text-align: center;
  color: var(--neutral-text);
  font-size: 0.85rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
}

/* CTA Modern */
.cta-section-modern {
  background: linear-gradient(135deg, var(--secondary-dark) 0%, var(--secondary-color) 100%);
  color: white;
  padding: 80px 0;
  margin-top: 60px;
  position: relative;
  overflow: hidden;
}

.cta-section-modern::before {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
  background-size: 30px 30px;
  opacity: 0.3;
}

.cta-content-modern {
  position: relative;
  z-index: 1;
  text-align: center;
  max-width: 800px;
  margin: 0 auto;
}

.cta-icon {
  font-size: 4rem;
  margin-bottom: 25px;
  opacity: 0.9;
}

.cta-title-modern {
  font-size: 2.8rem;
  font-weight: 800;
  margin-bottom: 15px;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.cta-text-modern {
  font-size: 1.2rem;
  opacity: 0.9;
  margin-bottom: 40px;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
}

.cta-buttons {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
}

.cta-buttons .btn {
  padding: 16px 32px;
  border-radius: 30px;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.cta-buttons .btn-light {
  background: white;
  color: var(--secondary-color);
  border: 2px solid white;
}

.cta-buttons .btn-light:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.cta-buttons .btn-outline-light {
  border: 2px solid white;
  background: transparent;
  color: white;
}

.cta-buttons .btn-outline-light:hover {
  background: white;
  color: var(--secondary-color);
  transform: translateY(-3px);
}

/* Animations */
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

/* Responsive Design */
@media (max-width: 1200px) {
  .hero-title {
    font-size: 3rem;
  }
  
  .illustration-icon {
    width: 60px;
    height: 60px;
    font-size: 1.8rem;
  }
}

@media (max-width: 992px) {
  .hero-content {
    flex-direction: column;
    text-align: center;
    gap: 30px;
  }
  
  .hero-badge {
    margin-left: auto;
    margin-right: auto;
  }
  
  .illustration-container {
    justify-content: center;
  }
  
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .jobs-hero {
    padding: 40px 25px;
    border-radius: 20px;
  }
  
  .hero-title {
    font-size: 2.5rem;
  }
  
  .hero-subtitle {
    font-size: 1.1rem;
  }
  
  .search-input-wrapper {
    flex-direction: column;
  }
  
  .search-input,
  .search-button {
    width: 100%;
  }
  
  .filters-grid {
    grid-template-columns: 1fr;
  }
  
  .results-header {
    flex-direction: column;
    align-items: stretch;
    text-align: center;
  }
  
  .results-info {
    flex-direction: column;
    gap: 10px;
  }
  
  .job-card-header {
    flex-direction: column;
    align-items: stretch;
  }
  
  .job-card-footer {
    flex-direction: column;
    align-items: stretch;
    gap: 20px;
  }
  
  .job-actions-modern {
    width: 100%;
    justify-content: center;
  }
  
  .pagination-modern {
    flex-direction: column;
    text-align: center;
    gap: 15px;
  }
  
  .cta-title-modern {
    font-size: 2.2rem;
  }
  
  .cta-buttons {
    flex-direction: column;
    align-items: center;
  }
  
  .cta-buttons .btn {
    width: 100%;
    max-width: 300px;
  }
}

@media (max-width: 576px) {
  .hero-title {
    font-size: 2rem;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .stat-card {
    padding: 20px;
  }
  
  .job-tags-modern {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .pagination {
    flex-wrap: wrap;
    justify-content: center;
  }
  
  .no-results-actions {
    flex-direction: column;
  }
  
  .no-results-actions .btn {
    width: 100%;
  }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Animated counters
  const statNumbers = document.querySelectorAll('.stat-number[data-count]');
  statNumbers.forEach(stat => {
    const target = parseInt(stat.dataset.count);
    const increment = Math.ceil(target / 50);
    let current = 0;
    
    const timer = setInterval(() => {
      current += increment;
      if(current >= target) {
        current = target;
        clearInterval(timer);
        stat.textContent = target.toLocaleString('pt-BR');
      } else {
        stat.textContent = Math.floor(current).toLocaleString('pt-BR');
      }
    }, 30);
  });
  
  // Save job functionality
  const saveButtons = document.querySelectorAll('.save-job-modern');
  saveButtons.forEach(button => {
    button.addEventListener('click', function() {
      const jobId = this.dataset.jobId;
      const icon = this.querySelector('i');
      
      if (icon.classList.contains('far')) {
        // Save
        icon.classList.remove('far');
        icon.classList.add('fas');
        this.style.color = 'var(--accent-color)';
        this.style.borderColor = 'var(--accent-color)';
        
        showToast('Vaga salva nos favoritos!', 'success');
      } else {
        // Unsave
        icon.classList.remove('fas');
        icon.classList.add('far');
        this.style.color = '';
        this.style.borderColor = '';
        
        showToast('Vaga removida dos favoritos.', 'info');
      }
    });
  });
  
  // Newsletter form
  const newsletterForm = document.querySelector('.newsletter-form-modern');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processando...';
      
      setTimeout(() => {
        showToast('Inscrição realizada com sucesso!', 'success');
        this.reset();
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      }, 1500);
    });
  }
  
  // Intersection Observer for job cards
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, { threshold: 0.1 });
  
  document.querySelectorAll('.job-card-modern').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
  });
  
  // Toast notification function
  function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerHTML = `
      <div class="toast-content">
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
        <span>${message}</span>
      </div>
      <button class="toast-close">
        <i class="fas fa-times"></i>
      </button>
    `;
    
    document.body.appendChild(toast);
    
    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => {
      toast.classList.add('hide');
      setTimeout(() => toast.remove(), 300);
    });
    
    setTimeout(() => {
      toast.classList.add('hide');
      setTimeout(() => toast.remove(), 300);
    }, 4000);
  }
  
  // Add toast styles
  const style = document.createElement('style');
  style.textContent = `
    .toast-notification {
      position: fixed;
      top: 20px;
      right: 20px;
      background: white;
      color: var(--neutral-dark);
      padding: 16px 20px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 15px;
      z-index: 9999;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      border-left: 4px solid var(--primary-color);
      animation: slideInRight 0.3s ease;
      max-width: 350px;
    }
    
    .toast-notification.success {
      border-left-color: var(--success-color);
    }
    
    .toast-notification.info {
      border-left-color: var(--primary-color);
    }
    
    .toast-content {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .toast-close {
      background: none;
      border: none;
      color: var(--neutral-text);
      cursor: pointer;
      font-size: 1rem;
      padding: 5px;
      transition: color 0.3s ease;
    }
    
    .toast-close:hover {
      color: var(--danger-color);
    }
    
    @keyframes slideInRight {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    
    .toast-notification.hide {
      animation: slideOutRight 0.3s ease forwards;
    }
    
    @keyframes slideOutRight {
      to {
        transform: translateX(100%);
        opacity: 0;
      }
    }
  `;
  document.head.appendChild(style);
});
</script>
@endpush