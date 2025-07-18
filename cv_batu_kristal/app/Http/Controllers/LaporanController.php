<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Pemesanan;


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
                    'id_pemesanan' => $keluar->barangKeluar->id_pemesanan ?? null,
                ];
            }

            // Urutkan berdasarkan tanggal
            ksort($transaksiGabung);

            $stok_awal = 0;
            foreach ($transaksiGabung as $tgl => $trx) {
                $id_pemesanan = isset($trx['keluar']['id_pemesanan']) ? $trx['keluar']['id_pemesanan'] : 'NA';
                $jumlah_masuk = isset($trx['masuk']) ? $trx['masuk']['jumlah'] : 0;
                $tgl_masuk = isset($trx['masuk']) ? $trx['masuk']['tanggal'] : '';
                $satuan_masuk = isset($trx['masuk']) ? $trx['masuk']['satuan'] : '';

                $jumlah_keluar = isset($trx['keluar']) ? $trx['keluar']['jumlah'] : 0;
                $tgl_keluar = isset($trx['keluar']) ? $trx['keluar']['tanggal'] : '';
                $satuan_keluar = isset($trx['keluar']) ? $trx['keluar']['satuan'] : '';

                // TAMPILKAN HANYA JIKA ADA MASUK DAN KELUAR (SEMUA KOMPONEN ADA)
if (
    ($jumlah_masuk > 0 && !empty($tgl_masuk)) ||
    ($jumlah_keluar > 0 && !empty($tgl_keluar))
) {
    $stok_akhir = $stok_awal + $jumlah_masuk - $jumlah_keluar;
    $laporan[] = [
        'id_pemesanan' => $id_pemesanan,
        'id_barang' => $barang->id_barang,
        'nama_barang' => $barang->nama_barang,
        'satuan' => $barang->satuan,
        'stok_awal' => $stok_awal,
        'tgl_masuk' => $tgl_masuk ?: '',
        'jumlah_masuk' => $jumlah_masuk > 0 ? $jumlah_masuk : '',
        'tgl_keluar' => $tgl_keluar ?: '',
        'jumlah_keluar' => $jumlah_keluar > 0 ? $jumlah_keluar : '',
        'stok_akhir' => $stok_akhir,
    ];
    $stok_awal = $stok_akhir;
}
            }
        }

        return view('laporan.barang', compact('laporan'));
    }


    public function laporanPemesanan(Request $request)
    {
        $filterType = $request->filterType;
        $tanggal = $request->tanggal;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $pemesanans = Pemesanan::with('detailPemesanan.barang');

        if ($filterType == 'tanggal' && $tanggal) {
            $pemesanans->whereDate('tanggal', $tanggal);
        }
        if ($filterType == 'bulan' && $bulan) {
            [$tahunBulan, $bulanBulan] = explode('-', $bulan);
            $pemesanans->whereYear('tanggal', $tahunBulan)->whereMonth('tanggal', $bulanBulan);
        }
        if ($filterType == 'tahun' && $tahun) {
            $pemesanans->whereYear('tanggal', $tahun);
        }

        $pemesanans = $pemesanans->get();

        return view('laporan.pemesanan', compact('pemesanans', 'filterType', 'tanggal', 'bulan', 'tahun'));
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

    $laporan = [];

    foreach ($barangs as $barang) {
        $masuks = $barang->detailBarangMasuk()->with('barangMasuk')->get();
        $keluars = $barang->detailBarangKeluar()->with('barangKeluar')->get();

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
                'id_pemesanan' => $keluar->barangKeluar->id_pemesanan ?? null,
            ];
        }

        ksort($transaksiGabung);

        $stok_awal = 0;
        foreach ($transaksiGabung as $tgl => $trx) {
            $id_pemesanan = isset($trx['keluar']['id_pemesanan']) ? $trx['keluar']['id_pemesanan'] : 'NA';
            $jumlah_masuk = isset($trx['masuk']) ? $trx['masuk']['jumlah'] : 0;
            $tgl_masuk = isset($trx['masuk']) ? $trx['masuk']['tanggal'] : '';
            $satuan_masuk = isset($trx['masuk']) ? $trx['masuk']['satuan'] : '';

            $jumlah_keluar = isset($trx['keluar']) ? $trx['keluar']['jumlah'] : 0;
            $tgl_keluar = isset($trx['keluar']) ? $trx['keluar']['tanggal'] : '';
            $satuan_keluar = isset($trx['keluar']) ? $trx['keluar']['satuan'] : '';

            if (
                ($jumlah_masuk > 0 && !empty($tgl_masuk)) ||
                ($jumlah_keluar > 0 && !empty($tgl_keluar))
            ) {
                $stok_akhir = $stok_awal + $jumlah_masuk - $jumlah_keluar;
                $laporan[] = [
                    'id_pemesanan' => $id_pemesanan,
                    'id_barang' => $barang->id_barang,
                    'nama_barang' => $barang->nama_barang,
                    'satuan' => $barang->satuan,
                    'stok_awal' => $stok_awal,
                    'tgl_masuk' => $tgl_masuk ?: '',
                    'jumlah_masuk' => $jumlah_masuk > 0 ? $jumlah_masuk : '',
                    'tgl_keluar' => $tgl_keluar ?: '',
                    'jumlah_keluar' => $jumlah_keluar > 0 ? $jumlah_keluar : '',
                    'stok_akhir' => $stok_akhir,
                ];
                $stok_awal = $stok_akhir;
            }
        }
    }

    return view('laporan.cetak_barang', compact('laporan', 'filter', 'value'));
}


    public function cetakLaporanPemesanan(Request $request)
    {
        $filter = $request->filter;
        $value = $request->value;

        $pemesanans = \App\Models\Pemesanan::with('detailPemesanan.barang');

        if ($filter && $value) {
            if ($filter === 'tanggal') {
                $pemesanans->whereDate('tanggal', $value);
            } elseif ($filter === 'bulan') {
                $pemesanans->whereMonth('tanggal', $value);
            } elseif ($filter === 'tahun') {
                $pemesanans->whereYear('tanggal', $value);
            }
        }

        $pemesanans = $pemesanans->get();

        return view('laporan.cetak_pemesanan', compact('pemesanans', 'filter', 'value'));
    }

    public function index(Request $request)
    {
        $query = Pemesanan::query();

        if ($request->tanggal) {
            $query->whereDate('tanggal_pemesanan', $request->tanggal);
        }
        if ($request->bulan) {
            $query->whereMonth('tanggal_pemesanan', $request->bulan);
        }
        if ($request->tahun) {
            $query->whereYear('tanggal_pemesanan', $request->tahun);
        }

        $pemesanans = $query->get();

        return view('laporan.pemesanan', compact('pemesanans'));
    }
}
