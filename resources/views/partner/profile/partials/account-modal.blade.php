<!-- Modal Configurações da Conta -->
<div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold" id="accountModalLabel">
                    <i class="fa-solid fa-user-gear me-2 text-warning"></i>
                    Configurações da Conta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('partner.profile.account.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom">Dados do Usuário</h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome Completo</label>
                            <input type="text" name="name" class="form-control form-control-lg rounded-3 border-2" 
                                   value="{{ old('name', $user->name) }}" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg rounded-3 border-2" 
                                   value="{{ old('email', $user->email) }}" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Telefone</label>
                            <input type="text" name="phone" class="form-control form-control-lg rounded-3 border-2" 
                                   value="{{ old('phone', $user->company->phone) }}">
                        </div>
                        
                        <div class="col-12">
                            <hr class="my-3">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom">Alterar Senha</h6>
                            <p class="small text-muted mb-3">
                                <i class="fa-regular fa-circle-info me-1"></i>
                                Deixe em branco se não quiser alterar a senha.
                            </p>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Senha Atual</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-2">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input type="password" name="current_password" class="form-control form-control-lg border-2">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nova Senha</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-2">
                                    <i class="fa-solid fa-key"></i>
                                </span>
                                <input type="password" name="new_password" class="form-control form-control-lg border-2">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Confirmar Nova Senha</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-2">
                                    <i class="fa-solid fa-check-double"></i>
                                </span>
                                <input type="password" name="new_password_confirmation" class="form-control form-control-lg border-2">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 bg-light rounded-bottom-4 p-3">
                    <button type="button" class="btn btn-light btn-lg rounded-3 px-4" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg rounded-3 px-5" style="background: linear-gradient(135deg, var(--accent-orange), #e67e22); border: none;">
                        <i class="fa-solid fa-save me-2"></i>
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>