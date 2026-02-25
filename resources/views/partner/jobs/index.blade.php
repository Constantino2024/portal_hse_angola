@extends('layouts.partner')

@section('title', 'Publicar Vagas · Parceiro')

@section('content')
<div class="container-fluid">
  <!-- Header -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-5">
    <div>
      <h1 class="section-title mb-2">Publicar Vagas</h1>
      <div class="text-muted d-flex align-items-center gap-2">
        <i class="fa-solid fa-briefcase"></i>
        <span>Crie e gerencie as vagas da sua empresa</span>
      </div>
    </div>

    <div class="d-flex flex-column flex-md-row gap-3 w-100 w-md-auto">
      <!-- Search Form -->
      <div class="position-relative w-100 w-md-auto">
        <form method="GET" class="d-flex">
          <div class="input-group flex-nowrap" style="min-width: 280px;">
            <input type="text" 
                   name="q" 
                   value="{{ request('q') }}" 
                   class="form-control form-control-sm border-end-0" 
                   placeholder="Buscar por título, empresa ou local..." 
                   style="border-radius: 8px 0 0 8px; padding: 0.5rem 1rem;">
            <button class="btn btn-outline-secondary border border-start-0" type="submit" style="border-radius: 0 8px 8px 0;">
              <i class="fa-solid fa-magnifying-glass"></i>
              <span class="d-none d-md-inline ms-1">Buscar</span>
            </button>
          </div>
        </form>
      </div>

      <!-- New Job Button -->
      <a href="{{ route('partner.jobs.create') }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100 w-md-auto">
        <i class="fa-solid fa-plus-circle"></i>
        <span>Nova Vaga</span>
      </a>
    </div>
  </div>

  <!-- Stats Summary -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body d-flex align-items-center gap-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center d-none d-sm-flex" 
               style="width: 40px; height: 40px; background: rgba(26, 54, 93, 0.1);">
            <i class="fa-solid fa-briefcase text-primary-dark fs-6"></i>
          </div>
          <div class="flex-grow-1">
            <div class="text-muted small">Total de Vagas</div>
            <div class="fw-bold fs-4">{{ $jobs->total() }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body d-flex align-items-center gap-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center d-none d-sm-flex" 
               style="width: 40px; height: 40px; background: rgba(45, 206, 137, 0.1);">
            <i class="fa-solid fa-circle-check text-success fs-6"></i>
          </div>
          <div class="flex-grow-1">
            <div class="text-muted small">Vagas Ativas</div>
            <div class="fw-bold fs-4">{{ $jobs->where('is_active', true)->count() }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body d-flex align-items-center gap-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center d-none d-sm-flex" 
               style="width: 40px; height: 40px; background: rgba(211, 84, 0, 0.1);">
            <i class="fa-solid fa-hourglass-half text-accent-orange fs-6"></i>
          </div>
          <div class="flex-grow-1">
            <div class="text-muted small">Publicadas Hoje</div>
            <div class="fw-bold fs-4">{{ $jobs->filter(fn($job) => $job->published_at?->isToday())->count() }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-body d-flex align-items-center gap-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center d-none d-sm-flex" 
               style="width: 40px; height: 40px; background: rgba(108, 117, 125, 0.1);">
            <i class="fa-solid fa-pause-circle text-secondary fs-6"></i>
          </div>
          <div class="flex-grow-1">
            <div class="text-muted small">Vagas Inativas</div>
            <div class="fw-bold fs-4">{{ $jobs->where('is_active', false)->count() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Table Card -->
  <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
    <div class="card-header bg-white border-0 py-3 px-3 px-md-4">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
        <h5 class="fw-semibold mb-0">Lista de Vagas</h5>
        <div class="text-muted small text-md-end">
          Mostrando {{ $jobs->firstItem() ?? 0 }}-{{ $jobs->lastItem() ?? 0 }} de {{ $jobs->total() }} vagas
        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle d-none d-md-table">
          <thead>
            <tr class="bg-light">
              <th class="px-3 px-md-4 py-3 border-0" style="min-width: 280px;">
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-briefcase text-primary-dark"></i>
                  <span>Vaga</span>
                </div>
              </th>
              <th class="px-3 px-md-4 py-3 border-0">
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-building text-primary-dark"></i>
                  <span>Empresa</span>
                </div>
              </th>
              <th class="px-3 px-md-4 py-3 border-0">
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-location-dot text-primary-dark"></i>
                  <span>Local</span>
                </div>
              </th>
              <th class="px-3 px-md-4 py-3 border-0">
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-calendar-day text-primary-dark"></i>
                  <span>Publicado</span>
                </div>
              </th>
              <th class="px-3 px-md-4 py-3 border-0 text-center">
                <div class="d-flex align-items-center gap-2 justify-content-center">
                  <i class="fa-solid fa-toggle-on text-primary-dark"></i>
                  <span>Status</span>
                </div>
              </th>
              <th class="px-3 px-md-4 py-3 border-0 text-end">
                <span>Ações</span>
              </th>
            </tr>
          </thead>
          <tbody>
          @forelse($jobs as $job)
            <tr class="border-top border-bottom-0">
              <td class="px-3 px-md-4 py-3">
                <div class="d-flex flex-column">
                  <a href="{{ route('partner.jobs.edit', $job) }}" 
                     class="fw-semibold text-dark text-decoration-none hover-text-primary">
                    {{ $job->title }}
                  </a>
                  <div class="d-flex gap-2 mt-1">
                    @if($job->type)
                      <span class="badge bg-primary-dark bg-opacity-10 text-primary-dark rounded-pill px-2 py-1 small">
                        {{ $job->type }}
                      </span>
                    @endif
                    @if($job->level)
                      <span class="badge bg-accent-orange bg-opacity-10 text-accent-orange rounded-pill px-2 py-1 small">
                        {{ $job->level }}
                      </span>
                    @endif
                  </div>
                </div>
              </td>
              <td class="px-3 px-md-4 py-3">
                <div class="d-flex align-items-center gap-2">
                  <div class="rounded-circle d-flex align-items-center justify-content-center" 
                       style="width: 28px; height: 28px; background: var(--light-gray);">
                    <i class="fa-solid fa-building text-muted" style="font-size: 0.75rem;"></i>
                  </div>
                  <span>{{ $job->company ?? '—' }}</span>
                </div>
              </td>
              <td class="px-3 px-md-4 py-3">
                <div class="d-flex align-items-center gap-2">
                  <i class="fa-solid fa-location-dot text-muted" style="font-size: 0.875rem;"></i>
                  <span>{{ $job->location ?? '—' }}</span>
                </div>
              </td>
              <td class="px-3 px-md-4 py-3">
                <div class="d-flex flex-column">
                  <span class="small fw-medium">{{ optional($job->published_at)->format('d/m/Y') }}</span>
                  <span class="text-muted smaller">{{ optional($job->published_at)->format('H:i') }}</span>
                </div>
              </td>
              <td class="px-3 px-md-4 py-3 text-center">
                <div class="d-inline-block">
                  @if($job->is_active)
                    <span class="badge d-flex align-items-center gap-1" 
                          style="background: rgba(45, 206, 137, 0.1); color: #2dce89; padding: 0.35rem 0.75rem; border-radius: 20px;">
                      <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i>
                      Ativa
                    </span>
                  @else
                    <span class="badge d-flex align-items-center gap-1" 
                          style="background: rgba(108, 117, 125, 0.1); color: #6c757d; padding: 0.35rem 0.75rem; border-radius: 20px;">
                      <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i>
                      Inativa
                    </span>
                  @endif
                </div>
              </td>
              <td class="px-3 px-md-4 py-3 text-end">
                <div class="d-flex align-items-center justify-content-end gap-2">
                  <!-- View Button -->
                  <a href="#" 
                     class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 32px; height: 32px;"
                     title="Visualizar">
                    <i class="fa-regular fa-eye"></i>
                  </a>
                  
                  <!-- Edit Button -->
                  <a href="{{ route('partner.jobs.edit', $job) }}" 
                     class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 32px; height: 32px;"
                     title="Editar">
                    <i class="fa-regular fa-pen-to-square"></i>
                  </a>
                  
                  <!-- Delete Button -->
                  <form action="{{ route('partner.jobs.destroy', $job) }}" 
                        method="POST" 
                        class="d-inline"
                        onsubmit="return confirm('Tem certeza que deseja remover esta vaga? Esta ação não pode ser desfeita.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 32px; height: 32px;"
                            title="Remover">
                      <i class="fa-regular fa-trash-can"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-3 px-md-4 py-5 text-center">
                <div class="d-flex flex-column align-items-center justify-content-center py-4">
                  <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" 
                       style="width: 80px; height: 80px; background: var(--light-gray);">
                    <i class="fa-regular fa-folder-open text-muted fs-3"></i>
                  </div>
                  <h5 class="fw-semibold mb-2">Nenhuma vaga encontrada</h5>
                  <p class="text-muted mb-4">Comece criando sua primeira vaga de emprego</p>
                  <a href="{{ route('partner.jobs.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    Criar Primeira Vaga
                  </a>
                </div>
              </td>
            </tr>
          @endforelse
          </tbody>
        </table>

        <!-- Mobile Cards View -->
        <div class="d-block d-md-none">
          @forelse($jobs as $job)
          <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body">
              <!-- Job Header -->
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="flex-grow-1">
                  <h6 class="fw-bold mb-1">{{ $job->title }}</h6>
                  <div class="d-flex flex-wrap gap-1 mb-2">
                    @if($job->type)
                      <span class="badge bg-primary-dark bg-opacity-10 text-primary-dark rounded-pill px-2 py-1 small">
                        {{ $job->type }}
                      </span>
                    @endif
                    @if($job->level)
                      <span class="badge bg-accent-orange bg-opacity-10 text-accent-orange rounded-pill px-2 py-1 small">
                        {{ $job->level }}
                      </span>
                    @endif
                  </div>
                </div>
                <div class="d-inline-block">
                  @if($job->is_active)
                    <span class="badge d-flex align-items-center gap-1" 
                          style="background: rgba(45, 206, 137, 0.1); color: #2dce89; padding: 0.35rem 0.75rem; border-radius: 20px;">
                      <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i>
                      Ativa
                    </span>
                  @else
                    <span class="badge d-flex align-items-center gap-1" 
                          style="background: rgba(108, 117, 125, 0.1); color: #6c757d; padding: 0.35rem 0.75rem; border-radius: 20px;">
                      <i class="fa-solid fa-circle" style="font-size: 0.5rem;"></i>
                      Inativa
                    </span>
                  @endif
                </div>
              </div>

              <!-- Job Details -->
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <div class="d-flex align-items-center gap-2 text-muted small">
                    <i class="fa-solid fa-building" style="font-size: 0.75rem;"></i>
                    <span class="truncate">{{ $job->company ?? '—' }}</span>
                  </div>
                </div>
                <div class="col-6">
                  <div class="d-flex align-items-center gap-2 text-muted small">
                    <i class="fa-solid fa-location-dot" style="font-size: 0.75rem;"></i>
                    <span class="truncate">{{ $job->location ?? '—' }}</span>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-flex align-items-center gap-2 text-muted small">
                    <i class="fa-solid fa-calendar-day" style="font-size: 0.75rem;"></i>
                    <span>{{ optional($job->published_at)->format('d/m/Y H:i') }}</span>
                  </div>
                </div>
              </div>

              <!-- Actions -->
              <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <a href="{{ route('partner.jobs.edit', $job) }}" 
                   class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                  <i class="fa-regular fa-pen-to-square"></i>
                  <span>Editar</span>
                </a>
                <div class="d-flex gap-2">
                  <a href="#" 
                     class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 36px; height: 36px;"
                     title="Visualizar">
                    <i class="fa-regular fa-eye"></i>
                  </a>
                  <form action="{{ route('partner.jobs.destroy', $job) }}" 
                        method="POST" 
                        class="d-inline"
                        onsubmit="return confirm('Tem certeza que deseja remover esta vaga? Esta ação não pode ser desfeita.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-outline-danger btn-sm rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 36px; height: 36px;"
                            title="Remover">
                      <i class="fa-regular fa-trash-can"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          @empty
          <div class="text-center py-5">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                 style="width: 80px; height: 80px; background: var(--light-gray);">
              <i class="fa-regular fa-folder-open text-muted fs-3"></i>
            </div>
            <h5 class="fw-semibold mb-2">Nenhuma vaga encontrada</h5>
            <p class="text-muted mb-4">Comece criando sua primeira vaga de emprego</p>
            <a href="{{ route('partner.jobs.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
              <i class="fa-solid fa-plus"></i>
              Criar Primeira Vaga
            </a>
          </div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Table Footer with Pagination -->
    @if($jobs->hasPages())
      <div class="card-footer bg-white border-0 py-3 px-3 px-md-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
          <div class="text-muted small text-center text-md-start">
            Página {{ $jobs->currentPage() }} de {{ $jobs->lastPage() }}
          </div>
          <div class="w-100 w-md-auto">
            {{ $jobs->links() }}
          </div>
          <div class="text-muted small text-center text-md-end d-none d-md-block">
            {{ $jobs->total() }} vagas totais
          </div>
        </div>
      </div>
    @endif
  </div>

  <!-- Quick Actions -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
          <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2">
            <i class="fa-solid fa-bolt text-accent-orange"></i>
            Ações Rápidas
          </h6>
          <div class="row g-2 g-md-3">
            <div class="col-6 col-md-3">
              <a href="{{ route('partner.jobs.create') }}" 
                 class="btn btn-outline-primary w-100 d-flex flex-column align-items-center justify-content-center p-2 p-md-3 rounded-3 hover-lift">
                <i class="fa-solid fa-plus-circle fs-3 mb-1 mb-md-2"></i>
                <span class="fw-semibold small text-center">Nova Vaga</span>
              </a>
            </div>
            <div class="col-6 col-md-3">
              <a href="#" 
                 class="btn btn-outline-primary w-100 d-flex flex-column align-items-center justify-content-center p-2 p-md-3 rounded-3 hover-lift">
                <i class="fa-solid fa-file-export fs-3 mb-1 mb-md-2"></i>
                <span class="fw-semibold small text-center">Exportar</span>
              </a>
            </div>
            <div class="col-6 col-md-3">
              <a href="#" 
                 class="btn btn-outline-primary w-100 d-flex flex-column align-items-center justify-content-center p-2 p-md-3 rounded-3 hover-lift">
                <i class="fa-solid fa-filter fs-3 mb-1 mb-md-2"></i>
                <span class="fw-semibold small text-center">Filtrar</span>
              </a>
            </div>
            <div class="col-6 col-md-3">
              <a href="#" 
                 class="btn btn-outline-primary w-100 d-flex flex-column align-items-center justify-content-center p-2 p-md-3 rounded-3 hover-lift">
                <i class="fa-solid fa-chart-bar fs-3 mb-1 mb-md-2"></i>
                <span class="fw-semibold small text-center">Relatório</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Custom styles for this page */
  .hover-text-primary:hover {
    color: var(--primary-dark) !important;
  }
  
  .hover-lift {
    transition: all 0.3s ease;
  }
  
  .hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .table tbody tr {
    transition: all 0.2s ease;
  }
  
  .table tbody tr:hover {
    background-color: rgba(26, 54, 93, 0.02);
  }
  
  .badge.bg-primary-dark.bg-opacity-10 {
    background-color: rgba(26, 54, 93, 0.1) !important;
  }
  
  .badge.bg-accent-orange.bg-opacity-10 {
    background-color: rgba(211, 84, 0, 0.1) !important;
  }
  
  .btn-outline-primary.rounded-circle,
  .btn-outline-danger.rounded-circle {
    transition: all 0.2s ease;
  }
  
  .btn-outline-primary.rounded-circle:hover {
    background-color: var(--primary-dark);
    border-color: var(--primary-dark);
    color: white;
  }
  
  .btn-outline-danger.rounded-circle:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
  }
  
  /* Custom pagination styles */
  .pagination {
    margin-bottom: 0;
    flex-wrap: wrap;
    justify-content: center;
  }
  
  .page-item .page-link {
    border: none;
    color: var(--dark-gray);
    padding: 0.375rem 0.5rem;
    margin: 0 0.125rem;
    border-radius: 6px;
    transition: all 0.2s ease;
    font-size: 0.875rem;
  }
  
  .page-item.active .page-link {
    background-color: var(--primary-dark);
    color: white;
  }
  
  .page-item:not(.active) .page-link:hover {
    background-color: var(--light-gray);
    color: var(--primary-dark);
  }
  
  .page-item.disabled .page-link {
    color: var(--medium-gray);
    background-color: transparent;
  }
  
  /* Status indicator */
  .badge i.fa-circle {
    animation: pulse 2s infinite;
  }
  
  @keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
  }
  
  /* Mobile specific styles */
  .truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 120px;
  }
  
  /* Responsive adjustments */
  @media (max-width: 768px) {
    .section-title {
      font-size: 1.5rem;
    }
    
    .card-header .d-flex {
      flex-direction: column;
      gap: 0.5rem;
      align-items: flex-start !important;
    }
    
    .input-group {
      min-width: 100% !important;
    }
    
    .fs-4 {
      font-size: 1.5rem !important;
    }
    
    /* Hide table headers on mobile */
    .d-md-table {
      display: none !important;
    }
    
    /* Show mobile cards */
    .d-block.d-md-none {
      display: block !important;
    }
    
    /* Adjust padding for mobile */
    .px-3.px-md-4 {
      padding-left: 1rem !important;
      padding-right: 1rem !important;
    }
    
    /* Adjust button sizes */
    .btn-sm {
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
    }
    
    /* Adjust action buttons */
    .btn-outline-primary.btn-sm,
    .btn-outline-danger.btn-sm {
      width: 32px !important;
      height: 32px !important;
    }
  }
  
  @media (max-width: 576px) {
    .container-fluid {
      padding-left: 0.75rem;
      padding-right: 0.75rem;
    }
    
    .row.g-3 {
      margin-left: -0.5rem;
      margin-right: -0.5rem;
    }
    
    .row.g-3 > [class*="col-"] {
      padding-left: 0.5rem;
      padding-right: 0.5rem;
    }
    
    .card-body {
      padding: 1rem !important;
    }
    
    .fs-4 {
      font-size: 1.25rem !important;
    }
    
    .text-muted.small {
      font-size: 0.75rem;
    }
    
    /* Adjust pagination for very small screens */
    .pagination {
      font-size: 0.75rem;
    }
    
    .page-item .page-link {
      padding: 0.25rem 0.375rem;
      margin: 0 0.0625rem;
      min-width: 28px;
    }
    
    /* Stack buttons on very small screens */
    @media (max-width: 360px) {
      .d-flex.flex-md-row.gap-3 {
        flex-direction: column !important;
        gap: 0.5rem !important;
      }
      
      .btn {
        width: 100% !important;
      }
    }
  }
  
  @media (min-width: 769px) and (max-width: 992px) {
    /* Tablet adjustments */
    .section-title {
      font-size: 1.75rem;
    }
    
    .px-3.px-md-4 {
      padding-left: 1.5rem !important;
      padding-right: 1.5rem !important;
    }
    
    .input-group {
      min-width: 240px !important;
    }
  }
  
  /* Animation for table rows */
  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .table tbody tr {
    animation: fadeIn 0.3s ease forwards;
  }
  
  .table tbody tr:nth-child(1) { animation-delay: 0.05s; }
  .table tbody tr:nth-child(2) { animation-delay: 0.1s; }
  .table tbody tr:nth-child(3) { animation-delay: 0.15s; }
  .table tbody tr:nth-child(4) { animation-delay: 0.2s; }
  .table tbody tr:nth-child(5) { animation-delay: 0.25s; }
  
  /* Mobile card animations */
  .d-block.d-md-none .card {
    animation: slideIn 0.3s ease forwards;
  }
  
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateX(-10px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  .d-block.d-md-none .card:nth-child(1) { animation-delay: 0.05s; }
  .d-block.d-md-none .card:nth-child(2) { animation-delay: 0.1s; }
  .d-block.d-md-none .card:nth-child(3) { animation-delay: 0.15s; }
  .d-block.d-md-none .card:nth-child(4) { animation-delay: 0.2s; }
  .d-block.d-md-none .card:nth-child(5) { animation-delay: 0.25s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Function to handle responsive behavior
  function handleResponsiveLayout() {
    const isMobile = window.innerWidth <= 768;
    
    // Adjust pagination for mobile
    const pagination = document.querySelector('.pagination');
    if (pagination && isMobile) {
      pagination.classList.add('justify-content-center');
    }
    
    // Add mobile-specific classes
    const statsCards = document.querySelectorAll('.card.border-0.shadow-sm.rounded-3');
    statsCards.forEach(card => {
      if (isMobile) {
        card.classList.add('py-2');
      } else {
        card.classList.remove('py-2');
      }
    });
  }
  
  // Initialize responsive layout
  handleResponsiveLayout();
  
  // Update on resize
  window.addEventListener('resize', handleResponsiveLayout);
  
  // Add touch events for mobile swipe (optional)
  let touchStartX = 0;
  let touchEndX = 0;
  
  document.addEventListener('touchstart', function(e) {
    touchStartX = e.changedTouches[0].screenX;
  });
  
  document.addEventListener('touchend', function(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
  });
  
  function handleSwipe() {
    const swipeThreshold = 50;
    const swipeDistance = touchEndX - touchStartX;
    
    if (Math.abs(swipeDistance) > swipeThreshold) {
      if (swipeDistance > 0) {
        // Swipe right - could be used for navigation
        console.log('Swiped right');
      } else {
        // Swipe left - could be used for navigation
        console.log('Swiped left');
      }
    }
  }
  
  // Improve mobile dropdowns
  document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      if (window.innerWidth <= 768) {
        e.preventDefault();
        const dropdown = new bootstrap.Dropdown(this);
        dropdown.toggle();
      }
    });
  });
  
  // Add loading state for actions
  document.querySelectorAll('form[onsubmit]').forEach(form => {
    form.addEventListener('submit', function() {
      const submitBtn = this.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processando...';
        submitBtn.disabled = true;
      }
    });
  });
  
  // Initialize animations
  const tableRows = document.querySelectorAll('tbody tr');
  tableRows.forEach((row, index) => {
    row.style.animationDelay = `${index * 0.05}s`;
  });
  
  const mobileCards = document.querySelectorAll('.d-block.d-md-none .card');
  mobileCards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
  });
});
</script>
@endsection