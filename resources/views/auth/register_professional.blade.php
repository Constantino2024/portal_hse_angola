@extends('layouts.app')

@section('title', 'Registo - Profissional')

@section('content')
<div class="container py-5 mt-8">
  <div class="row justify-content-center">
    <div class="col-12 col-md-7 col-lg-6">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="fa-solid fa-helmet-safety text-primary"></i>
            <h1 class="h4 mb-0">Registo (Profissional de HSE)</h1>
          </div>

          <form method="POST" action="{{ route('register.professional.store') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label">Nome completo</label>
              <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Palavra-passe</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
              @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
              <div class="form-text">Mínimo 8 caracteres.</div>
            </div>

            <div class="mb-3">
              <label class="form-label">Confirmar palavra-passe</label>
              <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">
              <i class="fa-solid fa-user-check me-2"></i> Criar conta
            </button>

            <div class="text-muted small mt-3">
              Após o registo, serás redirecionado para completar o teu perfil do Banco de Talentos.
            </div>

            <div class="mt-3">
              <a href="{{ route('register') }}" class="text-decoration-none">&larr; Voltar</a>
              <span class="text-muted mx-2">|</span>
              <a href="{{ route('login') }}" class="text-decoration-none">Já tenho conta</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
