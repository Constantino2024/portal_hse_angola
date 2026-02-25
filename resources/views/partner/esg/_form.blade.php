@php
  $isEdit = isset($initiative);
@endphp

<div class="row g-3">
  <div class="col-12 col-lg-8">
    <div class="mb-3">
      <label class="form-label">Título</label>
      <input type="text" name="title" class="form-control" value="{{ old('title', $initiative->title ?? '') }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Imagem (opcional)</label>
      <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
      @if($isEdit && !empty($initiative->image_path))
        <div class="mt-2">
          <img src="{{ asset('storage/' . $initiative->image_path) }}" alt="Imagem" style="max-height:140px" class="img-thumbnail">
        </div>
      @endif
      <div class="form-text">JPG/PNG/WEBP até 4MB (mín 300x300).</div>
    </div>

    <div class="row g-2">
      <div class="col-12 col-md-6">
        <label class="form-label">Área de foco</label>
        <input type="text" name="focus_area" class="form-control" value="{{ old('focus_area', $initiative->focus_area ?? '') }}" placeholder="Ambiental / Social / Governance...">
      </div>
      <div class="col-12 col-md-6">
        <label class="form-label">Local</label>
        <input type="text" name="location" class="form-control" value="{{ old('location', $initiative->location ?? '') }}" placeholder="Província / Cidade">
      </div>
    </div>

    <div class="row g-2 mt-1">
      <div class="col-12 col-md-6">
        <label class="form-label">Data de início</label>
        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($initiative->start_date ?? null)->format('Y-m-d')) }}">
      </div>
      <div class="col-12 col-md-6">
        <label class="form-label">Data de fim</label>
        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($initiative->end_date ?? null)->format('Y-m-d')) }}">
      </div>
    </div>

    <div class="mt-3">
      <label class="form-label">Resumo</label>
      <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $initiative->excerpt ?? '') }}</textarea>
    </div>

    <div class="mt-3">
      <label class="form-label">Descrição</label>
      <textarea name="description" class="form-control" rows="7">{{ old('description', $initiative->description ?? '') }}</textarea>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body">
        <div class="fw-semibold mb-2">Publicação</div>

        <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                 @checked(old('is_active', $initiative->is_active ?? true))>
          <label class="form-check-label" for="is_active">Ativa (visível no portal)</label>
        </div>

        <div class="text-muted small mt-3">
          <i class="fa-regular fa-circle-info me-1"></i>
          A iniciativa fica listada e disponível para consulta.
        </div>
      </div>
    </div>
  </div>
</div>
