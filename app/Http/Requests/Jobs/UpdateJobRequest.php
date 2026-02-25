<?php

namespace App\Http\Requests\Jobs;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Support\Sanitizer;

class UpdateJobRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:255'],
            'level' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'apply_link' => ['nullable', 'url'],
            'apply_email' => ['nullable', 'email'],
            'is_active' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_sponsored' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => Sanitizer::plain($this->input('title'), 255),
            'company' => Sanitizer::plain($this->input('company'), 255),
            'location' => Sanitizer::plain($this->input('location'), 255),
            'type' => Sanitizer::plain($this->input('type'), 255),
            'level' => Sanitizer::plain($this->input('level'), 255),
            'excerpt' => Sanitizer::plain($this->input('excerpt'), 2000),
            'description' => Sanitizer::rich($this->input('description')),
            'requirements' => Sanitizer::rich($this->input('requirements')),
            'apply_link' => Sanitizer::plain($this->input('apply_link'), 2000),
            'apply_email' => Sanitizer::plain($this->input('apply_email'), 255),
        ]);
    }
}
