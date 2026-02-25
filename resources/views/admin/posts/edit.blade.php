@extends('layouts.admin')

@section('title', 'Editar notícia')

@section('content')
<section class="admin-create-wrap container my-5">

    {{-- Header --}}
    <div class="admin-page-head mb-4">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <span class="admin-badge">
                    <i class="fa-solid fa-pen-to-square"></i> Admin · Edição
                </span>
                <h1 class="admin-title mt-2">Editar notícia</h1>
                <p class="admin-subtitle mb-0">
                    Está a editar uma notícia já publicada. As alterações entram em vigor após salvar.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('posts.show', $post->slug) }}" target="_blank"
                   class="btn btn-outline-primary rounded-pill">
                    <i class="fa-solid fa-eye me-1"></i> Ver notícia
                </a>

                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>

                <button form="postEditForm" type="submit" class="btn btn-primary rounded-pill">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Guardar alterações
                </button>
            </div>
        </div>
    </div>

    {{-- Erros --}}
    @if($errors->any())
        <div class="alert alert-danger rounded-4 shadow-sm">
            <strong><i class="fa-solid fa-triangle-exclamation me-1"></i> Corrija os erros abaixo:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="postEditForm"
          action="{{ route('admin.posts.update', $post) }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- ================= COLUNA PRINCIPAL ================= --}}
            <div class="col-lg-8">

                {{-- Conteúdo --}}
                <div class="card admin-card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">

                        <div class="admin-card-head mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="admin-icon">
                                    <i class="fa-solid fa-newspaper"></i>
                                </span>
                                <div>
                                    <h5 class="mb-0">Conteúdo da notícia</h5>
                                    <small class="text-muted">Edite título, texto e dados principais</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Título</label>
                            <input type="text"
                                   name="title"
                                   class="form-control form-control-lg"
                                   value="{{ old('title', $post->title) }}"
                                   required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Autor</label>
                                <input type="text"
                                       name="author_name"
                                       class="form-control"
                                       value="{{ old('author_name', $post->author_name) }}"
                                       required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Categoria</label>
                                <select name="category_id" class="form-select">
                                    <option value="">—</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            @selected(old('category_id', $post->category_id) == $cat->id)>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Subtítulo</label>
                            <input type="text"
                                   name="subtitle"
                                   class="form-control"
                                   value="{{ old('subtitle', $post->subtitle) }}">
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Resumo (excerpt)</label>
                            <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Conteúdo</label>
                            <textarea id="postBody"
                                      name="body"
                                      class="form-control admin-editor"
                                      rows="12"
                                      required>{{ old('body', $post->body) }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Configurações --}}
                <div class="card admin-card border-0 shadow-sm rounded-4 mt-4">
                    <div class="card-body p-4">

                        <div class="admin-card-head mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="admin-icon" style="background: rgba(45,139,139,.12); color: var(--secondary-color);">
                                    <i class="fa-solid fa-gears"></i>
                                </span>
                                <div>
                                    <h5 class="mb-0">Configurações</h5>
                                    <small class="text-muted">Vídeo, destaque e popularidade</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Vídeo (YouTube embed)</label>
                                <input type="url"
                                       name="video_url"
                                       class="form-control"
                                       value="{{ old('video_url', $post->video_url) }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Opções</label>
                                <div class="admin-switches">
                                    <label class="switch-item">
                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                               @checked(old('is_featured', $post->is_featured))>
                                        <span>Destaque (Hero)</span>
                                    </label>

                                    <label class="switch-item">
                                        <input class="form-check-input" type="checkbox" name="is_popular" value="1"
                                               @checked(old('is_popular', $post->is_popular))>
                                        <span>Popular</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ================= COLUNA LATERAL ================= --}}
            <div class="col-lg-4">

                {{-- Imagens --}}
                <div class="card admin-card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="mb-3"><i class="fa-solid fa-images me-1"></i> Imagens da notícia</h5>
                        <p class="text-muted small">
                            As imagens atuais são exibidas abaixo. Pode substituir qualquer uma.
                        </p>

                        @php
                            $images = [
                                ['field'=>'image',   'label'=>'Capa'],
                                ['field'=>'image_2', 'label'=>'Imagem 2'],
                                ['field'=>'image_3', 'label'=>'Imagem 3'],
                                ['field'=>'image_4', 'label'=>'Imagem 4'],
                            ];
                        @endphp

                        @foreach($images as $img)
                            <div class="upload-box mb-3">
                                <label class="upload-label">{{ $img['label'] }}</label>

                                @if($post->{$img['field']})
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/'.$post->{$img['field']}) }}"
                                             class="img-fluid rounded-3">
                                    </div>
                                @endif

                                <input type="file"
                                       name="{{ $img['field'] }}"
                                       class="form-control upload-input"
                                       accept="image/*">
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary w-100 mt-2">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Atualizar notícia
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</section>
@endsection

