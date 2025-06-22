<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import HasFactory
use Illuminate\Database\Eloquent\Model; // Import Model


class Post extends Model {
    use HasFactory;

    // Mass assignable fields
    protected $fillable = ['user_id', 'title', 'image_path', 'location', 'date'];

    // Define the relationship to User
    public function userprofile() {
        return $this->belongsTo(UserProfile::class);
    }
}
