@extends('layouts.partner')

@section('title', 'Editar Projecto · Parceiro')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
  <div>
    <h1 class="section-title mb-0">Editar projecto</h1>
    <div class="text-muted small">Atualiza o projecto e guarda.</div>
  </div>
  <a href="{{ route('partner.projects.index') }}" class="btn btn-outline-secondary">
    <i class="fa-solid fa-arrow-left me-1"></i> Voltar
  </a>
</div>

<form method="POST" action="{{ route('partner.projects.update', $project) }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')
  @include('partner.projects._form')
  <div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary">
      <i class="fa-solid fa-floppy-disk me-1"></i> Guardar
    </button>
    <a href="{{ route('partner.projects.index') }}" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
@endsection
