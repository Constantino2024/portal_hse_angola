<?php

namespace App\Http\Requests\Agenda;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Support\Sanitizer;

class UpdateAgendaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:40'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:20000'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:4096',
                'dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000',
            ],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'external_registration_url' => ['nullable', 'url'],
            'is_active' => ['sometimes', 'boolean'],
            'registration_enabled' => ['sometimes', 'boolean'],
            'is_online' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => Sanitizer::plain($this->input('title'), 255),
            'type' => Sanitizer::key($this->input('type'), 40) ?? $this->input('type'),
            'excerpt' => Sanitizer::plain($this->input('excerpt'), 2000),
            'description' => Sanitizer::plain($this->input('description'), 20000),
            'location' => Sanitizer::plain($this->input('location'), 255),
            'external_registration_url' => Sanitizer::plain($this->input('external_registration_url'), 2000),
        ]);
    }
}
