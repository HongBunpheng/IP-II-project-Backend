<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'promotion',
        'location',
        'address',
        'contact',
        'rating',
        'price',
        'details',
        'image',
        'detail_image',
    ];

    protected $casts = [
    'image' => 'array',
    'detail_image' => 'array',
    'rating' => 'decimal:2',
    'price' => 'decimal:2',
];

}
