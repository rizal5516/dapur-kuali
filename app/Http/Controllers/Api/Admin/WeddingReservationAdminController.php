<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWeddingReservationStatusRequest;
use App\Models\WeddingReservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeddingReservationAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Filter aman (whitelist)
        $status = $request->query('status');
        $allowedStatus = ['pending', 'confirmed', 'rejected', 'cancelled'];

        $query = WeddingReservation::query()->orderByDesc('id');

        if (is_string($status) && in_array($status, $allowedStatus, true)) {
            $query->where('status', $status);
        }

        // Jangan tampilkan data berlebihan (tapi admin tetap butuh detail)
        $items = $query->paginate(20);

        return response()->json($items);
    }

    public function updateStatus(UpdateWeddingReservationStatusRequest $request, int $id): JsonResponse
    {
        $reservation = WeddingReservation::query()->findOrFail($id);

        $reservation->status = $request->validated()['status'];
        $reservation->managed_by = $request->user()->id;
        $reservation->save();

        return response()->json([
            'message' => 'Status reservasi berhasil diperbarui.',
            'data' => $reservation->fresh(),
        ]);
    }
}
