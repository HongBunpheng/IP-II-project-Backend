<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'place_id',
    ];

    // Include Place relationship
    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
