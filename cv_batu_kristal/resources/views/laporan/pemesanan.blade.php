@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Laporan Pemesanan Barang
        </div>

        <div>
        <div class="card-body">
            <form id="filterForm" action="{{ route('laporan.pemesanan') }}" method="GET" class="d-flex align-items-center gap-2">
                <label for="filterType" class="form-label me-2" style="min-width: 140px;">Pilih Berdasarkan</label>
                <select id="filterType" name="filterType" class="form-select form-select-sm" style="width: 130px;">
                    <option value="">Filter</option>
                    <option value="tanggal" {{ request('filterType') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                    <option value="bulan" {{ request('filterType') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                    <option value="tahun" {{ request('filterType') == 'tahun' ? 'selected' : '' }}>Tahun</option>
                </select>
                <input type="date" id="inputTanggal" name="tanggal_pemesanan" class="form-control form-control-sm" style="width: 130px;" value="{{ request('tanggal_pemesanan') }}" disabled>
                <input type="month" id="inputBulan" name="bulan_pemesanan" class="form-control form-control-sm" style="width: 110px;" value="{{ request('bulan_pemesanan') }}" disabled>
                <input type="number" id="inputTahun" name="tahun_pemesanan" class="form-control form-control-sm" style="width: 80px;" min="2000" max="2099" placeholder="Tahun" value="{{ request('tahun_pemesanan') }}" disabled>
                <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                <a href="{{ route('laporan.pemesanan') }}" class="btn btn-sm btn-secondary">Reset</a>
                <a href="{{ route('laporan.pemesanan.cetak', array_merge(request()->all(), ['download' => 0])) }}" 
                    target="_blank" 
                    class="btn btn-sm btn-danger">
                    Cetak
                </a>
            </form>
        </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>ID Pemesanan</th>
                            <th>Tanggal Pemesanan</th>
                            <th>Nama Pemesan</th>
                            <th>Alamat</th>
                            <th>Daftar Barang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemesanans as $key => $pemesanan)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $pemesanan->id_pemesanan }}</td>
                            <td>{{ \Carbon\Carbon::parse($pemesanan->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $pemesanan->nama_pemesan ?? '-' }}</td>
                            <td>{{ $pemesanan->alamat }}</td>
                            <td>
                                <ul class="mb-2 ps-3">
                                    @foreach ($pemesanan->detailPemesanan as $detail)
                                        <li>
                                            {{ $detail->barang->nama_barang ?? '-' }} - 
                                            {{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }} 
                                            (Rp{{ number_format($detail->total_harga, 0, ',', '.') }})
                                        </li>
                                    @endforeach
                                </ul>
                                <strong>Total Bayar: Rp{{ number_format($pemesanan->total_bayar ?? 0, 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Data pemesanan belum tersedia.</td>
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
    document.addEventListener('DOMContentLoaded', function () {
        const filterType = document.getElementById('filterType');
        const inputTanggal = document.getElementById('inputTanggal');
        const inputBulan = document.getElementById('inputBulan');
        const inputTahun = document.getElementById('inputTahun');

        function updateInputs() {
            inputTanggal.disabled = filterType.value !== 'tanggal';
            inputBulan.disabled = filterType.value !== 'bulan';
            inputTahun.disabled = filterType.value !== 'tahun';
        }

        filterType.addEventListener('change', updateInputs);

        // Aktifkan input sesuai filter saat reload
        updateInputs();
    });
</script>