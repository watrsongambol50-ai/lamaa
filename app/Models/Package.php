<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name_ar', 'name_en', 'price', 'pieces_included',
        'free_delivery', 'description_ar', 'description_en', 'active',
    ];

    protected $casts = [
        'active'        => 'boolean',
        'free_delivery' => 'boolean',
    ];
}
