{{-- Modal Técnicos de Segurança --}}
<div class="modal fade" id="safetyModal" tabindex="-1" aria-labelledby="safetyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="safetyModalLabel">
                    <i class="fas fa-hard-hat me-2"></i>Técnicos de Segurança Cadastrados
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Buscar técnico..." id="searchSafety">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" id="filterLocationSafety">
                            <option value="">Todas as localizações</option>
                            @foreach(\App\Models\HseTalentProfile::distinct('province')->whereNotNull('province')->pluck('province') as $province)
                                <option value="{{ $province }}">{{ $province }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select form-select-sm" id="filterExperienceSafety">
                            <option value="">Todos os níveis</option>
                            <option value="junior">Júnior</option>
                            <option value="pleno">Pleno</option>
                            <option value="senior">Sênior</option>
                        </select>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Contacto</th>
                                <th>Posição Atual</th>
                                <th>Localização</th>
                                <th>Experiência</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="safetyTableBody">
                            @forelse($safetyProfessionals as $professional)
                            <tr>
                                <td><strong>{{ $professional->full_name ?? 'Não informado' }}</strong></td>
                                <td class="text-nowrap">
                                    @if($professional->phone)
                                        {{ $professional->phone }}
                                    @elseif($professional->email)
                                        {{ $professional->email }}
                                    @else
                                        Não informado
                                    @endif
                                </td>
                                <td>{{ $professional->current_position ?? 'Não informado' }}</td>
                                <td>{{ $professional->province ?? 'Não informado' }}</td>
                                <td>
                                    @if($professional->years_experience)
                                        {{ $professional->years_experience }} anos
                                    @elseif($professional->level)
                                        {{ ucfirst($professional->level) }}
                                    @else
                                        Não informado
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-primary" onclick="viewProfessionalDetails('{{ $professional->id }}')" title="Ver detalhes">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($professional->email)
                                        <a href="mailto:{{ $professional->email }}" class="btn btn-outline-success" title="Enviar email">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users-slash fa-2x mb-3"></i>
                                        <p>Nenhum técnico de segurança cadastrado</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <p class="text-muted mb-0 small">
                        <i class="fas fa-info-circle me-1"></i>
                        Mostrando {{ $safetyProfessionals->count() }} de {{ $totalSafetyTechnicians }} técnicos de segurança
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Médicos do Trabalho --}}
<div class="modal fade" id="medicalModal" tabindex="-1" aria-labelledby="medicalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="medicalModalLabel">
                    <i class="fas fa-user-md me-2"></i>Médicos do Trabalho Cadastrados
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Contacto</th>
                                <th>Posição Atual</th>
                                <th>Localização</th>
                                <th>Experiência</th>
                            </tr>
                        </thead>
                        <tbody id="medicalTableBody">
                            @forelse($medicalProfessionals as $professional)
                            <tr>
                                <td><strong>{{ $professional->full_name ?? 'Não informado' }}</strong></td>
                                <td class="text-nowrap">
                                    @if($professional->phone)
                                        {{ $professional->phone }}
                                    @elseif($professional->email)
                                        {{ $professional->email }}
                                    @else
                                        Não informado
                                    @endif
                                </td>
                                <td>{{ $professional->current_position ?? 'Não informado' }}</td>
                                <td>{{ $professional->province ?? 'Não informado' }}</td>
                                <td>
                                    @if($professional->years_experience)
                                        {{ $professional->years_experience }} anos
                                    @else
                                        Não informado
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-user-md fa-2x mb-3"></i>
                                        <p>Nenhum médico do trabalho cadastrado</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <p class="text-muted mb-0 small">
                        <i class="fas fa-info-circle me-1"></i>
                        Mostrando {{ $medicalProfessionals->count() }} de {{ $totalOccupationalDoctors }} médicos do trabalho
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Psicólogos --}}
<div class="modal fade" id="psychologyModal" tabindex="-1" aria-labelledby="psychologyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #8b5cf6; color: white;">
                <h5 class="modal-title" id="psychologyModalLabel">
                    <i class="fas fa-brain me-2"></i>Psicólogos do Trabalho Cadastrados
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Contacto</th>
                                <th>Posição Atual</th>
                                <th>Localização</th>
                                <th>Experiência</th>
                            </tr>
                        </thead>
                        <tbody id="psychologyTableBody">
                            @forelse($psychologyProfessionals as $professional)
                            <tr>
                                <td><strong>{{ $professional->full_name ?? 'Não informado' }}</strong></td>
                                <td class="text-nowrap">
                                    @if($professional->phone)
                                        {{ $professional->phone }}
                                    @elseif($professional->email)
                                        {{ $professional->email }}
                                    @else
                                        Não informado
                                    @endif
                                </td>
                                <td>{{ $professional->current_position ?? 'Não informado' }}</td>
                                <td>{{ $professional->province ?? 'Não informado' }}</td>
                                <td>
                                    @if($professional->years_experience)
                                        {{ $professional->years_experience }} anos
                                    @else
                                        Não informado
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-brain fa-2x mb-3"></i>
                                        <p>Nenhum psicólogo cadastrado</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <p class="text-muted mb-0 small">
                        <i class="fas fa-info-circle me-1"></i>
                        Mostrando {{ $psychologyProfessionals->count() }} de {{ $totalPsychologists }} psicólogos
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ambientalistas --}}
<div class="modal fade" id="environmentalModal" tabindex="-1" aria-labelledby="environmentalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="environmentalModalLabel">
                    <i class="fas fa-leaf me-2"></i>Ambientalistas Cadastrados
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Contacto</th>
                                <th>Posição Atual</th>
                                <th>Localização</th>
                                <th>Experiência</th>
                            </tr>
                        </thead>
                        <tbody id="environmentalTableBody">
                            @forelse($environmentalProfessionals as $professional)
                            <tr>
                                <td><strong>{{ $professional->full_name ?? 'Não informado' }}</strong></td>
                                <td class="text-nowrap">
                                    @if($professional->phone)
                                        {{ $professional->phone }}
                                    @elseif($professional->email)
                                        {{ $professional->email }}
                                    @else
                                        Não informado
                                    @endif
                                </td>
                                <td>{{ $professional->current_position ?? 'Não informado' }}</td>
                                <td>{{ $professional->province ?? 'Não informado' }}</td>
                                <td>
                                    @if($professional->years_experience)
                                        {{ $professional->years_experience }} anos
                                    @else
                                        Não informado
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-leaf fa-2x mb-3"></i>
                                        <p>Nenhum ambientalista cadastrado</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <p class="text-muted mb-0 small">
                        <i class="fas fa-info-circle me-1"></i>
                        Mostrando {{ $environmentalProfessionals->count() }} de {{ $totalEnvironmentalists }} ambientalistas
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Higienistas --}}
<div class="modal fade" id="hygieneModal" tabindex="-1" aria-labelledby="hygieneModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="hygieneModalLabel">
                    <i class="fas fa-hand-sparkles me-2"></i>Higienistas Ocupacionais Cadastrados
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Contacto</th>
                                <th>Posição Atual</th>
                                <th>Localização</th>
                                <th>Experiência</th>
                            </tr>
                        </thead>
                        <tbody id="hygieneTableBody">
                            @forelse($hygieneProfessionals as $professional)
                            <tr>
                                <td><strong>{{ $professional->full_name ?? 'Não informado' }}</strong></td>
                                <td class="text-nowrap">
                                    @if($professional->phone)
                                        {{ $professional->phone }}
                                    @elseif($professional->email)
                                        {{ $professional->email }}
                                    @else
                                        Não informado
                                    @endif
                                </td>
                                <td>{{ $professional->current_position ?? 'Não informado' }}</td>
                                <td>{{ $professional->province ?? 'Não informado' }}</td>
                                <td>
                                    @if($professional->years_experience)
                                        {{ $professional->years_experience }} anos
                                    @else
                                        Não informado
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-hand-sparkles fa-2x mb-3"></i>
                                        <p>Nenhum higienista cadastrado</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <p class="text-muted mb-0 small">
                        <i class="fas fa-info-circle me-1"></i>
                        Mostrando {{ $hygieneProfessionals->count() }} de {{ $totalHygienists }} higienistas
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ergonomistas --}}
<div class="modal fade" id="ergonomicsModal" tabindex="-1" aria-labelledby="ergonomicsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="ergonomicsModalLabel">
                    <i class="fas fa-chair me-2"></i>Ergonomistas Cadastrados
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Contacto</th>
                                <th>Posição Atual</th>
                                <th>Localização</th>
                                <th>Formação</th>
                            </tr>
                        </thead>
                        <tbody id="ergonomicsTableBody">
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-chair fa-2x mb-3"></i>
                                        <p>Nenhum ergonomista cadastrado</p>
                                        <small>Esta área ainda não possui profissionais cadastrados</small>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>