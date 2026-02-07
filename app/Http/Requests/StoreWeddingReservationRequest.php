<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWeddingReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // endpoint public, tidak butuh login
        return true;
    }

    public function rules(): array
    {
        return [
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'time_slot' => ['nullable', Rule::in(['siang', 'malam', 'custom'])],
            'guest_estimate' => ['nullable', 'integer', 'min:0', 'max:5000'],

            'contact_name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['required', 'string', 'min:8', 'max:30'],
            'email' => ['nullable', 'email:rfc,dns', 'max:190'],

            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_date.after_or_equal' => 'Tanggal acara minimal hari ini atau setelahnya.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Trim untuk mencegah input “spasi doang”
        $this->merge([
            'contact_name' => is_string($this->contact_name) ? trim($this->contact_name) : $this->contact_name,
            'phone' => is_string($this->phone) ? trim($this->phone) : $this->phone,
            'notes' => is_string($this->notes) ? trim($this->notes) : $this->notes,
        ]);
    }
}
