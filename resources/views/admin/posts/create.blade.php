@extends('layouts.admin')

@section('title', 'Nova notícia')

@section('content')
<section class="admin-create-wrap container my-5">

    {{-- Header --}}
    <div class="admin-page-head mb-4">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <span class="admin-badge">
                    <i class="fa-solid fa-shield-halved"></i> Admin · Gestão de Conteúdo
                </span>
                <h1 class="admin-title mt-2">Criar nova notícia</h1>
                <p class="admin-subtitle mb-0">
                    Preencha os campos, adicione imagens e publique. A data/hora são automáticas.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                </a>
                <button form="postCreateForm" type="submit" class="btn btn-primary rounded-pill">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Publicar
                </button>
            </div>
        </div>
    </div>

    {{-- Erros --}}
    @if($errors->any())
        <div class="alert alert-danger rounded-4 shadow-sm">
            <div class="d-flex align-items-start gap-2">
                <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                <div>
                    <strong>Corrija os erros abaixo:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form id="postCreateForm" action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            {{-- COLUNA PRINCIPAL --}}
            <div class="col-lg-8">

                {{-- Dados principais --}}
                <div class="card admin-card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-4">
                        <div class="admin-card-head mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="admin-icon">
                                    <i class="fa-solid fa-pen-nib"></i>
                                </span>
                                <div>
                                    <h5 class="mb-0">Conteúdo da notícia</h5>
                                    <small class="text-muted">Título, autor, resumo e conteúdo completo</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Título</label>
                            <input type="text" name="title" class="form-control form-control-lg"
                                   value="{{ old('title') }}"
                                   placeholder="Ex.: Novo regulamento de segurança no trabalho em 2026"
                                   required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Autor</label>
                                <input type="text" name="author_name" class="form-control"
                                       value="{{ old('author_name') }}"
                                       placeholder="Ex.: Redação Portal HSE"
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Categoria</label>
                                <select name="category_id" class="form-select">
                                    <option value="">—</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Subtítulo (opcional)</label>
                            <input type="text" name="subtitle" class="form-control"
                                   value="{{ old('subtitle') }}"
                                   placeholder="Uma frase curta que complementa o título">
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Resumo (excerpt)</label>
                            <textarea name="excerpt" class="form-control" rows="3"
                                      placeholder="Resumo curto para listagens e SEO">{{ old('excerpt') }}</textarea>
                            <div class="form-text">Dica: 140–220 caracteres costuma ficar perfeito para cards.</div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Conteúdo</label>
                            <textarea name="body" class="form-control admin-editor" rows="12"
                                      placeholder="Escreva aqui o conteúdo completo da notícia..."
                                      required>{{ old('body') }}</textarea>
                            <div class="form-text">
                                Pode colar texto formatado. Se o teu editor já envia HTML, mantém assim.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SEO + vídeo + flags --}}
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
                                <input type="url" name="video_url" class="form-control"
                                       value="{{ old('video_url') }}"
                                       placeholder="https://www.youtube.com/embed/SEU_ID">
                                <div class="form-text">
                                    Cole o link no formato <strong>/embed/</strong>. Ex.: youtube.com/embed/xxxx
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Opções</label>
                                <div class="admin-switches">
                                    <label class="switch-item">
                                        <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                               @checked(old('is_featured'))>
                                        <span><i class="fa-solid fa-star me-1"></i> Destaque (Hero)</span>
                                    </label>

                                    <label class="switch-item">
                                        <input class="form-check-input" type="checkbox" name="is_popular" value="1"
                                               @checked(old('is_popular'))>
                                        <span><i class="fa-solid fa-fire me-1"></i> Popular</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="admin-tip mt-3">
                            <i class="fa-solid fa-circle-info"></i>
                            <div>
                                <strong>Nota:</strong> a data/hora de publicação são inseridas automaticamente.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- COLUNA LATERAL --}}
            <div class="col-lg-4">

                {{-- Uploads --}}
                <div class="card admin-card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="admin-card-head mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="admin-icon" style="background: rgba(255,154,60,.15); color: var(--accent-color);">
                                    <i class="fa-solid fa-images"></i>
                                </span>
                                <div>
                                    <h5 class="mb-0">Imagens</h5>
                                    <small class="text-muted">Até 4 imagens (opcionais)</small>
                                </div>
                            </div>
                        </div>

                        <div class="admin-note mb-3">
                            <i class="fa-solid fa-image"></i>
                            <div>
                                <strong>A 1ª imagem é a capa.</strong>
                                As outras aparecem como galeria na página da notícia.
                            </div>
                        </div>

                        @php
                            $uploads = [
                                ['name'=>'image',   'label'=>'Capa (principal)', 'hint'=>'Recomendado: 1600×900'],
                                ['name'=>'image_2', 'label'=>'Imagem 2',         'hint'=>'Opcional'],
                                ['name'=>'image_3', 'label'=>'Imagem 3',         'hint'=>'Opcional'],
                                ['name'=>'image_4', 'label'=>'Imagem 4',         'hint'=>'Opcional'],
                            ];
                        @endphp

                        <div class="upload-grid">
                            @foreach($uploads as $u)
                                <div class="upload-box">
                                    <div class="upload-top">
                                        <div>
                                            <div class="upload-label">{{ $u['label'] }}</div>
                                            <div class="upload-hint">{{ $u['hint'] }}</div>
                                        </div>
                                        <span class="upload-chip">
                                            <i class="fa-solid fa-upload"></i>
                                        </span>
                                    </div>

                                    <input type="file"
                                           name="{{ $u['name'] }}"
                                           class="form-control upload-input"
                                           accept="image/*"
                                           data-preview="prev-{{ $u['name'] }}">

                                    <div class="upload-preview mt-2" id="prev-{{ $u['name'] }}">
                                        <div class="upload-empty">
                                            <i class="fa-regular fa-image"></i>
                                            <span>Sem imagem</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button class="btn btn-primary w-100 mt-3" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Publicar notícia
                        </button>

                        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fa-solid fa-list me-1"></i> Ver lista de notícias
                        </a>
                    </div>
                </div>



            </div>
        </div>

    </form>
