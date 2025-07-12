<?php

namespace App\Http\Controllers;

use App\Models\SuratJalan;
use App\Models\Pemesanan;
use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuratJalanController extends Controller
{
    public function index(Request $request)
    {
        // Tambahkan otomatis surat jalan berdasarkan pemesanan disetujui dan belum ada surat jalan
        $pemesanans = Pemesanan::with('barangKeluar.detailBarangKeluar')
            ->where('status', 'Disetujui')
            ->whereNotIn('id_pemesanan', SuratJalan::pluck('id_pemesanan'))
            ->get();

        foreach ($pemesanans as $pemesan) {
            // Pastikan ada barang keluar
            if ($pemesan->barangKeluar) {
                $last = SuratJalan::orderBy('id_surat_jalan', 'desc')->first();
                $nextNumber = ($last && preg_match('/SJ(\d+)/', $last->id_surat_jalan, $match)) ? intval($match[1]) + 1 : 1;
                $newId = 'SJ' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                SuratJalan::create([
                    'id_surat_jalan' => $newId,
                    'id_pemesanan' => $pemesan->id_pemesanan,
                    'tanggal' => Carbon::now(),
                    'nama_pemesan' => $pemesan->nama_pemesan,
                    'alamat' => $pemesan->alamat,
                ]);
            }
        }

        $query = SuratJalan::query();

        if ($request->filterType == 'tanggal' && $request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tgl_surat);
        } elseif ($request->filterType == 'bulan' && $request->filled('bulan_surat')) {
            $bulan = substr($request->bulan_surat, 5, 2);
            $tahun = substr($request->bulan_surat, 0, 4);
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        } elseif ($request->filterType == 'tahun' && $request->filled('tahun_surat')) {
            $query->whereYear('tanggal', $request->tahun_surat);
        }

        $suratJalans = $query->orderBy('tanggal', 'asc')->get();

        return view('surat_jalan.index', compact('suratJalans'));
    }

    public function create()
    {
        $pemesanans = Pemesanan::with('detailPemesanan.barang')->whereNotIn('id_pemesanan', function($query) {
            $query->select('id_pemesanan')->from('surat_jalans');
        })->get();

        $last = SuratJalan::orderBy('id_surat_jalan', 'desc')->first();
        $number = $last ? ((int)substr($last->id_surat_jalan, 2)) + 1 : 1;
        $newId = 'SJ' . str_pad($number, 4, '0', STR_PAD_LEFT);

        return view('surat_jalan.create', compact('pemesanans','newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required|exists:pemesanans,id_pemesanan',
            'id_surat_jalan' => 'required|unique:surat_jalans,id_surat_jalan',
            'tanggal' => 'required|date',
        ]);

        $pemesan = Pemesanan::findOrFail($request->id_pemesanan);

        SuratJalan::create([
            'id_surat_jalan' => $request->id_surat_jalan,
            'id_pemesanan' => $pemesan->id_pemesanan,
            'tanggal'       => $request->tanggal,
            'nama_pemesan'  => $pemesan->nama_pemesan,
            'alamat'        => $pemesan->alamat,
        ]);

        return redirect()->route('surat_jalan.index')->with('success', 'Surat jalan berhasil dibuat.');
    }

    public function show(SuratJalan $suratJalan)
    {
        return view('surat_jalan.show', compact('suratJalan'));
    }

    public function edit(SuratJalan $suratJalan)
    {
        $pemesanans = Pemesanan::all();
        return view('surat_jalan.edit', compact('suratJalan', 'pemesanans'));
    }

    public function update(Request $request, SuratJalan $suratJalan)
    {
        $request->validate([
            'id_pemesanan' => 'required|exists:pemesanans,id_pemesanan|unique:surat_jalans,id_pemesanan,' . $suratJalan->id_surat_jalan . ',id_surat_jalan',
            'tanggal' => 'required|date',
        ]);

        $pemesan = Pemesanan::findOrFail($request->id_pemesanan);

        $suratJalan->update([
            'id_pemesanan' => $pemesan->id_pemesanan,
            'tanggal'       => $request->tanggal,
            'nama_pemesan'  => $pemesan->nama_pemesan,
            'alamat'        => $pemesan->alamat,
        ]);

        return redirect()->route('surat_jalan.index')->with('success', 'Surat jalan berhasil diupdate.');
    }

    public function destroy(SuratJalan $suratJalan)
    {
        $suratJalan->delete();
        return redirect()->route('surat_jalan.index')->with('success', 'Surat jalan berhasil dihapus.');
    }

    public function cetak($id)
    {
        $suratJalan = SuratJalan::with('pemesanan.barangKeluar.detailBarangKeluar.barang')->findOrFail($id);
        return view('surat_jalan.cetak', compact('suratJalan'));
    }

    public function cetakLaporanSuratJalan(Request $request)
    {
        $filter = $request->filter;
        $value = $request->value;

        $suratJalans = SuratJalan::with('pemesanan.barangKeluar.detailBarangKeluar.barang');

        if ($filter && $value) {
            if ($filter === 'tanggal') {
                $suratJalans->whereDate('tanggal', $value);
            } elseif ($filter === 'bulan') {
                $suratJalans->whereMonth('tanggal', $value);
            } elseif ($filter === 'tahun') {
                $suratJalans->whereYear('tanggal', $value);
            }
        }

        $suratJalans = $suratJalans->get();

        return view('laporan.cetak_surat_jalan', compact('suratJalans', 'filter', 'value'));
    }
}