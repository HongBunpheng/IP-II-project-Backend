<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
    'name',
    'email',
    'password',
    'reset_code',
    'reset_code_expires_at',
];

}