</section>
@endsection

@push('styles')
<style>
/* ====== Cabeçalho da página ====== */
.admin-page-head{
    background: linear-gradient(135deg, rgba(26,95,122,.08), rgba(255,154,60,.08));
    border: 1px solid rgba(0,0,0,.06);
    border-radius: 18px;
    padding: 18px 18px;
    box-shadow: var(--shadow);
}
.admin-badge{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding: 8px 12px;
    border-radius: 999px;
    font-weight: 700;
    font-size: .85rem;
    background: rgba(26,95,122,.12);
    color: var(--primary-color);
}
.admin-title{
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-color);
    margin: 0;
}
.admin-subtitle{
    color: #555;
    margin-top: 6px;
}

/* ====== Cards ====== */
.admin-card{
    border: 1px solid rgba(0,0,0,.06);
}
.admin-card-head h5{
    font-weight: 800;
    color: var(--primary-color);
}
.admin-icon{
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display:flex;
    align-items:center;
    justify-content:center;
    background: rgba(26,95,122,.12);
    color: var(--primary-color);
}

/* ====== Inputs + Editor ====== */
.admin-editor{
    min-height: 260px;
    border-radius: 14px;
}
.form-control, .form-select{
    border-radius: 14px;
    border-color: rgba(0,0,0,.12);
}
.form-control:focus, .form-select:focus{
    border-color: rgba(255,154,60,.6);
    box-shadow: 0 0 0 4px rgba(255,154,60,.12);
}

/* ====== Switches ====== */
.admin-switches{
    display:flex;
    flex-direction:column;
    gap:10px;
    border: 1px dashed rgba(0,0,0,.12);
    border-radius: 14px;
    padding: 12px;
    background: rgba(248,249,250,.7);
}
.switch-item{
    display:flex;
    align-items:center;
    gap:10px;
    font-weight: 700;
    color: #2c3e50;
}
.switch-item .form-check-input{
    width: 1.15rem;
    height: 1.15rem;
    border-radius: 6px;
}

/* ====== Dicas ====== */
.admin-tip{
    display:flex;
    gap:10px;
    align-items:flex-start;
    padding: 12px 14px;
    border-radius: 14px;
    background: rgba(45,139,139,.08);
    border: 1px solid rgba(45,139,139,.15);
    color: #1f3b3b;
}
.admin-tip i{ margin-top: 2px; }

/* ====== Uploads ====== */
.admin-note{
    display:flex;
    gap:10px;
    align-items:flex-start;
    padding: 12px 14px;
    border-radius: 14px;
    background: rgba(255,154,60,.10);
    border: 1px solid rgba(255,154,60,.18);
    color: #5a3a16;
}
.admin-note i{ margin-top: 2px; }

.upload-grid{
    display:flex;
    flex-direction:column;
    gap:12px;
}
.upload-box{
    border: 1px solid rgba(0,0,0,.08);
    border-radius: 16px;
    padding: 12px;
    background: #fff;
    transition: .2s ease;
}
.upload-box:hover{
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}
.upload-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:10px;
    margin-bottom:10px;
}
.upload-label{
    font-weight: 900;
    font-size: .95rem;
    color: var(--primary-color);
}
.upload-hint{
    font-size: .82rem;
    color: #6b7280;
}
.upload-chip{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width: 34px;
    height: 34px;
    border-radius: 12px;
    background: rgba(26,95,122,.10);
    color: var(--primary-color);
}

/* preview */
.upload-preview{
    border: 1px dashed rgba(0,0,0,.14);
    border-radius: 14px;
    overflow:hidden;
    background: #fafafa;
    min-height: 120px;
    display:flex;
    align-items:center;
    justify-content:center;
}
.upload-empty{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:6px;
    color:#7b8794;
    font-size:.9rem;
}
.upload-empty i{ font-size:1.6rem; }
.upload-preview img{
    width:100%;
    height:140px;
    object-fit:cover;
    display:block;
}

/* ====== Checklist ====== */
.admin-checklist{
    list-style:none;
    padding:0;
    margin:0;
    display:flex;
    flex-direction:column;
    gap:10px;
}
.admin-checklist li{
    display:flex;
    gap:10px;
    align-items:center;
    padding: 10px 12px;
    border-radius: 14px;
    background: rgba(248,249,250,.9);
    border: 1px solid rgba(0,0,0,.06);
    font-weight: 600;
}
.admin-checklist li i{
    color: var(--secondary-color);
}

/* Responsivo */
@media (max-width: 576px){
    .admin-title{ font-size: 1.5rem; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.upload-input').forEach(input => {
        input.addEventListener('change', () => {
            const id = input.dataset.preview;
            const box = document.getElementById(id);
            if (!box) return;

            const file = input.files && input.files[0];
            if (!file) {
                box.innerHTML = `<div class="upload-empty"><i class="fa-regular fa-image"></i><span>Sem imagem</span></div>`;
                return;
            }

            const url = URL.createObjectURL(file);
            box.innerHTML = `<img src="${url}" alt="Preview">`;
        });
    });
});
</script>
@endpush
