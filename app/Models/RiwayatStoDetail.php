<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStoDetail extends Model
{
    protected $table = 'riwayat_sto_details';

    protected $fillable = [
        'riwayat_sto_id',
        'barang_id',
        'qty_sistem',
        'qty_fisik',
        'selisih',
        'is_adjusted',
    ];

    protected $casts = [
        'is_adjusted' => 'boolean',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function riwayatSto()
    {
        return $this->belongsTo(RiwayatSto::class);
    }
}