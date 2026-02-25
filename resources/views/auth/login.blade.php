@extends('layouts.app')

@section('title', 'Entrar')

@section('content')
<div class="container py-5 mt-8">
  <div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="fa-solid fa-shield-heart text-primary"></i>
            <h1 class="h4 mb-0">Entrar no Portal</h1>
          </div>

          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <form method="POST" action="{{ route('login.attempt') }}" class="mt-3">
            @csrf

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Palavra-passe</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                <label class="form-check-label" for="remember">Lembrar-me</label>
              </div>
              <a href="{{ route('register') }}" class="text-decoration-none">Criar conta</a>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">
              <i class="fa-solid fa-right-to-bracket me-2"></i> Entrar
            </button>

            <div class="text-muted small mt-3">
              Ao entrar, serás redirecionado automaticamente conforme o teu perfil (Empresa, Profissional ou Admin).
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
