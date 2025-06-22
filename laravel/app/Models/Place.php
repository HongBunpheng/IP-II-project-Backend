<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'province',
        'price',
        'rating',
        'images',
    ];

    // Automatically decode images from JSON when used
    protected $casts = [
        'images' => 'array',
    ];
}