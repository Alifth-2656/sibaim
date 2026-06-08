<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanStokHabis extends Model
{
    protected $table = 'laporan_stok_habis';

    protected $fillable = [
        'barang_id', 'pic', 'commodity', 'qty_diminta', 'type', 'status', 'catatan_admin',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
