<?php

namespace App\Http\Controllers;

use App\Models\DetailBarangKeluar;
use App\Models\BarangKeluar;
use App\Models\Barang;
use Illuminate\Http\Request;

class DetailBarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detailBarangKeluars = DetailBarangKeluar::with(['barang', 'barangKeluar.permintaan'])->get();
        return view('detail_barang_keluar.index', compact('detailBarangKeluars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::all();
        $barangKeluars = BarangKeluar::all();
        return view('detail_barang_keluar.create', compact('barangs', 'barangKeluars'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_keluar' => 'required|exists:barang_keluars,id_keluar',
            'details' => 'required|array',
            'details.*.id_barang' => 'required|exists:barangs,id',
            'details.*.jumlah' => 'required|integer|min:1',
            'details.*.satuan' => 'required|string|max:50',
        ]);

        foreach ($request->details as $detail) {
            // Simpan detail
            DetailBarangKeluar::create([
                'id_keluar' => $request->id_keluar,
                'id_barang' => $detail['id_barang'],
                'jumlah' => $detail['jumlah'],
                'satuan' => $detail['satuan'],
            ]);

            // Kurangi stok barang
            $barang = Barang::find($detail['id_barang']);
            $barang->stok -= $detail['jumlah'];
            $barang->save();
        }

        return redirect()->route('barang_keluar.index')->with('success', 'Detail barang keluar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DetailBarangKeluar $detailBarangKeluar)
    {
        return view('detail_barang_keluar.show', compact('detailBarangKeluar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetailBarangKeluar $detailBarangKeluar)
    {
        $barangs = Barang::all();
        return view('detail_barang_keluar.edit', compact('detailBarangKeluar', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetailBarangKeluar $detailBarangKeluar)
    {
        $request->validate([
            'id_barang' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
        ]);

        // Kembalikan stok sebelumnya
        $barangLama = Barang::find($detailBarangKeluar->id_barang);
        $barangLama->stok += $detailBarangKeluar->jumlah;
        $barangLama->save();

        // Update detail
        $detailBarangKeluar->update([
            'id_barang' => $request->id_barang,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
        ]);

        // Kurangi stok baru
        $barangBaru = Barang::find($request->id_barang);
        $barangBaru->stok -= $request->jumlah;
        $barangBaru->save();

        return redirect()->route('barang_keluar.index')->with('success', 'Detail barang keluar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetailBarangKeluar $detailBarangKeluar)
    {
        // Tambahkan stok kembali sebelum menghapus
        $barang = Barang::find($detailBarangKeluar->id_barang);
        $barang->stok += $detailBarangKeluar->jumlah;
        $barang->save();

        $detailBarangKeluar->delete();

        return redirect()->route('barang_keluar.index')->with('success', 'Detail barang keluar berhasil dihapus.');
    }
}