@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Laporan Barang
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('laporan.barang') }}" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="filter" class="form-label">Pilih Filter</label>
                    <select name="filter" id="filter" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="tanggal">Tanggal</option>
                        <option value="bulan">Bulan</option>
                        <option value="tahun">Tahun</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="value" class="form-label">Masukkan Nilai</label>
                    <input type="text" name="value" id="value" class="form-control" placeholder="Contoh: 2025-06-10 atau 06 atau 2025">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
                    <a href="{{ route('laporan.barang') }}" class="btn btn-secondary me-2">Reset</a>
                    <a href="{{ route('laporan.barang.cetak', request()->query()) }}" target="_blank" class="btn btn-success">Cetak</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Tanggal Input</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barangs as $barang)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td>{{ $barang->satuan }}</td>
                                <td>{{ $barang->stok }}</td>
                                <td>{{ \Carbon\Carbon::parse($barang->created_at)->format('d-m-Y') }}</td>
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