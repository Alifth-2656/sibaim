<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoDraft extends Model
{
    protected $fillable = ['user_id', 'pic','items', 'results'];

    protected $casts = [
        'results' => 'array',
        'items' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
