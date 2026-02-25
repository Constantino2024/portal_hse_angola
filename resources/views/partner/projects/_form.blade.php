@php
  $isEdit = isset($project);
@endphp

<div class="row g-3">
  <div class="col-12 col-lg-8">
    <div class="mb-3">
      <label class="form-label">Título do projecto</label>
      <input type="text" name="title" class="form-control" value="{{ old('title', $project->title ?? '') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Imagem (opcional)</label>
      <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
      @if($isEdit && !empty($project->image_path))
        <div class="mt-2">
          <img src="{{ asset('storage/' . $project->image_path) }}" alt="Imagem" style="max-height:140px" class="img-thumbnail">
        </div>
      @endif
      <div class="form-text">JPG/PNG/WEBP até 4MB (mín 300x300).</div>
    </div>

    <div class="row g-2">
      <div class="col-12 col-md-6">
        <label class="form-label">Cliente (opcional)</label>
        <input type="text" name="client" class="form-control" value="{{ old('client', $project->client ?? '') }}">
      </div>
      <div class="col-12 col-md-6">
        <label class="form-label">Sector</label>
        <input type="text" name="sector" class="form-control" value="{{ old('sector', $project->sector ?? '') }}" placeholder="Oil&Gas, Construção, Energia...">
      </div>
    </div>

    <div class="row g-2 mt-1">
      <div class="col-12 col-md-6">
        <label class="form-label">Local</label>
        <input type="text" name="location" class="form-control" value="{{ old('location', $project->location ?? '') }}">
      </div>
      <div class="col-12 col-md-3">
        <label class="form-label">Início</label>
        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($project->start_date ?? null)->format('Y-m-d')) }}">
      </div>
      <div class="col-12 col-md-3">
        <label class="form-label">Fim</label>
        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($project->end_date ?? null)->format('Y-m-d')) }}">
      </div>
    </div>

    <div class="mt-3">
      <label class="form-label">Resumo</label>
      <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $project->excerpt ?? '') }}</textarea>
    </div>

    <div class="mt-3">
      <label class="form-label">Descrição</label>
      <textarea name="description" class="form-control" rows="7">{{ old('description', $project->description ?? '') }}</textarea>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body">
        <div class="fw-semibold mb-2">Publicação</div>

        <div class="mb-3">
          <label class="form-label">Website (opcional)</label>
          <input type="url" name="website" class="form-control" value="{{ old('website', $project->website ?? '') }}" placeholder="https://...">
        </div>

        <hr>

        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                 @checked(old('is_active', $project->is_active ?? true))>
          <label class="form-check-label" for="is_active">Ativo (visível no portal)</label>
        </div>

        <div class="text-muted small mt-3">
          <i class="fa-regular fa-circle-info me-1"></i>
          O projecto fica listado e disponível para consulta.
        </div>
      </div>
    </div>
  </div>
</div>
