<?php

namespace App\Http\Controllers;

use App\Models\Permintaan;
use App\Models\DetailPermintaan;
use App\Models\Barang;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    public function index()
    {
        $permintaans = Permintaan::with('detailPermintaan.barang')->get();
        return view('permintaan.index', compact('permintaans'));
    }

    public function create()
    {
        $last = Permintaan::orderBy('id_permintaan', 'desc')->first();
        if ($last) {
            $lastNumber = intval(substr($last->id_permintaan, 1));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newIdPermintaan = 'P' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        $barangs = Barang::all();

        return view('permintaan.create', compact('newIdPermintaan', 'barangs'));
    }

    public function store(Request $request)
{
    $request->validate([
        'barang.*.id_barang' => 'required|exists:barangs,id_barang',
        'barang.*.jumlah' => 'required|integer|min:1',
        'barang.*.satuan'    => 'required|string',
        'id_permintaan' => 'required|unique:permintaans,id_permintaan',
        'nama_pemesan' => 'required|string|max:255',
        'alamat' => 'required|string',
        'tgl_permintaan' => 'required|date',
    ]);

    $permintaan = Permintaan::create([
        'id_permintaan' => $request->id_permintaan,
        'nama_pemesan' => $request->nama_pemesan,
        'alamat' => $request->alamat,
        'tgl_permintaan' => $request->tgl_permintaan,
        'status' => 'Menunggu Persetujuan',
        'total_bayar' => 0,
    ]);

    $totalBayar = 0;
    $totalJumlah = 0;

    foreach ($request->barang as $item) {
        $barang = Barang::where('id_barang', $item['id_barang'])->first();
        $jumlah = $item['jumlah'];
        $satuan = $item['satuan'];
        $total_harga = $barang->harga * $jumlah;

        DetailPermintaan::create([
            'id_permintaan' => $permintaan->id_permintaan,
            'id_barang' => $item['id_barang'],
            'jumlah' => $jumlah,
            'satuan' => $satuan,
            'total_harga' => $total_harga,
        ]);

        $totalBayar += $total_harga;
        $totalJumlah += $jumlah;
    }

    $permintaan->update([
        'jumlah' => $totalJumlah,
        'total_bayar' => $totalBayar,
    ]);

    return redirect()->route('permintaan.index')->with('success', 'Data permintaan berhasil ditambahkan.');
}


    public function show(Permintaan $permintaan)
    {
        return view('permintaan.show', compact('permintaan'));
    }

    public function edit(Permintaan $permintaan)
    {
        $permintaan->load('detailPermintaan.barang');
        $barangs = Barang::all(); // ✅ Ambil semua barang

        // Hitung total_harga per detail (misal harga satuan * jumlah)
        foreach ($permintaan->detailPermintaan as $detail) {
            $detail->total_harga = $detail->jumlah * $detail->barang->harga_satuan; // sesuaikan atribut harga
        }

        return view('permintaan.edit', compact('permintaan', 'barangs'));
    }

    public function update(Request $request, Permintaan $permintaan)
    {
        $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'tgl_permintaan' => 'required|date',
            'status' => 'required|string',
        ]);

        $permintaan->update([
            'nama_pemesan' => $request->nama_pemesan,
            'alamat' => $request->alamat,
            'tgl_permintaan' => $request->tgl_permintaan,
            'status' => $request->status,
        ]);

        return redirect()->route('permintaan.index')->with('success', 'Data permintaan berhasil diupdate.');
    }

    public function destroy(Permintaan $permintaan)
    {
        // Hapus juga detail permintaan terkait
        foreach ($permintaan->detailPermintaan as $detail) {
            $detail->delete();
        }

        $permintaan->delete();

        return redirect()->route('permintaan.index')->with('success', 'Data permintaan berhasil dihapus.');
    }

    public function dashboard()
    {
        $permintaanTerbaru = Permintaan::with('detailPermintaan.barang')->latest('tgl_permintaan')->take(5)->get();
        return view('dashboard', compact('permintaanTerbaru'));
    }
}
