@extends('layouts.admin')

@section('title', 'Admin - Editar Empresa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="section-title mb-0">Editar Empresa</h1>
    <a href="{{ route('admin.companies.index') }}" class="btn btn-light">
        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
    </a>
</div>

<div class="bg-white rounded-4 shadow-sm p-3 p-md-4">
    <form method="POST" action="{{ route('admin.companies.update', $company) }}" class="vstack gap-3" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
            <input type="text" name="name" class="form-control" value="{{ old('name', $company->name) }}" required>
        </div>

        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $company->email) }}" required>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Nova password (opcional)</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label">Confirmar nova password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>

        <div>
            <label class="form-label">Logo da empresa</label>

            @if(!empty($company->company_logo))
                <div class="d-flex align-items-center gap-3 mb-2">
                    <img src="{{ asset('storage/' . ltrim($company->company_logo, '/')) }}" alt="Logo" style="width:56px;height:56px;object-fit:cover" class="rounded-3 border">
                    <div>
                        <div class="small text-muted">Logo atual</div>
                        <label class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" name="remove_logo" value="1">
                            <span class="form-check-label">Remover logo</span>
                        </label>
                    </div>
                </div>
            @else
                <div class="small text-muted mb-2">Nenhum logo definido.</div>
            @endif

            <input type="file" name="company_logo" class="form-control" accept="image/png,image/jpeg,image/webp">
            <div class="form-text">Se escolher um novo ficheiro, ele substitui o logo atual. Formatos: JPG, PNG, WEBP. Máx: 2MB.</div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Guardar</button>
            <a href="{{ route('admin.companies.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
