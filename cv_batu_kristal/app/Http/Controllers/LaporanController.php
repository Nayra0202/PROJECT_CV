<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Permintaan;

class LaporanController extends Controller
{
    public function laporanBarang()
    {
        $barangs = Barang::all();
        return view('laporan.barang', compact('barangs'));
    }

    public function laporanPermintaan()
    {
        $permintaans = Permintaan::with('detailPermintaan')->get();
        return view('laporan.permintaan', compact('permintaans'));
    }

    public function cetakLaporanBarang(Request $request)
    {
        $filter = $request->filter;
        $value = $request->value;

        $barangs = Barang::query();

        if ($filter && $value) {
            if ($filter === 'tanggal') {
                $barangs->whereDate('created_at', $value);
            } elseif ($filter === 'bulan') {
                $barangs->whereMonth('created_at', $value);
            } elseif ($filter === 'tahun') {
                $barangs->whereYear('created_at', $value);
            }
        }

        $barangs = $barangs->get();

        return view('laporan.cetak_barang', compact('barangs', 'filter', 'value'));
    }

    public function permintaan(Request $request)
    {
        $filter = $request->filter;
        $value = $request->value;

        $permintaans = Permintaan::query();

        if ($filter && $value) {
            if ($filter === 'tanggal') {
                $permintaans->whereDate('created_at', $value);
            } elseif ($filter === 'bulan') {
                $permintaans->whereMonth('created_at', $value);
            } elseif ($filter === 'tahun') {
                $permintaans->whereYear('created_at', $value);
            }
        }

        $permintaans = $permintaans->with('detailPermintaan.barang')->get();

        return view('laporan.permintaan', compact('permintaans', 'filter', 'value'));
    }

    public function cetakLaporanPermintaan(Request $request)
    {
        $filter = $request->filter;
        $value = $request->value;

        $permintaans = \App\Models\Permintaan::with('detailPermintaan.barang');

        if ($filter && $value) {
            if ($filter === 'tanggal') {
                $permintaans->whereDate('tanggal', $value);
            } elseif ($filter === 'bulan') {
                $permintaans->whereMonth('tanggal', $value);
            } elseif ($filter === 'tahun') {
                $permintaans->whereYear('tanggal', $value);
            }
        }

        $permintaans = $permintaans->get();

        return view('laporan.cetak_permintaan', compact('permintaans', 'filter', 'value'));
    }


}
