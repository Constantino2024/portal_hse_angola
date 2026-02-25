@extends('layouts.app')

@section('title', 'Erro interno')
@section('meta_description', 'Erro interno - Portal HSE')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="mb-3" style="font-size: 54px; line-height: 1;">
                        <i class="fa-solid fa-bug"></i>
                    </div>

                    <h1 class="mb-2" style="font-weight: 800;">500</h1>
                    <h2 class="h4 mb-3">Ocorreu um erro no servidor</h2>
                    <p class="text-muted mb-4">
                        Algo deu errado ao processar a sua solicitação. A nossa equipa já foi notificada e estamos a resolver.
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                        <a href="{{ url('/') }}" class="btn btn-accent">
                            <i class="fa-solid fa-house me-1"></i> Ir para o Início
                        </a>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-rotate-right me-1"></i> Tentar novamente
                        </a>
                    </div>

                    <div class="mt-4 pt-3 border-top text-muted" style="font-size: 0.95rem;">
                        Dica: se o problema persistir, faça logout e login novamente, ou tente em outro navegador.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
