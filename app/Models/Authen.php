<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authen extends Model
{
    protected $fillable = [
    'name', 'email', 'password'
    ];
}
