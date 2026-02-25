@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row g-4">

{{-- ================= COLUNA PRINCIPAL ================= --}}
<div class="col-lg-8">
<div class="card border-0 shadow-sm rounded-4">
<div class="card-body p-4">

<h5 class="mb-3">
    <i class="fa-solid fa-pen-nib me-2"></i> Dados da notícia
</h5>

<input type="hidden" name="images_order" id="images_order">

<div class="mb-3">
    <label class="form-label">Título</label>
    <input type="text" name="title"
           class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title', $post->title ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Autor</label>
    <input type="text" name="author_name"
           class="form-control @error('author_name') is-invalid @enderror"
           value="{{ old('author_name', $post->author_name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Conteúdo</label>
    <textarea id="postBody" name="body"
              class="form-control @error('body') is-invalid @enderror"
              rows="10" required>{{ old('body', $post->body ?? '') }}</textarea>
</div>

</div>
</div>
</div>

{{-- ================= IMAGENS ================= --}}
<div class="col-lg-4">
<div class="card border-0 shadow-sm rounded-4">
<div class="card-body p-4">

<h5 class="mb-3">
    <i class="fa-solid fa-images me-2"></i> Imagens
</h5>

<p class="text-muted small">
    Arraste para ordenar. A primeira será a capa.
</p>

<div id="images-sortable" class="d-grid gap-3">

@php
$images = ['image', 'image_2', 'image_3', 'image_4'];
@endphp

@foreach($images as $img)
<div class="upload-box" data-name="{{ $img }}">
    <label class="upload-label text-capitalize">{{ str_replace('_', ' ', $img) }}</label>

    <input type="file"
           name="{{ $img }}"
           class="form-control upload-input @error($img) is-invalid @enderror"
           accept="image/*"
           data-preview="prev-{{ $img }}">

    <div class="upload-preview mt-2" id="prev-{{ $img }}">
        @if(!empty($post) && $post->{$img})
            <img src="{{ asset('storage/'.$post->{$img}) }}">
            <button type="button"
                    class="remove-image"
                    data-field="{{ $img }}">
                <i class="fa-solid fa-trash"></i>
            </button>
        @else
            <div class="upload-empty">
                <i class="fa-regular fa-image"></i>
                <span>Sem imagem</span>
            </div>
        @endif
    </div>

    @error($img)
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
@endforeach

</div>

<button class="btn btn-primary w-100 mt-3">
    <i class="fa-solid fa-floppy-disk me-1"></i>
    {{ isset($post) ? 'Atualizar' : 'Publicar' }} notícia
</button>

</div>
</div>
</div>

</div>
