@php
    /** @var \App\Models\CompanyNeed $need */
@endphp

<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Título</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $need->title) }}" required>
    </div>

    <div class="col-md-3">
        <label class="form-label">Nível</label>
        <select name="level" class="form-select" required>
            @foreach($levels as $k => $label)
                <option value="{{ $k }}" @selected(old('level', $need->level) === $k)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Área</label>
        <select name="area" class="form-select" required>
            @foreach($areas as $k => $label)
                <option value="{{ $k }}" @selected(old('area', $need->area) === $k)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Disponibilidade</label>
        <select name="availability" class="form-select" required>
            @foreach($availabilities as $k => $label)
                <option value="{{ $k }}" @selected(old('availability', $need->availability) === $k)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label">Província</label>
        <input type="text" name="province" class="form-control" value="{{ old('province', $need->province) }}" required>
    </div>

    <div class="col-12">
        <label class="form-label">Descrição (opcional)</label>
        <textarea name="description" class="form-control" rows="5">{{ old('description', $need->description) }}</textarea>
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input type="hidden" name="is_active" value="0">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $need->is_active ?? true))>
            <label class="form-check-label" for="is_active">Ativo (gerar matches)</label>
        </div>
    </div>
</div>