<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatMove extends Model
{
    protected $table = 'riwayat_moves';

    protected $fillable = [
        'barang_id',
        'from',
        'to',
        'pic',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
