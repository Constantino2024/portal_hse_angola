@extends('layouts.admin')

@section('title', 'Gestão de Utilizadores')

@section('content')
<div class="container-fluid px-0">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="section-title">Gestão de Utilizadores</h1>
            <p class="text-muted">Gerencie todos os utilizadores da plataforma</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-2"></i>Novo Utilizador
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="stats-card p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small mb-1">Total Utilizadores</p>
                        <h3 class="fs-1 mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <span class="badge bg-light text-dark">
                        <i class="fa-solid fa-arrow-up me-1"></i> +12% este mês
                    </span>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stats-card p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small mb-1">Ativos</p>
                        <h3 class="fs-1 mb-0">{{ $stats['active'] }}</h3>
                    </div>
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar" style="width: {{ $stats['total'] > 0 ? ($stats['active']/$stats['total'])*100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stats-card p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small mb-1">Empresas</p>
                        <h3 class="fs-1 mb-0">{{ $stats['by_role']['empresa'] }}</h3>
                    </div>
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-building"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="stats-card p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="small mb-1">Profissionais</p>
                        <h3 class="fs-1 mb-0">{{ $stats['by_role']['profissional'] }}</h3>
                    </div>
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters and Search --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0">
                            <i class="fa-solid fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               name="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Pesquisar por nome, email ou telefone..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select">
                        <option value="">Todos os perfis</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="empresa" {{ request('role') == 'empresa' ? 'selected' : '' }}>Empresa</option>
                        <option value="profissional" {{ request('role') == 'profissional' ? 'selected' : '' }}>Profissional</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Todos os status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativos</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fa-solid fa-filter me-2"></i>Filtrar
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                        <i class="fa-solid fa-rotate me-2"></i>Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bulk Actions --}}
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">
                            Selecionar todos
                        </label>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" id="bulkActivate">
                            <i class="fa-solid fa-check-circle me-1"></i>Ativar
                        </button>
                        <button class="btn btn-sm btn-outline-warning" id="bulkDeactivate">
                            <i class="fa-solid fa-ban me-1"></i>Desativar
                        </button>
                        <button class="btn btn-sm btn-outline-danger" id="bulkDelete">
                            <i class="fa-solid fa-trash me-1"></i>Eliminar
                        </button>
                    </div>
                </div>
                <span class="text-muted">
                    Mostrando {{ $users->firstItem() }}-{{ $users->lastItem() }} de {{ $users->total() }} utilizadores
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th width="40"></th>
                        <th>Utilizador</th>
                        <th>Contacto</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th>Registo</th>
                        <th width="120">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}" 
                                       {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3" style="width: 40px; height: 40px;">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/'.$user->avatar ) }}" class="rounded-circle w-100 h-100 object-fit-cover" alt="">
                                    @else
                                        <div class="rounded-circle bg-primary bg-opacity-10 w-100 h-100 d-flex align-items-center justify-content-center">
                                            <span class="fw-bold text-primary">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $user->display_name }}</h6>
                                    <small class="text-muted">@<span class="text-primary">{{ $user->email }}</span></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->phone)
                                <div><i class="fa-solid fa-phone me-2 text-muted small"></i>{{ $user->phone }}</div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ 
                                $user->role === 'admin' ? 'danger' : 
                                ($user->role === 'empresa' ? 'primary' : 'success') 
                            }} bg-opacity-10 text-{{ 
                                $user->role === 'admin' ? 'danger' : 
                                ($user->role === 'empresa' ? 'primary' : 'success') 
                            }} px-3 py-2 rounded-pill">
                                <i class="fa-solid fa-{{ 
                                    $user->role === 'admin' ? 'crown' : 
                                    ($user->role === 'empresa' ? 'building' : 'user-tie') 
                                }} me-2"></i>
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            @if($user->id === auth()->id())
                                <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                    <i class="fa-solid fa-user me-2"></i>Sessão atual
                                </span>
                            @else
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $user->is_active ? 'success' : 'secondary' }} px-3 py-2 rounded-pill">
                                    <i class="fa-solid fa-circle me-2 fs-10"></i>
                                    {{ $user->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                class="btn btn-sm btn-light" 
                                title="Ver detalhes">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                class="btn btn-sm btn-light" 
                                title="Editar">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <button type="button" 
                                            class="btn btn-sm btn-light dropdown-toggle dropdown-toggle-split" 
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" 
                                                method="POST" 
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item {{ $user->is_active ? 'text-warning' : 'text-success' }}">
                                                    <i class="fa-solid fa-{{ $user->is_active ? 'ban' : 'check-circle' }} me-2"></i>
                                                    {{ $user->is_active ? 'Desativar' : 'Ativar' }}
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.users.destroy', $user) }}" 
                                                method="POST" 
                                                class="d-inline"
                                                onsubmit="return confirm('Tem certeza que deseja eliminar este utilizador? Esta ação é irreversível.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fa-solid fa-trash me-2"></i>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fa-solid fa-users-slash fa-3x mb-3"></i>
                                <h5>Nenhum utilizador encontrado</h5>
                                <p>Tente ajustar os filtros de pesquisa.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div></div>
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

{{-- Bulk Action Form --}}
<form id="bulkActionForm" action="{{ route('admin.users.bulk-action') }}" method="POST" style="display: none;">
    @csrf
</form>
@endsection

@push('styles')
<style>
.avatar-circle {
    background: linear-gradient(135deg, var(--primary-dark), #2c5282);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    overflow: hidden;
}
.fs-10 { font-size: 0.625rem; }
.stats-card .icon-wrapper {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(5px);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.user-checkbox:not(:disabled)');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Update select all state when individual checkboxes change
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (selectAll) {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                const someChecked = Array.from(checkboxes).some(cb => cb.checked);
                
                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked && !allChecked;
            }
        });
    });

    // Bulk actions
    const bulkActionForm = document.getElementById('bulkActionForm');
    
    if (!bulkActionForm) {
        console.error('Bulk action form not found');
        return;
    }

    function performBulkAction(action) {
        const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);
        
        if (selectedUsers.length === 0) {
            alert('Selecione pelo menos um utilizador.');
            return;
        }

        let confirmMessage = '';
        switch(action) {
            case 'activate':
                confirmMessage = 'Tem certeza que deseja ativar os utilizadores selecionados?';
                break;
            case 'deactivate':
                confirmMessage = 'Tem certeza que deseja desativar os utilizadores selecionados?';
                break;
            case 'delete':
                confirmMessage = 'Tem certeza que deseja eliminar os utilizadores selecionados? Esta ação é irreversível.';
                break;
        }

        if (confirm(confirmMessage)) {
            // Create form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('action', action);
            formData.append('users', JSON.stringify(selectedUsers));

            // Submit form via fetch
            fetch('{{ route("admin.users.bulk-action") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(() => {
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao processar ação.');
            });
        }
    }

    document.getElementById('bulkActivate')?.addEventListener('click', () => performBulkAction('activate'));
    document.getElementById('bulkDeactivate')?.addEventListener('click', () => performBulkAction('deactivate'));
    document.getElementById('bulkDelete')?.addEventListener('click', () => performBulkAction('delete'));
});
</script>
@endpush