<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'category',
        'image_url',
        'alt_text',
        'sort_order',
        'is_active',
        'created_by',
    ];
}
