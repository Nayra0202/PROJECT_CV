<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Menampilkan daftar barang masuk, digroup berdasarkan ID masuk
    public function index(Request $request)
    {
        $query = BarangMasuk::query();

        if ($request->filterType == 'tanggal' && $request->filled('tgl_masuk')) {
            $query->whereDate('tgl_masuk', $request->tgl_masuk);
        } elseif ($request->filterType == 'bulan' && $request->filled('bulan_masuk')) {
            $bulan = substr($request->bulan_masuk, 5, 2);
            $tahun = substr($request->bulan_masuk, 0, 4);
            $query->whereMonth('tgl_masuk', $bulan)
                  ->whereYear('tgl_masuk', $tahun);
        } elseif ($request->filterType == 'tahun' && $request->filled('tahun_masuk')) {
            $query->whereYear('tgl_masuk', $request->tahun_masuk);
        }

        $barangMasuks = $query->orderBy('id_masuk', 'asc')->get();

        return view('barang_masuk.index', compact('barangMasuks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // Menampilkan form input barang masuk
    public function create()
    {
        $barangs = Barang::all(); // Ambil semua barang

        // Membuat ID Masuk otomatis
        $last = BarangMasuk::orderBy('id_masuk', 'desc')->first();
        if ($last && preg_match('/M(\d+)/', $last->id_masuk, $match)) {
            $nextNumber = intval($match[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        $newIdMasuk = 'M' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('barang_masuk.create', compact('barangs','newIdMasuk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // Menyimpan data barang masuk ke database
    public function store(Request $request)
    {
        // Ambil semua detail barang dari input
        $details = $request->barang;

        // Ambil semua id barang dari input
        $barangIds = collect($details)->pluck('id_barang')->toArray();

        // Ambil barang yang belum disetujui
        $barangsBelumDisetujui = \App\Models\Barang::whereIn('id_barang', $barangIds)
            ->where('status', '!=', 'disetujui')
            ->get(['id_barang', 'nama_barang']);

        if ($barangsBelumDisetujui->count() > 0) {
            $idsBelum = $barangsBelumDisetujui->pluck('id_barang')->toArray();

            $barangValid = collect($details)->reject(function($item) use ($idsBelum) {
                return in_array($item['id_barang'], $idsBelum);
            })->values()->all();

            $pesan = '';
            foreach ($barangsBelumDisetujui as $b) {
                $pesan .= 'ID Barang ' . $b->id_barang . ' dengan nama barang ' . $b->nama_barang . ' belum divalidasi/disetujui oleh Direktur. Silahkan masukkan data barang masuk yang lain.<br>';
            }

            return redirect()->back()
                ->withInput(['barang' => $barangValid])
                ->with('error', $pesan);
        }

        // Simpan data utama ke tabel barang_masuks
        $barangMasuk = BarangMasuk::create([
            'id_masuk' => $request->id_masuk,
            'tgl_masuk' => $request->tgl_masuk,
        ]);

        // Simpan setiap barang ke tabel detail_barang_masuks
        foreach ($request->barang as $detail) {
            DetailBarangMasuk::create([
                'id_masuk' => $barangMasuk->id_masuk,
                'id_barang' => $detail['id_barang'],
                'jumlah' => $detail['jumlah'],
                'satuan' => $detail['satuan'],
                
            ]);

        }

        return redirect()->route('barang_masuk.index')->with('success', 'Data barang masuk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    // Menampilkan detail 1 transaksi masuk
    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load('detailBarangMasuk.barang');
        return view('barang_masuk.show', compact('barangMasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangMasuk $barang_masuk)
    {
        return view('barang_masuk.edit', ['barangMasuk' => $barang_masuk]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $request->validate([
            'tgl_masuk' => 'required|date',
        ]);

        $barangMasuk->update([
            'tgl_masuk' => $request->tgl_masuk,
        ]);

        return redirect()->route('barang_masuk.edit', $barangMasuk)->with('success', 'Data barang masuk berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BarangMasuk $barangMasuk)
    {
        // Hapus detail terlebih dahulu untuk menjaga integritas data
        DetailBarangMasuk::where('id_masuk', $barangMasuk->id_masuk)->delete();
        $barangMasuk->delete();

        return redirect()->route('barang_masuk.index')->with('success', 'Data barang masuk berhasil dihapus.');
    }
}
