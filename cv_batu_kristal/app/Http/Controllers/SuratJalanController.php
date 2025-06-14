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
    public function index()
    {
        $suratJalans = SuratJalan::with('permintaan.detailPermintaan.barang')->get();
        return view('surat_jalan.index', compact('suratJalans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permintaans = Permintaan::all();
        return view('surat_jalan.create', compact('permintaans'));
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
            'id_permintaan' => $permintaan->id_permintaan,
            'tanggal'       => $request->tanggal,
            'nama_pemesan'  => $permintaan->nama_pemesan,
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
            'id_permintaan' => 'required|exists:permintaans,id_permintaan',
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

    public function cetak($id)
    {
        $suratJalan = SuratJalan::with('permintaan.detailPermintaan.barang')->findOrFail($id);
        return view('surat_jalan.cetak', compact('suratJalan'));
    }
}
