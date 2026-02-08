<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AdminResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            // 'email' => ['required', 'email:rfc,dns', 'max:190'],
            'email' => ['required', 'email', 'max:190'],
            'password' => [
                'required',
                'string',
                'confirmed',
                PasswordRule::min(10)->letters()->mixedCase()->numbers()->symbols(),
            ],
            'password_confirmation' => [
                'required',
                'string',
                'same:password',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => is_string($this->email) ? trim(mb_strtolower($this->email)) : $this->email,
        ]);
    }
}
