<?php

namespace App\Http\Requests\Companies;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Services\Support\Sanitizer;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = $this->route('company')?->id ?? $this->route('company') ?? null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $companyId],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            // Logo opcional da empresa (pode substituir)
            'company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            // Permite remover o logo atual
            'remove_logo' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => Sanitizer::plain($this->input('name'), 255),
            'email' => Sanitizer::plain($this->input('email'), 255),
            'remove_logo' => filter_var($this->input('remove_logo'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
