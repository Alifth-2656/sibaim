<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatOut extends Model
{
    protected $table = 'riwayat_outs';
    protected $fillable = [
        'barang_id',
        'qty',
        'pic',
        'barcode',
        'keterangan'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}