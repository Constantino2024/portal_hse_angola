@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h1 class="h4 mb-1">Painel do Administrador</h1>
            <div class="text-muted">Bem-vindo, {{ auth()->user()->name ?? 'Admin' }}.</div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted">Notícias</div>
                            <div class="h5 mb-0">Gerir posts</div>
                        </div>
                        <i class="fa-solid fa-newspaper fa-xl text-muted"></i>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-primary">Abrir</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted">Agenda</div>
                            <div class="h5 mb-0">Eventos & Workshops</div>
                        </div>
                        <i class="fa-solid fa-calendar-days fa-xl text-muted"></i>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.agenda.index') }}" class="btn btn-sm btn-primary">Abrir</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted">Empresas</div>
                            <div class="h5 mb-0">Cadastro & Gestão</div>
                        </div>
                        <i class="fa-solid fa-building fa-xl text-muted"></i>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.companies.index') }}" class="btn btn-sm btn-primary">Abrir</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
