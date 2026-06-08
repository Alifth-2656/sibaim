<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    public function details()
    {
        return $this->hasMany(PermintaanDetail::class);
    }

    protected $fillable = [
        'pic',
        'commodity',
        'tanggal',
        'status',
        'alamat',
        'status',
    ];
}
