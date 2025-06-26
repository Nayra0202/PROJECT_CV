<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Permintaan;

class LaporanController extends Controller
{
    public function laporanBarang()
    {
        $barangs = \App\Models\Barang::all();
        $laporan = [];

        foreach ($barangs as $barang) {
            // Ambil semua transaksi masuk dan keluar, urutkan berdasarkan tanggal
            $masuks = $barang->detailBarangMasuk()->with('barangMasuk')->get();
            $keluars = $barang->detailBarangKeluar()->with('barangKeluar')->get();

            // Gabungkan transaksi berdasarkan tanggal
            $transaksiGabung = [];

            foreach ($masuks as $masuk) {
                $tgl = $masuk->barangMasuk->tgl_masuk;
                $transaksiGabung[$tgl]['masuk'] = [
                    'tanggal' => $tgl,
                    'jumlah' => $masuk->jumlah,
                    'satuan' => $masuk->satuan ?? $barang->satuan,
                ];
            }
            foreach ($keluars as $keluar) {
                $tgl = $keluar->barangKeluar->tgl_keluar;
                $transaksiGabung[$tgl]['keluar'] = [
                    'tanggal' => $tgl,
                    'jumlah' => $keluar->jumlah,
                    'satuan' => $keluar->satuan ?? $barang->satuan,
                ];
            }

            // Urutkan berdasarkan tanggal
            ksort($transaksiGabung);

            $stok_awal = 0;
            foreach ($transaksiGabung as $tgl => $trx) {
                $jumlah_masuk = isset($trx['masuk']) ? $trx['masuk']['jumlah'] : 0;
                $tgl_masuk = isset($trx['masuk']) ? $trx['masuk']['tanggal'] : '';
                $satuan_masuk = isset($trx['masuk']) ? $trx['masuk']['satuan'] : '';

                $jumlah_keluar = isset($trx['keluar']) ? $trx['keluar']['jumlah'] : 0;
                $tgl_keluar = isset($trx['keluar']) ? $trx['keluar']['tanggal'] : '';
                $satuan_keluar = isset($trx['keluar']) ? $trx['keluar']['satuan'] : '';

                // TAMPILKAN HANYA JIKA ADA MASUK DAN KELUAR (SEMUA KOMPONEN ADA)
                if (
                    ($jumlah_masuk > 0 && !empty($tgl_masuk)) &&
                    ($jumlah_keluar > 0 && !empty($tgl_keluar))
                ) {
                    $stok_akhir = $stok_awal + $jumlah_masuk - $jumlah_keluar;
                    $laporan[] = [
                        'id_barang' => $barang->id_barang,
                        'nama_barang' => $barang->nama_barang,
                        'satuan' => $barang->satuan,
                        'stok_awal' => $stok_awal,
                        'tgl_masuk' => $tgl_masuk,
                        'jumlah_masuk' => $jumlah_masuk,
                        'tgl_keluar' => $tgl_keluar,
                        'jumlah_keluar' => $jumlah_keluar,
                        'stok_akhir' => $stok_akhir,
                    ];
                    $stok_awal = $stok_akhir;
                } else {
                    // Update stok_awal jika hanya ada masuk atau keluar, tapi tidak tampilkan baris
                    $stok_awal = $stok_awal + $jumlah_masuk - $jumlah_keluar;
                }
            }
        }

        return view('laporan.barang', compact('laporan'));
    }


    public function laporanPermintaan(Request $request)
    {
        $filterType = $request->filterType;
        $tanggal = $request->tanggal;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $permintaans = Permintaan::with('detailPermintaan.barang');

        if ($filterType == 'tanggal' && $tanggal) {
            $permintaans->whereDate('tanggal', $tanggal);
        }
        if ($filterType == 'bulan' && $bulan) {
            [$tahunBulan, $bulanBulan] = explode('-', $bulan);
            $permintaans->whereYear('tanggal', $tahunBulan)->whereMonth('tanggal', $bulanBulan);
        }
        if ($filterType == 'tahun' && $tahun) {
            $permintaans->whereYear('tanggal', $tahun);
        }

        $permintaans = $permintaans->get();

        return view('laporan.permintaan', compact('permintaans', 'filterType', 'tanggal', 'bulan', 'tahun'));
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

    public function index(Request $request)
    {
        $query = Permintaan::query();

        if ($request->tanggal) {
            $query->whereDate('tanggal_permintaan', $request->tanggal);
        }
        if ($request->bulan) {
            $query->whereMonth('tanggal_permintaan', $request->bulan);
        }
        if ($request->tahun) {
            $query->whereYear('tanggal_permintaan', $request->tahun);
        }

        $permintaan = $query->get();

        return view('laporan.permintaan', compact('permintaan'));
    }
}
