@extends('layouts.app')

@section('title', $job->title . ' - Vagas HSE')

@section('content')
<section class="job-detail-section mt-8">
  <div class="container">
    {{-- Job Hero Section --}}
    <div class="job-hero">
      <div class="hero-background">
        <div class="hero-pattern"></div>
        <div class="hero-gradient"></div>
      </div>
      <div class="hero-content">
        <div class="hero-main">
          {{-- Job Badges --}}
          <div class="job-badges">
            @if($job->is_sponsored)
              <span class="badge sponsored-badge">
                <i class="fas fa-bolt me-1"></i> Patrocinada
              </span>
            @endif
            @if($job->is_featured)
              <span class="badge featured-badge">
                <i class="fas fa-star me-1"></i> Destaque
              </span>
            @endif
            @if($job->is_urgent)
              <span class="badge urgent-badge">
                <i class="fas fa-clock me-1"></i> Urgente
              </span>
            @endif
          </div>

          {{-- Job Title --}}
          <h1 class="job-title">{{ $job->title }}</h1>

          {{-- Company Info --}}
          <div class="company-info">
            <div class="company-main">
              <i class="fas fa-building company-icon"></i>
              <span class="company-name">{{ $job->company ?? 'Empresa não informada' }}</span>
              @if($job->company_logo)
                <img src="{{ asset('storage/'.$job->owner->company->logo_path) }}" 
                     alt="{{ $job->company }}"
                     class="company-logo">
              @endif
            </div>
          </div>

          {{-- Job Meta --}}
          <div class="job-meta-grid">
            <div class="meta-item">
              <div class="meta-icon">
                <i class="fas fa-location-dot"></i>
              </div>
              <div class="meta-content">
                <span class="meta-label">Localização</span>
                <span class="meta-value">{{ $job->location ?? 'Não informada' }}</span>
              </div>
            </div>

            @if($job->type)
              <div class="meta-item">
                <div class="meta-icon">
                  <i class="fas fa-clock"></i>
                </div>
                <div class="meta-content">
                  <span class="meta-label">Tipo</span>
                  <span class="meta-value">{{ $job->type }}</span>
                </div>
              </div>
            @endif

            @if($job->level)
              <div class="meta-item">
                <div class="meta-icon">
                  <i class="fas fa-signal"></i>
                </div>
                <div class="meta-content">
                  <span class="meta-label">Nível</span>
                  <span class="meta-value">{{ $job->level }}</span>
                </div>
              </div>
            @endif

            @if($job->published_at)
              <div class="meta-item">
                <div class="meta-icon">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="meta-content">
                  <span class="meta-label">Publicada em</span>
                  <span class="meta-value">{{ $job->published_at->format('d/m/Y') }}</span>
                </div>
              </div>
            @endif

            @if($job->closing_date)
              <div class="meta-item">
                <div class="meta-icon">
                  <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="meta-content">
                  <span class="meta-label">Fecha em</span>
                  <span class="meta-value closing-date">{{ $job->closing_date->format('d/m/Y') }}</span>
                </div>
              </div>
            @endif

            @if($job->salary_range)
              <div class="meta-item salary-item">
                <div class="meta-icon">
                  <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="meta-content">
                  <span class="meta-label">Salário</span>
                  <span class="meta-value">{{ $job->salary_range }}</span>
                </div>
              </div>
            @endif
          </div>
        </div>

        {{-- Hero Illustration --}}
        <div class="hero-illustration">
          <div class="illustration-wrapper">
            <i class="fas fa-briefcase"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="main-content-wrapper">
      <div class="row g-4">
        {{-- MAIN CONTENT --}}
        <div class="col-lg-8">
          {{-- Excerpt --}}
          @if($job->excerpt)
            <div class="content-card excerpt-card">
              <div class="card-header">
                <i class="fas fa-sparkles me-2"></i>
                <h3>Resumo da Vaga</h3>
              </div>
              <div class="card-body">
                <p class="excerpt-text">{{ $job->excerpt }}</p>
              </div>
            </div>
          @endif

          {{-- Job Description --}}
          <div class="content-card">
            <div class="card-header">
              <i class="fas fa-file-lines me-2"></i>
              <h3>Descrição da Vaga</h3>
            </div>
            <div class="card-body">
              <div class="job-description">
                {!! nl2br(e($job->description ?? '—')) !!}
              </div>
            </div>
          </div>

          {{-- Requirements --}}
          @if($job->requirements)
            <div class="content-card">
              <div class="card-header">
                <i class="fas fa-list-check me-2"></i>
                <h3>Requisitos e Qualificações</h3>
              </div>
              <div class="card-body">
                <div class="job-requirements">
                  {!! nl2br(e($job->requirements)) !!}
                </div>
              </div>
            </div>
          @endif

          {{-- Benefits --}}
          @if($job->benefits)
            <div class="content-card">
              <div class="card-header">
                <i class="fas fa-gift me-2"></i>
                <h3>Benefícios</h3>
              </div>
              <div class="card-body">
                <div class="job-benefits">
                  {!! nl2br(e($job->benefits)) !!}
                </div>
              </div>
            </div>
          @endif

          {{-- Application Section --}}
          <div class="content-card apply-card">
            <div class="card-header">
              <i class="fas fa-paper-plane me-2"></i>
              <h3>Como Candidatar-se</h3>
            </div>
            <div class="card-body">
              @if($job->apply_link || $job->apply_email)
                <div class="apply-options">
                  @if($job->apply_link)
                    <div class="apply-option">
                      <div class="option-icon">
                        <i class="fas fa-external-link-alt"></i>
                      </div>
                      <div class="option-content">
                        <h4>Candidatura Online</h4>
                        <p>Acesse o formulário de candidatura diretamente pelo site da empresa.</p>
                        <a href="{{ $job->apply_link }}" 
                           target="_blank" 
                           class="btn btn-primary btn-lg">
                          <i class="fas fa-external-link-alt me-2"></i>
                          Acessar Formulário
                        </a>
                      </div>
                    </div>
                  @endif

                  @if($job->apply_email)
                    <div class="apply-option">
                      <div class="option-icon">
                        <i class="fas fa-envelope"></i>
                      </div>
                      <div class="option-content">
                        <h4>Candidatura por Email</h4>
                        <p>Envie seu currículo diretamente para o email do recrutador.</p>
                        <a href="mailto:{{ $job->apply_email }}?subject={{ urlencode('Candidatura: ' . $job->title) }}&body={{ urlencode('Prezados,\n\nSeguem em anexo meus documentos para a vaga de ' . $job->title . '.\n\nAtenciosamente,') }}" 
                           class="btn btn-secondary btn-lg">
                          <i class="fas fa-envelope me-2"></i>
                          Enviar Email
                        </a>
                      </div>
                    </div>
                  @endif
                </div>

                <div class="apply-tips">
                  <div class="tip-header">
                    <i class="fas fa-lightbulb text-warning"></i>
                    <h5>Dicas para sua candidatura</h5>
                  </div>
                  <ul class="tips-list">
                    <li>Revise todos os requisitos antes de se candidatar</li>
                    <li>Prepare um currículo atualizado e em PDF</li>
                    <li>Inclua uma carta de apresentação personalizada</li>
                    <li>Verifique o prazo de candidatura</li>
                  </ul>
                </div>
              @else
                <div class="no-apply-method">
                  <div class="no-apply-icon">
                    <i class="fas fa-info-circle"></i>
                  </div>
                  <h4>Método de candidatura não especificado</h4>
                  <p>Entre em contato com a empresa para obter informações sobre como se candidatar.</p>
                </div>
              @endif
            </div>
          </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-4">
          {{-- Job Summary --}}
          <div class="sidebar-card summary-card">
            <div class="card-header">
              <i class="fas fa-bullseye me-2"></i>
              <h3>Resumo da Vaga</h3>
            </div>
            <div class="card-body">
              <div class="summary-list">
                @if($job->company)
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-building"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Empresa</span>
                      <span class="summary-value">{{ $job->company }}</span>
                    </div>
                  </div>
                @endif

                @if($job->location)
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-location-dot"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Localização</span>
                      <span class="summary-value">{{ $job->location }}</span>
                    </div>
                  </div>
                @endif

                @if($job->type)
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-clock"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Tipo de Emprego</span>
                      <span class="summary-value">{{ $job->type }}</span>
                    </div>
                  </div>
                @endif

                @if($job->level)
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-signal"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Nível de Experiência</span>
                      <span class="summary-value">{{ $job->level }}</span>
                    </div>
                  </div>
                @endif

                @if($job->category)
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-tag"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Categoria</span>
                      <span class="summary-value">{{ $job->category }}</span>
                    </div>
                  </div>
                @endif

                @if($job->published_at)
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Publicada em</span>
                      <span class="summary-value">{{ $job->published_at->format('d/m/Y') }}</span>
                    </div>
                  </div>
                @endif

                @if($job->closing_date)
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-hourglass-end"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Fecha em</span>
                      <span class="summary-value text-danger">{{ $job->closing_date->format('d/m/Y') }}</span>
                    </div>
                  </div>
                @endif

                @if($job->is_remote)
                  <div class="summary-item">
                    <div class="summary-icon">
                      <i class="fas fa-laptop-house"></i>
                    </div>
                    <div class="summary-content">
                      <span class="summary-label">Modalidade</span>
                      <span class="summary-value text-success">Remoto</span>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>

          {{-- Action Buttons --}}
          <div class="sidebar-card actions-card">
            <div class="action-buttons">
              @if($job->apply_link)
                <a href="{{ $job->apply_link }}" 
                   target="_blank" 
                   class="btn btn-primary btn-lg w-100 mb-3">
                  <i class="fas fa-paper-plane me-2"></i>
                  Candidatar-se Agora
                </a>
              @endif
              
              <button class="btn btn-outline-primary btn-lg w-100 mb-3 save-job-btn" data-job-id="{{ $job->id }}">
                <i class="far fa-bookmark me-2"></i>
                Salvar Vaga
              </button>
              
              <button class="btn btn-outline-secondary btn-lg w-100 share-job-btn">
                <i class="fas fa-share-alt me-2"></i>
                Compartilhar
              </button>
            </div>
          </div>

          {{-- Related Jobs --}}
          @if(isset($latest) && $latest->count())
            <div class="sidebar-card related-jobs-card">
              <div class="card-header">
                <i class="fas fa-clock-rotate-left me-2"></i>
                <h3>Outras Vagas Recentes</h3>
              </div>
              <div class="card-body">
                <div class="related-jobs-list">
                  @foreach($latest as $relatedJob)
                    <a href="{{ route('jobs.show', $relatedJob->slug) }}" class="related-job-item">
                      <div class="related-job-header">
                        <h4 class="related-job-title">{{ $relatedJob->title }}</h4>
                        @if($relatedJob->is_sponsored)
                          <span class="badge bg-warning">Patrocinada</span>
                        @endif
                      </div>
                      <div class="related-job-meta">
                        <span class="company">{{ $relatedJob->company }}</span>
                        <span class="location">
                          <i class="fas fa-map-marker-alt"></i> {{ $relatedJob->location }}
                        </span>
                      </div>
                      <div class="related-job-footer">
                        <span class="date">
                          <i class="far fa-calendar"></i> {{ $relatedJob->published_at->format('d/m/Y') }}
                        </span>
                        <span class="type">{{ $relatedJob->type }}</span>
                      </div>
                    </a>
                  @endforeach
                </div>
                
                <div class="text-center mt-4">
                  <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary w-100">
                    Ver Todas as Vagas
                    <i class="fas fa-arrow-right ms-2"></i>
                  </a>
                </div>
              </div>
            </div>
          @endif

          {{-- Safety Tips --}}
          <div class="sidebar-card safety-card">
            <div class="card-header">
              <i class="fas fa-shield-alt me-2"></i>
              <h3>Dicas de Segurança</h3>
            </div>
            <div class="card-body">
              <div class="safety-tips">
                <div class="tip-item">
                  <i class="fas fa-check-circle text-success"></i>
                  <span>Não compartilhe dados pessoais sensíveis</span>
                </div>
                <div class="tip-item">
                  <i class="fas fa-check-circle text-success"></i>
                  <span>Desconfie de propostas suspeitas</span>
                </div>
                <div class="tip-item">
                  <i class="fas fa-check-circle text-success"></i>
                  <span>Verifique a empresa antes de aplicar</span>
                </div>
                <div class="tip-item">
                  <i class="fas fa-check-circle text-success"></i>
                  <span>Nunca pague para se candidatar</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Share Modal --}}
