<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photo extends Model
{
    //
        use HasFactory;

    protected $fillable = ['user_id', 'path', 'type'];

    public function userprofile() {
        return $this->belongsTo(UserProfile::class);
    }
}
