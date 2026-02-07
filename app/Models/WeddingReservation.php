<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeddingReservation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_date',
        'time_slot',
        'guest_estimate',
        'contact_name',
        'phone',
        'email',
        'notes',
        'status',
        'managed_by',
    ];
}
