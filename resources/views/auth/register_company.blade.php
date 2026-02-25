@extends('layouts.app')

@section('title', 'Registro de Empresa - Portal HSE')

@section('content')
<div class="container py-3 py-md-4 py-lg-5 mt-8">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-7">
            <div class="card border-0 shadow-lg rounded-3 rounded-md-4">
                <div class="card-header bg-primary text-white py-3 py-md-4 rounded-top-3 rounded-md-top-4">
                    <div class="d-flex align-items-center">
                        <div class="me-2 me-md-3">
                            <i class="fas fa-building fa-2x fa-md-3x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h1 class="h4 h-md-3 mb-1">Registro de Empresa</h1>
                            <p class="mb-0 opacity-75 small">Cadastre sua empresa no Portal HSE</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-3 p-md-4 p-lg-5">
                    @if(session('error'))
                        <div class="alert alert-danger mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Ao registrar sua empresa, você terá acesso para publicar vagas, acessar o banco de talentos e divulgar iniciativas.
                    </div>
                    
                    <form method="POST" action="{{ route('register.company.store') }}" enctype="multipart/form-data" id="companyRegisterForm">
                        @csrf

                        {{-- Dados do Responsável --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-2 text-primary"></i> Dados do Responsável
                            </h5>
                            
                            <div class="row g-2 g-md-3">
                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">E-mail Empresarial *</label>
                                    <input type="email" 
                                           class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required>
                                    <div class="form-text">Será utilizado para login e comunicação</div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Informações da Empresa --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-building me-2 text-primary"></i> Informações da Empresa
                            </h5>
                            
                            <div class="row g-2 g-md-3">
                                <div class="col-12">
                                    <label for="company_name" class="form-label">Nome da Empresa *</label>
                                    <input type="text" 
                                           class="form-control form-control-lg @error('company_name') is-invalid @enderror" 
                                           id="company_name" 
                                           name="company_name" 
                                           value="{{ old('company_name') }}" 
                                           required>
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label for="nif" class="form-label">NIF *</label>
                                    <input type="text" 
                                           class="form-control form-control-lg @error('nif') is-invalid @enderror" 
                                           id="nif" 
                                           name="nif" 
                                           value="{{ old('nif') }}" 
                                           required
                                           maxlength="25"
                                           placeholder="Número de Identificação Fiscal">
                                    <div class="form-text">Número fiscal da empresa</div>
                                    @error('nif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="trading_name" class="form-label">Nome Comercial</label>
                                    <input type="text" 
                                          class="form-control form-control-lg @error('trading_name') is-invalid @enderror" 
                                          id="trading_name" 
                                          name="trading_name" 
                                          value="{{ old('trading_name') }}"
                                          placeholder="(Opcional)">
                                    <div class="form-text">Nome pelo qual a empresa é conhecida</div>
                                    @error('trading_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="phone" class="form-label">Telefone *</label>
                                    <input type="tel" 
                                           class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           required
                                           placeholder="+244 XXX XXX XXX">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="sector" class="form-label">Setor de Atuação *</label>
                                    <select class="form-select form-select-lg @error('sector') is-invalid @enderror" 
                                            id="sector" 
                                            name="sector" 
                                            required>
                                        <option value="">Selecione o setor...</option>
                                        <option value="oil_gas" {{ old('sector') == 'oil_gas' ? 'selected' : '' }}>Óleo & Gás</option>
                                        <option value="mining" {{ old('sector') == 'mining' ? 'selected' : '' }}>Mineração</option>
                                        <option value="construction" {{ old('sector') == 'construction' ? 'selected' : '' }}>Construção Civil</option>
                                        <option value="industry" {{ old('sector') == 'industry' ? 'selected' : '' }}>Indústria</option>
                                        <option value="services" {{ old('sector') == 'services' ? 'selected' : '' }}>Serviços</option>
                                        <option value="consulting" {{ old('sector') == 'consulting' ? 'selected' : '' }}>Consultoria</option>
                                        <option value="energy" {{ old('sector') == 'energy' ? 'selected' : '' }}>Energia</option>
                                        <option value="transport" {{ old('sector') == 'transport' ? 'selected' : '' }}>Transporte</option>
                                        <option value="logistics" {{ old('sector') == 'logistics' ? 'selected' : '' }}>Logística</option>
                                        <option value="health" {{ old('sector') == 'health' ? 'selected' : '' }}>Saúde</option>
                                        <option value="education" {{ old('sector') == 'education' ? 'selected' : '' }}>Educação</option>
                                        <option value="other" {{ old('sector') == 'other' ? 'selected' : '' }}>Outro</option>
                                    </select>
                                    @error('sector')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="company_size" class="form-label">Porte da Empresa</label>
                                    <select class="form-select form-select-lg @error('company_size') is-invalid @enderror" 
                                            id="company_size" 
                                            name="company_size">
                                        <option value="">Selecione...</option>
                                        <option value="micro" {{ old('company_size') == 'micro' ? 'selected' : '' }}>Micro (1-10)</option>
                                        <option value="small" {{ old('company_size') == 'small' ? 'selected' : '' }}>Pequena (11-50)</option>
                                        <option value="medium" {{ old('company_size') == 'medium' ? 'selected' : '' }}>Média (51-200)</option>
                                        <option value="large" {{ old('company_size') == 'large' ? 'selected' : '' }}>Grande (201-500)</option>
                                        <option value="enterprise" {{ old('company_size') == 'enterprise' ? 'selected' : '' }}>Empresarial (501+)</option>
                                    </select>
                                    @error('company_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="foundation_year" class="form-label">Ano de Fundação</label>
                                    <input type="number" 
                                          class="form-control form-control-lg @error('foundation_year') is-invalid @enderror" 
                                          id="foundation_year" 
                                          name="foundation_year" 
                                          value="{{ old('foundation_year') }}"
                                          min="1800"
                                          max="{{ date('Y') }}"
                                          placeholder="Ex: 2010">
                                    @error('foundation_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="city" class="form-label">Cidade *</label>
                                    <input type="text" 
                                          class="form-control form-control-lg @error('city') is-invalid @enderror" 
                                          id="city" 
                                          name="city" 
                                          value="{{ old('city') }}" 
                                          required
                                          placeholder="Ex: Luanda">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="province" class="form-label">Província *</label>
                                    <select class="form-select form-select-lg @error('province') is-invalid @enderror" 
                                            id="province" 
                                            name="province" 
                                            required>
                                        <option value="">Selecione...</option>
                                        <option value="Luanda" {{ old('province') == 'Luanda' ? 'selected' : '' }}>Luanda</option>
                                        <option value="Benguela" {{ old('province') == 'Benguela' ? 'selected' : '' }}>Benguela</option>
                                        <option value="Huíla" {{ old('province') == 'Huíla' ? 'selected' : '' }}>Huíla</option>
                                        <option value="Cabinda" {{ old('province') == 'Cabinda' ? 'selected' : '' }}>Cabinda</option>
                                        <option value="Huambo" {{ old('province') == 'Huambo' ? 'selected' : '' }}>Huambo</option>
                                        <option value="Namibe" {{ old('province') == 'Namibe' ? 'selected' : '' }}>Namibe</option>
                                        <option value="Malanje" {{ old('province') == 'Malanje' ? 'selected' : '' }}>Malanje</option>
                                        <option value="Uíge" {{ old('province') == 'Uíge' ? 'selected' : '' }}>Uíge</option>
                                        <option value="Zaire" {{ old('province') == 'Zaire' ? 'selected' : '' }}>Zaire</option>
                                        <option value="Lunda Norte" {{ old('province') == 'Lunda Norte' ? 'selected' : '' }}>Lunda Norte</option>
                                        <option value="Lunda Sul" {{ old('province') == 'Lunda Sul' ? 'selected' : '' }}>Lunda Sul</option>
                                        <option value="Bié" {{ old('province') == 'Bié' ? 'selected' : '' }}>Bié</option>
                                        <option value="Moxico" {{ old('province') == 'Moxico' ? 'selected' : '' }}>Moxico</option>
                                        <option value="Cuando Cubango" {{ old('province') == 'Cuando Cubango' ? 'selected' : '' }}>Cuando Cubango</option>
                                        <option value="Cunene" {{ old('province') == 'Cunene' ? 'selected' : '' }}>Cunene</option>
                                    </select>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="address" class="form-label">Endereço Completo *</label>
                                    <input type="text" 
                                           class="form-control form-control-lg @error('address') is-invalid @enderror" 
                                           id="address" 
                                           name="address" 
                                           value="{{ old('address') }}" 
                                           required
                                           placeholder="Rua, número, bairro">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label">Descrição da Empresa</label>
                                    <textarea class="form-control form-control-lg @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3"
                                              placeholder="Descreva brevemente as atividades da sua empresa...">{{ old('description') }}</textarea>
                                    <div class="form-text">Opcional</div>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label for="website" class="form-label">Website</label>
                                    <input type="url" 
                                           class="form-control form-control-lg @error('website') is-invalid @enderror" 
                                           id="website" 
                                           name="website" 
                                           value="{{ old('website') }}"
                                           placeholder="https://exemplo.com">
                                    <div class="form-text">Opcional</div>
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 col-md-6">
                                    <label for="logo" class="form-label">Logo da Empresa</label>
                                    <input type="file" 
                                           class="form-control form-control-lg @error('logo') is-invalid @enderror" 
                                           id="logo" 
                                           name="logo"
                                           accept="image/*">
                                    <div class="form-text">JPG, PNG. Máx: 2MB (Opcional)</div>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="logoPreview" class="mt-2" style="display: none;">
                                        <img id="previewImage" class="img-thumbnail w-100" style="max-height: 150px; object-fit: contain;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Senha de Acesso --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-lock me-2 text-primary"></i> Senha de Acesso
                            </h5>
                            
                            <div class="row g-2 g-md-3">
                                <div class="col-12 col-md-6 position-relative">
                                    <label for="password" class="form-label">Senha *</label>
                                    <input type="password" 
                                           class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    <div class="form-text">Mínimo 8 caracteres</div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="password-strength mt-2">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" id="passwordStrength" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="text-muted d-block mt-1" id="passwordHint"></small>
                                    </div>
                                </div>
                                
                                <div class="col-12 col-md-6 position-relative">
                                    <label for="password_confirmation" class="form-label">Confirmar Senha *</label>
                                    <input type="password" 
                                           class="form-control form-control-lg" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           required>
                                    <div id="passwordMatch" class="form-feedback mt-1"></div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Termos e Condições --}}
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror" 
                                       type="checkbox" 
                                       id="terms" 
                                       name="terms" 
                                       value="1"
                                       {{ old('terms') ? 'checked' : '' }}
                                       required>
                                <label class="form-check-label" for="terms">
                                    Eu concordo com os 
                                    <a href="#" target="_blank" class="text-decoration-none">Termos de Uso</a> 
                                    e 
                                    <a href="#" target="_blank" class="text-decoration-none">Política de Privacidade</a> *
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg me-md-2">
                                <i class="fas fa-times me-2"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4 px-md-5" id="submitBtn">
                                <i class="fas fa-building me-2"></i> Registrar Empresa
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="mb-2">
                            Já tem uma conta? 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                                <i class="fas fa-sign-in-alt me-1"></i> Faça Login
                            </a>
                        </p>
                        <p class="mb-0">
                            É um profissional? 
                            <a href="{{ route('register.professional') }}" class="text-decoration-none fw-semibold">
                                <i class="fas fa-user-tie me-1"></i> Registrar como Profissional
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Estilos responsivos gerais */
@media (max-width: 768px) {
    .card-header .fa-building {
        font-size: 1.75rem !important;
    }
    
    .form-control-lg,
    .form-select-lg {
        font-size: 1rem;
        padding: 0.75rem 1rem;
    }
}

/* Ajustes para telas muito pequenas */
@media (max-width: 576px) {
    .container {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .card-body {
        padding: 1.25rem !important;
    }
    
    .btn-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
    
    .d-grid {
        gap: 0.75rem !important;
    }
}

/* Ajustes para telas grandes */
@media (max-width: 1200px) {
    .container {
        max-width: 1140px;
    }
}

/* Estilos para validação */
.form-feedback {
    font-size: 0.875rem;
    min-height: 20px;
}

.password-strength .progress {
    margin-top: 0.25rem;
}

.progress-bar {
    transition: width 0.5s ease;
}

/* Preview de imagem responsivo */
#previewImage {
    max-width: 100%;
    height: auto;
}

/* Tooltip de validação de email */
.email-validation-details {
    font-size: 0.85rem;
}

.email-validation-details .badge {
    font-size: 0.7rem;
    padding: 0.35rem 0.5rem;
}

/* Botões de mostrar/ocultar senha */
.password-toggle-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: #6c757d;
    padding: 0.5rem;
    z-index: 5;
}

/* Animações */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.img-thumbnail {
    transition: all 0.3s ease;
}

/* Efeito de foco suave */
.form-control:focus,
.form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    border-color: #86b7fe;
}

/* Estilo para checkboxes */
.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

/* Ajustes de espaçamento em telas médias */
@media (min-width: 768px) and (max-width: 991px) {
    .row.g-2.g-md-3 > [class*="col-"] {
        margin-bottom: 0.75rem;
    }
}

/* Garantir que os selects tenham a mesma altura */
.form-select-lg {
    height: calc(3.5rem + 2px);
}

/* Ajuste para campos com ícones de validação */
.form-control.is-valid,
.form-control.is-invalid {
    padding-right: calc(1.5em + 0.75rem);
    background-position: right calc(0.375em + 0.1875rem) center;
}

/* Responsividade do botão de envio */
@media (max-width: 767px) {
    .d-grid.d-md-flex .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .d-grid.d-md-flex {
        flex-direction: column;
    }
}

/* Melhorar legibilidade em telas pequenas */
@media (max-width: 576px) {
    h5 {
        font-size: 1.1rem;
    }
    
    .form-label {
        font-weight: 500;
    }
    
    .alert {
        font-size: 0.9rem;
    }
}

/* Otimização para tablets */
@media (min-width: 768px) and (max-width: 1024px) {
    .container {
        padding-left: 20px;
        padding-right: 20px;
    }
    
    .col-md-6 {
        padding-left: 10px;
        padding-right: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos do formulário
    const form = document.getElementById('companyRegisterForm');
    const emailInput = document.getElementById('email');
    const nifInput = document.getElementById('nif');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const logoInput = document.getElementById('logo');
    const submitBtn = document.getElementById('submitBtn');
    const emailFeedback = document.getElementById('emailFeedback');
    const nifFeedback = document.getElementById('nifFeedback');
    const passwordMatchFeedback = document.getElementById('passwordMatch');
    
    // Timeouts para debounce
    let emailValidationTimeout = null;
    let nifValidationTimeout = null;
    let passwordValidationTimeout = null;

    // ========== VALIDAÇÃO DE EMAIL COM API ==========
    
    // Validação em tempo real com debounce


    // ========== VALIDAÇÃO DE NIF ==========
    
    nifInput.addEventListener('input', function() {
        clearTimeout(nifValidationTimeout);
        
        nifValidationTimeout = setTimeout(() => {
            const nif = this.value.trim();
            if (nif.length >= 18) { // NIF mínimo de 9 caracteres
                validateNif(nif);
            }
        }, 1000);
    });

    nifInput.addEventListener('blur', function() {
        const nif = this.value.trim();
        if (nif.length > 0) {
            validateNif(nif);
        }
    });

    function validateNif(nif) {
        // Resetar visual
        nifInput.classList.remove('is-valid', 'is-invalid');
        updateFeedback(nifFeedback, '<i class="fas fa-spinner fa-spin"></i> Verificando NIF...', 'info');
        
        fetch('{{ route("register.check.nif") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ nif: nif })
        })
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                updateFeedback(nifFeedback, 
                    '<i class="fas fa-check-circle me-1"></i> NIF disponível', 
                    'success'
                );
                nifInput.classList.remove('is-invalid');
                nifInput.classList.add('is-valid');
            } else {
                updateFeedback(nifFeedback, 
                    '<i class="fas fa-times-circle me-1"></i> NIF já registrado', 
                    'danger'
                );
                nifInput.classList.remove('is-valid');
                nifInput.classList.add('is-invalid');
            }
        })
        .catch(error => {
            console.error('Erro na validação do NIF:', error);
            updateFeedback(nifFeedback, 
                '<i class="fas fa-exclamation-circle me-1"></i> Erro na verificação', 
                'warning'
            );
        });
    }

    // ========== VALIDAÇÃO DE SENHA ==========
    
    // Força da senha
    passwordInput.addEventListener('input', function() {
        clearTimeout(passwordValidationTimeout);
        
        passwordValidationTimeout = setTimeout(() => {
            validatePasswordStrength(this.value);
            checkPasswordMatch();
        }, 300);
    });

    // Correspondência de senhas
    confirmPasswordInput.addEventListener('input', function() {
        clearTimeout(passwordValidationTimeout);
        passwordValidationTimeout = setTimeout(checkPasswordMatch, 300);
    });

    function validatePasswordStrength(password) {
        const strengthBar = document.getElementById('passwordStrength');
        const hint = document.getElementById('passwordHint');
        
        let strength = 0;
        let hintText = '';
        
        // Critérios de força
        if (password.length >= 8) strength += 25;
        if (password.length >= 12) strength += 10;
        
        if (/[A-Z]/.test(password)) strength += 20;
        if (/[a-z]/.test(password)) strength += 10;
        if (/[0-9]/.test(password)) strength += 20;
        if (/[^A-Za-z0-9]/.test(password)) strength += 25;
        
        // Limitar a 100%
        strength = Math.min(strength, 100);
        
        // Atualizar barra de progresso
        strengthBar.style.width = strength + '%';
        strengthBar.style.transition = 'width 0.5s ease';
        
        // Determinar cor e mensagem
        if (strength < 30) {
            strengthBar.className = 'progress-bar bg-danger';
            hintText = 'Senha muito fraca';
            passwordInput.classList.remove('is-valid');
        } else if (strength < 50) {
            strengthBar.className = 'progress-bar bg-warning';
            hintText = 'Senha fraca';
            passwordInput.classList.remove('is-valid');
        } else if (strength < 75) {
            strengthBar.className = 'progress-bar bg-info';
            hintText = 'Senha razoável';
            passwordInput.classList.add('is-valid');
            passwordInput.classList.remove('is-invalid');
        } else {
            strengthBar.className = 'progress-bar bg-success';
            hintText = 'Senha forte!';
            passwordInput.classList.add('is-valid');
            passwordInput.classList.remove('is-invalid');
        }
        
        hint.textContent = hintText;
    }

    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword.length === 0) {
            passwordMatchFeedback.innerHTML = '';
            confirmPasswordInput.classList.remove('is-valid', 'is-invalid');
            return;
        }
        
        if (password === confirmPassword && password.length > 0) {
            passwordMatchFeedback.innerHTML = 
                '<span class="text-success"><i class="fas fa-check-circle me-1"></i> As senhas coincidem</span>';
            confirmPasswordInput.classList.remove('is-invalid');
            confirmPasswordInput.classList.add('is-valid');
        } else {
            passwordMatchFeedback.innerHTML = 
                '<span class="text-danger"><i class="fas fa-times-circle me-1"></i> As senhas não coincidem</span>';
            confirmPasswordInput.classList.remove('is-valid');
            confirmPasswordInput.classList.add('is-invalid');
        }
    }

    // ========== PREVIEW DO LOGO ==========
    
    logoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('logoPreview');
        const previewImage = document.getElementById('previewImage');
        
        if (file) {
            // Validar tamanho (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('O arquivo é muito grande. Tamanho máximo: 2MB');
                this.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Validar tipo
            if (!file.type.match('image.*')) {
                alert('Por favor, selecione uma imagem (JPG, PNG, etc.)');
                this.value = '';
                preview.style.display = 'none';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.style.display = 'block';
                
                // Adicionar animação
                previewImage.style.opacity = '0';
                previewImage.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    previewImage.style.transition = 'all 0.3s ease';
                    previewImage.style.opacity = '1';
                    previewImage.style.transform = 'scale(1)';
                }, 10);
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // ========== VALIDAÇÃO DO FORMULÁRIO NO SUBMIT ==========
    
    form.addEventListener('submit', function(e) {
        const terms = document.getElementById('terms');
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        // Resetar erros
        requiredFields.forEach(field => {
            field.classList.remove('is-invalid');
        });
        
        // Verificar campos obrigatórios
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
                
                // Scroll para o primeiro campo inválido
                if (isValid === false) {
                    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    field.focus();
                }
            }
        });
        
        // Verificar senhas
        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.classList.add('is-invalid');
            passwordMatchFeedback.innerHTML = 
                '<span class="text-danger"><i class="fas fa-times-circle me-1"></i> As senhas não coincidem</span>';
            isValid = false;
            
            if (isValid === false) {
                passwordInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                passwordInput.focus();
            }
        }
        
        // Verificar termos
        if (!terms.checked) {
            terms.classList.add('is-invalid');
            isValid = false;
            
            if (isValid === false) {
                terms.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        // Verificar email válido (se foi verificado)
        if (emailInput.classList.contains('is-invalid')) {
            isValid = false;
            emailInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            emailInput.focus();
        }
        
        // Verificar NIF válido
        if (nifInput.classList.contains('is-invalid')) {
            isValid = false;
            nifInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            nifInput.focus();
        }
        
        if (!isValid) {
            e.preventDefault();
            
            // Mostrar mensagem de erro
            const errorAlert = document.getElementById('formErrorAlert') || createErrorAlert();
            errorAlert.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2"></i>
                Por favor, corrija os erros destacados no formulário antes de enviar.
            `;
            errorAlert.style.display = 'block';
            
            // Scroll para o topo do formulário
            window.scrollTo({ top: form.offsetTop - 100, behavior: 'smooth' });
        } else {
            // Desabilitar botão e mostrar loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processando registro...';
            
            // Adicionar delay para mostrar o loading
            setTimeout(() => {
                form.submit();
            }, 500);
        }
    });

    // ========== FUNÇÕES AUXILIARES ==========
    
    function updateFeedback(element, message, type) {
        if (!element) return;
        
        const colors = {
            'success': 'text-success',
            'danger': 'text-danger',
            'warning': 'text-warning',
            'info': 'text-info',
            'muted': 'text-muted'
        };
        
        element.innerHTML = `<span class="${colors[type] || 'text-muted'} small">${message}</span>`;
    }

    function showEmailDetails(data) {
        let tooltip = document.getElementById('emailDetailsTooltip');
        
        if (!tooltip) {
            tooltip = document.createElement('div');
            tooltip.id = 'emailDetailsTooltip';
            tooltip.className = 'mt-2 p-3 bg-white border rounded shadow-sm';
            tooltip.style.fontSize = '0.8rem';
            tooltip.style.display = 'none';
            tooltip.style.position = 'absolute';
            tooltip.style.zIndex = '1000';
            tooltip.style.maxWidth = '300px';
            tooltip.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
            
            emailInput.parentNode.appendChild(tooltip);
            
            // Mostrar/ocultar tooltip
            emailInput.addEventListener('mouseenter', () => {
                if (tooltip.innerHTML.trim() !== '') {
                    tooltip.style.display = 'block';
                }
            });
            
            emailInput.addEventListener('mouseleave', () => {
                setTimeout(() => {
                    if (!tooltip.matches(':hover')) {
                        tooltip.style.display = 'none';
                    }
                }, 100);
            });
            
            tooltip.addEventListener('mouseleave', () => {
                tooltip.style.display = 'none';
            });
        }
        
        // Construir HTML dos detalhes
        let detailsHtml = `
            <div class="email-validation-details">
                <h6 class="mb-2"><i class="fas fa-search me-1"></i> Detalhes da Verificação</h6>
                
                <div class="row g-1 mb-2">
                    <div class="col-6">
                        <span class="badge bg-${data.valid ? 'success' : 'danger'} w-100">
                            <i class="fas fa-${data.valid ? 'check' : 'times'} me-1"></i>
                            ${data.valid ? 'Válido' : 'Inválido'}
                        </span>
                    </div>
                    <div class="col-6">
                        <span class="badge bg-${data.disposable ? 'danger' : 'secondary'} w-100">
                            <i class="fas fa-${data.disposable ? 'trash-alt' : 'building'} me-1"></i>
                            ${data.disposable ? 'Descartável' : 'Permanente'}
                        </span>
                    </div>
                </div>
                
                <div class="row g-1 mb-2">
                    <div class="col-6">
                        <span class="badge bg-${data.free ? 'info' : 'primary'} w-100">
                            <i class="fas fa-${data.free ? 'envelope' : 'briefcase'} me-1"></i>
                            ${data.free ? 'Gratuito' : 'Corporativo'}
                        </span>
                    </div>
                    <div class="col-6">
                        <span class="badge bg-${data.deliverable ? 'success' : 'warning'} w-100">
                            <i class="fas fa-${data.deliverable ? 'check-double' : 'question-circle'} me-1"></i>
                            ${data.deliverable ? 'Entregável' : 'Não verificado'}
                        </span>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted">Pontuação:</small>
                        <strong>${data.score}/100</strong>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-${getScoreColor(data.score)}" 
                             role="progressbar" 
                             style="width: ${data.score}%">
                        </div>
                    </div>
                </div>
                
                ${data.provider ? `
                <div class="mt-2 text-center">
                    <small class="text-muted">
                        <i class="fas fa-server me-1"></i>
                        Serviço: ${data.provider}
                    </small>
                </div>
                ` : ''}
            </div>
        `;
        
        tooltip.innerHTML = detailsHtml;
        tooltip.style.display = 'block';
        
        // Posicionar tooltip
        const rect = emailInput.getBoundingClientRect();
        tooltip.style.top = (rect.bottom + window.scrollY + 5) + 'px';
        tooltip.style.left = (rect.left + window.scrollX) + 'px';
    }
    
    function getScoreColor(score) {
        if (score >= 80) return 'success';
        if (score >= 60) return 'info';
        if (score >= 40) return 'warning';
        return 'danger';
    }
    
    function createErrorAlert() {
        const alertDiv = document.createElement('div');
        alertDiv.id = 'formErrorAlert';
        alertDiv.className = 'alert alert-danger alert-dismissible fade show';
        alertDiv.style.display = 'none';
        alertDiv.innerHTML = `
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        form.parentNode.insertBefore(alertDiv, form);
        return alertDiv;
    }
    
    // ========== MASCARAS DE INPUT ==========
    
    // Máscara para telefone
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = '+' + value;
                } else if (value.length <= 6) {
                    value = '+' + value.substring(0, 3) + ' ' + value.substring(3);
                } else if (value.length <= 9) {
                    value = '+' + value.substring(0, 3) + ' ' + value.substring(3, 6) + ' ' + value.substring(6);
                } else {
                    value = '+' + value.substring(0, 3) + ' ' + value.substring(3, 6) + ' ' + 
                            value.substring(6, 9) + ' ' + value.substring(9, 12);
                }
            }
            
            e.target.value = value;
        });
    }
    
    // Máscara para NIF
    nifInput.addEventListener('input', function(e) {
        let value = e.target.value;
        if (value.length > 18) {
            value = value.substring(0, 18);
            
        }
        e.target.value = value;
    });
    
    // Máscara para ano de fundação
    const yearInput = document.getElementById('foundation_year');
    if (yearInput) {
        yearInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 4) {
                value = value.substring(0, 4);
            }
            e.target.value = value;
            
            // Validar ano
            const currentYear = new Date().getFullYear();
            if (value.length === 4) {
                const year = parseInt(value);
                if (year < 1800 || year > currentYear) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            }
        });
    }
    
    // ========== TOGGLE VISUALIZAÇÃO DE SENHA ==========
    
    // Adicionar botões para mostrar/ocultar senha
    const passwordContainer = passwordInput.parentNode;
    const confirmPasswordContainer = confirmPasswordInput.parentNode;
    
    [passwordContainer, confirmPasswordContainer].forEach(container => {
        const toggleBtn = document.createElement('button');
        toggleBtn.type = 'button';
        toggleBtn.className = 'password-toggle-btn';
        toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
        
        const input = container.querySelector('input');
        
        toggleBtn.addEventListener('click', function() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
        
        container.style.position = 'relative';
        container.appendChild(toggleBtn);
    });
    
    // ========== VALIDAÇÃO INICIAL ==========
    
    // Validar campos preenchidos automaticamente pelo navegador
    setTimeout(() => {
        if (nifInput.value) {
            validateNif(nifInput.value);
        }
        if (passwordInput.value) {
            validatePasswordStrength(passwordInput.value);
            checkPasswordMatch();
        }
    }, 1000);
});
</script>
@endpush