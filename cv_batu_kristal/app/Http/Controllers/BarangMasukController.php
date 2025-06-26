<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
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

    public function create()
    {
        $barangs = Barang::all();
        $last = BarangMasuk::orderBy('id_masuk', 'desc')->first();
        $nextNumber = $last && preg_match('/M(\d+)/', $last->id_masuk, $match) ? intval($match[1]) + 1 : 1;
        $newIdMasuk = 'M' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('barang_masuk.create', compact('barangs', 'newIdMasuk'));
    }

    public function store(Request $request)
    {
        $details = $request->barang;
        $barangIds = collect($details)->pluck('id_barang')->toArray();

        $barangsBelumDisetujui = Barang::whereIn('id_barang', $barangIds)
            ->where('status', '!=', 'disetujui')
            ->get(['id_barang', 'nama_barang']);

        if ($barangsBelumDisetujui->count() > 0) {
            $idsBelum = $barangsBelumDisetujui->pluck('id_barang')->toArray();

            $barangValid = collect($details)->reject(function ($item) use ($idsBelum) {
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

        $barangMasuk = BarangMasuk::create([
            'id_masuk' => $request->id_masuk,
            'tgl_masuk' => $request->tgl_masuk,
        ]);

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

    public function show(BarangMasuk $barangMasuk)
    {
        $barangMasuk->load('detailBarangMasuk.barang');
        return view('barang_masuk.show', compact('barangMasuk'));
    }

    public function edit($id_masuk)
    {
        $barangMasuk = BarangMasuk::with('detailBarangMasuk')->where('id_masuk', $id_masuk)->firstOrFail();
        $barangs = Barang::all();

        return view('barang_masuk.edit', compact('barangMasuk', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);

        $request->validate([
            'tgl_masuk' => 'required|date',
            'detail' => 'required|array',
            'detail.*.id_detail_masuk' => 'required|exists:detail_barang_masuks,id_detail_masuk',
            'detail.*.jumlah' => 'required|integer|min:1',
            'detail.*.satuan' => 'required|string',
        ]);

        DB::transaction(function () use ($barangMasuk, $request) {
            $barangMasuk->update([
                'tgl_masuk' => $request->tgl_masuk,
            ]);

            foreach ($request->detail as $item) {
                DetailBarangMasuk::where('id_detail_masuk', $item['id_detail_masuk'])->update([
                    'jumlah' => $item['jumlah'],
                    'satuan' => $item['satuan'],
                ]);
            }
        });

        return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil diperbarui.');
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        DetailBarangMasuk::where('id_masuk', $barangMasuk->id_masuk)->delete();
        $barangMasuk->delete();

        return redirect()->route('barang_masuk.index')->with('success', 'Data barang masuk berhasil dihapus.');
    }
}