<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Barang;

class DashboardController extends Controller
{
    public function index()
    {
        $barangs = Barang::whereNotNull('gambar')->where('status', '!=', 'Menunggu')->get(); // ambil semua barang yang punya gambar

        $pemesanans = \App\Models\Pemesanan::orderBy('tgl_pemesanan', 'desc')->take(5)->get();
        $totalPemesanan = \App\Models\Pemesanan::count();
        $totalBarang = \App\Models\Barang::count();
        $totalBarangMasuk = \App\Models\BarangMasuk::count();
        $totalBarangKeluar = \App\Models\BarangKeluar::count();


        // 👉 Tambahkan baris ini:
        $pemesananBaru = Pemesanan::where('status', 'Menunggu Persetujuan')->count();
        $barangBelumDisetujui = Barang::where('status', 'Menunggu Persetujuan')->count();

        return view('dashboard', compact(
            'pemesananBaru',
            'barangs',
            'pemesanans',
            'totalPemesanan',
            'totalBarang',
            'totalBarangMasuk',
            'totalBarangKeluar',
            'barangBelumDisetujui'
        ));
    }
}
