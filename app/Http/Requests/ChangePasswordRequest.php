<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'new_password'     => [
                'required',
                'string',
                'min:8',
                'max:128',
                'confirmed',
                'different:current_password',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&_\-#^])[A-Za-z\d@$!%*?&_\-#^]+$/',
            ],
            'new_password_confirmation' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required'     => 'Password baru wajib diisi.',
            'new_password.min'          => 'Password baru minimal 8 karakter.',
            'new_password.max'          => 'Password baru maksimal 128 karakter.',
            'new_password.confirmed'    => 'Konfirmasi password baru tidak cocok.',
            'new_password.different'    => 'Password baru tidak boleh sama dengan password saat ini.',
            'new_password.regex'        => 'Password baru harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial (@$!%*?&_-#^).',
            'new_password_confirmation.required' => 'Konfirmasi password baru wajib diisi.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Data yang diberikan tidak valid.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}