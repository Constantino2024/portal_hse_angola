@php
  $isEdit = isset($job);
@endphp

<!-- Form Container -->
<div class="form-container">
  
  <!-- Main Form Grid -->
  <div class="row g-4">
    
    <!-- Left Column - Main Form Fields -->
    <div class="col-12 col-lg-8">
      
      <!-- Basic Information Card -->
      <div class="form-card">
        <div class="form-card-header">
          <div class="form-card-icon">
            <i class="fa-solid fa-info-circle"></i>
          </div>
          <div>
            <h3 class="form-card-title">Informações Básicas</h3>
            <p class="form-card-subtitle">Dados essenciais da vaga</p>
          </div>
        </div>
        
        <div class="form-card-body">
          <!-- Title -->
          <div class="form-group">
            <label class="form-label required">
              <i class="fa-solid fa-heading"></i>
              Título da Vaga
            </label>
            <div class="input-with-icon">
              <i class="input-icon fa-solid fa-briefcase"></i>
              <input type="text" 
                     name="title" 
                     class="form-control input-lg" 
                     value="{{ old('title', $job->title ?? '') }}" 
                     placeholder="Ex: Desenvolvedor Front-end Sênior"
                     required
                     autofocus>
            </div>
            <div class="form-hint">
              Seja claro e objetivo. Use palavras-chave relevantes.
            </div>
          </div>
          
          <!-- Company & Location -->
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label class="form-label">
                  <i class="fa-solid fa-building"></i>
                  Empresa
                </label>
                <div class="input-with-icon">
                  <i class="input-icon fa-solid fa-city"></i>
                  <input type="text" 
                         name="company" 
                         class="form-control" 
                         value="{{ old('company', $job->company ?? '') }}" 
                         placeholder="Nome da empresa (opcional)">
                </div>
              </div>
            </div>
            
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label class="form-label required">
                  <i class="fa-solid fa-location-dot"></i>
                  Localização
                </label>
                <div class="input-with-icon">
                  <i class="input-icon fa-solid fa-map-pin"></i>
                  <input type="text" 
                         name="location" 
                         class="form-control" 
                         value="{{ old('location', $job->location ?? '') }}" 
                         placeholder="Ex: Luanda, Benguela, Remoto..."
                         required>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Type & Level -->
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label class="form-label">
                  <i class="fa-solid fa-briefcase"></i>
                  Tipo de Emprego
                </label>
                <div class="select-with-icon">
                  <i class="input-icon fa-solid fa-clock"></i>
                  <select name="type" class="form-select">
                    <option value="">Selecione o tipo...</option>
                    <option value="Tempo Integral" {{ old('type', $job->type ?? '') == 'Tempo Integral' ? 'selected' : '' }}>Tempo Integral</option>
                    <option value="Meio Período" {{ old('type', $job->type ?? '') == 'Meio Período' ? 'selected' : '' }}>Meio Período</option>
                    <option value="Contrato" {{ old('type', $job->type ?? '') == 'Contrato' ? 'selected' : '' }}>Contrato</option>
                    <option value="Estágio" {{ old('type', $job->type ?? '') == 'Estágio' ? 'selected' : '' }}>Estágio</option>
                    <option value="Freelance" {{ old('type', $job->type ?? '') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                    <option value="Remoto" {{ old('type', $job->type ?? '') == 'Remoto' ? 'selected' : '' }}>Remoto</option>
                  </select>
                </div>
                <div class="form-hint">
                  Ou digite um tipo personalizado
                </div>
                <input type="text" 
                       name="type_custom" 
                       class="form-control mt-2" 
                       placeholder="Digite outro tipo..."
                       style="display: none;">
              </div>
            </div>
            
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label class="form-label">
                  <i class="fa-solid fa-chart-line"></i>
                  Nível de Experiência
                </label>
                <div class="select-with-icon">
                  <i class="input-icon fa-solid fa-user-graduate"></i>
                  <select name="level" class="form-select">
                    <option value="">Selecione o nível...</option>
                    <option value="Júnior" {{ old('level', $job->level ?? '') == 'Júnior' ? 'selected' : '' }}>Júnior</option>
                    <option value="Pleno" {{ old('level', $job->level ?? '') == 'Pleno' ? 'selected' : '' }}>Pleno</option>
                    <option value="Sénior" {{ old('level', $job->level ?? '') == 'Sénior' ? 'selected' : '' }}>Sénior</option>
                    <option value="Especialista" {{ old('level', $job->level ?? '') == 'Especialista' ? 'selected' : '' }}>Especialista</option>
                    <option value="Gestor" {{ old('level', $job->level ?? '') == 'Gestor' ? 'selected' : '' }}>Gestor</option>
                    <option value="Diretor" {{ old('level', $job->level ?? '') == 'Diretor' ? 'selected' : '' }}>Diretor</option>
                  </select>
                </div>
                <div class="form-hint">
                  Ou digite um nível personalizado
                </div>
                <input type="text" 
                       name="level_custom" 
                       class="form-control mt-2" 
                       placeholder="Digite outro nível..."
                       style="display: none;">
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Description & Requirements Card -->
      <div class="form-card">
        <div class="form-card-header">
          <div class="form-card-icon">
            <i class="fa-solid fa-align-left"></i>
          </div>
          <div>
            <h3 class="form-card-title">Conteúdo da Vaga</h3>
            <p class="form-card-subtitle">Detalhes, requisitos e informações</p>
          </div>
        </div>
        
        <div class="form-card-body">
          <!-- Excerpt -->
          <div class="form-group">
            <label class="form-label">
              <i class="fa-solid fa-quote-left"></i>
              Resumo (Excerpt)
            </label>
            <div class="textarea-with-counter">
              <textarea name="excerpt" 
                        class="form-control" 
                        rows="3" 
                        placeholder="Breve descrição que aparecerá na listagem de vagas..."
                        maxlength="200"
                        data-counter="excerpt-counter">{{ old('excerpt', $job->excerpt ?? '') }}</textarea>
              <div class="char-counter" id="excerpt-counter">0/200</div>
            </div>
            <div class="form-hint">
              Máximo 200 caracteres. Use para chamar atenção dos candidatos.
            </div>
          </div>
          
          <!-- Description -->
          <div class="form-group">
            <label class="form-label required">
              <i class="fa-solid fa-file-lines"></i>
              Descrição Completa
            </label>
            <div class="textarea-with-counter">
              <textarea name="description" 
                        class="form-control" 
                        rows="6" 
                        placeholder="Descreva detalhadamente a vaga: responsabilidades, atividades, objetivos..."
                        maxlength="2000"
                        data-counter="description-counter"
                        required>{{ old('description', $job->description ?? '') }}</textarea>
              <div class="char-counter" id="description-counter">0/2000</div>
            </div>
            <div class="form-hint">
              Seja detalhado mas objetivo. Use marcadores para listas.
            </div>
          </div>
          
          <!-- Requirements -->
          <div class="form-group">
            <label class="form-label">
              <i class="fa-solid fa-list-check"></i>
              Requisitos e Competências
            </label>
            <div class="textarea-with-counter">
              <textarea name="requirements" 
                        class="form-control" 
                        rows="5" 
                        placeholder="Liste os requisitos obrigatórios e desejáveis, competências técnicas e comportamentais..."
                        maxlength="1500"
                        data-counter="requirements-counter">{{ old('requirements', $job->requirements ?? '') }}</textarea>
              <div class="char-counter" id="requirements-counter">0/1500</div>
            </div>
            <div class="form-hint">
              Separe por tópicos. Diferencie requisitos obrigatórios e desejáveis.
            </div>
          </div>
          
          <!-- Quick Format Toolbar -->
          <div class="format-toolbar mb-3">
            <small class="text-muted me-2">Formatação rápida:</small>
            <button type="button" class="format-btn" data-format="bullet">
              <i class="fa-solid fa-list-ul"></i> Lista
            </button>
            <button type="button" class="format-btn" data-format="bold">
              <i class="fa-solid fa-bold"></i> Negrito
            </button>
            <button type="button" class="format-btn" data-format="italic">
              <i class="fa-solid fa-italic"></i> Itálico
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Right Column - Sidebar -->
    <div class="col-12 col-lg-4">
      
      <!-- Application Settings Card -->
      <div class="form-card">
        <div class="form-card-header">
          <div class="form-card-icon">
            <i class="fa-solid fa-paper-plane"></i>
          </div>
          <div>
            <h3 class="form-card-title">Candidatura</h3>
            <p class="form-card-subtitle">Como os candidatos irão se aplicar</p>
          </div>
        </div>
        
        <div class="form-card-body">
          <!-- Apply Link -->
          <div class="form-group">
            <label class="form-label">
              <i class="fa-solid fa-link"></i>
              Link de Candidatura
            </label>
            <div class="input-with-icon">
              <i class="input-icon fa-solid fa-globe"></i>
              <input type="url" 
                     name="apply_link" 
                     class="form-control" 
                     value="{{ old('apply_link', $job->apply_link ?? '') }}" 
                     placeholder="https://formulario.empresa.com">
            </div>
            <div class="form-hint">
              Link externo para formulário de candidatura.
            </div>
          </div>
          
          <!-- Apply Email -->
          <div class="form-group">
            <label class="form-label">
              <i class="fa-solid fa-envelope"></i>
              E-mail para Candidaturas
            </label>
            <div class="input-with-icon">
              <i class="input-icon fa-solid fa-at"></i>
              <input type="email" 
                     name="apply_email" 
                     class="form-control" 
                     value="{{ old('apply_email', $job->apply_email ?? '') }}" 
                     placeholder="rh@empresa.com">
            </div>
            <div class="form-hint">
              Candidatos poderão enviar currículos por e-mail.
            </div>
          </div>
          
          <div class="divider">
            <span>Configurações</span>
          </div>
          
          <!-- Status Switch -->
          <div class="form-group">
            <div class="status-toggle">
              <div class="toggle-header">
                <i class="fa-solid fa-toggle-on"></i>
                <span class="toggle-label">Status da Vaga</span>
              </div>
              <div class="toggle-switch">
                <input class="form-check-input" 
                       type="checkbox" 
                       role="switch" 
                       id="is_active" 
                       name="is_active" 
                       value="1"
                       @checked(old('is_active', $job->is_active ?? true))>
                <label class="form-check-label" for="is_active">
                  <span class="toggle-status-text">Ativa</span>
                  <small class="toggle-status-hint">Visível no portal</small>
                </label>
              </div>
            </div>
            <div class="form-hint">
              Desative para ocultar temporariamente a vaga.
            </div>
          </div>
          
          <!-- Salary (Optional Additional Field) -->
          <div class="form-group">
            <label class="form-label">
              <i class="fa-solid fa-money-bill-wave"></i>
              Faixa Salarial (opcional)
            </label>
            <div class="input-with-icon">
              <i class="input-icon fa-solid fa-dollar-sign"></i>
              <input type="text" 
                     name="salary" 
                     class="form-control" 
                     value="{{ old('salary', $job->salary ?? '') }}" 
                     placeholder="Ex: 150.000 - 250.000 Kz">
            </div>
            <div class="form-hint">
              Pode ajudar a atrair mais candidatos qualificados.
            </div>
          </div>
          
          <!-- Additional Info -->
          <div class="info-box">
            <div class="info-box-icon">
              <i class="fa-regular fa-circle-info"></i>
            </div>
            <div class="info-box-content">
              <div class="info-box-title">Informações Adicionais</div>
              <div class="info-box-text">
                Destaques e patrocínios são geridos pela administração do portal.
                Entre em contato para mais informações sobre visibilidade.
              </div>
            </div>
          </div>
          
          <!-- Save Status -->
          @if($isEdit)
            <div class="save-status">
              <div class="save-status-icon">
                <i class="fa-solid fa-clock"></i>
              </div>
              <div class="save-status-content">
                <div class="save-status-text">Última edição</div>
                <div class="save-status-date">
                  {{ $job->updated_at->format('d/m/Y H:i') }}
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
      
      <!-- Quick Actions Card -->
      <div class="form-card">
        <div class="form-card-header">
          <div class="form-card-icon">
            <i class="fa-solid fa-bolt"></i>
          </div>
          <div>
            <h3 class="form-card-title">Ações Rápidas</h3>
          </div>
        </div>
        
        <div class="form-card-body">
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="fa-solid fa-cloud-arrow-up me-2"></i>
              {{ $isEdit ? 'Atualizar Vaga' : 'Publicar Vaga' }}
            </button>
            
            <button type="button" class="btn btn-outline-primary" onclick="previewForm()">
              <i class="fa-solid fa-eye me-2"></i>
              Pré-visualizar
            </button>
            
            @if($isEdit)
              <button type="button" class="btn btn-outline-secondary" onclick="duplicateJob()">
                <i class="fa-solid fa-copy me-2"></i>
                Duplicar Vaga
              </button>
            @endif
            
            <a href="{{ route('partner.jobs.index') }}" class="btn btn-outline-danger">
              <i class="fa-solid fa-times me-2"></i>
              Cancelar
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Form Container */
  .form-container {
    padding: 0;
  }
  
  /* Form Cards */
  .form-card {
    background: var(--pure-white);
    border: 1px solid var(--medium-gray);
    border-radius: 16px;
    margin-bottom: 1.5rem;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
  }
  
  .form-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
  }
  
  .form-card-header {
    background: linear-gradient(135deg, var(--primary-dark) 0%, #2c5282 100%);
    color: white;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .form-card-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
  }
  
  .form-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    color: white;
  }
  
  .form-card-subtitle {
    font-size: 0.875rem;
    opacity: 0.9;
    margin: 0.25rem 0 0;
    color: rgba(255, 255, 255, 0.9);
  }
  
  .form-card-body {
    padding: 1.5rem;
  }
  
  /* Form Groups */
  .form-group {
    margin-bottom: 1.5rem;
  }
  
  .form-group:last-child {
    margin-bottom: 0;
  }
  
  .form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
  }
  
  .form-label.required::after {
    content: '*';
    color: #dc3545;
    margin-left: 0.25rem;
  }
  
  /* Inputs with Icons */
  .input-with-icon {
    position: relative;
  }
  
  .input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--dark-gray);
    z-index: 1;
  }
  
  .input-with-icon .form-control {
    padding-left: 3rem;
  }
  
  .input-lg {
    padding: 1rem 1rem 1rem 3rem;
    font-size: 1.1rem;
    border-radius: 12px;
    border: 2px solid var(--medium-gray);
  }
  
  .input-lg:focus {
    border-color: var(--accent-orange);
    box-shadow: 0 0 0 4px rgba(211, 84, 0, 0.1);
  }
  
  /* Select with Icons */
  .select-with-icon {
    position: relative;
  }
  
  .select-with-icon .input-icon {
    pointer-events: none;
  }
  
  .select-with-icon .form-select {
    padding-left: 3rem;
    cursor: pointer;
  }
  
  /* Textareas with Counters */
  .textarea-with-counter {
    position: relative;
  }
  
  .char-counter {
    position: absolute;
    bottom: 0.5rem;
    right: 1rem;
    font-size: 0.75rem;
    color: var(--dark-gray);
    background: rgba(255, 255, 255, 0.9);
    padding: 0.125rem 0.5rem;
    border-radius: 12px;
    transition: all 0.2s ease;
  }
  
  .char-counter.near-limit {
    color: var(--accent-orange);
    background: rgba(211, 84, 0, 0.1);
  }
  
  .char-counter.over-limit {
    color: #dc3545;
    background: rgba(220, 53, 69, 0.1);
  }
  
  /* Form Hints */
  .form-hint {
    font-size: 0.8125rem;
    color: var(--dark-gray);
    margin-top: 0.375rem;
    line-height: 1.4;
  }
  
  /* Format Toolbar */
  .format-toolbar {
    background: var(--light-gray);
    border: 1px solid var(--medium-gray);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
  }
  
  .format-btn {
    background: white;
    border: 1px solid var(--medium-gray);
    border-radius: 6px;
    padding: 0.375rem 0.75rem;
    font-size: 0.8125rem;
    color: var(--text-dark);
    transition: all 0.2s ease;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
  }
  
  .format-btn:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
    color: white;
  }
  
  /* Status Toggle */
  .status-toggle {
    background: var(--light-gray);
    border: 1px solid var(--medium-gray);
    border-radius: 12px;
    padding: 1rem;
  }
  
  .toggle-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
  }
  
  .toggle-label {
    font-weight: 600;
    color: var(--text-dark);
  }
  
  .toggle-switch {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .toggle-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
    margin: 0;
  }
  
  .toggle-switch .form-check-input:checked {
    background-color: var(--accent-orange);
    border-color: var(--accent-orange);
  }
  
  .toggle-status-text {
    font-weight: 600;
    color: var(--text-dark);
  }
  
  .toggle-status-hint {
    display: block;
    font-size: 0.8125rem;
    color: var(--dark-gray);
    margin-top: 0.125rem;
  }
  
  /* Divider */
  .divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 1.5rem 0;
    color: var(--dark-gray);
    font-size: 0.875rem;
    font-weight: 500;
  }
  
  .divider::before,
  .divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid var(--medium-gray);
  }
  
  .divider span {
    padding: 0 1rem;
  }
  
  /* Info Box */
  .info-box {
    background: rgba(26, 54, 93, 0.05);
    border: 1px solid rgba(26, 54, 93, 0.1);
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1.5rem;
  }
  
  .info-box-icon {
    width: 32px;
    height: 32px;
    background: var(--primary-dark);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
  }
  
  .info-box-title {
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 0.25rem;
  }
  
  .info-box-text {
    font-size: 0.875rem;
    color: var(--dark-gray);
    line-height: 1.5;
  }
  
  /* Save Status */
  .save-status {
    background: var(--light-gray);
    border: 1px solid var(--medium-gray);
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }
  
  .save-status-icon {
    width: 32px;
    height: 32px;
    background: var(--accent-orange);
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .save-status-text {
    font-size: 0.75rem;
    color: var(--dark-gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  
  .save-status-date {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.875rem;
  }
  
  /* Sticky Sidebar */
  .sticky-sidebar {
    position: sticky;
    top: 20px;
    z-index: 100;
  }
  
  /* Buttons */
  .btn-lg {
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    border-radius: 12px;
  }
  
  .btn-primary {
    background: var(--accent-orange);
    border-color: var(--accent-orange);
  }
  
  .btn-primary:hover {
    background: var(--accent-orange-hover);
    border-color: var(--accent-orange-hover);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(211, 84, 0, 0.2);
  }
  
  /* Responsive */
  @media (max-width: 992px) {
    .form-card-header {
      padding: 1.25rem;
    }
    
    .form-card-body {
      padding: 1.25rem;
    }
    
    .input-lg {
      font-size: 1rem;
      padding: 0.875rem 0.875rem 0.875rem 3rem;
    }
    
    .sticky-sidebar {
      position: static;
    }
  }
  
  @media (max-width: 576px) {
    .form-card {
      border-radius: 12px;
    }
    
    .form-card-header {
      flex-direction: column;
      text-align: center;
      gap: 0.75rem;
      padding: 1rem;
    }
    
    .form-card-icon {
      width: 40px;
      height: 40px;
      font-size: 1rem;
    }
    
    .form-card-title {
      font-size: 1.125rem;
    }
    
    .form-card-subtitle {
      font-size: 0.8125rem;
    }
    
    .form-card-body {
      padding: 1rem;
    }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Character counters
  const textareas = document.querySelectorAll('textarea[data-counter]');
  
  textareas.forEach(textarea => {
    const counterId = textarea.getAttribute('data-counter');
    const counter = document.getElementById(counterId);
    const maxLength = textarea.getAttribute('maxlength') || 1000;
    
    function updateCounter() {
      const length = textarea.value.length;
      if (counter) {
        counter.textContent = `${length}/${maxLength}`;
        counter.classList.remove('near-limit', 'over-limit');
        
        if (length > maxLength * 0.8) {
          counter.classList.add('near-limit');
        }
        if (length > maxLength) {
          counter.classList.add('over-limit');
        }
      }
    }
    
    // Initial update
    updateCounter();
    
    // Update on input
    textarea.addEventListener('input', updateCounter);
  });
  
  // Custom input for type and level
  const typeSelect = document.querySelector('select[name="type"]');
  const typeCustom = document.querySelector('input[name="type_custom"]');
  const levelSelect = document.querySelector('select[name="level"]');
  const levelCustom = document.querySelector('input[name="level_custom"]');
  
  function setupCustomInput(select, customInput) {
    if (select && customInput) {
      select.addEventListener('change', function() {
        if (this.value === '') {
          customInput.style.display = 'block';
        } else {
          customInput.style.display = 'none';
          customInput.value = '';
        }
      });
      
      customInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
          select.value = '';
        }
      });
    }
  }
  
  setupCustomInput(typeSelect, typeCustom);
  setupCustomInput(levelSelect, levelCustom);
  
  // Format toolbar buttons
  const formatButtons = document.querySelectorAll('.format-btn');
  let activeTextarea = null;
  
  // Track active textarea
  textareas.forEach(textarea => {
    textarea.addEventListener('focus', function() {
      activeTextarea = this;
    });
    
    textarea.addEventListener('blur', function() {
      activeTextarea = null;
    });
  });
  
  // Format functions
  formatButtons.forEach(button => {
    button.addEventListener('click', function() {
      if (!activeTextarea) return;
      
      const format = this.getAttribute('data-format');
      const start = activeTextarea.selectionStart;
      const end = activeTextarea.selectionEnd;
      const text = activeTextarea.value;
      const selectedText = text.substring(start, end);
      
      let formattedText = '';
      
      switch(format) {
        case 'bullet':
          if (selectedText) {
            const lines = selectedText.split('\n');
            formattedText = lines.map(line => line.trim() ? '• ' + line : '').join('\n');
          } else {
            formattedText = '• ';
          }
          break;
        case 'bold':
          formattedText = '**' + (selectedText || 'texto em negrito') + '**';
          break;
        case 'italic':
          formattedText = '_' + (selectedText || 'texto em itálico') + '_';
          break;
      }
      
      if (selectedText) {
        // Replace selected text
        activeTextarea.value = text.substring(0, start) + formattedText + text.substring(end);
        activeTextarea.setSelectionRange(start, start + formattedText.length);
      } else {
        // Insert at cursor
        activeTextarea.value = text.substring(0, start) + formattedText + text.substring(start);
        const newPos = start + formattedText.length;
        activeTextarea.setSelectionRange(newPos, newPos);
      }
      
      activeTextarea.focus();
      activeTextarea.dispatchEvent(new Event('input'));
    });
  });
  
  // Form validation
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function(e) {
      const requiredInputs = this.querySelectorAll('[required]');
      let isValid = true;
      
      requiredInputs.forEach(input => {
        if (!input.value.trim()) {
          isValid = false;
          input.classList.add('is-invalid');
          
          // Add error message
          if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('invalid-feedback')) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = 'Este campo é obrigatório';
            input.parentNode.appendChild(errorDiv);
          }
        } else {
          input.classList.remove('is-invalid');
        }
      });
      
      if (!isValid) {
        e.preventDefault();
        
        // Scroll to first error
        const firstError = this.querySelector('.is-invalid');
        if (firstError) {
          firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
          firstError.focus();
        }
      }
    });
  }
});

// Form functions
function previewForm() {
  // Collect form data
  const formData = new FormData(document.querySelector('form'));
  const data = Object.fromEntries(formData);
  
  // Here you would typically send this to a preview endpoint
  // For now, show a message
  alert('Funcionalidade de pré-visualização será implementada em breve!');
}

function duplicateJob() {
  if (confirm('Deseja criar uma cópia desta vaga? Todos os dados serão duplicados.')) {
    // Here you would typically make an API call to duplicate the job
    // For now, redirect to create with current data
    const form = document.querySelector('form');
    form.action = "{{ route('partner.jobs.store') }}";
    form.submit();
  }
}
</script>