@extends('layouts.admin')

@section('title', 'Admin - Novo item da Agenda')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="section-title mb-0">Novo item da Agenda</h1>
    <a href="{{ route('admin.agenda.index') }}" class="btn btn-light">
        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
    </a>
</div>

<div class="bg-white rounded-4 shadow-sm p-3 p-md-4">
    <form method="POST" action="{{ route('admin.agenda.store') }}" class="vstack gap-3" enctype="multipart/form-data">
        @csrf

        @if($errors->any())
            <div class="alert alert-danger">
                <div class="fw-bold mb-1">Corrige os erros:</div>
                <ul class="mb-0">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('admin.agenda._form', ['item' => null, 'types' => $types])

        <div class="d-flex gap-2">
            <button class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Guardar</button>
            <a href="{{ route('admin.agenda.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
