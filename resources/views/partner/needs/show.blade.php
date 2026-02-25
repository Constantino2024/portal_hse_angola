@extends('layouts.partner')

@section('title', 'Matches · Banco de Talentos')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1"><i class="fa-solid fa-wand-magic-sparkles me-2"></i>Matches para: {{ $need->title }}</h1>
        <div class="text-muted">
            {{ $levels[$need->level] ?? $need->level }} · {{ $areas[$need->area] ?? $need->area }} · {{ $availabilities[$need->availability] ?? $need->availability }} · {{ $need->province }}
        </div>
    </div>
    <a href="{{ route('partner.needs.index') }}" class="btn btn-outline-secondary mt-3 mt-md-0">
        <i class="fa-solid fa-arrow-left me-2"></i>Voltar
    </a>
</div>

@if(!$need->is_active)
    <div class="alert alert-warning">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        Esta necessidade está marcada como <strong>inativa</strong>. Ativa-a para que apareça nos processos internos de matching.
    </div>
@endif

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <h6 class="mb-3"><i class="fa-solid fa-clipboard-check me-2"></i>Detalhes</h6>
                <p class="mb-2"><strong>Título:</strong> {{ $need->title }}</p>
                <p class="mb-2"><strong>Nível:</strong> {{ $levels[$need->level] ?? $need->level }}</p>
                <p class="mb-2"><strong>Área:</strong> {{ $areas[$need->area] ?? $need->area }}</p>
                <p class="mb-2"><strong>Disponibilidade:</strong> {{ $availabilities[$need->availability] ?? $need->availability }}</p>
                <p class="mb-2"><strong>Província:</strong> {{ $need->province }}</p>
                @if($need->description)
                    <hr>
                    <div class="text-muted" style="white-space: pre-wrap">{{ $need->description }}</div>
                @endif
                <hr>
                <a href="{{ route('partner.needs.edit', $need) }}" class="btn btn-outline-secondary w-100">
                    <i class="fa-solid fa-pen me-2"></i>Editar necessidade
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <h6 class="mb-3"><i class="fa-solid fa-users me-2"></i>Talentos compatíveis</h6>

                @if($matches->count() === 0)
                    <div class="text-muted">Ainda não há matches exactos para estes filtros. Podes ajustar a necessidade (ex.: província) ou usar a pesquisa de talentos.</div>
                    <div class="mt-3">
                        <a href="{{ route('partner.talents.index') }}" class="btn btn-outline-primary">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>Pesquisar Talentos
                        </a>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach($matches as $p)
                            <div class="col-md-6">
                                <div class="border rounded-4 p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">{{ $p->user->name ?? 'Profissional' }}</div>
                                            <div class="text-muted small">
                                                {{ $levels[$p->level] ?? $p->level }} · {{ $areas[$p->area] ?? $p->area }} · {{ $availabilities[$p->availability] ?? $p->availability }}
                                            </div>
                                        </div>
                                        <span class="badge text-bg-light border">{{ $p->province }}</span>
                                    </div>

                                    @if($p->headline)
                                        <div class="mt-2">{{ $p->headline }}</div>
                                    @endif

                                    @if($p->bio)
                                        <div class="text-muted small mt-2" style="white-space: pre-wrap">{{ \Illuminate\Support\Str::limit($p->bio, 160) }}</div>
                                    @endif

                                    <div class="mt-3 d-flex gap-2">
                                        @if($p->cv_path)
                                            <a class="btn btn-sm btn-outline-secondary" href="{{ asset('storage/'.$p->cv_path) }}" target="_blank">
                                                <i class="fa-solid fa-file-arrow-down me-1"></i> CV
                                            </a>
                                        @endif
                                        <span class="btn btn-sm btn-outline-info disabled" aria-disabled="true">
                                            <i class="fa-solid fa-bolt me-1"></i> Match
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        {{ $matches->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection