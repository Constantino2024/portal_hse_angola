<?php

namespace App\Http\Requests\Companies;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Services\Support\Sanitizer;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is handled by policies in the controller.
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            // Logo opcional da empresa
            'company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => Sanitizer::plain($this->input('name'), 255),
            'email' => Sanitizer::plain($this->input('email'), 255),
        ]);
    }
}
