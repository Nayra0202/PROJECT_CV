@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            Daftar Harga
            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">Tambah Barang</a>
        </div>
        <div class="card-body">
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
                            <td class="d-flex gap-1">
                                <a href="{{ route('barang.edit', $barang->id_barang) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
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