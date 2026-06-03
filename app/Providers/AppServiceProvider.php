<?php

namespace App\Providers;

use App\Models\Barang;
use App\Models\RiwayatIn;
use App\Models\RiwayatOut;
use App\Models\RiwayatMove;
use App\Models\RiwayatSto;
use App\Models\Permintaan;
use App\Observers\StokObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Auto-clear dashboard cache setiap kali data berubah
        Barang::observe(StokObserver::class);
        RiwayatIn::observe(StokObserver::class);
        RiwayatOut::observe(StokObserver::class);
        RiwayatMove::observe(StokObserver::class);
        RiwayatSto::observe(StokObserver::class);
        Permintaan::observe(StokObserver::class);
    }
}
