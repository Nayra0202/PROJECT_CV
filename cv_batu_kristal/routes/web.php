<?php

use App\Http\Controllers\BarangController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\DetailBarangKeluarController;
use App\Http\Controllers\SuratJalanController;
use App\Http\Controllers\DetailBarangMasukController;
use App\Http\Controllers\DetailPermintaanController;
use App\Http\Controllers\DetailSuratJalanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailPemesananController;
use App\Models\DetailPemesanan;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Resource route untuk menu utama
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('barang', BarangController::class);
    Route::resource('barang_masuk', BarangMasukController::class);
    Route::resource('pemesanan', PemesananController::class);
    Route::resource('barang_keluar', BarangKeluarController::class);
    Route::resource('surat_jalan', SuratJalanController::class); 
    Route::resource('detail_barang_masuk', DetailBarangMasukController::class);
    Route::resource('detail_pemesanan', DetailPemesananController::class);
    Route::resource('detail_barang_keluar', DetailBarangKeluarController::class);
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->middleware('auth')->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/api/pemesanan/{id}/barangs', [BarangKeluarController::class, 'getBarangByPermintaan']);
    Route::get('/surat_jalan/cetak/{id}', [SuratJalanController::class, 'cetak'])->name('surat_jalan.cetak');
    Route::get('/laporan/barang', [LaporanController::class, 'laporanBarang'])->name('laporan.barang');
    Route::get('/laporan/barang/cetak', [LaporanController::class, 'cetakLaporanBarang'])->name('laporan.barang.cetak');
    Route::get('/laporan/pemesanan', [LaporanController::class, 'laporanPemesanan'])->name('laporan.pemesanan');
    Route::get('/laporan/pemesanan/cetak', [LaporanController::class, 'cetakLaporanPemesanan'])->name('laporan.pemesanan.cetak');
    Route::get('/pemesanan/{id}/cetak', [PemesananController::class, 'cetak'])->name('pemesanan.cetak');
    Route::get('/profil', function () {
        return view('profile.profil');
    })->middleware('auth')->name('profile.show');
}); 

Route::get('/cari-barang', function(Request $request) {
    $barangs = \App\Models\Barang::where('nama_barang', 'like', '%'.$request->q.'%')->get();
    return response()->json($barangs);
});


require __DIR__.'/auth.php';



