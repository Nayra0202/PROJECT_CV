@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            Daftar Surat Jalan
            <a href="{{ route('surat_jalan.create') }}" class="btn btn-primary btn-sm">Buat Surat Jalan</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-4">
                <form action="{{ route('surat_jalan.index') }}" method="GET" class="d-flex flex-column gap-2">
                    <label for="filterType" class="form-label mb-1" style="min-width: 140px;">Pilih Berdasarkan</label>
                    <div class="d-flex align-items-center gap-2">
                        <select id="filterType" name="filterType" class="form-select" style="width: 160px;">
                            <option value="">Filter</option>
                            <option value="tanggal" {{ request('filterType') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                            <option value="bulan" {{ request('filterType') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                            <option value="tahun" {{ request('filterType') == 'tahun' ? 'selected' : '' }}>Tahun</option>
                        </select>
                        <input type="date" id="inputTanggal" name="tgl_surat" class="form-control" style="width: 180px;" value="{{ request('tgl_surat') }}" {{ request('filterType') == 'tanggal' ? '' : 'disabled' }}>
                        <input type="month" id="inputBulan" name="bulan_surat" class="form-control" style="width: 150px;" value="{{ request('bulan_surat') }}" {{ request('filterType') == 'bulan' ? '' : 'disabled' }}>
                        <input type="number" id="inputTahun" name="tahun_surat" class="form-control" style="width: 120px;" min="2000" max="2099" placeholder="Tahun" value="{{ request('tahun_surat') }}" {{ request('filterType') == 'tahun' ? '' : 'disabled' }}>
                        <button type="submit" class="btn btn-primary" style="min-width: 90px;">Cari</button>
                        <a href="{{ route('surat_jalan.index') }}" class="btn btn-secondary" style="min-width: 90px;">Reset</a>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Pemesan</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratJalans as $sj)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sj->tanggal }}</td>
                            <td>{{ $sj->nama_pemesan }}</td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($sj->permintaan->detailPermintaan as $detail)
                                        <li>{{ $detail->barang->nama_barang ?? '-' }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($sj->permintaan->detailPermintaan as $detail)
                                        <li>{{ $detail->jumlah }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($sj->permintaan->detailPermintaan as $detail)
                                        <li>{{ $detail->satuan }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="d-flex gap-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('surat_jalan.edit', $sj->id_surat_jalan) }}" class="btn btn-sm p-1" title="Edit">
                                    <img src="{{ asset('images/icons/edit.png') }}" alt="Edit" width="20" height="20">
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('surat_jalan.destroy', $sj->id_surat_jalan) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus semua barang di permintaan ini?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm p-1" title="Hapus">
                                        <img src="{{ asset('images/icons/trash.png') }}" alt="Hapus" width="20" height="20">
                                    </button>
                                </form>

                                {{-- Tombol Cetak --}}
                                <a href="{{ route('surat_jalan.cetak', $sj->id_surat_jalan) }}" target="_blank" class="btn btn-sm p-1" title="Cetak">
                                    <img src="{{ asset('images/icons/printer.png') }}" alt="Cetak" width="20" height="20">
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Data surat jalan belum tersedia.</td>
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