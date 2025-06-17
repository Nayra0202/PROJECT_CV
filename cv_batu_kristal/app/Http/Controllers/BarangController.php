<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->filterType == 'tanggal' && $request->filled('tgl_masuk')) {
            $query->whereDate('created_at', $request->tgl_masuk);
        } elseif ($request->filterType == 'bulan' && $request->filled('bulan_masuk')) {
            $query->whereMonth('created_at', substr($request->bulan_masuk, 5, 2))
                  ->whereYear('created_at', substr($request->bulan_masuk, 0, 4));
        } elseif ($request->filterType == 'tahun' && $request->filled('tahun_masuk')) {
            $query->whereYear('created_at', $request->tahun_masuk);
        }

        $barangs = $query->get();

        return view('barang.index', compact('barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil ID terakhir
        $last = Barang::orderBy('id_barang', 'desc')->first();
        if ($last && preg_match('/B(\d+)/', $last->id_barang, $match)) {
            $nextNumber = intval($match[1]) + 1;
        } else {
            $nextNumber = 1;
        }
        $newIdBarang = 'B' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('barang.create', compact('newIdBarang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_barang'   => 'required|string|max:255|unique:barangs,id_barang',
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required',
            'satuan'      => 'required|string|max:50',
        ]);

        $harga = preg_replace('/[^\d]/', '', $request->harga);

        Barang::create([
            'id_barang'     => $request->id_barang,
            'nama_barang'   => $request->nama_barang,
            'harga'         => $harga,
            'stok'          => 0,
            'satuan'        => $request->satuan,
            'status'        => 'Menunggu',
            'keterangan'    => $request->keterangan ?? 'Menunggu Persetujuan',
            'tgl_input'     => now(),
            'tgl_disetujui' => null,
            'id_user'       => Auth::id(),
        ]);

        return redirect()->route('barang.index')->with('success', 'Data barang berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
{
    $request->validate([
        'nama_barang' => 'required|string|max:255',
        'harga'       => 'required',
        'satuan'      => 'required|string|max:50',
        'status'      => 'required|in:Menunggu,Disetujui,Ditolak',
    ]);

    $harga = preg_replace('/[^\d]/', '', $request->harga);

    // Data dasar
    $data = [
        'nama_barang' => $request->nama_barang,
        'harga'       => $harga,
        'satuan'      => $request->satuan,
        'status'      => $request->status,
    ];

    if ($request->status == 'Disetujui') {
        if (!$barang->tgl_disetujui) {
            $data['tgl_disetujui'] = now();
        }
        $data['keterangan'] = 'Sudah Oke';
    } elseif ($request->status == 'Menunggu') {
        $data['keterangan'] = 'Menunggu Persetujuan';
        $data['tgl_disetujui'] = null;
    } elseif ($request->status == 'Ditolak') {
        $data['keterangan'] = 'Ditolak';
        $data['tgl_disetujui'] = null;
    }

    $barang->update($data);

    return redirect()->route('barang.index')->with('success', 'Data barang berhasil diupdate.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Data barang berhasil dihapus.');
    }
}