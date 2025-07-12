@extends('layouts.main') 

@section('content')
<div class="container mt-4">
 @php
    $pesananBaru = $pemesanans->where('status', 'Menunggu Persetujuan')->count();
    $user = auth()->user();
@endphp

@if($user && $user->role === 'Sekretaris' && $pesananBaru > 0)
    <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>
            Terdapat <strong>{{ $pesananBaru }}</strong> pesanan baru yang belum disetujui.
        </div>
    </div>
@endif


        <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            Daftar Pemesanan Barang
            @php
                $blockedRoles = ['Direktur', 'Sekretaris', 'Bagian Gudang', 'Bagian Pengiriman'];
            @endphp

            @if(!in_array(auth()->user()->role, $blockedRoles))
                <a href="{{ route('pemesanan.create') }}" class="btn btn-primary btn-sm">Tambah Pemesanan</a>
            @endif

        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-4">
                <form action="{{ route('pemesanan.index') }}" method="GET" class="d-flex flex-column gap-3">
                    <label for="filterType" class="form-label" style="font-size: 1.1rem;">Pilih Berdasarkan</label>
                    <div class="d-flex align-items-center gap-3">
                        <select id="filterType" name="filterType" class="form-select form-select-lg" style="width: 180px;">
                            <option value="">Filter</option>
                            <option value="tanggal" {{ request('filterType') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                            <option value="bulan" {{ request('filterType') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                            <option value="tahun" {{ request('filterType') == 'tahun' ? 'selected' : '' }}>Tahun</option>
                        </select>
                        <input type="date" id="inputTanggal" name="tgl_pemesanan" class="form-control form-control-lg" style="width: 200px;" value="{{ request('tgl_pemesanan') }}" {{ request('filterType') == 'tanggal' ? '' : 'disabled' }}>
                        <input type="month" id="inputBulan" name="bulan_pemesanan" class="form-control form-control-lg" style="width: 170px;" value="{{ request('bulan_pemesanan') }}" {{ request('filterType') == 'bulan' ? '' : 'disabled' }}>
                        <input type="number" id="inputTahun" name="tahun_pemesanan" class="form-control form-control-lg" style="width: 130px;" min="2000" max="2099" placeholder="Tahun" value="{{ request('tahun_pemesanan') }}" {{ request('filterType') == 'tahun' ? '' : 'disabled' }}>
                        <button type="submit" class="btn btn-lg btn-primary" style="min-width: 100px;">Cari</button>
                        <a href="{{ route('pemesanan.index') }}" class="btn btn-lg btn-secondary" style="min-width: 100px;">Reset</a>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pemesanan</th>
                            <th>Daftar Barang</th>
                            <th>Tanggal Pemesanan</th>
                            <th>Nama Pemesan</th>
                            <th>Alamat</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemesanans as $pemesanan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pemesanan->id_pemesanan }}</td>
                                <td>
                                    <ul>
                                        @foreach($pemesanan->detailPemesanan as $detail)
                                                @if($detail->barang)
                                                    <li>{{ $detail->barang->nama_barang }} - {{ $detail->jumlah }} {{ $detail->barang->satuan }} (Rp{{ number_format($detail->total_harga, 0, ',', '.') }})</li>
                                                @else
                                                    <li><em>Data barang tidak ditemukan</em></li>
                                                @endif
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $pemesanan->tgl_pemesanan }}</td>
                                <td>{{ $pemesanan->nama_pemesan }}</td>
                                <td>{{ $pemesanan->alamat }}</td>
                                
                                <td>Rp{{ number_format($pemesanan->total_bayar, 0, ',', '.') }}</td>
                                <td>{{ $pemesanan->status }}</td>
                                <td class="d-flex gap-2">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('pemesanan.edit', $pemesanan->id_pemesanan) }}" class="btn btn-sm p-1" title="Edit">
                                        <img src="{{ asset('images/icons/edit.png') }}" alt="Edit" width="20">
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('pemesanan.destroy', $pemesanan->id_pemesanan) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus semua barang di pemesanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm p-1" title="Hapus">
                                            <img src="{{ asset('images/icons/trash.png') }}" alt="Hapus" width="20">
                                        </button>
                                    </form>

                                    {{-- Tombol Cetak --}}
                                    @if(auth()->user()->role === 'Sekretaris')
                                    <a href="{{ route('pemesanan.cetak', $pemesanan->id_pemesanan) }}" target="_blank" class="btn btn-sm p-1" title="Cetak">
                                        <img src="{{ asset('images/icons/printer.png') }}" alt="Cetak" width="20">
                                    </a>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Data pemesanan belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterType = document.getElementById('filterType');
    const inputTanggal = document.getElementById('inputTanggal');
    const inputBulan = document.getElementById('inputBulan');
    const inputTahun = document.getElementById('inputTahun');

    function updateInputs() {
        inputTanggal.disabled = filterType.value !== 'tanggal';
        inputBulan.disabled = filterType.value !== 'bulan';
        inputTahun.disabled = filterType.value !== 'tahun';
    }

    filterType.addEventListener('change', function() {
        inputTanggal.value = '';
        inputBulan.value = '';
        inputTahun.value = '';
        updateInputs();
    });

    updateInputs();
});
</script>
