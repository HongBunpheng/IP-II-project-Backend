<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'location', 'mentions', 'readTime', 'images'];

    protected $casts = [
        'images' => 'array',
        'mentions' => 'array',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}