<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;

class DashboardController extends Controller
{
    public function index()
    {
        $permintaans = \App\Models\Permintaan::orderBy('tgl_permintaan', 'desc')->take(5)->get();
        $totalPermintaan = \App\Models\Permintaan::count();
        $totalBarang = \App\Models\Barang::count();
        $totalBarangMasuk = \App\Models\BarangMasuk::count();
        $totalBarangKeluar = \App\Models\BarangKeluar::count();

        return view('dashboard', compact(
            'permintaans',
            'totalPermintaan',
            'totalBarang',
            'totalBarangMasuk',
            'totalBarangKeluar'
        ));
    }
}
