<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PemesananController extends Controller
{
    
    public function index(Request $request)
    {
        // Filter berdasarkan role: jika user adalah Klien, hanya tampilkan permintaannya sendiri
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userId = $user->id;

        $query = Pemesanan::query();

        if ($user && $user->role === 'Klien') {
            $query->where('user_id', $userId);
        }
        
        if ($request->filterType == 'tanggal' && $request->filled('tgl_pemesanan')) {
            $query->whereDate('tgl_pemesanan', $request->tgl_pemesanan);
        } elseif ($request->filterType == 'bulan' && $request->filled('bulan_permesanan')) {
            $bulan = substr($request->bulan_permesanan, 5, 2);
            $tahun = substr($request->bulan_permesanan, 0, 4);
            $query->whereMonth('tgl_pemesanan', $bulan)
                  ->whereYear('tgl_pemesanan', $tahun);
        } elseif ($request->filterType == 'tahun' && $request->filled('tahun_pemesanan')) {
            $query->whereYear('tgl_pemesanan', $request->tahun_pemesanan);
        }

        $pemesanans = $query->with('detailPemesanan.barang')->orderBy('tgl_pemesanan', 'asc')->get();

        return view('pemesanan.index', compact('pemesanans'));
    }

    public function create(Request $request)
    {
        $last = Pemesanan::orderBy('id_pemesanan', 'desc')->first();
        if ($last) {
            $lastNumber = intval(substr($last->id_pemesanan, 1));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newIdPemesanan = 'P' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Tambahkan fitur pencarian barang
        $query = Barang::query();
        if ($request->q) {
            $query->where('nama_barang', 'like', '%' . $request->q . '%');
        }

        //Hanya barang dengan stok > 0 yang ditampilkan
        $barangs = $query->where('stok', '>', 0)->get();

        return view('pemesanan.create', compact('newIdPemesanan', 'barangs'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'barang.*.id_barang' => 'required|exists:barangs,id_barang',
            'barang.*.jumlah' => 'required|integer|min:1',
            'barang.*.satuan'    => 'required|string',
            'id_pemesanan' => 'required|unique:pemesanans,id_pemesanan',
            'nama_pemesan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'tgl_pemesanan' => 'required|date',
        ]);

        $pemesanan = Pemesanan::create([
            'id_pemesanan' => $request->id_pemesanan,
            'user_id' => $user->id,
            'nama_pemesan' => $request->nama_pemesan,
            'alamat' => $request->alamat,
            'tgl_pemesanan' => $request->tgl_pemesanan,
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

            DetailPemesanan::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'id_barang' => $item['id_barang'],
                'jumlah' => $jumlah,
                'satuan' => $satuan,
                'total_harga' => $total_harga,
            ]);

            $totalBayar += $total_harga;
            $totalJumlah += $jumlah;
        }

        $pemesanan->update([
            'jumlah' => $totalJumlah,
            'total_bayar' => $totalBayar,
        ]);

        return redirect()->route('pemesanan.index')->with('success', 'Data pemesanan berhasil ditambahkan.');
    }


    public function show(Pemesanan $pemesanan)
    {
        return view('pemesanan.show', compact('pemesanan'));
    }

    public function edit(Pemesanan $pemesanan)
    {
        $pemesanan->load('detailPemesanan.barang');
        $barangs = Barang::all(); // ✅ Ambil semua barang

        // Hitung total_harga per detail (misal harga satuan * jumlah)
        foreach ($pemesanan->detailPemesanan as $detail) {
            $detail->total_harga = $detail->jumlah * $detail->barang->harga; // sesuaikan atribut harga
        }

        return view('pemesanan.edit', compact('pemesanan', 'barangs'));
    }

public function update(Request $request, Pemesanan $pemesanan)
{
    $user = Auth::user();
    $role = strtolower($user->role);

    $request->validate([
        'nama_pemesan' => 'required|string|max:255',
        'alamat' => 'required|string',
        'tgl_pemesanan' => 'required|date',
    ]);

    $pemesanan->nama_pemesan = $request->nama_pemesan;
    $pemesanan->alamat = $request->alamat;
    $pemesanan->tgl_pemesanan = $request->tgl_pemesanan;

    if ($role !== 'klien') {
        $request->validate(['status' => 'required|string']);
        $pemesanan->status = $request->status;
    }

$totalBayar = 0;

foreach ($request->details as $detail) {
    $detailPemesanan = DetailPemesanan::with('barang')->find($detail['id_detail_pemesanan']);
    if ($detailPemesanan) {
        $jumlah = intval($detail['jumlah']);
        $harga = $detailPemesanan->barang ? $detailPemesanan->barang->harga : 0;

        $detailPemesanan->jumlah = $jumlah;
        $detailPemesanan->total_harga = $jumlah * $harga;
        $detailPemesanan->save();

        $totalBayar += $detailPemesanan->total_harga;
    }
}

$pemesanan->total_bayar = $totalBayar;
$pemesanan->save();

    return redirect()->route('pemesanan.index')->with('success', 'Data pemesanan berhasil diupdate.');
}

    public function destroy(Pemesanan $pemesanan)
    {
        // Hapus juga detail pemesanan terkait
        foreach ($pemesanan->detailPemesanan as $detail) {
            $detail->delete();
        }

        $pemesanan->delete();

        return redirect()->route('pemesanan.index')->with('success', 'Data pemesanan berhasil dihapus.');
    }

    public function dashboard()
    {
        $pemesananTerbaru = Pemesanan::with('detailPemesanan.barang')->latest('tgl_pemesanan')->take(5)->get();
        return view('dashboard', compact('pemesananTerbaru'));
    }

    public function cetak($id)
    {
        $pemesanan = Pemesanan::with('detailPemesanan.barang')->findOrFail($id);
        $tanggalCetak = Carbon::now()->locale('id')->isoFormat('D MMMM YYYY');

        return view('pemesanan.cetak', compact('pemesanan', 'tanggalCetak'));
    }
}
