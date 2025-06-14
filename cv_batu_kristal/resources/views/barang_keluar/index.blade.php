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
                            <td class="d-flex gap-1">
                                <a href="{{ route('barang_keluar.edit', $barangKeluar->id_keluar) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('barang_keluar.destroy', $barangKeluar->id_keluar) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
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

@endsection