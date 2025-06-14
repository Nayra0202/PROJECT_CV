<?php

use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\DetailBarangKeluarController;
use App\Http\Controllers\SuratJalanController;
use App\Http\Controllers\DetailBarangMasukController;
use App\Http\Controllers\DetailPermintaanController;
use App\Http\Controllers\DetailSuratJalanController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [PermintaanController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Resource route untuk menu utama
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('barang', BarangController::class);
    Route::resource('barang_masuk', BarangMasukController::class);
    Route::resource('permintaan', PermintaanController::class);
    Route::resource('barang_keluar', BarangKeluarController::class);
    Route::resource('surat_jalan', SuratJalanController::class); 
    Route::resource('detail_barang_masuk', DetailBarangMasukController::class);
    Route::resource('detail_permintaan', DetailPermintaanController::class);
    Route::resource('detail_barang_keluar', DetailBarangKeluarController::class);
    Route::resource('detail_surat_jalans', DetailSuratJalanController::class);
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/api/permintaan/{id}/barangs', [BarangKeluarController::class, 'getBarangByPermintaan']);
    Route::get('/surat_jalan/cetak/{id}', [SuratJalanController::class, 'cetak'])->name('surat_jalan.cetak');
    Route::get('/laporan/barang', [LaporanController::class, 'laporanBarang'])->name('laporan.barang');
    Route::get('/laporan/barang/cetak', [LaporanController::class, 'cetakLaporanBarang'])->name('laporan.barang.cetak');
    Route::get('/laporan/permintaan', [LaporanController::class, 'laporanPermintaan'])->name('laporan.permintaan');
    Route::get('/laporan/permintaan/cetak', [LaporanController::class, 'cetakLaporanPermintaan'])->name('laporan.permintaan.cetak');
});

require __DIR__.'/auth.php';



