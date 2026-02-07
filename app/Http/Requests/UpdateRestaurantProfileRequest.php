<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRestaurantProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'brand_name' => ['sometimes', 'string', 'min:2', 'max:120'],
            'tagline' => ['sometimes', 'nullable', 'string', 'max:160'],
            'about' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'address' => ['sometimes', 'nullable', 'string', 'max:2000'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'whatsapp_number' => ['sometimes', 'nullable', 'string', 'max:30'],
            'email' => ['sometimes', 'nullable', 'email:rfc,dns', 'max:190'],
            'instagram_url' => ['sometimes', 'nullable', 'url', 'max:255'],
            'google_maps_embed' => ['sometimes', 'nullable', 'string', 'max:8000'],
            'opening_hours_text' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'brand_name' => is_string($this->brand_name) ? trim($this->brand_name) : $this->brand_name,
            'tagline' => is_string($this->tagline) ? trim($this->tagline) : $this->tagline,
        ]);
    }
}
