<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

/**
 * Clear dashboard cache setiap kali ada perubahan data stok.
 * Dipasang di: Barang, RiwayatIn, RiwayatOut, RiwayatMove, RiwayatSto, Permintaan
 */
class StokObserver
{
    public function created($model): void { $this->clearCache(); }
    public function updated($model): void { $this->clearCache(); }
    public function deleted($model): void { $this->clearCache(); }

    private function clearCache(): void
    {
        Cache::forget('dashboard_improvement_stats');
        Cache::forget('dashboard_admin_stats');
        Cache::forget('dashboard_comodity');
        Cache::forget('dashboard_permintaan_improvement');
        Cache::forget('dashboard_permintaan_admin');
        Cache::forget('dashboard_sto_reminder');
    }
}
