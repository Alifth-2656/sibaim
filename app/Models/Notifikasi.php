<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';
    protected $fillable = ['judul', 'pesan', 'tipe', 'permintaan_id', 'for_roles', 'is_read'];

    protected $casts = [
        'for_roles' => 'array',
        'is_read'   => 'boolean',
    ];

    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class);
    }
}