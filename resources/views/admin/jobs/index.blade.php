@extends('layouts.admin')

@section('title', 'Admin - Vagas HSE')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
  <div>
    <h1 class="section-title mb-0">Gerir Vagas HSE</h1>
    <div class="text-muted small">Crie, edite e destaque oportunidades de emprego.</div>
  </div>

  <div class="d-flex gap-2">
    <form method="GET" class="d-flex gap-2">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Buscar vaga...">
      <button class="btn btn-outline-secondary" type="submit">
        <i class="fa-solid fa-magnifying-glass"></i>
      </button>
    </form>

    <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">
      <i class="fa-solid fa-plus me-1"></i> Nova vaga
    </a>
  </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th style="min-width:320px;">Vaga</th>
            <th>Empresa</th>
            <th>Local</th>
            <th>Publicado</th>
            <th class="text-center">Ativa</th>
            <th class="text-center">Destaque</th>
            <th class="text-center">Patroc.</th>
            <th class="text-end">Ações</th>
          </tr>
        </thead>
        <tbody>
        @forelse($jobs as $job)
          <tr>
            <td>
              <div class="fw-semibold">{{ $job->title }}</div>
              <div class="text-muted small">{{ $job->type ?? '—' }} @if($job->level) · {{ $job->level }} @endif</div>
            </td>
            <td>{{ $job->company ?? '—' }}</td>
            <td>{{ $job->location ?? '—' }}</td>
            <td class="text-muted small">{{ optional($job->published_at)->format('d/m/Y H:i') }}</td>

            <td class="text-center">
              @if($job->is_active)
                <span class="badge text-bg-success">Sim</span>
              @else
                <span class="badge text-bg-secondary">Não</span>
              @endif
            </td>

            <td class="text-center">
              @if($job->is_featured)
                <span class="badge text-bg-warning"><i class="fa-solid fa-star"></i></span>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>

            <td class="text-center">
              @if($job->is_sponsored)
                <span class="badge text-bg-dark"><i class="fa-solid fa-bolt"></i></span>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>

            <td class="text-end">
              <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa-regular fa-pen-to-square"></i>
              </a>
              <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Apagar esta vaga?')">
                  <i class="fa-regular fa-trash-can"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">
              Nenhuma vaga encontrada.
            </td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- Paginação (Anterior/Próximo) --}}
<div class="d-flex justify-content-between align-items-center mt-4">
  <div>
    @if($jobs->onFirstPage())
      <button class="btn btn-outline-secondary" disabled>
        <i class="fa-solid fa-arrow-left me-1"></i> Anterior
      </button>
    @else
      <a class="btn btn-outline-secondary" href="{{ $jobs->previousPageUrl() }}">
        <i class="fa-solid fa-arrow-left me-1"></i> Anterior
      </a>
    @endif
  </div>

  <div class="text-muted small">Página {{ $jobs->currentPage() }} de {{ $jobs->lastPage() }}</div>

  <div>
    @if($jobs->hasMorePages())
      <a class="btn btn-outline-secondary" href="{{ $jobs->nextPageUrl() }}">
        Próximo <i class="fa-solid fa-arrow-right ms-1"></i>
      </a>
    @else
      <button class="btn btn-outline-secondary" disabled>
        Próximo <i class="fa-solid fa-arrow-right ms-1"></i>
      </button>
    @endif
  </div>
</div>
@endsection
