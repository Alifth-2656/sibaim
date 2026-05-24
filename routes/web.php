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
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\UserController;



/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->role . '.dashboard');
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
| NOTIFIKASI — ✅ Dipindah ke dalam middleware auth, accessible semua role
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::post('/read-all', [NotifikasiController::class, 'readAll'])->name('readAll');
    Route::get('/detail/{id}', [NotifikasiController::class, 'detail'])->name('detail');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('dashboard');

    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

    // Laporan (bikin dulu placeholder biar route-nya ada)
    Route::get('/laporan/permintaan', function () {
        return view('admin.laporan.permintaan');
    })->name('laporan.permintaan');

    Route::get('/laporan/stok', function () {
        return view('admin.laporan.stok');
    })->name('laporan.stok');

    // Kelola User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
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
            Route::post('/store', [PermintaanBarangController::class, 'store'])->name('store');
            Route::get('/riwayat', [PermintaanBarangController::class, 'riwayat'])->name('riwayat');
        });
    });
});

/*
|--------------------------------------------------------------------------
| IMPROVEMENT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:improvement'])->group(function () {   // ✅ Tambah middleware
    Route::prefix('improvement')->name('improvement.')->group(function () {

        Route::get('/', [DashboardController::class, 'improvement'])->name('dashboard');

        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');

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
        });

        Route::prefix('history')->name('history.')->group(function () {
            Route::get('/', [HistoryController::class, 'index'])->name('index');
            Route::get('/in', [HistoryController::class, 'inIndex'])->name('in.index');
            Route::get('/in/export', [HistoryController::class, 'exportIn'])->name('in.export');
            Route::get('/out', [HistoryController::class, 'outIndex'])->name('out.index');
            Route::get('/out/export', [HistoryController::class, 'exportOut'])->name('out.export');
            Route::get('/move', [HistoryController::class, 'moveIndex'])->name('move.index');
            Route::get('/move/export', [HistoryController::class, 'exportMove'])->name('move.export');
        });
    });
});
