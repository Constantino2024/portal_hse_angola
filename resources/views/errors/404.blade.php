@extends('layouts.app')

@section('title', 'Página não encontrada')
@section('meta_description', 'Página não encontrada - Portal HSE')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="mb-3" style="font-size: 54px; line-height: 1;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <h1 class="mb-2" style="font-weight: 800;">404</h1>
                    <h2 class="h4 mb-3">Página não encontrada</h2>
                    <p class="text-muted mb-4">
                        A página que você tentou acessar não existe, foi movida ou o link está incorreto.
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                        <a href="{{ url('/') }}" class="btn btn-accent">
                            <i class="fa-solid fa-house me-1"></i> Voltar ao Início
                        </a>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Voltar
                        </a>
                    </div>

                    <div class="mt-4 pt-3 border-top text-muted" style="font-size: 0.95rem;">
                        Se você acha que isso é um erro, contacte o suporte ou tente novamente mais tarde.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