<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-share-alt me-2"></i>
          Compartilhar Vaga
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="share-options">
          <button class="share-option" data-platform="whatsapp">
            <i class="fab fa-whatsapp"></i>
            <span>WhatsApp</span>
          </button>
          <button class="share-option" data-platform="linkedin">
            <i class="fab fa-linkedin"></i>
            <span>LinkedIn</span>
          </button>
          <button class="share-option" data-platform="facebook">
            <i class="fab fa-facebook"></i>
            <span>Facebook</span>
          </button>
          <button class="share-option" data-platform="twitter">
            <i class="fab fa-twitter"></i>
            <span>Twitter</span>
          </button>
          <button class="share-option" data-platform="email">
            <i class="fas fa-envelope"></i>
            <span>Email</span>
          </button>
          <button class="share-option" data-platform="copy">
            <i class="fas fa-copy"></i>
            <span>Copiar Link</span>
          </button>
        </div>
        
        <div class="share-link mt-4">
          <label class="form-label">Link da vaga</label>
          <div class="input-group">
            <input type="text" 
                   class="form-control" 
                   value="{{ url()->current() }}"
                   readonly>
            <button class="btn btn-outline-secondary copy-link-btn">
              <i class="fas fa-copy"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
/* Job Detail Section */
.job-detail-section {
  padding: 30px 0 80px;
  background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
}

