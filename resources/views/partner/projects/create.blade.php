@extends('layouts.partner')

@section('title', 'Novo Projecto · Parceiro')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="section-title mb-0">Novo projecto</h1>
    <div class="text-muted small">Cria um projecto para divulgar no portal.</div>
  </div>
  <a href="{{ route('partner.projects.index') }}" class="btn btn-outline-secondary">
    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
  </a>
</div>

<form method="POST" action="{{ route('partner.projects.store') }}" enctype="multipart/form-data">
  @csrf
  @include('partner.projects._form')
  <div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary">
      <i class="fa-solid fa-cloud-arrow-up me-1"></i> Publicar
    </button>
    <a href="{{ route('partner.projects.index') }}" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
@endsection
