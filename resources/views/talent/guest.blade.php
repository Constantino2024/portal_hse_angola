@extends('layouts.app')

@section('title', 'Acesso restrito')

@section('content')
<div class="container py-5 mt-8">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="alert alert-warning shadow-sm rounded-4">
                <h4 class="mb-2"><i class="fa-solid fa-lock me-2"></i>Precisas estar autenticado</h4>
                <p class="mb-0 text-muted">Para criar/atualizar o teu perfil e anexar o CV, o sistema precisa que estejas autenticado. Se ainda não tens conta, cria uma conta e depois volta aqui.</p>
            </div>
        </div>
    </div>
</div>
@endsection