/* Job Hero */
.job-hero {
  background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
  border-radius: 24px;
  padding: 50px;
  margin-bottom: 40px;
  position: relative;
  overflow: hidden;
  box-shadow: var(--shadow-large);
}

.hero-background {
  position: absolute;
  inset: 0;
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

.hero-gradient {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 100px;
  background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, transparent 100%);
}

.hero-content {
  position: relative;
  z-index: 1;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 40px;
  color: white;
}

.hero-main {
  flex: 1;
}

.job-badges {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.job-badges .badge {
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.9rem;
  display: inline-flex;
  align-items: center;
  border: 2px solid rgba(255,255,255,0.3);
}

.sponsored-badge {
  background: rgba(255, 107, 53, 0.2);
  color: #ffd166;
}

.featured-badge {
  background: rgba(0, 168, 89, 0.2);
  color: #90ee90;
}

.urgent-badge {
  background: rgba(220, 53, 69, 0.2);
  color: #ff9aa2;
}

.job-title {
  font-size: 2.8rem;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 25px;
  color: white;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.company-info {
  margin-bottom: 30px;
}

.company-main {
  display: flex;
  align-items: center;
  gap: 15px;
  font-size: 1.2rem;
}

.company-icon {
  font-size: 1.4rem;
  opacity: 0.9;
}

.company-name {
  font-weight: 600;
  color: white;
  font-size: 1.3rem;
}

.company-logo {
  width: 50px;
  height: 50px;
  border-radius: 10px;
  object-fit: contain;
  background: white;
  padding: 5px;
}

.job-meta-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.meta-item {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 15px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 15px;
  transition: transform 0.3s ease;
}

.meta-item:hover {
  transform: translateY(-5px);
  background: rgba(255, 255, 255, 0.15);
}

.meta-item.salary-item {
  background: rgba(255, 209, 102, 0.15);
}

.meta-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  background: rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
}

.meta-content {
  flex: 1;
}

.meta-label {
  display: block;
  font-size: 0.9rem;
  opacity: 0.8;
  color: white;
  margin-bottom: 5px;
}

.meta-value {
  display: block;
  color: #f1d3d3;
  font-size: 1.1rem;
  font-weight: 600;
}

.meta-value.closing-date {
  color: #ff9aa2;
}

.hero-illustration {
  display: flex;
  align-items: center;
  justify-content: center;
}

.illustration-wrapper {
  width: 120px;
  height: 120px;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3.5rem;
  animation: float 6s ease-in-out infinite;
}

@keyframes float {
  0%, 100% { transform: translateY(0) rotate(0deg); }
  50% { transform: translateY(-20px) rotate(5deg); }
}

/* Content Cards */
.content-card {
  background: white;
  border-radius: 20px;
  margin-bottom: 25px;
  box-shadow: var(--shadow);
  border: 2px solid transparent;
  transition: all 0.3s ease;
}

.content-card:hover {
  border-color: var(--primary-light);
  box-shadow: var(--shadow-large);
}

.excerpt-card {
  border-left: 5px solid var(--accent-color);
}

.apply-card {
  border-left: 5px solid var(--secondary-color);
}

.card-header {
  background: linear-gradient(90deg, var(--primary-light) 0%, rgba(230, 242, 255, 0.3) 100%);
  padding: 25px 30px;
  border-radius: 20px 20px 0 0;
  display: flex;
  align-items: center;
  gap: 15px;
}

.card-header i {
  font-size: 1.5rem;
  color: var(--primary-color);
}

.card-header h3 {
  margin: 0;
  font-size: 1.4rem;
  font-weight: 700;
  color: white;
}

.card-body {
  padding: 30px;
}

/* Excerpt */
.excerpt-text {
  font-size: 1.1rem;
  line-height: 1.7;
  color: var(--neutral-text);
  font-weight: 500;
}

/* Job Description & Requirements */
.job-description,
.job-requirements,
.job-benefits {
  line-height: 1.8;
  color: var(--neutral-text);
  font-size: 1.05rem;
}

.job-description p,
.job-requirements p,
.job-benefits p {
  margin-bottom: 1.2em;
}

.job-description ul,
.job-requirements ul,
.job-benefits ul {
  margin-left: 20px;
  margin-bottom: 1.2em;
}

.job-description li,
.job-requirements li,
.job-benefits li {
  margin-bottom: 0.5em;
  position: relative;
}

.job-description li::before,
.job-requirements li::before,
.job-benefits li::before {
  content: '•';
  color: var(--primary-color);
  font-weight: bold;
  position: absolute;
  left: -15px;
}

/* Apply Section */
.apply-options {
  display: flex;
  flex-direction: column;
  gap: 30px;
}

.apply-option {
  background: var(--neutral-light);
  border-radius: 15px;
  padding: 25px;
  display: flex;
  gap: 20px;
  align-items: flex-start;
  transition: all 0.3s ease;
}

.apply-option:hover {
  background: white;
  box-shadow: var(--shadow);
  transform: translateY(-5px);
}

.option-icon {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  background: var(--primary-light);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  color: var(--primary-color);
  flex-shrink: 0;
}

.option-content {
  flex: 1;
}

.option-content h4 {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--neutral-dark);
  margin-bottom: 10px;
}

