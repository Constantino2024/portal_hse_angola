@extends('layouts.app')

@section('title', $post->title . ' | Portal HSE')
@section('meta_title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)

@section('meta_extra')
<meta property="og:title" content="{{ $post->meta_title ?? $post->title }}">
<meta property="og:description" content="{{ $post->meta_description ?? $post->excerpt }}">

@if($post->image_url)
<meta property="og:image" content="{{ asset('storage/'.$post->image_url) }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
@endif
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="article">
<meta property="article:published_time" content="{{ $post->published_at }}">
<meta property="article:author" content="{{ $post->author_name }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $post->meta_title ?? $post->title }}">
<meta name="twitter:description" content="{{ $post->meta_description ?? $post->excerpt }}">
@if($post->image_url)
<meta name="twitter:image" content="{{ asset('storage/'.$post->image_url) }}">
@endif

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
@php
    $shareUrl = urlencode(url()->current());
    $shareTitle = urlencode($post->title);
    $shareText = urlencode($post->title . ' - Portal HSE');

    // Galeria (capa + 3 extras)
    $gallery = collect([
        $post->image_url ?? null,
        $post->image_2_url ?? null,
        $post->image_3_url ?? null,
        $post->image_4_url ?? null,
    ])->filter()->values();

    $relatedPosts = \App\Models\Post::whereNotNull('published_at')
        ->where('id', '!=', $post->id)
        ->where(function($query) use ($post) {
            $query->where('category_id', $post->category_id)
                  ->orWhere('author_name', $post->author_name);
        })
        ->latest('published_at')
        ->take(6)
        ->get();

    if ($relatedPosts->count() < 3) {
        $additionalPosts = \App\Models\Post::whereNotNull('published_at')
            ->where('id', '!=', $post->id)
            ->whereNotIn('id', $relatedPosts->pluck('id'))
            ->latest('published_at')
            ->take(6 - $relatedPosts->count())
            ->get();
        
        $relatedPosts = $relatedPosts->merge($additionalPosts);
    }

    $readingTime = $post->reading_time ?? ceil(str_word_count(strip_tags($post->body)) / 200);
@endphp

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="breadcrumb-wrapper">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
            <li class="breadcrumb-item"><a href="{{ route('posts.public') }}">Notícias</a></li>
            @if($post->category)
            <li class="breadcrumb-item"><a href="{{ route('posts.public') }}?category={{ $post->category->slug }}">{{ $post->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 50) }}</li>
        </ol>
    </div>
</nav>

<section class="single-post-wrapper">
    <div class="container">
        <div class="row g-5">

            {{-- ===================== CONTEÚDO PRINCIPAL ===================== --}}
            <div class="col-lg-8">

                {{-- Cabeçalho do Post --}}
                <div class="post-header mb-4">
                    <div class="post-header-top">
                        <span class="category-badge">{{ $post->category->name ?? 'Geral' }}</span>
                        @if($post->is_featured)
                            <span class="featured-badge">
                                <i class="fas fa-star"></i> Destaque
                            </span>
                        @endif
                        @if($post->is_exclusive)
                            <span class="exclusive-badge">
                                <i class="fas fa-crown"></i> Exclusivo
                            </span>
                        @endif
                    </div>

                    <h1 class="post-title">{{ $post->title }}</h1>
                    
                    @if($post->subtitle)
                        <h2 class="post-subtitle">{{ $post->subtitle }}</h2>
                    @endif

                    <div class="post-meta">
                        <div class="post-author">
                            <div class="author-avatar">
                                @if($post->author_photo)
                                    <img src="{{ asset('storage/'.$post->author_photo) }}" alt="{{ $post->author_name }}">
                                @else
                                    <i class="fas fa-user-circle"></i>
                                @endif
                            </div>
                            <div class="author-info">
                                <strong>{{ $post->author_name }}</strong>
                                <span>Portal HSE</span>
                            </div>
                        </div>
                        
                        <div class="post-meta-details">
                            <div class="meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ optional($post->published_at)->format('d \\d\\e F \\d\\e Y') }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $readingTime }} min de leitura</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-eye"></i>
                                <span>{{ $post->views ?? '0' }} visualizações</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===================== SHARE BAR ===================== --}}
                <div class="share-bar mb-5">
                    <div class="share-bar-content">
                        <span class="share-label">Compartilhar:</span>
                        <div class="share-buttons">
                            <a href="https://api.whatsapp.com/send?text={{ $shareText }}%20{{ $shareUrl }}" 
                               target="_blank" 
                               class="share-btn whatsapp" 
                               title="Compartilhar no WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" 
                               target="_blank" 
                               class="share-btn facebook" 
                               title="Compartilhar no Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" 
                               target="_blank" 
                               class="share-btn linkedin" 
                               title="Compartilhar no LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" 
                               target="_blank" 
                               class="share-btn twitter" 
                               title="Compartilhar no X (Twitter)">
                                <i class="fab fa-x-twitter"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" 
                               target="_blank" 
                               class="share-btn email" 
                               title="Enviar por email">
                                <i class="fas fa-envelope"></i>
                            </a>
                            <button class="share-btn copy-link" 
                                    title="Copiar link" 
                                    onclick="copyToClipboard('{{ url()->current() }}')">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ===================== GALERIA DE IMAGENS ===================== --}}
                @if($gallery->count())
                    <div class="post-gallery mb-5">
                        {{-- Imagem principal --}}
                        <div class="post-featured-image">
                            <img src="{{ asset('storage/'.$gallery->first()) }}" 
                                 alt="{{ $post->title }}"
                                 class="featured-img"
                                 id="featuredImage"
                                 loading="eager">
                            
                            @if($gallery->count() > 1)
                                <div class="image-counter">
                                    <i class="fas fa-images"></i> 1/{{ $gallery->count() }}
                                </div>
                                <button class="gallery-nav prev" onclick="changeGalleryImage(-1)">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="gallery-nav next" onclick="changeGalleryImage(1)">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            @endif
                        </div>

                        {{-- Thumbnails --}}
                        @if($gallery->count() > 1)
                            <div class="gallery-thumbnails">
                                @foreach($gallery as $index => $img)
                                    <button class="thumbnail-btn {{ $index === 0 ? 'active' : '' }}" 
                                            onclick="showGalleryImage({{ $index }})">
                                        <img src="{{ asset('storage/'.$img) }}" 
                                             alt="Imagem {{ $index + 1 }} da notícia"
                                             loading="lazy">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                {{-- ===================== VÍDEO ===================== --}}
                @if($post->video_url)
                    <div class="post-video-wrapper mb-5" id="videoSection">
                        <div class="video-header">
                            <h3><i class="fas fa-play-circle"></i> Vídeo da Notícia</h3>
                            @if($post->video_caption)
                                <p class="video-caption">{{ $post->video_caption }}</p>
                            @endif
                        </div>
                        <div class="video-container">
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ $post->video_url }}" 
                                        title="{{ $post->title }}"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                        loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ===================== CORPO DO POST ===================== --}}
                <article class="post-body mb-5">
                    <div class="post-content">
                        {!! $post->body !!}
                    </div>

                    {{-- Tags --}}
                    @if($post->tags)
                        <div class="post-tags mt-4">
                            <strong>Tags:</strong>
                            @foreach(explode(',', $post->tags) as $tag)
                                <a href="{{ route('posts.public') }}?tag={{ trim($tag) }}" class="tag-link">
                                    #{{ trim($tag) }}
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Post Footer --}}
                    <div class="post-footer mt-5">
                        <div class="post-footer-content">
                            <div class="post-updated">
                                <i class="fas fa-history"></i>
                                <span>Atualizado em {{ $post->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            
                            <div class="post-actions">
                                <button class="action-btn save-btn" onclick="toggleSavePost({{ $post->id }})">
                                    <i class="far fa-bookmark"></i> Salvar
                                </button>
                                <button class="action-btn print-btn" onclick="window.print()">
                                    <i class="fas fa-print"></i> Imprimir
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- ===================== COMENTÁRIOS ===================== --}}
                <section class="comments-section">
                    <div class="section-header">
                        <h2 class="section-title">
                            <i class="fas fa-comments"></i> Comentários
                            <span class="comments-count" id="comments-total"></span>
                        </h2>
                        <p class="section-subtitle">Partilhe a sua opinião sobre esta notícia</p>
                    </div>

                    <div class="comments-container">
                        {{-- Lista de comentários --}}
                        <div id="comments-list" class="comments-list"></div>

                        {{-- Paginação --}}
                        <div class="comments-pagination">
                            <button id="prev-comments" class="btn btn-pagination" disabled>
     
                            <i class="fas fa-chevron-left"></i> Anterior
                            </button>
                            
                            <div class="pagination-info">
                                <span id="comments-page-info">Página 1</span>
                            </div>
                            
                            <button id="next-comments" class="btn btn-pagination" disabled>
                                Próximo <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>

                        {{-- Formulário de comentário --}}
                        <div class="comment-form-card">
                            <div class="form-header">
                                <h4>Deixe o seu comentário</h4>
                                <p class="form-subtitle">O seu email não será publicado. Campos obrigatórios marcados com *</p>
                            </div>

                            <form id="comment-form" action="{{ route('comments.store.ajax', $post) }}" method="POST">
                                @csrf

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="author_name">Nome *</label>
                                        <input type="text" 
                                               id="author_name" 
                                               name="author_name" 
                                               class="form-control" 
                                               placeholder="Seu nome" 
                                               required>
                                    </div>

                                    <div class="form-group">
                                        <label for="author_email">Email *</label>
                                        <input type="email" 
                                               id="author_email" 
                                               name="author_email" 
                                               class="form-control" 
                                               placeholder="seu@email.com" 
                                               required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="comment_body">Comentário *</label>
                                    <textarea id="comment_body" 
                                              name="body" 
                                              class="form-control" 
                                              rows="5" 
                                              placeholder="Digite seu comentário aqui..."
                                              required></textarea>
                                    <div class="char-counter">
                                        <span id="char-count">0</span> / 500 caracteres
                                    </div>
                                </div>

                                <div class="form-footer">
                                    
                                    <button type="submit" class="btn btn-primary" id="commentSubmitBtn">
                                        <i class="fas fa-paper-plane me-2"></i> Enviar comentário
                                    </button>
                                </div>

                                {{-- Mensagens de feedback --}}
                                <div id="comment-success" class="alert alert-success mt-3 d-none">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Comentário enviado com sucesso! Aguarde a aprovação.
                                </div>

                                <div id="comment-error" class="alert alert-danger mt-3 d-none">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <span id="error-message">Ocorreu um erro ao enviar. Tente novamente.</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>

            </div>

            {{-- ===================== SIDEBAR ===================== --}}
            <div class="col-lg-4">
                {{-- Newsletter --}}
                <div class="sidebar-card newsletter-card">
                    <div class="card-header">
                        <i class="fas fa-envelope-open-text"></i>
                        <h3>Newsletter HSE</h3>
                    </div>
                    <div class="card-body">
                        <p>Receba as últimas notícias e conteúdos exclusivos sobre Saúde, Segurança e Ambiente.</p>
                        
                        <form action="{{ route('subscribers.store') }}" method="POST" class="sidebar-newsletter-form">
                            @csrf
                            <div class="input-group">
                                <input type="email" 
                                       name="email" 
                                       class="form-control" 
                                       placeholder="Seu melhor email"
                                       required>
                                <button type="submit" class="btn btn-accent">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                            <small class="form-text">
                                <i class="fas fa-lock"></i> Respeitamos sua privacidade
                            </small>
                        </form>
                        
                        <div class="newsletter-benefits">
                            <div class="benefit-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Conteúdo exclusivo</span>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Zero spam</span>
                            </div>
                            <div class="benefit-item">
                                <i class="fas fa-check-circle"></i>
                                <span>Cancele quando quiser</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Posts Relacionados --}}
                <div class="sidebar-card related-posts-card">
                    <div class="card-header">
                        <i class="fas fa-newspaper"></i>
                        <h3>Notícias Relacionadas</h3>
                    </div>
                    <div class="card-body">
                        @forelse($relatedPosts as $related)
                            <a href="{{ route('posts.show', $related->slug) }}" class="related-post-item">
                                <div class="related-post-image">
                                    @if($related->image_url)
                                        <img src="{{ asset('storage/'.$related->image_url) }}" 
                                             alt="{{ $related->title }}"
                                             loading="lazy">
                                    @else
                                        <div class="image-placeholder">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="related-post-content">
                                    <h4>{{ Str::limit($related->title, 60) }}</h4>
                                    <div class="post-meta-small">
                                        <span>{{ optional($related->published_at)->format('d/m/Y') }}</span>
                                        <span>•</span>
                                        <span>{{ $related->category->name ?? 'Geral' }}</span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="text-muted">Não há notícias relacionadas no momento.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Categorias Populares --}}
                <div class="sidebar-card categories-card">
                    <div class="card-header">
                        <i class="fas fa-folder"></i>
                        <h3>Categorias</h3>
                    </div>
                    <div class="card-body">
                        <ul class="categories-list">
                            @foreach(\App\Models\Category::withCount('posts')->orderBy('posts_count', 'desc')->take(8)->get() as $category)
                                <li>
                                    <a href="{{ route('posts.public') }}?category={{ $category->slug }}">
                                        <span class="category-name">{{ $category->name }}</span>
                                        <span class="category-count">{{ $category->posts_count }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Anúncio/Patrocinador --}}
                <div class="sidebar-card ad-card">
                    <div class="card-header">
                        <i class="fas fa-bullhorn"></i>
                        <h3>Patrocinador</h3>
                    </div>
                    <div class="card-body">
                        <div class="ad-content">
                            <img src="https://via.placeholder.com/300x150/0066cc/ffffff?text=HSE+PRO" 
                                 alt="HSE Pro - Patrocinador"
                                 loading="lazy"
                                 class="ad-image">
                            <div class="ad-text">
                                <h5>Soluções HSE Integradas</h5>
                                <p>Consultoria especializada em Saúde, Segurança e Ambiente</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    Saiba Mais <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===================== POSTS RECOMENDADOS ===================== --}}
