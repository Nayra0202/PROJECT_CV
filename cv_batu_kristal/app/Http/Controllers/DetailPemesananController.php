<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPemesanan;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class DetailPemesananController extends Controller
{
    public function index() 
    {
        $detailPemesanans = DetailPemesanan::with('barang')->where('id_pemesanan')->get();
        return view('detail_pemesanan.index', compact('detailPemesanans'));
    }
    
    public function create()
    {
        $barangs = Barang::all();
        $pemesanans = Pemesanan::all();
        return view('detail_pemesanan.create', compact('barangs', 'pemesanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required|exists:pemesanans,id_pemesanan',
            'id_barang' => 'required|exists:barangs,id_barang',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
        ]);

        // Ambil harga barang
        $barang = Barang::where('id_barang', $request->id_barang)->first();
        $total_harga = $barang->harga * $request->jumlah;

        // Simpan detail pemesanan
        DetailPemesanan::create([
            'id_pemesanan' => $request->id_pemesanan,
            'id_barang' => $request->id_barang,
            'jumlah' => $request->jumlah,
            'total_harga' => $total_harga,
            'satuan' => $request->satuan,
        ]);

        // Update total_bayar di tabel pemesanans
        $pemesanan = Pemesanan::where('id_pemesanan', $request->id_pemesanan)->first();
        $pemesanan->total_bayar += $total_harga;
        $pemesanan->save();

        return redirect()->route('pemesanan.index')->with('success', 'Detail pemesanan berhasil ditambahkan.');
    }

    public function show(DetailPemesanan $detailPemesanan)
    {
        return view('detail_pemesanan.show', compact('detailPemesanan'));
    }

    public function edit(DetailPemesanan $detailPemesanan)
    {
        $barangs = Barang::all();
        return view('detail_pemesanan.edit', compact('detailPemesanan', 'barangs'));
    }

    public function update(Request $request, DetailPemesanan $detailPemesanan)
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
        $selisih = $newTotalHarga - $detailPemesanan->total_harga;

        // Update detail pemesanan
        $detailPemesanan->update([
            'id_barang' => $request->id_barang,
            'jumlah' => $request->jumlah,
            'total_harga' => $newTotalHarga,
            'satuan' => $request->satuan,
        ]);

        // Update total_bayar di pemesanan
        $pemesanan = $detailPemesanan->pemesanan;
        $pemesanan->total_bayar += $selisih;
        $pemesanan->save();

        return redirect()->route('pemesanan.index')->with('success', 'Detail pemesanan berhasil diupdate.');
    }

    public function destroy(DetailPemesanan $detailPemesanan)
    {
        // Kurangi total_bayar di pemesanan sebelum hapus
        $pemesanan = $detailPemesanan->pemesanan;
        $pemesanan->total_bayar -= $detailPemesanan->total_harga;
        $pemesanan->save();

        $detailPemesanan->delete();

        return redirect()->route('pemesanan.index')->with('success', 'Barang berhasil dihapus dari pemesanan.');
    }
}
