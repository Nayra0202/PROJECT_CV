<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\Barang;

class DashboardController extends Controller
{
    public function index()
    {
        $barangs = Barang::whereNotNull('gambar')->where('status', '!=', 'Menunggu')->get(); // ambil semua barang yang punya gambar

        $permintaans = \App\Models\Permintaan::orderBy('tgl_permintaan', 'desc')->take(5)->get();
        $totalPermintaan = \App\Models\Permintaan::count();
        $totalBarang = \App\Models\Barang::count();
        $totalBarangMasuk = \App\Models\BarangMasuk::count();
        $totalBarangKeluar = \App\Models\BarangKeluar::count();


        // 👉 Tambahkan baris ini:
        $pemesananBaru = Permintaan::where('status', 'Menunggu Persetujuan')->count();

        return view('dashboard', compact(
            'pemesananBaru',
            'barangs',
            'permintaans',
            'totalPermintaan',
            'totalBarang',
            'totalBarangMasuk',
            'totalBarangKeluar'
        ));
    }
}
