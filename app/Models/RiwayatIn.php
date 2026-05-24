<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatIn extends Model
{
    // 🔥 PASTIIN INI DI-UNCOMMENT DAN SESUAI DENGAN NAMA DI DATABASE
    protected $table = 'riwayat_ins'; 

    protected $fillable = [
        'barang_id',
        'barcode',
        'qty',
        'pic',
        'keterangan',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}