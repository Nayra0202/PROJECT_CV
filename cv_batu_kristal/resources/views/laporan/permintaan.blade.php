@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Laporan Permintaan Barang
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('laporan.permintaan') }}" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Pilih Filter</label>
                    <select name="filter" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="tanggal" {{ request('filter') == 'tanggal' ? 'selected' : '' }}>Tanggal</option>
                        <option value="bulan" {{ request('filter') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                        <option value="tahun" {{ request('filter') == 'tahun' ? 'selected' : '' }}>Tahun</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Masukkan Nilai</label>
                    <input type="text" name="value" class="form-control" placeholder="Contoh: 2025-06-10 / 06 / 2025" value="{{ request('value') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
                    <a href="{{ route('laporan.permintaan') }}" class="btn btn-secondary me-2">Reset</a>

                    @if(count($permintaans))
                    <a href="{{ route('laporan.permintaan.cetak', request()->query()) }}" target="_blank" class="btn btn-success">
                        <i class="bi bi-printer-fill"></i> Cetak
                    </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>ID Permintaan</th>
                            <th>Tanggal Permintaan</th>
                            <th>Nama Pemesan</th>
                            <th>Daftar Barang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permintaans as $key => $permintaan)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $permintaan->id_permintaan }}</td>
                            <td>{{ \Carbon\Carbon::parse($permintaan->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $permintaan->nama_pemesan ?? '-' }}</td>
                        <td>
                            <ul class="mb-2 ps-3">
                                @foreach ($permintaan->detailPermintaan as $detail)
                                    <li>
                                        {{ $detail->barang->nama_barang ?? '-' }} - 
                                        {{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }} 
                                        (Rp{{ number_format($detail->total_harga, 0, ',', '.') }})
                                    </li>
                                @endforeach
                            </ul>
                            <strong>Total Bayar: Rp{{ number_format($permintaan->total_bayar ?? 0, 0, ',', '.') }}</strong>
                        </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection