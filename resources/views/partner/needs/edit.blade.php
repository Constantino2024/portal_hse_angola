@extends('layouts.partner')

@section('title', 'Editar necessidade · Banco de Talentos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="fa-solid fa-pen me-2"></i>Editar necessidade</h1>
    <a href="{{ route('partner.needs.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Voltar
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('partner.needs.update', $need) }}">
            @csrf
            @method('PUT')
            @include('partner.needs._form')

            <div class="mt-4 d-flex justify-content-end">
                <button class="btn btn-accent" type="submit"><i class="fa-solid fa-floppy-disk me-2"></i>Salvar</button>
            </div>
        </form>
    </div>
</div>
@endsection