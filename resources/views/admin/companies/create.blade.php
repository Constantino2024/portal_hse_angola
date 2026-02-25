@extends('layouts.admin')

@section('title', 'Admin - Nova Empresa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="section-title mb-0">Nova Empresa</h1>
    <a href="{{ route('admin.companies.index') }}" class="btn btn-light">
        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
    </a>
</div>

<div class="bg-white rounded-4 shadow-sm p-3 p-md-4">
    <form method="POST" action="{{ route('admin.companies.store') }}" class="vstack gap-3" enctype="multipart/form-data">
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

        <div>
            <label class="form-label">Nome da empresa</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Confirmar password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <div>
            <label class="form-label">Logo da empresa (opcional)</label>
            <input type="file" name="company_logo" class="form-control" accept="image/png,image/jpeg,image/webp">
            <div class="form-text">Formatos: JPG, PNG, WEBP. Máx: 2MB.</div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Criar</button>
            <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
