<?php

namespace App\Http\Controllers;

use App\Models\SuratJalan;
use App\Models\Permintaan;
use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use App\Models\DetailSuratJalan;
use Illuminate\Http\Request;

class SuratJalanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SuratJalan::query();

        if ($request->filterType == 'tanggal' && $request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tgl_surat);
        } elseif ($request->filterType == 'bulan' && $request->filled('bulan_surat')) {
            $bulan = substr($request->bulan_surat, 5, 2);
            $tahun = substr($request->bulan_surat, 0, 4);
            $query->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
        } elseif ($request->filterType == 'tahun' && $request->filled('tahun_surat')) {
            $query->whereYear('tanggal', $request->tahun_surat);
        }

        $suratJalans = $query->orderBy('tanggal', 'asc')->get();

        return view('surat_jalan.index', compact('suratJalans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permintaans = Permintaan::with('detailPermintaan.barang')->whereNotIn('id_permintaan', function($query) {
        $query->select('id_permintaan')
              ->from('surat_jalans');
    })
    ->get();

        // Ambil ID terakhir dari surat jalan
        $last = SuratJalan::orderBy('id_surat_jalan', 'desc')->first();

        if ($last) {
            // Ambil angka dari ID terakhir, misal SJ0005 -> 5
            $number = (int)substr($last->id_surat_jalan, 2) + 1;
        } else {
            $number = 1;
        }

        // Format ID baru, misal SJ0006
        $newId = 'SJ' . str_pad($number, 4, '0', STR_PAD_LEFT);

        return view('surat_jalan.create', compact('permintaans','newId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_permintaan' => 'required|exists:permintaans,id_permintaan',
            'tanggal' => 'required|date',
        ]);

        $permintaan = Permintaan::findOrFail($request->id_permintaan);

        
        $suratJalan = SuratJalan::create([
            'id_surat_jalan' => $request->id_surat_jalan,
            'id_permintaan' => $permintaan->id_permintaan,
            'tanggal'       => $request->tanggal,
            'nama_pemesan'  => $permintaan->nama_pemesan,
            'alamat'  => $permintaan->alamat,
        ]);

        // Ambil detail barang keluar berdasarkan permintaan
        $detailBarangKeluar = DetailBarangKeluar::where('id_permintaan', $permintaan->id_permintaan)->get();

        foreach ($detailBarangKeluar as $item) {
            DetailSuratJalan::create([
                'id_surat_jalan' => $suratJalan->id_surat_jalan,
                'nama_barang'    => $item->nama_barang,
                'jumlah'         => $item->jumlah,
                'satuan'         => $item->satuan,
            ]);
        }

        return redirect()->route('surat_jalan.index')->with('success', 'Surat jalan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratJalan $suratJalan)
    {
        return view('surat_jalan.show', compact('suratJalan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratJalan $suratJalan)
    {
        $permintaans = Permintaan::all();
        return view('surat_jalan.edit', compact('suratJalan', 'permintaans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\SuratJalan $suratJalan)
    {
        $request->validate([
            'id_permintaan' => 'required|exists:permintaans,id_permintaan|unique:surat_jalans,id_permintaan',
            'tanggal' => 'required|date',
        ]);

        $permintaan = \App\Models\Permintaan::findOrFail($request->id_permintaan);
        $barangKeluar = \App\Models\BarangKeluar::where('id_permintaan', $permintaan->id_permintaan)->first();

        $suratJalan->update([
            'id_permintaan' => $permintaan->id_permintaan,
            'tanggal'       => $request->tanggal,
            'nama_pemesan'  => $permintaan->nama_pemesan,
            'nama_barang'   => $barangKeluar ? $barangKeluar->nama_barang : '-',
            'jumlah'        => $barangKeluar ? $barangKeluar->jumlah : 0,
            'satuan'        => $barangKeluar ? $barangKeluar->satuan : '-',
        ]);

        return redirect()->route('surat_jalan.index')->with('success', 'Surat jalan berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratJalan $suratJalan)
    {
        $suratJalan->delete();
        return redirect()->route('surat_jalan.index')->with('success', 'Surat jalan berhasil dihapus.');
    }

    /**
     * Cetak Surat Jalan
     */
    public function cetak($id)
    {
        $suratJalan = SuratJalan::with('permintaan.detailPermintaan.barang')->findOrFail($id);
        return view('surat_jalan.cetak', compact('suratJalan'));
    }

    /**
     * Cetak Laporan Surat Jalan
     */
    public function cetakLaporanSuratJalan(Request $request)
    {
        $filter = $request->filter;
        $value = $request->value;

        $suratJalans = \App\Models\SuratJalan::with('detailSuratJalan.barang');

        if ($filter && $value) {
            if ($filter === 'tanggal') {
                $suratJalans->whereDate('tgl_surat', $value);
            } elseif ($filter === 'bulan') {
                $suratJalans->whereMonth('tgl_surat', $value);
            } elseif ($filter === 'tahun') {
                $suratJalans->whereYear('tgl_surat', $value);
            }
        }

        $suratJalans = $suratJalans->get();

        return view('laporan.cetak_surat_jalan', compact('suratJalans', 'filter', 'value'));
    }
}
