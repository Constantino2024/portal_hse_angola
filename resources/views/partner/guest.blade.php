@extends('layouts.partner')

@section('title', 'Área do Parceiro')

@section('content')
<div class="container-fluid">
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4 p-lg-5">
      <div class="d-flex align-items-start gap-3">
        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:56px;height:56px;">
          <i class="fa-solid fa-lock text-secondary"></i>
        </div>
        <div>
          <h1 class="h4 mb-1">Acesso restrito</h1>
          <p class="text-muted mb-3">
            Para usar a <strong>Área do Parceiro / Empresa</strong>, é necessário autenticar.
          </p>

          <div class="alert alert-warning rounded-4 mb-0">
            <div class="fw-semibold mb-1">Nota</div>
            <div class="text-muted">
              basta garantir que o utilizador tem <code>role = empresa</code>.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
