@extends('layouts.admin')

@section('title', 'Admin - Editar Vaga')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h1 class="section-title mb-0">Editar Vaga</h1>
    <div class="text-muted small">Atualize informações e status da vaga.</div>
  </div>
  <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">
    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
  </a>
</div>

@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('admin.jobs.update', $job) }}" method="POST">
@csrf
@method('PUT')

<div class="row g-4">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-4">

        <h5 class="mb-3">
          <i class="fa-solid fa-pen-nib me-2"></i> Dados da vaga
        </h5>

        <div class="mb-3">
          <label class="form-label">Título</label>
          <input type="text" name="title" class="form-control" required value="{{ old('title', $job->title) }}">
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Empresa</label>
            <input type="text" name="company" class="form-control" value="{{ old('company', $job->company) }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Local</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $job->location) }}">
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-md-6">
            <label class="form-label">Tipo</label>
            <input type="text" name="type" class="form-control" value="{{ old('type', $job->type) }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Nível</label>
            <input type="text" name="level" class="form-control" value="{{ old('level', $job->level) }}">
          </div>
        </div>

        <div class="mt-3 mb-3">
          <label class="form-label">Resumo (opcional)</label>
          <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $job->excerpt) }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Descrição</label>
          <textarea name="description" class="form-control" rows="7">{{ old('description', $job->description) }}</textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Requisitos</label>
          <textarea name="requirements" class="form-control" rows="6">{{ old('requirements', $job->requirements) }}</textarea>
        </div>

      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-4">
        <h5 class="mb-3">
          <i class="fa-solid fa-paper-plane me-2"></i> Candidatura e status
        </h5>

        <div class="mb-3">
          <label class="form-label">Link de candidatura (opcional)</label>
          <input type="url" name="apply_link" class="form-control" value="{{ old('apply_link', $job->apply_link) }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Email de candidatura (opcional)</label>
          <input type="email" name="apply_email" class="form-control" value="{{ old('apply_email', $job->apply_email) }}">
        </div>

        <div class="mt-3 d-flex flex-column gap-2">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                   {{ old('is_active', $job->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Ativa</label>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured"
                   {{ old('is_featured', $job->is_featured) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">Destaque na Home</label>
          </div>

          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="is_sponsored" value="1" id="is_sponsored"
                   {{ old('is_sponsored', $job->is_sponsored) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_sponsored">Patrocinada</label>
          </div>
        </div>

        <button class="btn btn-primary w-100 mt-4" type="submit">
          <i class="fa-solid fa-floppy-disk me-1"></i> Guardar alterações
        </button>

      </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mt-4">
      <div class="card-body p-4">
        <h5 class="mb-2"><i class="fa-solid fa-link me-2"></i> Link rápido</h5>
        <a href="{{ route('jobs.show', $job->slug) }}" target="_blank" class="btn btn-outline-secondary w-100">
          <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Ver no portal
        </a>
      </div>
    </div>
  </div>
</div>

</form>
@endsection
