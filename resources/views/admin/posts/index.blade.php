@extends('layouts.admin')

@section('title', 'Admin - Notícias')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

    <div>
        <h1 class="mb-1" style="font-weight:800; color: var(--primary-color);">
            Gerir notícias
        </h1>
        <p class="text-muted mb-0">
            Crie, edite e organize as publicações do Portal HSE.
        </p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary rounded-pill">
            <i class="fa-solid fa-plus me-1"></i> Nova notícia
        </a>

        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary rounded-pill">
            <i class="fa-solid fa-globe me-1"></i> Ver site
        </a>
    </div>
</div>

{{-- Card de filtros --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('admin.posts.index') }}">
            <div class="row g-2 g-md-3 align-items-end">

                <div class="col-lg-4">
                    <label class="form-label fw-semibold">Pesquisar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text"
                               name="q"
                               value="{{ request('q') }}"
                               class="form-control"
                               placeholder="Título, autor, resumo...">
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="form-label fw-semibold">Categoria</label>
                    <select name="category_id" class="form-select">
                        <option value="">Todas</option>
                        @foreach(($categories ?? \App\Models\Category::all()) as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold">Destaque</label>
                    <select name="featured" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" @selected(request('featured') === '1')>Sim</option>
                        <option value="0" @selected(request('featured') === '0')>Não</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold">Popular</label>
                    <select name="popular" class="form-select">
                        <option value="">Todos</option>
                        <option value="1" @selected(request('popular') === '1')>Sim</option>
                        <option value="0" @selected(request('popular') === '0')>Não</option>
                    </select>
                </div>

                <div class="col-lg-1 col-md-6 d-grid">
                    <button class="btn btn-primary">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                </div>

                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2 mt-1">
                        <a class="btn btn-sm btn-outline-secondary rounded-pill"
                           href="{{ route('admin.posts.index') }}">
                            <i class="fa-solid fa-rotate-left me-1"></i> Limpar filtros
                        </a>

                        <span class="text-muted small align-self-center">
                            Total nesta página: <strong>{{ $posts->count() }}</strong> · Total geral: <strong>{{ $posts->total() }}</strong>
                        </span>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- Tabela --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th style="width:44%;">Notícia</th>
                        <th>Categoria</th>
                        <th>Publicado</th>
                        <th class="text-center">Destaque</th>
                        <th class="text-center">Popular</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            {{-- Notícia + thumb --}}
                            <td>
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        @php
                                            $thumb = $post->image_url ?? $post->image ?? null; // compatível
                                        @endphp
                                        @if($thumb)
                                            <img src="{{ asset('storage/'.$thumb) }}"
                                                 alt="{{ $post->title }}"
                                                 style="width:78px; height:54px; object-fit:cover; border-radius:12px;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center bg-light"
                                                 style="width:78px; height:54px; border-radius:12px;">
                                                <i class="fa-regular fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-grow-1">
                                        <div class="fw-semibold" style="line-height:1.2;">
                                            {{ $post->title }}
                                        </div>

                                        <div class="text-muted small mt-1">
                                            <i class="fa-regular fa-user me-1"></i> {{ $post->author_name ?? '-' }}
                                            <span class="mx-2">·</span>
                                            <span class="badge text-bg-secondary-subtle">
                                                ID: {{ $post->id }}
                                            </span>

                                            @if(empty($post->published_at))
                                                <span class="badge text-bg-warning ms-2">
                                                    Rascunho
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge rounded-pill text-bg-info-subtle">
                                    {{ $post->category->name ?? '—' }}
                                </span>
                            </td>

                            <td class="text-muted small">
                                @if($post->published_at)
                                    {{ $post->published_at->format('d/m/Y') }}
                                    <div class="text-muted" style="font-size:.78rem;">
                                        {{ $post->published_at->format('H:i') }}
                                    </div>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="text-center">
                                @if($post->is_featured)
                                    <span class="badge rounded-pill text-bg-success">
                                        <i class="fa-solid fa-star me-1"></i> Sim
                                    </span>
                                @else
                                    <span class="badge rounded-pill text-bg-secondary">
                                        Não
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($post->is_popular)
                                    <span class="badge rounded-pill text-bg-success">
                                        <i class="fa-solid fa-fire me-1"></i> Sim
                                    </span>
                                @else
                                    <span class="badge rounded-pill text-bg-secondary">
                                        Não
                                    </span>
                                @endif
                            </td>

                            {{-- Ações --}}
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.posts.edit', $post) }}">
                                                <i class="fa-solid fa-pen-to-square me-2"></i> Editar
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('posts.show', $post->slug) }}" target="_blank">
                                                <i class="fa-solid fa-eye me-2"></i> Ver no site
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                                  onsubmit="return confirm('Apagar esta notícia?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">
                                                    <i class="fa-solid fa-trash me-2"></i> Apagar
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fa-regular fa-folder-open" style="font-size:2rem;"></i>
                                    <div class="mt-2 fw-semibold">Nenhuma notícia encontrada</div>
                                    <div class="small">Tente remover filtros ou criar uma nova notícia.</div>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary rounded-pill">
                                        <i class="fa-solid fa-plus me-1"></i> Nova notícia
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- Paginação --}}
    @if($posts->hasPages())
        <div class="p-3 p-md-4 border-top">
            {{ $posts->onEachSide(1)->links('pagination.custom') }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* pequenos refinamentos só para esta página */
.table thead th {
    font-size: .85rem;
    text-transform: uppercase;
    letter-spacing: .02em;
    color: #5b6472;
}
.dropdown-menu {
    border-radius: 14px;
}
</style>
@endpush
