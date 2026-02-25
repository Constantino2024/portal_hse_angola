@php
    /** @var \App\Models\AgendaItem|null $item */
@endphp

<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Título</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $item->title ?? '') }}" required>
    </div>

    <div class="col-12">
        <label class="form-label">Imagem (obrigatória ao criar)</label>
        <input type="file" name="image" class="form-control" accept="image/*" {{ isset($item) && $item ? '' : 'required' }}>
        @if(isset($item) && $item && $item->image_path)
            <div class="form-text">
                Atual: <a href="{{ asset('storage/'.$item->image_path) }}" target="_blank">abrir imagem</a>
            </div>
        @endif
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Tipo</label>
        <select name="type" class="form-select" required>
            @foreach($types as $k => $label)
                <option value="{{ $k }}" @selected(old('type', $item->type ?? '')===$k)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Início</label>
        <input type="datetime-local" name="starts_at" class="form-control"
               value="{{ old('starts_at', isset($item) && $item->starts_at ? $item->starts_at->format('Y-m-d\TH:i') : '') }}" required>
    </div>

    <div class="col-12 col-md-4">
        <label class="form-label">Fim (opcional)</label>
        <input type="datetime-local" name="ends_at" class="form-control"
               value="{{ old('ends_at', isset($item) && $item->ends_at ? $item->ends_at->format('Y-m-d\TH:i') : '') }}">
    </div>

    <div class="col-12">
        <label class="form-label">Resumo (opcional)</label>
        <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt', $item->excerpt ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label">Descrição (opcional)</label>
        <textarea name="description" class="form-control" rows="7">{{ old('description', $item->description ?? '') }}</textarea>
        <div class="form-text">Dica: podes colar texto com quebras de linha.</div>
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label">Local (opcional)</label>
        <input type="text" name="location" class="form-control" value="{{ old('location', $item->location ?? '') }}" placeholder="Ex: Luanda, Huambo, Online...">
    </div>

    <div class="col-12 col-md-3">
        <label class="form-label">Capacidade (opcional)</label>
        <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $item->capacity ?? '') }}" min="1" placeholder="Ilimitado">
    </div>

    <div class="col-12 col-md-3">
        <label class="form-label">Link externo (opcional)</label>
        <input type="url" name="external_registration_url" class="form-control" value="{{ old('external_registration_url', $item->external_registration_url ?? '') }}" placeholder="https://...">
    </div>

    {{-- ... outros campos ... --}}

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" name="is_online" id="is_online"
                value="1" @checked(old('is_online', $item->is_online ?? false))>
            <label class="form-check-label" for="is_online">Online</label>
        </div>
        
        <div class="form-check form-switch">
            <input type="hidden" name="registration_enabled" value="0">
            <input class="form-check-input" type="checkbox" role="switch" name="registration_enabled" 
                id="registration_enabled" value="1" 
                @checked(old('registration_enabled', $item->registration_enabled ?? true))>
            <label class="form-check-label" for="registration_enabled">Inscrição direta ativada</label>
        </div>
        
        <div class="form-check form-switch">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" type="checkbox" role="switch" name="is_active" 
                id="is_active" value="1" 
                @checked(old('is_active', $item->is_active ?? true))>
            <label class="form-check-label" for="is_active">Ativo / Publicado</label>
        </div>
    </div>
</div>