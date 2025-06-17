<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Barang;
use App\Models\DetailBarangKeluar;
use App\Models\Permintaan;
use App\Models\DetailPermintaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangKeluar::query();

        if ($request->filterType == 'tanggal' && $request->filled('tgl_keluar')) {
            $query->whereDate('tgl_keluar', $request->tgl_keluar);
        } elseif ($request->filterType == 'bulan' && $request->filled('bulan_keluar')) {
            $bulan = substr($request->bulan_keluar, 5, 2);
            $tahun = substr($request->bulan_keluar, 0, 4);
            $query->whereMonth('tgl_keluar', $bulan)
                  ->whereYear('tgl_keluar', $tahun);
        } elseif ($request->filterType == 'tahun' && $request->filled('tahun_keluar')) {
            $query->whereYear('tgl_keluar', $request->tahun_keluar);
        }

        $barangKeluars = $query->orderBy('tgl_keluar', 'asc')->get();

        return view('barang_keluar.index', compact('barangKeluars'));
    }

    public function create()
    {
        // Ambil id_permintaan yang sudah digunakan di tabel barang_keluars
        $usedPermintaanIds = BarangKeluar::pluck('id_permintaan');

        $permintaans = Permintaan::with('detailPermintaan.barang')
                ->whereNotIn('id_permintaan', $usedPermintaanIds)
                ->get();

        // Membuat ID Keluar otomatis
        $last = BarangKeluar::orderBy('id_keluar', 'desc')->first();
        if ($last && preg_match('/K(\d+)/', $last->id_keluar, $match)) {
            $nextNumber = intval($match[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        $newIdKeluar = 'K' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('barang_keluar.create', compact('permintaans', 'newIdKeluar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_keluar' => 'required|unique:barang_keluars,id_keluar',
            'id_permintaan' => [
                'required',
                'exists:permintaans,id_permintaan',
                function ($attribute, $value, $fail) {
                    if (\App\Models\BarangKeluar::where('id_permintaan', $value)->exists()) {
                        $fail('Permintaan ini sudah digunakan untuk barang keluar.');
                    }
                },
            ],
            'barang' => 'required|array|min:1',
            'barang.*.id_barang' => 'required|exists:barangs,id_barang',
            'barang.*.jumlah' => 'required|integer|min:1',
            'barang.*.satuan' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {
            // Simpan ke tabel barang_keluars (header)
            $barangKeluar = BarangKeluar::create([
                'id_keluar' => $request->id_keluar,
                'id_permintaan' => $request->id_permintaan,
                'tgl_keluar' => now(),
            ]);

            // Simpan detail barang keluar  
            foreach ($request->barang as $data) {
                DetailBarangKeluar::create([
                    'id_keluar' => $barangKeluar->id_keluar,
                    'id_barang' => $data['id_barang'],
                    'jumlah' => $data['jumlah'],
                    'satuan' => $data['satuan'],
                ]);
            }
        });

        return redirect()->route('barang_keluar.index')->with('success', 'Data barang keluar berhasil ditambahkan.');
    }

    public function show(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load('detailBarangKeluar.barang', 'permintaan');
        return view('barang_keluar.show', compact('barangKeluar'));
    }

    public function edit(BarangKeluar $barangKeluar)
    {
        $barangKeluar->load('detailBarangKeluar.barang','permintaan');
        return view('barang_keluar.edit', compact('barangKeluar'));
    }

    public function update(Request $request, BarangKeluar $barangKeluar)
    {
        $request->validate([
            'tgl_keluar' => 'required|date',
        ]);

        $barangKeluar->update([
            'tgl_keluar' => $request->tgl_keluar,
        ]);

        return redirect()->route('barang_keluar.index')->with('success', 'Data barang keluar berhasil diupdate.');
    }

    public function destroy(BarangKeluar $barangKeluar)
    {
        DB::transaction(function () use ($barangKeluar) {
            DetailBarangKeluar::where('id_keluar', $barangKeluar->id_keluar)->delete();
            $barangKeluar->delete();
        });

        return redirect()->route('barang_keluar.index')->with('success', 'Data barang keluar berhasil dihapus.');
    }

    // AJAX API (jika dipakai)
    public function getBarangByPermintaan($id)
    {
        $detailPermintaans = DetailPermintaan::with('barang')
            ->where('id_permintaan', $id)
            ->get()
            ->map(function($item) {
                return [
                    'id_barang' => $item->barang->id_barang,
                    'nama_barang' => $item->barang->nama_barang,
                    'jumlah' => $item->jumlah,
                    'satuan' => $item->satuan,
                ];
            });

        return response()->json($detailPermintaans);
    }
}
