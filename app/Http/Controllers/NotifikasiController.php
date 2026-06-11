<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Permintaan;
use Illuminate\Support\Facades\Cache;

class NotifikasiController extends Controller
{
    public function readAll()
    {
        Notifikasi::whereJsonContains('for_roles', auth()->user()->role)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Clear cache notif untuk user ini
        Cache::forget('notif_' . auth()->user()->role . '_' . auth()->id());

        return back();
    }

    public function detail($permintaanId)
    {
        Notifikasi::where('permintaan_id', $permintaanId)->update(['is_read' => true]);

        // Clear cache notif untuk semua role yang mungkin terima notif ini
        Cache::forget('notif_' . auth()->user()->role . '_' . auth()->id());

        $permintaan = Permintaan::with('details.barang')->findOrFail($permintaanId);

        return view('admin.notifikasi.detail', compact('permintaan'));
    }
}
