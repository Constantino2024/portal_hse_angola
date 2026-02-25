<?php

namespace App\Http\Requests\Educational;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Support\Sanitizer;

class UpdateEducationalContentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:50'],
            'topic' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:2000'],
            'body' => ['required', 'string'],
            'cover_image' => ['nullable','file','mimes:jpg,jpeg,png,webp','mimetypes:image/jpeg,image/png,image/webp','max:4096','dimensions:min_width=300,min_height=300,max_width=8000,max_height=8000'],
            'is_active' => ['sometimes','boolean'],
            'is_featured' => ['sometimes','boolean'],
            'is_premium' => ['sometimes','boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => Sanitizer::plain($this->input('title'), 255),
            'level' => Sanitizer::key($this->input('level'), 50) ?? $this->input('level'),
            'topic' => Sanitizer::plain($this->input('topic'), 255),
            'excerpt' => Sanitizer::plain($this->input('excerpt'), 2000),
            'body' => Sanitizer::rich($this->input('body')),
        ]);
    }
}
