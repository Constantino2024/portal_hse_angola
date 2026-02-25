@extends('layouts.admin')

@section('title', 'Admin - Agenda HSE & ESG')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="section-title mb-0">Agenda HSE & ESG</h1>
        <div class="text-muted small">Gerir eventos, workshops, formações, datas internacionais e webinars.</div>
    </div>
    <a href="{{ route('admin.agenda.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus me-1"></i> Novo item
    </a>
</div>

<form class="row g-2 mb-3" method="GET" action="{{ route('admin.agenda.index') }}">
    <div class="col-12 col-md-5">
        <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Pesquisar por título, tipo ou local...">
    </div>
    <div class="col-12 col-md-3">
        <select name="type" class="form-select">
            <option value="">— Tipo —</option>
            @foreach($types as $k => $label)
                <option value="{{ $k }}" @selected(request('type')===$k)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-md-2">
        <button class="btn btn-outline-secondary w-100"><i class="fa-solid fa-filter me-1"></i> Filtrar</button>
    </div>
    <div class="col-12 col-md-2">
        <a class="btn btn-light w-100" href="{{ route('admin.agenda.index') }}"><i class="fa-solid fa-rotate-left me-1"></i> Limpar</a>
    </div>
</form>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive bg-white rounded-4 shadow-sm">
    <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
            <tr>
                <th>Título</th>
                <th>Tipo</th>
                <th>Data</th>
                <th>Local</th>
                <th class="text-center">Ativo</th>
                <th class="text-end">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $item->title }}</div>
                        <div class="text-muted small">/agenda-hse/{{ $item->slug }}</div>
                    </td>
                    <td><span class="badge text-bg-light border">{{ $types[$item->type] ?? $item->type }}</span></td>
                    <td class="text-muted">
                        {{ $item->starts_at?->format('d/m/Y H:i') }}
                    </td>
                    <td class="text-muted">
                        {{ $item->is_online ? 'Online' : ($item->location ?? '—') }}
                    </td>
                    <td class="text-center">
                        @if($item->is_active)
                            <span class="badge text-bg-success">Sim</span>
                        @else
                            <span class="badge text-bg-secondary">Não</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.agenda.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('admin.agenda.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Apagar este item?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">Nenhum item encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $items->links() }}
</div>
@endsection
