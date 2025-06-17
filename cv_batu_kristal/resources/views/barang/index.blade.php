@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            Daftar Harga
            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">Tambah Barang</a>
        </div>
        <div class="card-body">

            <div class="mb-4">
    <form id="filterForm" action="{{ route('barang.index') }}" method="GET" class="d-flex align-items-center gap-2">
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
        <a href="{{ route('barang.index') }}" class="btn btn-sm btn-secondary">Reset</a>
    </form>
</div>

<script>
(function() {
    var filterType = document.getElementById('filterType');
    var inputTanggal = document.getElementById('inputTanggal');
    var inputBulan = document.getElementById('inputBulan');
    var inputTahun = document.getElementById('inputTahun');
    var resetBtn = document.getElementById('resetBtn');

    filterType.onchange = function() {
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
    };

    resetBtn.onclick = function(e) {
        e.preventDefault();
        filterType.value = '';
        inputTanggal.value = '';
        inputBulan.value = '';
        inputTahun.value = '';
        inputTanggal.disabled = true;
        inputBulan.disabled = true;
        inputTahun.disabled = true;
    };
})();
</script>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Barang</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Stok</th> 
                            <th>Satuan</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Tanggal Input</th>
                            <th>Tanggal Disetujui</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangs as $barang)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $barang->id_barang }}</td>
                            <td>{{ $barang->nama_barang }}</td>
                            <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                            <td>{{ $barang->stok }}</td> 
                            <td>{{ $barang->satuan }}</td>
                            <td>{{ $barang->status ?: 'Menunggu' }}</td>
                            <td>
                                @if($barang->status === 'Menunggu')
                                    Menunggu Persetujuan
                                @elseif($barang->status === 'Disetujui')
                                    Sudah Oke
                                @else
                                    {{ $barang->keterangan }}
                                @endif
                            </td>
                            <td>{{ $barang->tgl_input ? \Carbon\Carbon::parse($barang->tgl_input)->format('d-m-Y') : '-' }}</td>
                            <td>{{ $barang->tgl_disetujui ? \Carbon\Carbon::parse($barang->tgl_disetujui)->format('d-m-Y') : '-' }}</td>
                            <td class="d-flex gap-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('barang.edit', $barang->id_barang) }}" class="btn btn-sm p-1" title="Edit">
                                    <img src="{{ asset('images/icons/edit.png') }}" alt="Edit" width="20" height="20">
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data barang ini?')" style="display:inline;">
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
                            <td colspan="11" class="text-center">Data barang belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection