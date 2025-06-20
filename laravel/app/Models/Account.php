<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Account extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'dob',
        'address',
        'profile_picture',
        'featured_picture',
        'social_links',
        'bio',
        'nickname',
        'reset_code',
        'reset_code_expires_at',
    ];

    protected $casts = [
        'featured_picture' => 'array',
        'social_links' => 'array',
        'dob' => 'date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'reset_code',
        'reset_code_expires_at',
    ];
    
    public function journals()
    {
        return $this->hasMany(Journal::class);
    }
}
