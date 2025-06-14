@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Detail Barang Masuk (ID: {{ $barangMasuk->id_masuk }})
        </div>
        <div class="card-body">
            <a href="{{ route('barang_masuk.edit', $barangMasuk) }}">Edit</a>
                @csrf
                @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tanggal Masuk</label>
                <input type="date" class="form-control" value="{{ $barangMasuk->tgl_masuk }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Daftar Barang</label>
                <ul class="list-group">
                    @foreach($barangMasuk->detailBarangMasuk as $detail)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $detail->barang->nama_barang }}
                            <span>{{ $detail->jumlah }} {{ $detail->barang->satuan }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <a href="{{ route('barang_masuk.index') }}" class="btn btn-secondary mt-3">Kembali</a>

        </div>
    </div>
    </div>
</div>
@endsection