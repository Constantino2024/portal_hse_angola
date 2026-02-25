<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $body = (string) $this->input('body', '');
        // Sanitização leve (regra de negócio/XSS mais forte fica no Service)
        $body = trim(preg_replace('/\s+/u', ' ', $body) ?? '');
        $this->merge(['body' => $body]);
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:1', 'max:2000'],
        ];
    }
}
