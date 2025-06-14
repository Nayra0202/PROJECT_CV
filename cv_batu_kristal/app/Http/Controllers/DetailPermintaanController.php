<?php

namespace App\Http\Controllers;

use App\Models\DetailPermintaan;
use App\Models\Barang;
use App\Models\Permintaan;
use Illuminate\Http\Request;

class DetailPermintaanController extends Controller
{
    public function index() 
    {
        $detailPermintaans = DetailPermintaan::with('barang')->where('id_permintaan')->get();
        return view('detail_permintaan.index', compact('detailPermintaans'));
    }
    
    public function create()
    {
        $barangs = Barang::all();
        $permintaans = Permintaan::all();
        return view('detail_permintaan.create', compact('barangs', 'permintaans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_permintaan' => 'required|exists:permintaans,id_permintaan',
            'id_barang' => 'required|exists:barangs,id_barang',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
        ]);

        // Ambil harga barang
        $barang = Barang::where('id_barang', $request->id_barang)->first();
        $total_harga = $barang->harga * $request->jumlah;

        // Simpan detail permintaan
        DetailPermintaan::create([
            'id_permintaan' => $request->id_permintaan,
            'id_barang' => $request->id_barang,
            'jumlah' => $request->jumlah,
            'total_harga' => $total_harga,
            'satuan' => $request->satuan,
        ]);

        // Update total_bayar di tabel permintaans
        $permintaan = Permintaan::where('id_permintaan', $request->id_permintaan)->first();
        $permintaan->total_bayar += $total_harga;
        $permintaan->save();

        return redirect()->route('permintaan.index')->with('success', 'Detail permintaan berhasil ditambahkan.');
    }

    public function show(DetailPermintaan $detailPermintaan)
    {
        return view('detail_permintaan.show', compact('detailPermintaan'));
    }

    public function edit(DetailPermintaan $detailPermintaan)
    {
        $barangs = Barang::all();
        return view('detail_permintaan.edit', compact('detailPermintaan', 'barangs'));
    }

    public function update(Request $request, DetailPermintaan $detailPermintaan)
    {
        $request->validate([
            'id_barang' => 'required|exists:barangs,id_barang',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
        ]);

        // Hitung total_harga baru
        $barang = Barang::where('id_barang', $request->id_barang)->first();
        $newTotalHarga = $barang->harga * $request->jumlah;

        // Hitung selisih dengan total_harga sebelumnya
        $selisih = $newTotalHarga - $detailPermintaan->total_harga;

        // Update detail permintaan
        $detailPermintaan->update([
            'id_barang' => $request->id_barang,
            'jumlah' => $request->jumlah,
            'total_harga' => $newTotalHarga,
            'satuan' => $request->satuan,
        ]);

        // Update total_bayar di permintaan
        $permintaan = $detailPermintaan->permintaan;
        $permintaan->total_bayar += $selisih;
        $permintaan->save();

        return redirect()->route('permintaan.index')->with('success', 'Detail permintaan berhasil diupdate.');
    }

    public function destroy(DetailPermintaan $detailPermintaan)
    {
        // Kurangi total_bayar di permintaan sebelum hapus
        $permintaan = $detailPermintaan->permintaan;
        $permintaan->total_bayar -= $detailPermintaan->total_harga;
        $permintaan->save();

        $detailPermintaan->delete();

        return redirect()->route('permintaan.index')->with('success', 'Barang berhasil dihapus dari permintaan.');
    }
}