<section class="recommended-posts">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Você Também Pode Gostar</h2>
            <p class="section-subtitle">Descubra mais conteúdos relevantes sobre HSE</p>
        </div>
        
        <div class="recommended-grid">
            @foreach(\App\Models\Post::whereNotNull('published_at')
                ->where('id', '!=', $post->id)
                ->inRandomOrder()
                ->take(3)
                ->get() as $recommended)
                <article class="recommended-card">
                    <a href="{{ route('posts.show', $recommended->slug) }}" class="recommended-image">
                        @if($recommended->image_url)
                            <img src="{{ asset('storage/'.$recommended->image_url) }}" 
                                 alt="{{ $recommended->title }}"
                                 loading="lazy">
                        @else
                            <div class="image-placeholder">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        @endif
                        <span class="category-tag">{{ $recommended->category->name ?? 'Geral' }}</span>
                    </a>
                    <div class="recommended-content">
                        <h3>
                            <a href="{{ route('posts.show', $recommended->slug) }}">
                                {{ Str::limit($recommended->title, 70) }}
                            </a>
                        </h3>
                        <div class="recommended-meta">
                            <span>{{ optional($recommended->published_at)->format('d/m/Y') }}</span>
                            <span>•</span>
                            <span>{{ $recommended->reading_time ?? '3' }} min</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== AUTHOR BOX ===================== --}}
