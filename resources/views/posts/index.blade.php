@extends('layouts.app')

@section('title', $search ? 'Resultados para: "' . $search . '"' : 'Notícias HSE')

@section('meta_description', 'Acompanhe todas as notícias sobre Saúde, Segurança e Ambiente. Conteúdos técnicos, atualizações do setor e informações relevantes para profissionais HSE.')

@section('content')

<div class="news-index-page">
    {{-- Header da página --}}
    <div class="page-header">
        <div class="container">
            <div class="header-content">
                <div class="header-left pt-2">
                    <h1 class="page-title">
                        @if(!empty($search))
                            <i class="fas fa-search me-2"></i> Resultados para: "{{ $search }}"
                        @else
                            <i class="fas fa-newspaper me-2"></i> Notícias HSE
                        @endif
                    </h1>
                    <p class="page-subtitle">
                        @if(!empty($search))
                            Encontramos <span class="highlight">{{ $posts->total() }}</span> resultado(s) para sua pesquisa
                        @else
                            Acompanhe todas as notícias sobre Saúde, Segurança e Ambiente
                        @endif
                    </p>
                </div>
                <div class="header-right">
                    <div class="results-info">
                        <span class="results-count">{{ $posts->total() }} notícias</span>
                        @if(!empty($search))
                            <a href="{{ route('posts.public') }}" class="clear-search">
                                <i class="fas fa-times"></i> Limpar busca
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de filtros e busca --}}
    <div class="filters-section">
        <div class="container">
            <div class="filters-content">
                {{-- Barra de busca --}}
                <div class="search-box">
                    <form action="{{ route('posts.public') }}" method="GET" class="search-form">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   name="q" 
                                   class="form-control" 
                                   placeholder="Buscar notícias..." 
                                   value="{{ $search ?? '' }}"
                                   aria-label="Buscar notícias">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </form>
                </div>

                {{-- Filtros avançados --}}
                <div class="advanced-filters">
                    <div class="filters-row">
                        {{-- Filtro por categoria --}}
                        <div class="filter-group">
                            <label for="category-filter" class="filter-label">
                                <i class="fas fa-folder me-1"></i> Categoria
                            </label>
                            <select id="category-filter" class="form-select filter-select" onchange="window.location.href = this.value">
                                <option value="{{ route('posts.public') }}">Todas as categorias</option>
                                @foreach($categories as $category)
                                    <option value="{{ route('posts.public') }}?category={{ $category->slug }}"
                                            {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->posts_count ?? 0 }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filtro por data --}}
                        <div class="filter-group">
                            <label for="date-filter" class="filter-label">
                                <i class="fas fa-calendar me-1"></i> Período
                            </label>
                            <select id="date-filter" class="form-select filter-select" onchange="window.location.href = this.value">
                                <option value="{{ route('posts.public') }}">Todo o período</option>
                                <option value="{{ route('posts.public') }}?period=today" {{ request('period') == 'today' ? 'selected' : '' }}>
                                    Hoje
                                </option>
                                <option value="{{ route('posts.public') }}?period=week" {{ request('period') == 'week' ? 'selected' : '' }}>
                                    Esta semana
                                </option>
                                <option value="{{ route('posts.public') }}?period=month" {{ request('period') == 'month' ? 'selected' : '' }}>
                                    Este mês
                                </option>
                                <option value="{{ route('posts.public') }}?period=year" {{ request('period') == 'year' ? 'selected' : '' }}>
                                    Este ano
                                </option>
                            </select>
                        </div>

                        {{-- Filtro por ordenação --}}
                        <div class="filter-group">
                            <label for="sort-filter" class="filter-label">
                                <i class="fas fa-sort me-1"></i> Ordenar por
                            </label>
                            <select id="sort-filter" class="form-select filter-select" onchange="window.location.href = this.value">
                                <option value="{{ route('posts.public') }}?sort=latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                                    Mais recentes
                                </option>
                                <option value="{{ route('posts.public') }}?sort=oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                    Mais antigos
                                </option>
                                <option value="{{ route('posts.public') }}?sort=popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                    Mais populares
                                </option>
                                <option value="{{ route('posts.public') }}?sort=views" {{ request('sort') == 'views' ? 'selected' : '' }}>
                                    Mais visualizadas
                                </option>
                            </select>
                        </div>

                        {{-- Botão para resetar filtros --}}
                        @if(request()->hasAny(['q', 'category', 'period', 'sort']))
                            <div class="filter-group">
                                <a href="{{ route('posts.public') }}" class="btn btn-outline-secondary reset-filters">
                                    <i class="fas fa-redo me-1"></i> Limpar filtros
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tags de filtros ativos --}}
                @if(request()->hasAny(['q', 'category', 'period', 'sort']))
                    <div class="active-filters">
                        <span class="active-filters-label">Filtros ativos:</span>
                        <div class="filter-tags">
                            @if($search)
                                <span class="filter-tag">
                                    Busca: "{{ $search }}"
                                    <a href="{{ route('posts.public', Arr::except(request()->query(), ['q'])) }}" class="remove-filter">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('category'))
                                @php $cat = $categories->where('slug', request('category'))->first(); @endphp
                                @if($cat)
                                    <span class="filter-tag">
                                        Categoria: {{ $cat->name }}
                                        <a href="{{ route('posts.public', Arr::except(request()->query(), ['category'])) }}" class="remove-filter">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                            @endif
                            @if(request('period'))
                                <span class="filter-tag">
                                    Período: 
                                    @switch(request('period'))
                                        @case('today') Hoje @break
                                        @case('week') Esta semana @break
                                        @case('month') Este mês @break
                                        @case('year') Este ano @break
                                    @endswitch
                                    <a href="{{ route('posts.public', Arr::except(request()->query(), ['period'])) }}" class="remove-filter">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                            @if(request('sort'))
                                <span class="filter-tag">
                                    Ordenação: 
                                    @switch(request('sort'))
                                        @case('latest') Mais recentes @break
                                        @case('oldest') Mais antigos @break
                                        @case('popular') Mais populares @break
                                        @case('views') Mais visualizadas @break
                                    @endswitch
                                    <a href="{{ route('posts.public', Arr::except(request()->query(), ['sort'])) }}" class="remove-filter">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Conteúdo principal --}}
    <div class="news-content-section">
        <div class="container">
            @if($posts->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3>Nenhuma notícia encontrada</h3>
                    <p class="text-muted">
                        @if(!empty($search))
                            Não encontramos resultados para "{{ $search }}". Tente outros termos de busca.
                        @else
                            Ainda não há notícias publicadas nesta categoria.
                        @endif
                    </p>
                    <a href="{{ route('posts.public') }}" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i> Voltar para todas as notícias
                    </a>
                </div>
            @else
                {{-- Layout toggle --}}
                <div class="layout-toggle">
                    <span class="toggle-label">Visualização:</span>
                    <div class="toggle-buttons">
                        <button class="toggle-btn active" data-layout="grid" title="Visualização em Grade">
                            <i class="fas fa-th-large"></i>
                        </button>
                        <button class="toggle-btn" data-layout="list" title="Visualização em Lista">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>

                {{-- Grid de notícias --}}
                <div class="news-grid" id="newsGrid" data-layout="grid">
                    @foreach($posts as $post)
                        <article class="news-card">
                            <div class="news-card-image">
                                <a href="{{ route('posts.show', $post->slug) }}">
                                    @if($post->image_url)
                                        <img src="{{ asset('storage/'.$post->image_url) }}" 
                                             alt="{{ $post->title }}"
                                             loading="lazy">
                                    @else
                                        <div class="image-placeholder">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    @endif
                                </a>
                                <div class="news-card-badge">
                                    <span class="category-badge">{{ $post->category->name ?? 'Geral' }}</span>
                                    @if($post->is_featured)
                                        <span class="featured-badge">
                                            <i class="fas fa-star"></i> Destaque
                                        </span>
                                    @endif
                                    @if($post->is_pinned)
                                        <span class="pinned-badge">
                                            <i class="fas fa-thumbtack"></i> Fixado
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="news-card-content">
                                <div class="news-meta">
                                    <span class="meta-item">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ optional($post->published_at)->format('d/m/Y') }}
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-clock"></i>
                                        {{ $post->reading_time ?? '5' }} min
                                    </span>
                                    <span class="meta-item">
                                        <i class="fas fa-eye"></i>
                                        {{ number_format($post->views ?? 0) }}
                                    </span>
                                </div>
                                
                                <h3 class="news-title">
                                    <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                
                                <p class="news-excerpt">{{ $post->excerpt }}</p>
                                
                                <div class="news-author">
                                    <div class="author-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="author-info">
                                        <strong>{{ $post->author_name }}</strong>
                                        <span>Portal HSE</span>
                                    </div>
                                </div>
                                
                                <div class="news-footer">
                                    <a href="{{ route('posts.show', $post->slug) }}" class="read-more-btn">
                                        Ler Notícia <i class="fas fa-arrow-right"></i>
                                    </a>
                                    <div class="news-actions">
                                        <button class="action-btn" onclick="shareContent('{{ $post->title }}', '{{ route('posts.show', $post->slug) }}')" title="Compartilhar">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                        <button class="action-btn save-btn" data-post-id="{{ $post->id }}" title="Salvar">
                                            <i class="far fa-bookmark"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Paginação --}}
                @if($posts->hasPages())
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            Mostrando {{ $posts->firstItem() }} a {{ $posts->lastItem() }} de {{ $posts->total() }} resultados
                        </div>
                        {{ $posts->onEachSide(1)->links('pagination.custom') }}
                    </div>
                @endif

                {{-- Newsletter --}}
                <div class="newsletter-cta">
                    <div class="newsletter-content">
                        <div class="newsletter-icon">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div class="newsletter-text">
                            <h4>Não perca nenhuma notícia!</h4>
                            <p>Receba as principais notícias HSE diretamente no seu email.</p>
                        </div>
                        <div class="newsletter-form">
                            <form action="{{ route('subscribers.store') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="email" 
                                           name="email" 
                                           class="form-control" 
                                           placeholder="seu@email.com"
                                           required>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Inscrever
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Sidebar para categorias populares --}}
    @if(!$posts->isEmpty() && $categories->isNotEmpty())
        <div class="categories-sidebar">
            <div class="container">
                <div class="sidebar-content">
                    <h4 class="sidebar-title">
                        <i class="fas fa-tags me-2"></i> Categorias Populares
                    </h4>
                    <div class="categories-list">
                        @foreach($categories->take(8) as $category)
                            <a href="{{ route('posts.public') }}?category={{ $category->slug }}" 
                               class="category-item {{ request('category') == $category->slug ? 'active' : '' }}">
                                <span class="category-name">{{ $category->name }}</span>
                                <span class="category-count">{{ $category->posts_count ?? 0 }}</span>
                            </a>
                        @endforeach
                    </div>
                    
                    @if($categories->count() > 8)
                        <div class="view-all-categories">
                            <a href="#" class="btn btn-link" data-bs-toggle="collapse" data-bs-target="#moreCategories">
                                Ver todas as categorias <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="collapse" id="moreCategories">
                                <div class="categories-list mt-3">
                                    @foreach($categories->slice(8) as $category)
                                        <a href="{{ route('posts.public') }}?category={{ $category->slug }}" 
                                           class="category-item {{ request('category') == $category->slug ? 'active' : '' }}">
                                            <span class="category-name">{{ $category->name }}</span>
                                            <span class="category-count">{{ $category->posts_count ?? 0 }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@endsection

@push('styles')
<style>
    /* Estilos específicos para a página de notícias */
    .news-index-page {
        padding-bottom: 80px;
        margin-top: 80px;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 60px 0 40px;
        margin-bottom: 40px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 20px;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .page-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .page-subtitle .highlight {
        font-weight: 700;
        color: var(--accent-color);
    }

    .results-info {
        text-align: right;
    }

    .results-count {
        display: block;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: rgba(255, 255, 255, 0.9);
    }

    .clear-search {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: color 0.3s ease;
    }

    .clear-search:hover {
        color: white;
        text-decoration: underline;
    }

    /* Filters Section */
    .filters-section {
        background: white;
        padding: 30px 0;
        border-bottom: 2px solid var(--neutral-medium);
        margin-bottom: 40px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .search-box {
        margin-bottom: 25px;
    }

    .search-form .input-group {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .search-form .input-group-text {
        background: white;
        border: none;
        padding: 0 20px;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .search-form .form-control {
        border: none;
        padding: 15px 20px;
        font-size: 1rem;
        height: 56px;
    }

    .search-form .form-control:focus {
        box-shadow: none;
    }

    .search-form .btn-primary {
        padding: 0 30px;
        font-weight: 600;
        border-radius: 0;
        height: 56px;
    }

    .advanced-filters {
        margin-bottom: 20px;
    }

    .filters-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        display: block;
        font-weight: 600;
        color: var(--neutral-dark);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .filter-select {
        border: 2px solid var(--neutral-medium);
        border-radius: 8px;
        padding: 10px 15px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .filter-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    .reset-filters {
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
        height: 44px;
    }

    /* Active Filters */
    .active-filters {
        padding-top: 20px;
        border-top: 2px solid var(--neutral-medium);
    }

    .active-filters-label {
        display: block;
        font-weight: 600;
        color: var(--neutral-dark);
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .filter-tags {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-tag {
        background: var(--primary-light);
        color: white;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .remove-filter {
        color: white;
        text-decoration: none;
        font-size: 0.8rem;
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    .remove-filter:hover {
        opacity: 1;
    }

    /* Layout Toggle */
    .layout-toggle {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--neutral-medium);
    }

    .toggle-label {
        font-weight: 600;
        color: var(--neutral-dark);
        font-size: 0.95rem;
    }

    .toggle-buttons {
        display: flex;
        gap: 5px;
        background: var(--neutral-light);
        border-radius: 8px;
        padding: 4px;
        border: 2px solid var(--neutral-medium);
    }

    .toggle-btn {
        width: 40px;
        height: 40px;
        border: none;
        background: transparent;
        border-radius: 6px;
        color: var(--neutral-text);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .toggle-btn:hover,
    .toggle-btn.active {
        background: var(--primary-color);
        color: white;
    }

    /* News Grid */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
        transition: all 0.3s ease;
    }

    .news-grid[data-layout="list"] {
        grid-template-columns: 1fr;
    }

    .news-grid[data-layout="list"] .news-card {
        display: flex;
        flex-direction: row;
        min-height: 200px;
    }

    .news-grid[data-layout="list"] .news-card-image {
        flex: 0 0 300px;
        height: auto;
        border-radius: 15px 0 0 15px;
    }

    .news-grid[data-layout="list"] .news-card-content {
        flex: 1;
        padding: 30px;
    }

    .news-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: all 0.4s ease;
        border: 2px solid var(--neutral-medium);
    }

    .news-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-large);
        border-color: var(--primary-light);
    }

    .news-card-image {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .news-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .news-card:hover .news-card-image img {
        transform: scale(1.05);
    }

    .image-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }

    .news-card-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .category-badge {
        padding: 6px 12px;
        background: var(--primary-color);
        color: white;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        box-shadow: 0 4px 10px rgba(0, 102, 204, 0.2);
    }

    .featured-badge {
        padding: 6px 12px;
        background: var(--accent-color);
        color: white;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 4px 10px rgba(255, 107, 53, 0.2);
    }

    .pinned-badge {
        padding: 6px 12px;
        background: var(--secondary-color);
        color: white;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 4px 10px rgba(0, 168, 89, 0.2);
    }

    .news-card-content {
        padding: 25px;
    }

    .news-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        color: var(--neutral-text);
        opacity: 0.8;
    }

    .meta-item i {
        color: var(--secondary-color);
        font-size: 0.9rem;
    }

    .news-title {
        font-size: 1.3rem;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 12px;
    }

    .news-title a {
        color: var(--neutral-dark);
        text-decoration: none;
        transition: color 0.3s ease;
        display: block;
    }

    .news-title a:hover {
        color: var(--primary-color);
    }

    .news-excerpt {
        color: var(--neutral-text);
        line-height: 1.6;
        margin-bottom: 20px;
        font-size: 0.95rem;
    }

    .news-author {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid var(--neutral-medium);
    }

    .author-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--neutral-light), var(--neutral-medium));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--secondary-color);
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .author-info {
        flex: 1;
    }

    .author-info strong {
        display: block;
        font-size: 0.95rem;
        color: var(--neutral-dark);
        margin-bottom: 2px;
    }

    .author-info span {
        font-size: 0.85rem;
        color: var(--neutral-text);
        opacity: 0.8;
    }

    .news-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .read-more-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 10px 20px;
        border: 2px solid var(--primary-color);
        border-radius: 25px;
        transition: all 0.3s ease;
        background: white;
    }

    .read-more-btn:hover {
        background: var(--primary-color);
        color: white;
        gap: 12px;
        transform: translateX(5px);
    }

    .news-actions {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid var(--neutral-medium);
        background: transparent;
        color: var(--neutral-text);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .action-btn:hover {
        border-color: var(--accent-color);
        color: var(--accent-color);
        transform: rotate(15deg);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        max-width: 600px;
        margin: 0 auto;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--neutral-medium);
        margin-bottom: 30px;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.8rem;
        color: var(--neutral-dark);
        margin-bottom: 15px;
        font-weight: 700;
    }

    .empty-state p {
        font-size: 1.1rem;
        color: var(--neutral-text);
        margin-bottom: 30px;
        opacity: 0.8;
    }

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        margin: 60px 0;
        padding-top: 30px;
        border-top: 2px solid var(--neutral-medium);
    }

    .pagination-info {
        color: var(--neutral-text);
        font-size: 0.95rem;
        opacity: 0.8;
    }

    /* Newsletter CTA */
    .newsletter-cta {
        background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
        border-radius: 20px;
        padding: 40px;
        margin: 60px 0;
        color: white;
        box-shadow: var(--shadow-large);
    }

    .newsletter-content {
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .newsletter-icon {
        font-size: 3rem;
        opacity: 0.9;
    }

    .newsletter-text {
        flex: 1;
        min-width: 300px;
    }

    .newsletter-text h4 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .newsletter-text p {
        opacity: 0.9;
        margin: 0;
        font-size: 1rem;
    }

    .newsletter-form {
        flex: 1;
        min-width: 300px;
    }

    .newsletter-form .input-group {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .newsletter-form .form-control {
        border: none;
        padding: 15px 20px;
        font-size: 1rem;
        height: 56px;
    }

    .newsletter-form .btn-primary {
        background: var(--accent-color);
        border-color: var(--accent-color);
        padding: 0 30px;
        font-weight: 600;
        height: 56px;
        transition: all 0.3s ease;
    }

    .newsletter-form .btn-primary:hover {
        background: var(--accent-dark);
        border-color: var(--accent-dark);
        transform: translateY(-2px);
    }

    /* Categories Sidebar */
    .categories-sidebar {
        background: var(--neutral-light);
        padding: 40px 0;
        border-top: 2px solid var(--neutral-medium);
        margin-top: 40px;
    }

    .sidebar-content {
        max-width: 1200px;
        margin: 0 auto;
    }

    .sidebar-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--neutral-dark);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
    }

    .categories-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }

    .category-item {
        background: white;
        border: 2px solid var(--neutral-medium);
        border-radius: 10px;
        padding: 15px 20px;
        text-decoration: none;
        color: var(--neutral-dark);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .category-item:hover,
    .category-item.active {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    .category-item.active .category-count {
        background: white;
        color: var(--primary-color);
    }

    .category-count {
        background: var(--neutral-medium);
        color: var(--neutral-text);
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        min-width: 30px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .category-item:hover .category-count {
        background: white;
        color: var(--primary-color);
    }

    .view-all-categories {
        margin-top: 25px;
        text-align: center;
    }

    .view-all-categories .btn-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* Responsividade */
    @media (max-width: 992px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .results-info {
            text-align: left;
        }

        .news-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        }

        .newsletter-content {
            flex-direction: column;
            text-align: center;
        }

        .newsletter-text,
        .newsletter-form {
            min-width: 100%;
        }

        .categories-list {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }

        .filters-row {
            flex-direction: column;
            gap: 15px;
        }

        .filter-group {
            min-width: 100%;
        }

        .news-grid {
            grid-template-columns: 1fr;
        }

        .news-grid[data-layout="list"] .news-card {
            flex-direction: column;
        }

        .news-grid[data-layout="list"] .news-card-image {
            flex: 0 0 200px;
            border-radius: 15px 15px 0 0;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }

    @media (max-width: 576px) {
        .page-header {
            padding: 40px 0 30px;
        }

        .page-title {
            font-size: 1.7rem;
        }

        .search-form .btn-primary {
            padding: 0 20px;
        }

        .newsletter-cta {
            padding: 30px 20px;
        }

        .categories-list {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Layout toggle
    const toggleBtns = document.querySelectorAll('.toggle-btn');
    const newsGrid = document.getElementById('newsGrid');
    
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const layout = this.getAttribute('data-layout');
            
            // Update active button
            toggleBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update grid layout
            newsGrid.setAttribute('data-layout', layout);
            
            // Save preference to localStorage
            localStorage.setItem('newsLayout', layout);
        });
    });
    
    // Load saved layout preference
    const savedLayout = localStorage.getItem('newsLayout') || 'grid';
    const savedBtn = document.querySelector(`.toggle-btn[data-layout="${savedLayout}"]`);
    if (savedBtn) {
        savedBtn.classList.add('active');
        newsGrid.setAttribute('data-layout', savedLayout);
    }
    
    // Save post functionality
    const saveBtns = document.querySelectorAll('.save-btn');
    saveBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const icon = this.querySelector('i');
            
            // Toggle save state
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.style.color = 'var(--accent-color)';
                savePost(postId, true);
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.style.color = '';
                savePost(postId, false);
            }
        });
    });
    
    function savePost(postId, save) {
        // Here you would make an AJAX request to save/unsave the post
        console.log(save ? 'Saving post' : 'Removing saved post', postId);
        
        // Example AJAX call:
        // fetch(`/posts/${postId}/save`, {
        //     method: save ? 'POST' : 'DELETE',
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        //         'Content-Type': 'application/json'
        //     }
        // })
        // .then(response => response.json())
        // .then(data => {
        //     console.log(data.message);
        // })
        // .catch(error => console.error('Error:', error));
    }
    
    // Share functionality
    window.shareContent = function(title, url) {
        if (navigator.share) {
            navigator.share({
                title: title,
                text: 'Confira esta notícia do Portal HSE:',
                url: url
            }).then(() => {
                console.log('Conteúdo compartilhado com sucesso');
            }).catch(console.error);
        } else {
            // Fallback for browsers that don't support Web Share API
            const shareUrl = `${window.location.origin}/share?title=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`;
            navigator.clipboard.writeText(url).then(() => {
                showToast('Link copiado para a área de transferência!', 'success');
            }).catch(err => {
                console.error('Erro ao copiar link: ', err);
                // Fallback to opening in new window
                window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
            });
        }
    };
    
    function showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
    
    // Newsletter form submission
    const newsletterForm = document.querySelector('.newsletter-form form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processando...';
            
            // Simulate API call
            setTimeout(() => {
                // In real app, you would send the form data
                // fetch(this.action, {
                //     method: 'POST',
                //     body: formData
                // })
                // .then(response => response.json())
                // .then(data => {
                //     if (data.success) {
                //         showToast('Inscrição realizada com sucesso!', 'success');
                //         this.reset();
                //     } else {
                //         showToast(data.message || 'Erro ao realizar inscrição', 'error');
                //     }
                // })
                
                // For demo purposes
                showToast('Inscrição realizada com sucesso!', 'success');
                this.reset();
                
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1500);
        });
    }
    
    // Smooth scroll to top when clicking pagination links
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            
            // Smooth scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
            
            // Navigate after scroll
            setTimeout(() => {
                window.location.href = href;
            }, 300);
        });
    });
    
    // Filter animation
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Add loading state
            const container = this.closest('.filters-section');
            container.style.opacity = '0.7';
            container.style.pointerEvents = 'none';
            
            // Remove loading state after navigation
            setTimeout(() => {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }, 500);
        });
    });
});
</script>
@endpush