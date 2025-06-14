<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailBarangMasukController extends Controller
{
    public function index()
    {
        // Menampilkan semua data barang masuk (group by id_masuk)
        $barangMasuks = BarangMasuk::with('detailBarangMasuk.barang')->get();

        return view('detail_barang_masuk.index', compact('barangMasuks'));
    }

    public function create()
    {
        $barangs = Barang::select('id_barang', 'nama_barang', 'satuan')->get();

        // Generate ID Masuk baru
        $last = BarangMasuk::orderBy('id_masuk', 'desc')->first();
        if ($last && preg_match('/M(\d+)/', $last->id_masuk, $match)) {
            $nextNumber = intval($match[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        $newIdMasuk = 'M' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('detail_barang_masuk.create', compact('newIdMasuk', 'barangs'));
    }

    public function store(Request $request)
    {
        // Validasi
    $request->validate([
        'id_masuk' => 'required',
        'tgl_masuk' => 'required|date',
        'barang' => 'required|array',
        'barang.*.id_barang' => 'required|exists:barangs,id_barang',
        'barang.*.jumlah' => 'required|integer|min:1',
        'barang.*.satuan' => 'required|string',
    ]);

    // Simpan ke tabel barang_masuks
    BarangMasuk::create([
        'id_masuk' => $request->id_masuk,
        'tgl_masuk' => $request->tgl_masuk,
    ]);

    // Simpan banyak detail barang masuk
    foreach ($request->barang as $detail) {
        DetailBarangMasuk::create([
            'id_masuk' => $request->id_masuk,
            'id_barang' => $detail['id_barang'],
            'jumlah' => $detail['jumlah'],
            'satuan' => $detail['satuan'],
        ]);
        }

        return redirect()->route('barang_masuk.index')->with('success', 'Data barang masuk berhasil disimpan.');
    }

    public function show($id_masuk)
    {
        $barangMasuk = BarangMasuk::with('details.barang')->where('id_masuk', $id_masuk)->firstOrFail();

        return view('detail_barang_masuk.show', compact('barangMasuk'));
    }

    public function edit($id_masuk)
    {
        $barangMasuk = BarangMasuk::with('details')->where('id_masuk', $id_masuk)->firstOrFail();
        $barangs = Barang::all();

        return view('detail_barang_masuk.edit', compact('barangMasuk', 'barangs'));
    }

    public function update(Request $request, $id_masuk)
    {
        $request->validate([
            'tgl_masuk' => 'required|date',
            'barang.*.id_barang' => 'required|exists:barangs,id_barang',
            'barang.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $id_masuk) {
    $barangMasuk = BarangMasuk::where('id_masuk', $id_masuk)->firstOrFail();
    $barangMasuk->update([
        'tgl_masuk' => $request->tgl_masuk,
    ]);

    // Ambil detail lama untuk rollback stok
    $detailsLama = DetailBarangMasuk::where('id_masuk', $id_masuk)->get();
    foreach ($detailsLama as $oldDetail) {
        Barang::where('id_barang', $oldDetail->id_barang)->decrement('stok', $oldDetail->jumlah);
        }

    // Hapus detail lama
    DetailBarangMasuk::where('id_masuk', $id_masuk)->delete();

    // Simpan dan update stok baru
    foreach ($request->barang as $item) {
        DetailBarangMasuk::create([
            'id_masuk' => $id_masuk,
            'id_barang' => $item['id_barang'],
            'jumlah' => $item['jumlah'],
            'satuan' => $item['satuan'],
        ]);

        $barang = Barang::where('id_barang', $item['id_barang'])->first();
        if ($barang) {
            $barang->increment('stok', $item['jumlah']);
        }
    }
});


        return redirect()->route('detail_barang_masuk.index')->with('success', 'Data berhasil diupdate.');
    }

    public function destroy($id_masuk)
    {
        DB::transaction(function () use ($id_masuk) {
        $details = DetailBarangMasuk::where('id_masuk', $id_masuk)->get();

        foreach ($details as $detail) {
            Barang::where('id_barang', $detail->id_barang)->decrement('stok', $detail->jumlah);
        }

        DetailBarangMasuk::where('id_masuk', $id_masuk)->delete();
        BarangMasuk::where('id_masuk', $id_masuk)->delete();
    });

        return redirect()->route('detail_barang_masuk.index')->with('success', 'Data berhasil dihapus.');
    }
}
