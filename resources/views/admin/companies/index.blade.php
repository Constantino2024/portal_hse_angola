@extends('layouts.admin')

@section('title', 'Admin - Empresas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="section-title mb-0">Empresas</h1>
    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> Nova Empresa
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-4 shadow-sm p-3 p-md-4">
    <form class="row g-2 mb-3" method="GET" action="{{ route('admin.companies.index') }}">
        <div class="col-12 col-md-8">
            <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Pesquisar por nome ou email">
        </div>
        <div class="col-12 col-md-4 d-flex gap-2">
            <button class="btn btn-outline-primary flex-grow-1"><i class="fa-solid fa-magnifying-glass me-1"></i> Buscar</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.companies.index') }}">Limpar</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Empresa</th>
                    <th>Email</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $c)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if(!empty($c->company_logo))
                                    <img src="{{ asset('storage/' . ltrim($c->company_logo, '/')) }}" alt="Logo" style="width:32px;height:32px;object-fit:cover" class="rounded-2 border">
                                @else
                                    <div style="width:32px;height:32px" class="rounded-2 border d-flex align-items-center justify-content-center bg-light fw-semibold text-muted">
                                        {{ strtoupper(mb_substr($c->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="fw-semibold">{{ $c->name }}</div>
                            </div>
                        </td>
                        <td>{{ $c->email }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.companies.edit', $c) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.companies.destroy', $c) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Remover esta empresa?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Nenhuma empresa encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $companies->links() }}
</div>
@endsection
