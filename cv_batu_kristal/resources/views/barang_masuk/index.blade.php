@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            Daftar Barang Masuk
            <a href="{{ route('barang_masuk.create') }}" class="btn btn-primary btn-sm">Tambah Barang Masuk</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="mb-4">
                <form id="filterForm" action="{{ route('barang_masuk.index') }}" method="GET" class="d-flex align-items-center gap-2">
                    <label for="filterType" class="form-label me-2" style="min-width: 140px;">Pilih Berdasarkan</label>
                    <select id="filterType" name="filterType" class="form-select form-select-sm" style="width: 130px;">
                        <option value="">Filter</option>
                        <option value="tanggal">Tanggal</option>
                        <option value="bulan">Bulan</option>
                        <option value="tahun">Tahun</option>
                    </select>
                    <input type="date" id="inputTanggal" name="tgl_masuk" class="form-control form-control-sm" style="width: 130px;" disabled>
                    <input type="month" id="inputBulan" name="bulan_masuk" class="form-control form-control-sm" style="width: 110px;" disabled>
                    <input type="number" id="inputTahun" name="tahun_masuk" class="form-control form-control-sm" style="width: 80px;" min="2000" max="2099" placeholder="Tahun" disabled>
                    <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                    <a href="{{ route('barang_masuk.index') }}" class="btn btn-sm btn-secondary">Reset</a>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Masuk</th>
                            <th>Daftar Barang</th>
                            <th>Tanggal Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($barangMasuks as $barangMasuk)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $barangMasuk->id_masuk }}</td>
                        <td>
                            <ul>
                                @foreach($barangMasuk->detailBarangMasuk as $detail)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $detail->barang->nama_barang }}
                                        <span>{{ $detail->jumlah }} {{ $detail->barang->satuan }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $barangMasuk->tgl_masuk }}</td>
                        <td class="d-flex gap-2">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('barang_masuk.edit', $barangMasuk->id_masuk) }}" class="btn btn-sm p-1" title="Edit">
                                <img src="{{ asset('images/icons/edit.png') }}" alt="Edit" width="20" height="20">
                            </a>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('barang_masuk.destroy', $barangMasuk->id_masuk) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data barang masuk ini?')" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm p-1" title="Hapus">
                                    <img src="{{ asset('images/icons/trash.png') }}" alt="Hapus" width="20" height="20">
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

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

    filterType.addEventListener('change', function() {
        inputTanggal.disabled = true;
        inputBulan.disabled = true;
        inputTahun.disabled = true;
        if (this.value === 'tanggal') {
            inputTanggal.disabled = false;
        } else if (this.value === 'bulan') {
            inputBulan.disabled = false;
        } else if (this.value === 'tahun') {
            inputTahun.disabled = false;
        }
    });
});
</script>