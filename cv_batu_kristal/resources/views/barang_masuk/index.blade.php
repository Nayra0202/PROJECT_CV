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
                        <td>
                            <a href="{{ route('barang_masuk.edit', ['barang_masuk' => $barangMasuk->id_masuk]) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('barang_masuk.destroy', $barangMasuk->id_masuk) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</button>
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