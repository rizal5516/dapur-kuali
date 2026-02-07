<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'cuisine_type',
        'sort_order',
        'is_active',
        'created_by',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}
