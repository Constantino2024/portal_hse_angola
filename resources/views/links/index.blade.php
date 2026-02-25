@extends('layouts.app')

@section('title', 'Links Úteis')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h1 class="mb-1"><i class="fa-solid fa-link text-primary me-2"></i>Links Úteis</h1>
            <p class="text-muted mb-0">Acesso rápido a instituições, normas e plataformas relevantes para HSE & ESG.</p>
        </div>
        <a href="{{ route('chatbot.index') }}" class="btn btn-outline-primary">
            <i class="fa-solid fa-robot me-2"></i>Chatbot HSE
        </a>
    </div>

    @php
        $titles = [
            'principais' => 'Instituições & Referências',
            'certificacao' => 'Certificações & Formação',
            'instituicoes' => 'Entidades Nacionais',
            'plataformas' => 'Plataformas do Ecossistema',
        ];
    @endphp

    <div class="row g-4">
        @foreach($sections as $key => $items)
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3">
                            <i class="fa-solid fa-folder-open me-2 text-primary"></i>
                            {{ $titles[$key] ?? \Illuminate\Support\Str::title(str_replace('_',' ', $key)) }}
                        </h3>

                        <div class="row g-3">
                            @foreach($items as $link)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <a class="useful-link-card" href="{{ $link['url'] }}" target="_blank" rel="noopener">
                                        <div class="d-flex gap-3">
                                            <div class="useful-link-icon">
                                                <i class="{{ $link['icon'] ?? 'fa-solid fa-arrow-up-right-from-square' }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="useful-link-title">{{ $link['label'] }}</div>
                                                @if(!empty($link['desc']))
                                                    <div class="useful-link-desc">{{ $link['desc'] }}</div>
                                                @endif
                                            </div>
                                            <div class="useful-link-out">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        @if($key === 'plataformas')
                            <div class="alert alert-info mt-4 mb-0 rounded-4">
                                <i class="fa-solid fa-circle-info me-2"></i>
                                Alguns links do ecossistema podem ainda estar em fase de configuração. Se um link estiver “em breve”, avisa-me que eu ajusto para o domínio correcto.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