@if($post->author_bio || $post->author_photo)
<section class="author-section">
    <div class="container">
        <div class="author-card">
            <div class="author-header">
                <div class="author-avatar-large">
                    @if($post->author_photo)
                        <img src="{{ asset('storage/'.$post->author_photo) }}" alt="{{ $post->author_name }}">
                    @else
                        <i class="fas fa-user-circle"></i>
                    @endif
                </div>
                <div class="author-info">
                    <h3>{{ $post->author_name }}</h3>
                    <p class="author-title">Colaborador do Portal HSE</p>
                    @if($post->author_role)
                        <span class="author-role">{{ $post->author_role }}</span>
                    @endif
                </div>
            </div>
            
            @if($post->author_bio)
                <div class="author-bio">
                    <p>{{ $post->author_bio }}</p>
                </div>
            @endif
            
            <div class="author-stats">
                <div class="stat-item">
                    <i class="fas fa-newspaper"></i>
                    <span>{{ $post->author_posts_count ?? '0' }} artigos</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-eye"></i>
                    <span>{{ $post->author_views_count ?? '0' }} visualizações</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endif 

{{-- Toast para link copiado --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="copyToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body">
            <i class="fas fa-check-circle me-2 text-success"></i>
            Link copiado para a área de transferência!
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/postshow.css') }}">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ====== GALERIA DE IMAGENS ======
    const galleryImages = @json($gallery->map(fn($img) => asset('storage/'.$img)));
    let currentGalleryIndex = 0;
    const featuredImage = document.getElementById('featuredImage');
    const thumbnailButtons = document.querySelectorAll('.thumbnail-btn');

    function showGalleryImage(index) {
        if (index < 0 || index >= galleryImages.length) return;
        
        currentGalleryIndex = index;
        
        // Atualiza imagem principal
        featuredImage.src = galleryImages[index];
        
        // Atualiza contador
        const counter = document.querySelector('.image-counter');
        if (counter) {
            counter.innerHTML = `<i class="fas fa-images"></i> ${index + 1}/${galleryImages.length}`;
        }
        
        // Atualiza thumbnails ativos
        thumbnailButtons.forEach((btn, i) => {
            btn.classList.toggle('active', i === index);
        });
    }

    function changeGalleryImage(direction) {
        let newIndex = currentGalleryIndex + direction;
        
        if (newIndex < 0) newIndex = galleryImages.length - 1;
        if (newIndex >= galleryImages.length) newIndex = 0;
        
        showGalleryImage(newIndex);
    }

    // Event listeners para a galeria
    document.querySelectorAll('.thumbnail-btn').forEach((btn, index) => {
        btn.addEventListener('click', () => showGalleryImage(index));
    });

    if (document.querySelector('.gallery-nav.prev')) {
        document.querySelector('.gallery-nav.prev').addEventListener('click', () => changeGalleryImage(-1));
        document.querySelector('.gallery-nav.next').addEventListener('click', () => changeGalleryImage(1));
    }

    // Navegação por teclado
    document.addEventListener('keydown', (e) => {
        if (galleryImages.length > 1) {
            if (e.key === 'ArrowLeft') changeGalleryImage(-1);
            if (e.key === 'ArrowRight') changeGalleryImage(1);
        }
    });

    // ====== COPIAR LINK ======
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            const toast = new bootstrap.Toast(document.getElementById('copyToast'));
            toast.show();
        }).catch(err => {
            console.error('Erro ao copiar: ', err);
        });
    };

    // ====== CONTADOR DE CARACTERES DO COMENTÁRIO ======
    const commentTextarea = document.getElementById('comment_body');
    const charCount = document.getElementById('char-count');
    
    if (commentTextarea && charCount) {
        commentTextarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            
            if (length > 500) {
                charCount.style.color = '#dc3545';
            } else if (length > 400) {
                charCount.style.color = '#ffc107';
            } else {
                charCount.style.color = '#28a745';
            }
        });
    }

    // ====== PAGINAÇÃO DE COMENTÁRIOS ======
    let currentCommentsUrl = "{{ route('comments.ajax', $post) }}";
    let commentsTotal = 0;

    async function loadComments(url) {
        if (!url) return;

        try {
            const response = await fetch(url);
            const result = await response.json();

            const list = document.getElementById('comments-list');
            list.innerHTML = '';

            // Atualiza total de comentários
            commentsTotal = result.total || 0;
            const totalEl = document.getElementById('comments-total');
            if (totalEl) {
                totalEl.textContent = `(${commentsTotal})`;
            }

            if (!result.data || result.data.length === 0) {
                list.innerHTML = `
                    <div class="empty-comments">
                        <i class="fas fa-comments"></i>
                        <h4>Nenhum comentário ainda</h4>
                        <p>Seja o primeiro a comentar esta notícia!</p>
                    </div>
                `;
            } else {
                result.data.forEach(comment => {
                    const commentDate = new Date(comment.created_at);
                    const formattedDate = commentDate.toLocaleDateString('pt-PT', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    list.insertAdjacentHTML('beforeend', `
                        <div class="comment-item">
                            <div class="comment-header">
                                <div class="comment-avatar">
                                    <span>${comment.name.charAt(0).toUpperCase()}</span>
                                </div>
                                <div class="comment-author">
                                    <strong>${comment.name}</strong>
                                    <span class="comment-date">
                                        <i class="fas fa-clock"></i> ${comment.date}
                                    </span>
                                </div>
                            </div>
                            <div class="comment-body">
                                ${comment.body}
                            </div>
                            ${comment.reply_to ? `
                                <div class="comment-reply">
                                    <i class="fas fa-reply"></i> Em resposta a ${comment.reply_to}
                                </div>
                            ` : ''}
                        </div>
                    `);
                });
            }

            // Atualiza botões de paginação
            const prevBtn = document.getElementById('prev-comments');
            const nextBtn = document.getElementById('next-comments');
            const pageInfo = document.getElementById('comments-page-info');

            prevBtn.disabled = !result.prev_page_url;
            nextBtn.disabled = !result.next_page_url;

            if (pageInfo) {
                pageInfo.textContent = `Página ${result.current_page} de ${result.last_page}`;
            }

            prevBtn.dataset.url = result.prev_page_url || '';
            nextBtn.dataset.url = result.next_page_url || '';

        } catch (error) {
            console.error('Erro ao carregar comentários:', error);
        }
    }

    // Carrega primeira página
    loadComments(currentCommentsUrl);

    // Event listeners para paginação
    document.getElementById('prev-comments').addEventListener('click', function() {
        if (this.dataset.url) loadComments(this.dataset.url);
    });

    document.getElementById('next-comments').addEventListener('click', function() {
        if (this.dataset.url) loadComments(this.dataset.url);
    });

    // ====== ENVIO DE COMENTÁRIO ======
    const commentForm = document.getElementById('comment-form');
    const successEl = document.getElementById('comment-success');
    const errorEl = document.getElementById('comment-error');
    const submitBtn = document.getElementById('commentSubmitBtn');
    

    if (commentForm) {
        commentForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Esconde mensagens anteriores
            if (successEl) successEl.classList.add('d-none');
            if (errorEl) errorEl.classList.add('d-none');

            // Salva texto original do botão
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            
            // Desabilita botão e mostra loading
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Enviando...
                `;
            }

            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Erro ao enviar comentário');
                }

                // Sucesso
                if (successEl) {
                    successEl.classList.remove('d-none');
                }

                // Limpa formulário
                this.reset();
                
                // Reseta contador de caracteres
                if (charCount) {
                    charCount.textContent = '0';
                    charCount.style.color = '#28a745';
                }

                // Recarrega comentários
                setTimeout(() => {
                    loadComments("{{ route('comments.ajax', $post) }}");
                    location.reload();
                }, 1000);

            } catch (error) {
                // Erro
                if (errorEl) {
                    errorEl.querySelector('#error-message').textContent = error.message;
                    errorEl.classList.remove('d-none');
                }
            } finally {
                // Restaura botão
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            }
        });
    }

    // ====== SALVAR POST ======
    window.toggleSavePost = function(postId) {
        const saveBtn = document.querySelector('.save-btn');
        const icon = saveBtn.querySelector('i');
        
        // Toggle visual
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            saveBtn.innerHTML = '<i class="fas fa-bookmark"></i> Salvo';
            saveBtn.classList.add('saved');
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            saveBtn.innerHTML = '<i class="far fa-bookmark"></i> Salvar';
            saveBtn.classList.remove('saved');
        }

        // Em produção, aqui faria uma requisição AJAX para salvar/remover
        console.log('Post ' + postId + ' salvo/removido');
    };

    // ====== SCROLL SUAVE PARA ÂNCORAS ======
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });

});
</script>
@endpush