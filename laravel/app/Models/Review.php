<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['account_id', 'reviewable_id', 'reviewable_type', 'rating', 'comment'];

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
