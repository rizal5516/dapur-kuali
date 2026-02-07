<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'file',
                // hanya image umum, hindari svg untuk MVP (SVG bisa jadi XSS kalau tidak hati-hati)
                'mimes:jpg,jpeg,png,webp',
                'max:5120', // 5MB
            ],
            'folder' => ['nullable', 'string', 'max:50'], // whitelist akan ditangani controller
        ];
    }
}
