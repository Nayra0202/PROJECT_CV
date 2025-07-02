<?php

namespace App\Http\Controllers;

use App\Models\SuratJalan;
use App\Models\Permintaan;
use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuratJalanController extends Controller
{
    public function index(Request $request)
    {
        // Tambahkan otomatis surat jalan berdasarkan permintaan disetujui dan belum ada surat jalan
        $permintaans = Permintaan::with('barangKeluar.detailBarangKeluar')
            ->where('status', 'Disetujui')
            ->whereNotIn('id_permintaan', SuratJalan::pluck('id_permintaan'))
            ->get();

        foreach ($permintaans as $permintaan) {
            // Pastikan ada barang keluar
            if ($permintaan->barangKeluar) {
                $last = SuratJalan::orderBy('id_surat_jalan', 'desc')->first();
                $nextNumber = ($last && preg_match('/SJ(\d+)/', $last->id_surat_jalan, $match)) ? intval($match[1]) + 1 : 1;
                $newId = 'SJ' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                SuratJalan::create([
                    'id_surat_jalan' => $newId,
                    'id_permintaan' => $permintaan->id_permintaan,
                    'tanggal' => Carbon::now(),
                    'nama_pemesan' => $permintaan->nama_pemesan,
                    'alamat' => $permintaan->alamat,
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
        $permintaans = Permintaan::with('detailPermintaan.barang')->whereNotIn('id_permintaan', function($query) {
            $query->select('id_permintaan')->from('surat_jalans');
        })->get();

        $last = SuratJalan::orderBy('id_surat_jalan', 'desc')->first();
        $number = $last ? ((int)substr($last->id_surat_jalan, 2)) + 1 : 1;
        $newId = 'SJ' . str_pad($number, 4, '0', STR_PAD_LEFT);

        return view('surat_jalan.create', compact('permintaans','newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_permintaan' => 'required|exists:permintaans,id_permintaan',
            'id_surat_jalan' => 'required|unique:surat_jalans,id_surat_jalan',
            'tanggal' => 'required|date',
        ]);

        $permintaan = Permintaan::findOrFail($request->id_permintaan);

        SuratJalan::create([
            'id_surat_jalan' => $request->id_surat_jalan,
            'id_permintaan' => $permintaan->id_permintaan,
            'tanggal'       => $request->tanggal,
            'nama_pemesan'  => $permintaan->nama_pemesan,
            'alamat'        => $permintaan->alamat,
        ]);

        return redirect()->route('surat_jalan.index')->with('success', 'Surat jalan berhasil dibuat.');
    }

    public function show(SuratJalan $suratJalan)
    {
        return view('surat_jalan.show', compact('suratJalan'));
    }

    public function edit(SuratJalan $suratJalan)
    {
        $permintaans = Permintaan::all();
        return view('surat_jalan.edit', compact('suratJalan', 'permintaans'));
    }

    public function update(Request $request, SuratJalan $suratJalan)
    {
        $request->validate([
            'id_permintaan' => 'required|exists:permintaans,id_permintaan|unique:surat_jalans,id_permintaan,' . $suratJalan->id_surat_jalan . ',id_surat_jalan',
            'tanggal' => 'required|date',
        ]);

        $permintaan = Permintaan::findOrFail($request->id_permintaan);

        $suratJalan->update([
            'id_permintaan' => $permintaan->id_permintaan,
            'tanggal'       => $request->tanggal,
            'nama_pemesan'  => $permintaan->nama_pemesan,
            'alamat'        => $permintaan->alamat,
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
        $suratJalan = SuratJalan::with('permintaan.barangKeluar.detailBarangKeluar.barang')->findOrFail($id);
        return view('surat_jalan.cetak', compact('suratJalan'));
    }

    public function cetakLaporanSuratJalan(Request $request)
    {
        $filter = $request->filter;
        $value = $request->value;

        $suratJalans = SuratJalan::with('permintaan.barangKeluar.detailBarangKeluar.barang');

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