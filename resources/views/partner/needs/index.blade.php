@extends('layouts.partner')

@section('title', 'Necessidades · Banco de Talentos')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1"><i class="fa-solid fa-clipboard-list me-2"></i>Necessidades da Empresa</h1>
        <div class="text-muted">Publica as necessidades de recrutamento e deixa o sistema sugerir matches automaticamente.</div>
    </div>
    <a href="{{ route('partner.needs.create') }}" class="btn btn-accent mt-3 mt-md-0">
        <i class="fa-solid fa-plus me-2"></i>Nova necessidade
    </a>
</div>

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>Título</th>
                    <th>Nível</th>
                    <th>Área</th>
                    <th>Disponibilidade</th>
                    <th>Província</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($needs as $n)
                    <tr>
                        <td class="fw-semibold">{{ $n->title }}</td>
                        <td>{{ $levels[$n->level] ?? $n->level }}</td>
                        <td>{{ $areas[$n->area] ?? $n->area }}</td>
                        <td>{{ $availabilities[$n->availability] ?? $n->availability }}</td>
                        <td>{{ $n->province }}</td>
                        <td>
                            @if($n->is_active)
                                <span class="badge text-bg-success"><i class="fa-solid fa-check me-1"></i>Ativo</span>
                            @else
                                <span class="badge text-bg-secondary">Inativo</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('partner.needs.show', $n) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-wand-magic-sparkles"></i>
                            </a>
                            <a href="{{ route('partner.needs.edit', $n) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('partner.needs.destroy', $n) }}" method="POST" class="d-inline" onsubmit="return confirm('Remover esta necessidade?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Ainda não há necessidades. Cria a primeira para ver matches.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $needs->links() }}
        </div>
    </div>
</div>
@endsection
