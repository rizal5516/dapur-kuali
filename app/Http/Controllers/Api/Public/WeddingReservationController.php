<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWeddingReservationRequest;
use App\Models\WeddingReservation;
use Illuminate\Http\JsonResponse;

class WeddingReservationController extends Controller
{
    public function store(StoreWeddingReservationRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Default values aman
        $reservation = WeddingReservation::query()->create([
            'event_date' => $data['event_date'],
            'time_slot' => $data['time_slot'] ?? 'custom',
            'guest_estimate' => $data['guest_estimate'] ?? 0,

            'contact_name' => $data['contact_name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,

            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'managed_by' => null,
        ]);

        // Jangan balikin semua field sensitif/internal (misalnya managed_by) kalau tidak perlu
        return response()->json([
            'message' => 'Permintaan reservasi wedding berhasil dikirim.',
            'data' => [
                'id' => $reservation->id,
                'status' => $reservation->status,
                'event_date' => $reservation->event_date,
                'time_slot' => $reservation->time_slot,
                'guest_estimate' => $reservation->guest_estimate,
                'contact_name' => $reservation->contact_name,
            ],
        ], 201);
    }
}
