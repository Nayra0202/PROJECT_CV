@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            Daftar Barang Keluar
            <a href="{{ route('barang_keluar.create') }}" class="btn btn-primary btn-sm">Tambah Barang Keluar</a>
        </div>
        <div class="card-body">
            <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
            <div class="mb-4">
    <form action="{{ route('barang_keluar.index') }}" method="GET" class="d-flex align-items-center gap-2">
        <label for="filterType" class="form-label me-2" style="min-width: 140px;">Pilih Berdasarkan</label>
        <select id="filterType" name="filterType" class="form-select form-select-sm" style="width: 130px;">
            <option value="">Filter</option>
            <option value="tanggal" {{ request('filterType') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
            <option value="bulan" {{ request('filterType') == 'bulan' ? 'selected' : '' }}>Bulan</option>
            <option value="tahun" {{ request('filterType') == 'tahun' ? 'selected' : '' }}>Tahun</option>
        </select>
        <input type="date" id="inputTanggal" name="tgl_keluar" class="form-control form-control-sm" style="width: 130px;" value="{{ request('tgl_keluar') }}" {{ request('filterType') == 'tanggal' ? '' : 'disabled' }}>
        <input type="month" id="inputBulan" name="bulan_keluar" class="form-control form-control-sm" style="width: 110px;" value="{{ request('bulan_keluar') }}" {{ request('filterType') == 'bulan' ? '' : 'disabled' }}>
        <input type="number" id="inputTahun" name="tahun_keluar" class="form-control form-control-sm" style="width: 80px;" min="2000" max="2099" placeholder="Tahun" value="{{ request('tahun_keluar') }}" {{ request('filterType') == 'tahun' ? '' : 'disabled' }}>
        <button type="submit" class="btn btn-sm btn-primary">Cari</button>
        <a href="{{ route('barang_keluar.index') }}" class="btn btn-sm btn-secondary">Reset</a>
    </form>
</div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Keluar</th>
                            <th>ID Permintaan</th>
                            <th>Daftar Barang</th>
                            <th>Tanggal Keluar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangKeluars as $barangKeluar)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $barangKeluar->id_keluar }}</td>
                            <td>{{ $barangKeluar->permintaan->id_permintaan }}</td>
                            <td>
                                <ul class="mb-0">
                                    @foreach($barangKeluar->detailBarangKeluar as $detail)
                                        <li>{{ $detail->barang->nama_barang }} - {{ $detail->jumlah }} {{ $detail->satuan }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($barangKeluar->tgl_keluar)->format('d-m-Y') }}</td>
                                                                                <td class="d-flex gap-2">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('barang_keluar.edit', $barangKeluar->id_keluar) }}" class="btn btn-sm p-1" title="Edit">
                                <img src="{{ asset('images/icons/edit.png') }}" alt="Edit" width="20" height="20">
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('barang_keluar.destroy', $barangKeluar->id_keluar) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data barang keluar ini?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm p-1" title="Hapus">
                                    <img src="{{ asset('images/icons/trash.png') }}" alt="Hapus" width="20" height="20">
                                </button>
                            </form>
                        </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Data barang keluar belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
            </div>
        </div>
    </div>
</div>
</div>

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
@endsection