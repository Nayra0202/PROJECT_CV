<?php

namespace App\Http\Controllers;

use App\Models\SuratJalan;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuratJalanController extends Controller
{
    public function index(Request $request)
    {
        // Buat otomatis surat jalan berdasarkan barang keluar yang belum dibuatkan surat jalan
        $barangKeluars = BarangKeluar::with('pemesanan')
            ->whereNotIn('id_keluar', SuratJalan::pluck('id_keluar'))
            ->get();

        foreach ($barangKeluars as $barangKeluar) {
            $last = SuratJalan::orderBy('id_surat_jalan', 'desc')->first();
            $nextNumber = ($last && preg_match('/SJ(\d+)/', $last->id_surat_jalan, $match)) ? intval($match[1]) + 1 : 1;
            $newId = 'SJ' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            SuratJalan::create([
                'id_surat_jalan' => $newId,
                'id_keluar' => $barangKeluar->id_keluar,
                'tanggal' => Carbon::now(),
                'nama_pemesan' => $barangKeluar->pemesanan->nama_pemesan ?? '-',
                'alamat' => $barangKeluar->pemesanan->alamat ?? '-',
            ]);
        }

        $query = SuratJalan::with('barangKeluar.pemesanan');

        if ($request->filterType == 'tanggal' && $request->filled('tgl_surat')) {
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
        $barangKeluars = BarangKeluar::with('pemesanan')
            ->whereNotIn('id_keluar', SuratJalan::pluck('id_keluar'))
            ->get();

        $last = SuratJalan::orderBy('id_surat_jalan', 'desc')->first();
        $number = $last ? ((int)substr($last->id_surat_jalan, 2)) + 1 : 1;
        $newId = 'SJ' . str_pad($number, 4, '0', STR_PAD_LEFT);

        return view('surat_jalan.create', compact('barangKeluars', 'newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_surat_jalan' => 'required|unique:surat_jalans,id_surat_jalan',
            'id_keluar' => 'required|exists:barang_keluars,id_keluar',
            'tanggal' => 'required|date',
        ]);

        $barangKeluar = BarangKeluar::with('pemesanan')->findOrFail($request->id_keluar);

        SuratJalan::create([
            'id_surat_jalan' => $request->id_surat_jalan,
            'id_keluar' => $barangKeluar->id_keluar,
            'tanggal' => $request->tanggal,
            'nama_pemesan' => $barangKeluar->pemesanan->nama_pemesan ?? '-',
            'alamat' => $barangKeluar->pemesanan->alamat ?? '-',
        ]);

        return redirect()->route('surat_jalan.index')->with('success', 'Surat jalan berhasil dibuat.');
    }

    public function show(SuratJalan $suratJalan)
    {
        return view('surat_jalan.show', compact('suratJalan'));
    }

    public function edit(SuratJalan $suratJalan)
    {
        $barangKeluars = BarangKeluar::with('pemesanan')->get();
        return view('surat_jalan.edit', compact('suratJalan', 'barangKeluars'));
    }

    public function update(Request $request, SuratJalan $suratJalan)
    {
        $request->validate([
            'id_keluar' => 'required|exists:barang_keluars,id_keluar|unique:surat_jalans,id_keluar,' . $suratJalan->id_surat_jalan . ',id_surat_jalan',
            'tanggal' => 'required|date',
        ]);

        $barangKeluar = BarangKeluar::with('pemesanan')->findOrFail($request->id_keluar);

        $suratJalan->update([
            'id_keluar' => $barangKeluar->id_keluar,
            'tanggal' => $request->tanggal,
            'nama_pemesan' => $barangKeluar->pemesanan->nama_pemesan ?? '-',
            'alamat' => $barangKeluar->pemesanan->alamat ?? '-',
        ]);

        return redirect()->route('surat_jalan.index')->with('success', 'Surat jalan berhasil diperbarui.');
    }

    public function destroy(SuratJalan $suratJalan)
    {
        $suratJalan->delete();
        return redirect()->route('surat_jalan.index')->with('success', 'Surat jalan berhasil dihapus.');
    }

    public function cetak($id)
    {
        $suratJalan = SuratJalan::with('barangKeluar.detailBarangKeluar.barang', 'barangKeluar.pemesanan')->findOrFail($id);
        return view('surat_jalan.cetak', compact('suratJalan'));
    }

    public function cetakLaporanSuratJalan(Request $request)
    {
        $filter = $request->filter;
        $value = $request->value;

        $suratJalans = SuratJalan::with('barangKeluar.detailBarangKeluar.barang', 'barangKeluar.pemesanan');

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