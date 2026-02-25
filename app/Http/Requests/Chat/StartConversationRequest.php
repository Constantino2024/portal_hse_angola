<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class StartConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    protected function prepareForValidation(): void
    {
        $rid = $this->input('recipient_id');
        if (is_string($rid)) {
            $rid = trim($rid);
        }
        $this->merge(['recipient_id' => $rid]);
    }

    public function rules(): array
    {
        return [
            'recipient_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
