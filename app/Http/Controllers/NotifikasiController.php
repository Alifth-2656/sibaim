<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Permintaan;

class NotifikasiController extends Controller
{
    public function readAll()
    {
        Notifikasi::whereJsonContains('for_roles', auth()->user()->role)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back();
    }

    public function detail($permintaanId)
    {
        Notifikasi::where('permintaan_id', $permintaanId)->update(['is_read' => true]);

        $permintaan = Permintaan::with('details.barang')->findOrFail($permintaanId);

        return view('improvement.notifikasi.detail', compact('permintaan'));
    }
}