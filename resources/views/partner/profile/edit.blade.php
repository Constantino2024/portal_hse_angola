@extends('layouts.partner')

@section('title', 'Editar Perfil da Empresa')


@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h2 fw-bold mb-2">Editar Perfil da Empresa</h1>
            <p class="text-muted">
                <i class="fa-regular fa-circle-info me-2"></i>
                Atualize as informações da sua empresa para manter seu perfil sempre atualizado.
            </p>
        </div>
        <a href="{{ route('partner.profile') }}" class="btn btn-light">
            <i class="fa-solid fa-arrow-left me-2"></i>
            Voltar ao Perfil
        </a>
    </div>

    <form action="{{ route('partner.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')

        <!-- Logo e Banner Section -->
        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-image"></i>
                Logo e Banner
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Logo da Empresa</label>
                    <div class="upload-area @if($company->logo_path) has-image @endif" id="logoUpload">
                        <input type="file" name="logo" id="logoInput" accept="image/*" style="display: none;">
                        
                        @if($company->logo_path)
                            <img src="{{ $company->logo_url }}" alt="Logo" id="logoPreview">
                            <div class="upload-remove" onclick="removeLogo(event)" title="Remover logo">
                                <i class="fa-solid fa-times"></i>
                            </div>
                        @else
                            <div class="text-center">
                                <div class="upload-icon">
                                    <i class="fa-solid fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">Clique para fazer upload do logo</div>
                                <div class="upload-hint">PNG, JPG ou SVG (max. 2MB)</div>
                            </div>
                        @endif
                    </div>
                    @error('logo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Banner da Empresa</label>
                    <div class="upload-area @if($company->banner_path) has-image @endif" id="bannerUpload">
                        <input type="file" name="banner" id="bannerInput" accept="image/*" style="display: none;">
                        
                        @if($company->banner_path)
                            <img src="{{ $company->banner_url }}" alt="Banner" id="bannerPreview">
                            <div class="upload-remove" onclick="removeBanner(event)" title="Remover banner">
                                <i class="fa-solid fa-times"></i>
                            </div>
                        @else
                            <div class="text-center">
                                <div class="upload-icon">
                                    <i class="fa-solid fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">Clique para fazer upload do banner</div>
                                <div class="upload-hint">JPG ou PNG (max. 5MB, recomendado 1200x300px)</div>
                            </div>
                        @endif
                    </div>
                    @error('banner')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Informações Básicas -->
        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-building"></i>
                Informações Básicas
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Nome da Empresa *</label>
                    <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" 
                           value="{{ old('company_name', $company->company_name) }}" required>
                    @error('company_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nome Fantasia</label>
                    <input type="text" name="trading_name" class="form-control @error('trading_name') is-invalid @enderror" 
                           value="{{ old('trading_name', $company->trading_name) }}">
                    @error('trading_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">NIF *</label>
                    <input type="text" name="nif" class="form-control @error('nif') is-invalid @enderror" 
                           value="{{ old('nif', $company->nif) }}" required>
                    @error('nif')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Setor de Atividade *</label>
                    <select name="sector" class="form-select @error('sector') is-invalid @enderror" required>
                        <option value="">Selecione o setor</option>
                        @foreach($sectors as $value => $label)
                            <option value="{{ $value }}" {{ old('sector', $company->sector) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('sector')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Porte da Empresa *</label>
                    <select name="company_size" class="form-select @error('company_size') is-invalid @enderror" required>
                        <option value="">Selecione o porte</option>
                        @foreach($companySizes as $value => $label)
                            <option value="{{ $value }}" {{ old('company_size', $company->company_size) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_size')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ano de Fundação</label>
                    <input type="number" name="foundation_year" class="form-control @error('foundation_year') is-invalid @enderror" 
                           value="{{ old('foundation_year', $company->foundation_year) }}" min="1900" max="{{ date('Y') }}">
                    @error('foundation_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contactos -->
        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-address-book"></i>
                Contactos
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <label class="form-label">Telefone *</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                           value="{{ old('phone', $company->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $company->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Website</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-globe"></i></span>
                        <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" 
                               value="{{ old('website', $company->website) }}" placeholder="https://www.exemplo.com">
                    </div>
                    @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pessoa de Contacto -->
        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-user-tie"></i>
                Pessoa de Contacto
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Nome do Contacto *</label>
                    <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" 
                           value="{{ old('contact_person', $company->contact_person) }}" required>
                    @error('contact_person')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cargo/Função</label>
                    <input type="text" name="contact_position" class="form-control @error('contact_position') is-invalid @enderror" 
                           value="{{ old('contact_position', $company->contact_position) }}">
                    @error('contact_position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Localização -->
        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-location-dot"></i>
                Localização
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Endereço *</label>
                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                           value="{{ old('address', $company->address) }}" required>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cidade *</label>
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                           value="{{ old('city', $company->city) }}" required>
                    @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Província *</label>
                    <select name="province" class="form-select @error('province') is-invalid @enderror" required>
                        <option value="">Selecione a província</option>
                        @foreach($provinces as $value => $label)
                            <option value="{{ $value }}" {{ old('province', $company->province) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('province')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">País</label>
                    <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" 
                           value="{{ old('country', $company->country ?? 'Angola') }}" required>
                    @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sobre a Empresa -->
        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-align-left"></i>
                Sobre a Empresa
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <label class="form-label">Descrição</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                              rows="5">{{ old('description', $company->description) }}</textarea>
                    <small class="text-muted">Descreva a sua empresa, principais atividades e diferenciais.</small>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Missão</label>
                    <textarea name="mission" class="form-control @error('mission') is-invalid @enderror" 
                              rows="3">{{ old('mission', $company->mission) }}</textarea>
                    @error('mission')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Visão</label>
                    <textarea name="vision" class="form-control @error('vision') is-invalid @enderror" 
                              rows="3">{{ old('vision', $company->vision) }}</textarea>
                    @error('vision')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Valores</label>
                    <textarea name="values" class="form-control @error('values') is-invalid @enderror" 
                              rows="3">{{ old('values', $company->values) }}</textarea>
                    @error('values')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Redes Sociais -->
        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-share-nodes"></i>
                Redes Sociais
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label">Facebook</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-brands fa-facebook-f"></i></span>
                        <input type="url" name="facebook" class="form-control @error('facebook') is-invalid @enderror" 
                               value="{{ old('facebook', $company->facebook) }}" placeholder="https://facebook.com/...">
                    </div>
                    @error('facebook')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">LinkedIn</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-brands fa-linkedin-in"></i></span>
                        <input type="url" name="linkedin" class="form-control @error('linkedin') is-invalid @enderror" 
                               value="{{ old('linkedin', $company->linkedin) }}" placeholder="https://linkedin.com/company/...">
                    </div>
                    @error('linkedin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Twitter/X</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-brands fa-x-twitter"></i></span>
                        <input type="url" name="twitter" class="form-control @error('twitter') is-invalid @enderror" 
                               value="{{ old('twitter', $company->twitter) }}" placeholder="https://twitter.com/...">
                    </div>
                    @error('twitter')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Instagram</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-brands fa-instagram"></i></span>
                        <input type="url" name="instagram" class="form-control @error('instagram') is-invalid @enderror" 
                               value="{{ old('instagram', $company->instagram) }}" placeholder="https://instagram.com/...">
                    </div>
                    @error('instagram')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- HSE & ESG -->
        <div class="form-section">
            <div class="form-section-title">
                <i class="fa-solid fa-leaf"></i>
                HSE & ESG
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" name="has_hse_department" class="form-check-input" role="switch" 
                               id="hasHseDept" {{ old('has_hse_department', $company->has_hse_department) ? 'checked' : '' }}>
                        <label class="form-check-label" for="hasHseDept">Possui Departamento de HSE</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-check form-switch mb-3">
                        <input type="checkbox" name="has_esg_policy" class="form-check-input" role="switch" 
                               id="hasEsgPolicy" {{ old('has_esg_policy', $company->has_esg_policy) ? 'checked' : '' }}>
                        <label class="form-check-label" for="hasEsgPolicy">Possui Política ESG</label>
                    </div>
                </div>
            </div>

            <div class="row g-4" id="hseInfo" style="{{ $company->has_hse_department ? '' : 'display: none;' }}">
                <div class="col-md-6">
                    <label class="form-label">Nome do Gestor HSE</label>
                    <input type="text" name="hse_manager_name" class="form-control @error('hse_manager_name') is-invalid @enderror" 
                           value="{{ old('hse_manager_name', $company->hse_manager_name) }}">
                    @error('hse_manager_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contacto do Gestor HSE</label>
                    <input type="text" name="hse_manager_contact" class="form-control @error('hse_manager_contact') is-invalid @enderror" 
                           value="{{ old('hse_manager_contact', $company->hse_manager_contact) }}">
                    @error('hse_manager_contact')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="d-flex gap-3 justify-content-end mt-4 mb-5">
            <a href="{{ route('partner.profile') }}" class="btn-cancel">
                <i class="fa-solid fa-times me-2"></i>
                Cancelar
            </a>
            <button type="submit" class="btn-save">
                <i class="fa-solid fa-save me-2"></i>
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<!-- Hidden form for remove operations -->
<form id="removeLogoForm" action="{{ route('partner.profile.logo.remove') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="removeBannerForm" action="{{ route('partner.profile.banner.remove') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
    .form-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
        border: 1px solid var(--medium-gray);
    }

    .form-section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--medium-gray);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-section-title i {
        color: var(--accent-orange);
        font-size: 1.25rem;
    }

    .form-label {
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border: 2px solid var(--medium-gray);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--accent-orange);
        box-shadow: 0 0 0 4px rgba(211, 84, 0, 0.1);
    }

    .form-control.is-invalid, .form-select.is-invalid {
        border-color: #dc3545;
        background-image: none;
    }

    .invalid-feedback {
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .upload-area {
        border: 3px dashed var(--medium-gray);
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: var(--light-gray);
        position: relative;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-area:hover {
        border-color: var(--accent-orange);
        background: rgba(211, 84, 0, 0.05);
    }

    .upload-area.has-image {
        border-style: solid;
        border-color: var(--primary-dark);
        padding: 0.5rem;
    }

    .upload-area img {
        max-width: 100%;
        max-height: 180px;
        border-radius: 12px;
    }

    .upload-remove {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: white;
        border: 2px solid #dc3545;
        color: #dc3545;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        z-index: 10;
    }

    .upload-remove:hover {
        background: #dc3545;
        color: white;
    }

    .upload-icon {
        font-size: 3rem;
        color: var(--dark-gray);
        margin-bottom: 1rem;
    }

    .upload-text {
        color: var(--dark-gray);
        font-weight: 500;
    }

    .upload-hint {
        font-size: 0.875rem;
        color: var(--dark-gray);
        margin-top: 0.5rem;
    }

    .btn-save {
        background: var(--accent-orange);
        color: white;
        padding: 1rem 2.5rem;
        font-weight: 600;
        border-radius: 12px;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        background: var(--accent-orange-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(211, 84, 0, 0.3);
    }

    .btn-cancel {
        background: var(--light-gray);
        color: var(--text-dark);
        padding: 1rem 2.5rem;
        font-weight: 600;
        border-radius: 12px;
        border: 2px solid var(--medium-gray);
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: var(--medium-gray);
        color: var(--text-dark);
    }

    .preview-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        backdrop-filter: blur(5px);
    }

    .input-group-text {
        background: var(--light-gray);
        border: 2px solid var(--medium-gray);
        border-radius: 10px 0 0 10px;
        color: var(--dark-gray);
    }

    .input-group .form-control {
        border-left: none;
        border-radius: 0 10px 10px 0;
    }

    .form-check-input:checked {
        background-color: var(--accent-orange);
        border-color: var(--accent-orange);
    }
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle HSE fields
    const hasHseDept = document.getElementById('hasHseDept');
    const hseInfo = document.getElementById('hseInfo');
    
    if (hasHseDept) {
        hasHseDept.addEventListener('change', function() {
            hseInfo.style.display = this.checked ? 'flex' : 'none';
        });
    }

    // Logo upload
    const logoUpload = document.getElementById('logoUpload');
    const logoInput = document.getElementById('logoInput');
    
    if (logoUpload) {
        logoUpload.addEventListener('click', function() {
            logoInput.click();
        });
    }

    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const html = `
                        <img src="${e.target.result}" id="logoPreview">
                        <div class="upload-remove" onclick="removeLogo(event)" title="Remover logo">
                            <i class="fa-solid fa-times"></i>
                        </div>
                    `;
                    logoUpload.innerHTML = html;
                    logoUpload.classList.add('has-image');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Banner upload
    const bannerUpload = document.getElementById('bannerUpload');
    const bannerInput = document.getElementById('bannerInput');
    
    if (bannerUpload) {
        bannerUpload.addEventListener('click', function() {
            bannerInput.click();
        });
    }

    if (bannerInput) {
        bannerInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const html = `
                        <img src="${e.target.result}" id="bannerPreview">
                        <div class="upload-remove" onclick="removeBanner(event)" title="Remover banner">
                            <i class="fa-solid fa-times"></i>
                        </div>
                    `;
                    bannerUpload.innerHTML = html;
                    bannerUpload.classList.add('has-image');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // Preview before submit (optional)
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            // You could add a loading indicator here
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Salvando...';
                submitBtn.disabled = true;
            }
        });
    }
});

// Remove logo function
function removeLogo(event) {
    event.stopPropagation();
    
    if (confirm('Tem certeza que deseja remover o logo?')) {
        // Submit the remove form via AJAX
        fetch('{{ route("partner.profile.logo.remove") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page or update UI
                location.reload();
            } else {
                alert('Erro ao remover o logo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao remover o logo.');
        });
    }
}

// Remove banner function
function removeBanner(event) {
    event.stopPropagation();
    
    if (confirm('Tem certeza que deseja remover o banner?')) {
        fetch('{{ route("partner.profile.banner.remove") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao remover o banner.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao remover o banner.');
        });
    }
}

// Optional: Drag and drop functionality
function setupDragDrop(uploadArea, inputElement) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        uploadArea.classList.add('bg-light');
    }

    function unhighlight(e) {
        uploadArea.classList.remove('bg-light');
    }

    uploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        inputElement.files = files;
        
        // Trigger change event
        const event = new Event('change', { bubbles: true });
        inputElement.dispatchEvent(event);
    }
}

// Apply drag drop to upload areas
document.addEventListener('DOMContentLoaded', function() {
    const logoUpload = document.getElementById('logoUpload');
    const logoInput = document.getElementById('logoInput');
    const bannerUpload = document.getElementById('bannerUpload');
    const bannerInput = document.getElementById('bannerInput');
    
    if (logoUpload && logoInput && !logoUpload.querySelector('img')) {
        setupDragDrop(logoUpload, logoInput);
    }
    
    if (bannerUpload && bannerInput && !bannerUpload.querySelector('img')) {
        setupDragDrop(bannerUpload, bannerInput);
    }
});
</script>
@endpush