.option-content p {
  color: var(--neutral-text);
  margin-bottom: 20px;
  line-height: 1.6;
}

.option-content .btn {
  padding: 12px 30px;
  border-radius: 12px;
  font-weight: 600;
  font-size: 1rem;
}

.apply-tips {
  margin-top: 40px;
  padding: 25px;
  background: linear-gradient(135deg, #fff8e1 0%, #fff3cd 100%);
  border-radius: 15px;
  border-left: 4px solid #ffc107;
}

.tip-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
}

.tip-header i {
  font-size: 1.8rem;
}

.tip-header h5 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: 700;
  color: #856404;
}

.tips-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.tips-list li {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  color: #856404;
  font-weight: 500;
}

.tips-list li::before {
  content: '✓';
  color: #28a745;
  font-weight: bold;
  flex-shrink: 0;
}

.no-apply-method {
  text-align: center;
  padding: 40px 20px;
}

.no-apply-icon {
  font-size: 4rem;
  color: var(--neutral-medium);
  margin-bottom: 20px;
  opacity: 0.6;
}

.no-apply-method h4 {
  font-size: 1.5rem;
  color: var(--neutral-dark);
  margin-bottom: 15px;
}

.no-apply-method p {
  color: var(--neutral-text);
  font-size: 1.1rem;
  max-width: 500px;
  margin: 0 auto;
}

