<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import HasFactory
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // Import Notifiable

class Userprofile extends Model {
    use HasFactory, Notifiable;

    // Mass assignable fields
    protected $fillable = [
        'name', 'email', 'password',
        'profile_image', 'cover_image',
        'location', 'instagram', 'nickname',
        'phone', 'address', 'dob'
    ];

    // Define the relationship with posts
    public function posts() {
        return $this->hasMany(Post::class);
    }

    // Define the relationship with photos
    public function photos() {
        return $this->hasMany(Photo::class);
    }
}
