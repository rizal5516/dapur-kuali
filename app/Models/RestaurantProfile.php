<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'brand_name',
        'tagline',
        'about',
        'address',
        'phone',
        'whatsapp_number',
        'email',
        'instagram_url',
        'google_maps_embed',
        'opening_hours_text',
        'updated_by',
    ];
}