/* Sidebar Cards */
.sidebar-card {
  background: white;
  border-radius: 20px;
  margin-bottom: 25px;
  box-shadow: var(--shadow);
  border: 2px solid var(--neutral-medium);
  overflow: hidden;
}

.summary-card .card-header,
.actions-card .card-header,
.related-jobs-card .card-header,
.safety-card .card-header {
  background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
  color: white;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.summary-card .card-header h3,
.actions-card .card-header h3,
.related-jobs-card .card-header h3,
.safety-card .card-header h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: 700;
  color: white;
}

.summary-card .card-body,
.actions-card .card-body,
.related-jobs-card .card-body,
.safety-card .card-body {
  padding: 25px;
}

/* Summary List */
.summary-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.summary-item {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px;
  background: var(--neutral-light);
  border-radius: 12px;
  transition: all 0.3s ease;
}

.summary-item:hover {
  background: white;
  box-shadow: var(--shadow);
  transform: translateX(5px);
}

.summary-icon {
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

.summary-content {
  flex: 1;
}

.summary-label {
  display: block;
  font-size: 0.85rem;
  color: var(--neutral-text);
  margin-bottom: 4px;
}

.summary-value {
  display: block;
  font-size: 1rem;
  font-weight: 600;
  color: var(--neutral-dark);
}

/* Action Buttons */
.actions-card {
  border: 2px solid var(--primary-light);
}

.actions-card .card-body {
  padding: 30px;
}

.action-buttons .btn {
  padding: 16px 24px;
  border-radius: 12px;
  font-weight: 600;
  font-size: 1.05rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
}

.action-buttons .btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

/* Related Jobs */
.related-jobs-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.related-job-item {
  background: var(--neutral-light);
  border-radius: 12px;
  padding: 20px;
  text-decoration: none;
  color: inherit;
  border: 2px solid transparent;
  transition: all 0.3s ease;
  display: block;
}

.related-job-item:hover {
  background: white;
  border-color: var(--primary-light);
  transform: translateX(5px);
  text-decoration: none;
  color: inherit;
}

.related-job-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 10px;
  margin-bottom: 10px;
}

.related-job-title {
  font-size: 1rem;
  font-weight: 700;
  color: var(--neutral-dark);
  margin: 0;
  line-height: 1.3;
  flex: 1;
}

.related-job-header .badge {
  padding: 4px 8px;
  font-size: 0.7rem;
}

.related-job-meta {
  display: flex;
  flex-direction: column;
  gap: 5px;
  margin-bottom: 10px;
}

.related-job-meta .company {
  font-size: 0.9rem;
  color: var(--neutral-dark);
  font-weight: 500;
}

.related-job-meta .location {
  font-size: 0.85rem;
  color: var(--neutral-text);
  display: flex;
  align-items: center;
  gap: 5px;
}

.related-job-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.8rem;
  color: var(--neutral-text);
}

.related-job-footer .date {
  display: flex;
  align-items: center;
  gap: 5px;
}

.related-job-footer .type {
  background: rgba(0, 102, 204, 0.1);
  color: var(--primary-color);
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
}

/* Safety Tips */
.safety-tips {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.tip-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 15px;
  background: rgba(40, 167, 69, 0.05);
  border-radius: 10px;
  border-left: 4px solid var(--success-color);
}

.tip-item i {
  font-size: 1.1rem;
  flex-shrink: 0;
}

.tip-item span {
  font-size: 0.9rem;
  color: var(--neutral-dark);
  font-weight: 500;
}

/* Share Modal */
.share-options {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
}

.share-option {
  border: 2px solid var(--neutral-medium);
  background: white;
  border-radius: 12px;
  padding: 20px 10px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.share-option:hover {
  transform: translateY(-5px);
  border-color: var(--primary-color);
}

.share-option[data-platform="whatsapp"]:hover {
  background: #25D366;
  color: white;
  border-color: #25D366;
}

.share-option[data-platform="linkedin"]:hover {
  background: #0077B5;
  color: white;
  border-color: #0077B5;
}

.share-option[data-platform="facebook"]:hover {
  background: #1877F2;
  color: white;
  border-color: #1877F2;
}

.share-option[data-platform="twitter"]:hover {
  background: #1DA1F2;
  color: white;
  border-color: #1DA1F2;
}

.share-option[data-platform="email"]:hover {
  background: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}

.share-option[data-platform="copy"]:hover {
  background: var(--secondary-color);
  color: white;
  border-color: var(--secondary-color);
}

.share-option i {
  font-size: 1.8rem;
}

.share-option span {
  font-size: 0.9rem;
  font-weight: 600;
}

.copy-link-btn:hover {
  background: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .job-title {
    font-size: 2.4rem;
  }
  
  .illustration-wrapper {
    width: 100px;
    height: 100px;
    font-size: 3rem;
  }
}

@media (max-width: 992px) {
  .hero-content {
    flex-direction: column;
    gap: 30px;
  }
  
  .hero-illustration {
    display: none;
  }
  
  .job-meta-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .job-hero {
    padding: 30px 25px;
    border-radius: 20px;
  }
  
  .job-title {
    font-size: 2rem;
  }
  
  .job-meta-grid {
    grid-template-columns: 1fr;
  }
  
  .card-header {
    padding: 20px;
  }
  
  .card-body {
    padding: 25px;
  }
  
  .apply-option {
    flex-direction: column;
    text-align: center;
  }
  
  .share-options {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 576px) {
  .job-title {
    font-size: 1.8rem;
  }
  
  .company-main {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  
  .apply-options {
    gap: 20px;
  }
  
  .action-buttons .btn {
    padding: 14px 20px;
    font-size: 1rem;
  }
  
  .share-options {
    grid-template-columns: 1fr;
  }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Save job functionality
  const saveJobBtn = document.querySelector('.save-job-btn');
  if (saveJobBtn) {
    saveJobBtn.addEventListener('click', function() {
      const jobId = this.dataset.jobId;
      const icon = this.querySelector('i');
      
      if (icon.classList.contains('far')) {
        // Save job
        icon.classList.remove('far');
        icon.classList.add('fas');
        this.innerHTML = '<i class="fas fa-bookmark me-2"></i> Vaga Salva';
        this.classList.remove('btn-outline-primary');
        this.classList.add('btn-success');
        
        showToast('Vaga salva com sucesso!', 'success');
      } else {
        // Unsave job
        icon.classList.remove('fas');
        icon.classList.add('far');
        this.innerHTML = '<i class="far fa-bookmark me-2"></i> Salvar Vaga';
        this.classList.remove('btn-success');
        this.classList.add('btn-outline-primary');
        
        showToast('Vaga removida dos salvos.', 'info');
      }
    });
  }
  
  // Share functionality
  const shareBtn = document.querySelector('.share-job-btn');
  if (shareBtn) {
    shareBtn.addEventListener('click', function() {
      const modal = new bootstrap.Modal(document.getElementById('shareModal'));
      modal.show();
    });
  }
  
  // Share options
  const shareOptions = document.querySelectorAll('.share-option');
  const copyLinkBtn = document.querySelector('.copy-link-btn');
  const currentUrl = window.location.href;
  const jobTitle = document.querySelector('.job-title').textContent;
  
  shareOptions.forEach(option => {
    option.addEventListener('click', function() {
      const platform = this.dataset.platform;
      const shareText = `Confira esta vaga: ${jobTitle}`;
      
      switch(platform) {
        case 'whatsapp':
          window.open(`https://wa.me/?text=${encodeURIComponent(shareText + ' ' + currentUrl)}`, '_blank');
          break;
        case 'linkedin':
          window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(currentUrl)}`, '_blank');
          break;
        case 'facebook':
          window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentUrl)}`, '_blank');
          break;
        case 'twitter':
          window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(shareText)}&url=${encodeURIComponent(currentUrl)}`, '_blank');
          break;
        case 'email':
          window.open(`mailto:?subject=${encodeURIComponent(shareText)}&body=${encodeURIComponent('Confira esta oportunidade: ' + currentUrl)}`);
          break;
        case 'copy':
          copyToClipboard(currentUrl);
          break;
      }
    });
  });
  
  // Copy link button
  if (copyLinkBtn) {
    copyLinkBtn.addEventListener('click', function() {
      const input = this.closest('.input-group').querySelector('input');
      copyToClipboard(input.value);
    });
  }
  
  // Copy to clipboard function
  function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
      showToast('Link copiado para a área de transferência!', 'success');
    }).catch(err => {
      console.error('Erro ao copiar:', err);
      showToast('Erro ao copiar o link', 'error');
    });
  }
  
  // Toast notification
  function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
      <div class="toast-content">
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
      </div>
      <button class="toast-close">
        <i class="fas fa-times"></i>
      </button>
    `;
    
    document.body.appendChild(toast);
    
    // Add toast styles if not already added
    if (!document.querySelector('#toast-styles')) {
      const style = document.createElement('style');
      style.id = 'toast-styles';
      style.textContent = `
        .toast-notification {
          position: fixed;
          bottom: 30px;
          right: 30px;
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
          animation: slideInUp 0.3s ease;
          max-width: 350px;
        }
        
        .toast-success {
          border-left-color: var(--success-color);
        }
        
        .toast-error {
          border-left-color: var(--danger-color);
        }
        
        .toast-info {
          border-left-color: var(--primary-color);
        }
        
        .toast-content {
          display: flex;
          align-items: center;
          gap: 10px;
        }
        
        .toast-content i {
          font-size: 1.2rem;
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
        
        @keyframes slideInUp {
          from {
            transform: translateY(100%);
            opacity: 0;
          }
          to {
            transform: translateY(0);
            opacity: 1;
          }
        }
      `;
      document.head.appendChild(style);
    }
    
    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => {
      toast.style.animation = 'slideOutDown 0.3s ease forwards';
      setTimeout(() => toast.remove(), 300);
    });
    
    setTimeout(() => {
      toast.style.animation = 'slideOutDown 0.3s ease forwards';
      setTimeout(() => toast.remove(), 300);
    }, 4000);
  }
  
  // Add slideOutDown animation
  const slideOutStyle = document.createElement('style');
  slideOutStyle.textContent = `
    @keyframes slideOutDown {
      to {
        transform: translateY(100%);
        opacity: 0;
      }
    }
  `;
  document.head.appendChild(slideOutStyle);
});
</script>
@endpush