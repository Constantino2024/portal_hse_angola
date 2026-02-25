{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Criar Novo Utilizador')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="section-title">Criar Novo Utilizador</h1>
            <p class="text-muted">Adicione um novo utilizador à plataforma</p>
        </div>
        <div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                <i class="fa-solid fa-arrow-left me-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informações do Utilizador</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required
                                       placeholder="Ex: João Silva">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required
                                       placeholder="exemplo@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="role" class="form-label">Perfil <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        required>
                                    <option value="">Selecione um perfil</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="empresa" {{ old('role') == 'empresa' ? 'selected' : '' }}>Empresa</option>
                                    <option value="profissional" {{ old('role') == 'profissional' ? 'selected' : '' }}>Profissional</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Palavra-passe <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="Mínimo 8 caracteres">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Mínimo 8 caracteres</small>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmar Palavra-passe <span class="text-danger">*</span></label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       placeholder="Repita a palavra-passe">
                            </div>

                            <div class="col-12">
                                <hr>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           role="switch" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Utilizador Ativo
                                    </label>
                                    <small class="text-muted d-block">Se ativado, o utilizador poderá aceder à plataforma imediatamente</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save me-2"></i>Criar Utilizador
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informações Importantes</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info bg-info bg-opacity-10 border-0">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        <strong>Perfil Admin:</strong>
                        <p class="mb-0 mt-2">Utilizadores com perfil Admin têm acesso total a todas as áreas administrativas da plataforma.</p>
                    </div>

                    <div class="alert alert-warning bg-warning bg-opacity-10 border-0 mt-3">
                        <i class="fa-solid fa-key me-2"></i>
                        <strong>Palavra-passe:</strong>
                        <p class="mb-0 mt-2">A palavra-passe deve ter pelo menos 8 caracteres e será armazenada de forma segura (encriptada).</p>
                    </div>

                    <div class="alert alert-success bg-success bg-opacity-10 border-0 mt-3">
                        <i class="fa-solid fa-check-circle me-2"></i>
                        <strong>Email de Boas-vindas:</strong>
                        <p class="mb-0 mt-2">O utilizador receberá um email com as instruções de acesso após a criação.</p>
                    </div>

                    <hr>

                    <h6 class="mb-2">Perfis Disponíveis:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="badge bg-danger me-2">Admin</span>
                            Acesso total à administração
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-primary me-2">Empresa</span>
                            Perfil para empresas e organizações
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-success me-2">Profissional</span>
                            Perfil para profissionais HSE
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.alert {
    border-left: 4px solid;
}
.alert-info {
    border-left-color: var(--primary-dark);
}
.alert-warning {
    border-left-color: var(--accent-orange);
}
.alert-success {
    border-left-color: #10b981;
}
</style>
@endpush