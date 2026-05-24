<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    // Nama tabel di database (kalo lu pake nama 'barangs', Laravel udah otomatis tau)
    protected $table = 'barangs';

    // Kolom-kolom yang boleh diisi manual
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'qty',
        'alamat',
        'min',
        'max',
        'satuan',
        'image', // 🔥 TAMBAH INI
    ];

    // Tips: Kalo mau bikin perhitungan otomatis, bisa tambahin method di sini
    // Contoh: Cek apakah stok sudah mencapai batas minimum
    public function isStokKritis()
    {
        return $this->qty <= $this->min;
    }
}
