<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PermintaanBarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelolaBarangController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'comodity' => redirect()->route('comodity.dashboard'),
            default    => abort(403),
        };
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH (Guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.process');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| NOTIFIKASI — accessible semua role
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::post('/read-all', [NotifikasiController::class, 'readAll'])->name('readAll');
    Route::get('/detail/{id}', [NotifikasiController::class, 'detail'])->name('detail');
});

/*
|--------------------------------------------------------------------------
| ADMIN (includes all improvement features)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('dashboard');

    // Inventory (pakai view improvement)
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

    // Kelola Barang
    Route::prefix('kelola_barang')->name('kelola_barang.')->group(function () {
        Route::get('/', [KelolaBarangController::class, 'index'])->name('index');
        Route::get('/tambah', [KelolaBarangController::class, 'create'])->name('create');
        Route::post('/tambah', [KelolaBarangController::class, 'store'])->name('store');
        Route::get('/stok', [KelolaBarangController::class, 'stok'])->name('stok');
        Route::post('/stok', [KelolaBarangController::class, 'storeStok'])->name('stok.store');
        Route::get('/pindah', [KelolaBarangController::class, 'pindah'])->name('pindah');
        Route::post('/pindah', [KelolaBarangController::class, 'updatePindah'])->name('pindah.update');
        Route::get('/keluar', [KelolaBarangController::class, 'keluar'])->name('keluar');
        Route::post('/keluar', [KelolaBarangController::class, 'storeKeluar'])->name('out.store');
        Route::get('/sto', [KelolaBarangController::class, 'sto'])->name('sto');
        Route::post('/sto/check', [KelolaBarangController::class, 'checkSto'])->name('sto.check');
        Route::post('/sto/confirm', [KelolaBarangController::class, 'confirmSto'])->name('sto.confirm');
        Route::post('/sto/discard-draft', [KelolaBarangController::class, 'discardStoDraft'])->name('sto.discard_draft');
        Route::get('/edit', [KelolaBarangController::class, 'editBarang'])->name('edit');
        Route::put('/edit', [KelolaBarangController::class, 'updateBarang'])->name('edit.update');
    });

    // History
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [HistoryController::class, 'index'])->name('index');
        Route::get('/in', [HistoryController::class, 'inIndex'])->name('in.index');
        Route::get('/in/export', [HistoryController::class, 'exportIn'])->name('in.export');
        Route::get('/out', [HistoryController::class, 'outIndex'])->name('out.index');
        Route::get('/out/export', [HistoryController::class, 'exportOut'])->name('out.export');
        Route::get('/move', [HistoryController::class, 'moveIndex'])->name('move.index');
        Route::get('/move/export', [HistoryController::class, 'exportMove'])->name('move.export');
        Route::get('/sto', [HistoryController::class, 'stoIndex'])->name('sto.index');
        Route::get('/sto/{id}', [HistoryController::class, 'stoDetail'])->name('sto.detail');
        Route::get('/permintaan', [HistoryController::class, 'historyPermintaan'])->name('permintaan.index');
    });

    // Kelola User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Laporan stok (pakai view improvement)
    Route::get('/laporan/stok', [LaporanController::class, 'laporanStok'])->name('laporan.stok');
    Route::get('/laporan/stok/export', [LaporanController::class, 'exportStok'])->name('laporan.stok.export');

    // Laporan stok habis
    Route::get('/laporan/stok-habis', [LaporanController::class, 'stokHabis'])->name('laporan.stok_habis');
    Route::patch('/laporan/stok-habis/{id}/tangani', [LaporanController::class, 'tanganiStokHabis'])->name('laporan.stok_habis.tangani');

    // Pending permintaan
    Route::get('/laporan/pending-permintaan', [LaporanController::class, 'pendingPermintaan'])->name('laporan.pending_permintaan');
    Route::patch('/laporan/permintaan/{id}/konfirmasi', [LaporanController::class, 'konfirmasiPermintaan'])->name('laporan.permintaan.konfirmasi');

    Route::get('/laporan/check-daily', [LaporanController::class, 'checkDaily'])->name('laporan.check_daily');
});

/*
|--------------------------------------------------------------------------
| COMODITY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:comodity'])->group(function () {
    Route::prefix('comodity')->name('comodity.')->group(function () {

        Route::get('/', [DashboardController::class, 'comodity'])->name('dashboard');

        Route::get('/data_barang', [BarangController::class, 'index'])->name('data_barang.index');

        Route::prefix('permintaan')->name('permintaan.')->group(function () {
            Route::get('/', [PermintaanBarangController::class, 'index'])->name('index');
            Route::get('/pilih', [PermintaanBarangController::class, 'pilih'])->name('pilih');
            Route::post('/cek-stok', [PermintaanBarangController::class, 'cekStok'])->name('cek_stok');
            Route::get('/konfirmasi', [PermintaanBarangController::class, 'konfirmasi'])->name('konfirmasi');
            Route::post('/store', [PermintaanBarangController::class, 'store'])->name('store');
            Route::get('/riwayat', [PermintaanBarangController::class, 'riwayat'])->name('riwayat');
        });
    });
});
