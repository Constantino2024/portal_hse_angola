@extends('layouts.app')

@section('title', 'Criar conta')

@section('content')
<div class="container py-5 mt-8">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-8">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="fa-solid fa-user-plus text-primary"></i>
            <h1 class="h4 mb-0">Criar conta</h1>
          </div>
          <p class="text-muted mb-4">
            Escolhe o tipo de conta que queres criar no Portal HSE Angola.
          </p>

          <div class="row g-3">
            <div class="col-12 col-md-6">
              <a href="{{ route('register.professional') }}" class="text-decoration-none">
                <div class="border rounded-4 p-4 h-100 hover-shadow">
                  <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary-subtle rounded-3 p-3">
                      <i class="fa-solid fa-helmet-safety fa-lg text-primary"></i>
                    </div>
                    <div>
                      <div class="fw-semibold">Sou Profissional de HSE</div>
                      <div class="text-muted small">Criar perfil + anexar CV e aparecer em matches.</div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-md-6">
              <a href="{{ route('register.company') }}" class="text-decoration-none">
                <div class="border rounded-4 p-4 h-100 hover-shadow">
                  <div class="d-flex align-items-center gap-3">
                    <div class="bg-warning-subtle rounded-3 p-3">
                      <i class="fa-solid fa-building fa-lg text-warning"></i>
                    </div>
                    <div>
                      <div class="fw-semibold">Sou Empresa / Parceiro</div>
                      <div class="text-muted small">Publicar vagas, iniciativas ESG, projectos e necessidades.</div>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>

          <div class="mt-4">
            <span class="text-muted">Já tens conta?</span>
            <a href="{{ route('login') }}" class="text-decoration-none">Entrar</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.hover-shadow:hover{box-shadow:0 0.5rem 1.2rem rgba(0,0,0,.08);}
</style>
@endsection
