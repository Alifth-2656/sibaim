<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatSto extends Model
{
    protected $table = 'riwayat_stos';

    protected $fillable = [
        'pic',
        'tanggal',
        'total_item',
        'total_match',
        'total_selisih',
    ];

    public function details()
    {
        return $this->hasMany(RiwayatStoDetail::class);
    }
}