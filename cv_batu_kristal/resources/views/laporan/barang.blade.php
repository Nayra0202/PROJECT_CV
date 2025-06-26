@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Laporan Barang
        </div>
        <div class="card-body">
            <form id="filterForm" action="{{ route('laporan.barang') }}" method="GET" class="d-flex align-items-center gap-2">
                <label for="filterType" class="form-label me-2" style="min-width: 140px;">Pilih Berdasarkan</label>
                <select id="filterType" name="filterType" class="form-select form-select-sm" style="width: 130px;">
                    <option value="">Filter</option>
                    <option value="tanggal" {{ request('filterType') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                    <option value="bulan" {{ request('filterType') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                    <option value="tahun" {{ request('filterType') == 'tahun' ? 'selected' : '' }}>Tahun</option>
                </select>
                <input type="date" id="inputTanggal" name="tanggal" class="form-control form-control-sm" style="width: 130px;" value="{{ request('tanggal') }}" disabled>
                <input type="month" id="inputBulan" name="bulan" class="form-control form-control-sm" style="width: 110px;" value="{{ request('bulan') }}" disabled>
                <input type="number" id="inputTahun" name="tahun" class="form-control form-control-sm" style="width: 80px;" min="2000" max="2099" placeholder="Tahun" value="{{ request('tahun') }}" disabled>
                <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                <a href="{{ route('laporan.barang') }}" class="btn btn-sm btn-secondary">Reset</a>
            </form>

            <div class="table-responsive mt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Stok Awal</th>
                            <th>Tanggal Masuk • Jumlah Masuk</th>
                            <th>Tanggal Keluar • Jumlah Keluar</th>
                            <th>Stok Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $row)
                        <tr>
                            <td>{{ $row['id_barang']?? ''  }}</td>
                            <td>{{ $row['nama_barang'] }}</td>
                            <td>{{ $row['stok_awal'] ?? ''  }}</td>
                            <td>
                                {{ $row['tgl_masuk'] ?? '' }}
                                @if($row['jumlah_masuk'] > 0)
                                    • {{ $row['jumlah_masuk'] }} {{ $row['satuan'] }}
                                @endif
                            </td>
                            <td>
                                {{ $row['tgl_keluar' ] ?? ''  }}
                                @if($row['jumlah_keluar'] > 0)
                                    • {{ $row['jumlah_keluar'] }} {{ $row['satuan'] }}
                                @endif
                            </td>
                            <td>{{ $row['stok_akhir'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Data laporan barang belum tersedia.</td>
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
        updateInputs();
    });
</script>