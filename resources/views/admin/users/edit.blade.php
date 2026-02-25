@extends('layouts.admin')

@section('title', 'Editar Utilizador - ' . $user->display_name)

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="section-title">Editar Utilizador</h1>
            <p class="text-muted">Atualize as informações do utilizador</p>
        </div>
        <div>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info me-2">
                <i class="fa-solid fa-eye me-2"></i>Ver Perfil
            </a>
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
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nome Completo</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Telefone</label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="role" class="form-label">Perfil</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" 
                                        name="role" 
                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="empresa" {{ old('role', $user->role) == 'empresa' ? 'selected' : '' }}>Empresa</option>
                                    <option value="profissional" {{ old('role', $user->role) == 'profissional' ? 'selected' : '' }}>Profissional</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($user->id === auth()->id())
                                    <small class="text-muted">Não pode alterar o seu próprio perfil</small>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Nova Palavra-passe</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password">
                                <small class="text-muted">Deixe em branco para manter a atual</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmar Nova Palavra-passe</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
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
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                           {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Utilizador Ativo
                                    </label>
                                    @if($user->id === auth()->id())
                                        <br><small class="text-muted">Não pode alterar o status do seu próprio utilizador</small>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save me-2"></i>Guardar Alterações
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
                    <h5 class="mb-0">Informações Adicionais</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted d-block">ID do Utilizador</small>
                            <strong>#{{ $user->id }}</strong>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">Registado em</small>
                            <strong>{{ $user->created_at->format('d/m/Y H:i') }}</strong>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">Última atualização</small>
                            <strong>{{ $user->updated_at->format('d/m/Y H:i') }}</strong>
                        </li>
                        @if($user->email_verified_at)
                        <li class="mb-3">
                            <small class="text-muted d-block">Email verificado em</small>
                            <strong>{{ $user->email_verified_at->format('d/m/Y H:i') }}</strong>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection