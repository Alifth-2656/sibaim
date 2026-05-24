<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanDetail extends Model
{
    protected $fillable = [
        'id',
        'permintaan_id',
        'barang_id',
        'qty',
    ];


    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
