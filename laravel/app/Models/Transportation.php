<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    protected $fillable = [
        'departure_time',
        'arrival_time',
        'departure_city',
        'arrival_city',
        'distance',
        'travel_time',
        'price'
    ];
}