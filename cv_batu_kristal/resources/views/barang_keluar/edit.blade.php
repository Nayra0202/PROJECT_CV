@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">Edit Data Barang Keluar</div>
        <div class="card-body">
            <form action="{{ route('barang_keluar.update', $barangKeluar->id_keluar) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ID Keluar --}}
                <div class="mb-3">
                    <label class="form-label">ID Keluar</label>
                    <input type="text" class="form-control" value="{{ $barangKeluar->id_keluar }}" readonly>
                </div>

                {{-- ID Permintaan --}}
                <div class="mb-3">
                    <label class="form-label">ID Permintaan</label>
                    <input type="text" class="form-control" value="{{ $barangKeluar->id_permintaan }} - {{ $barangKeluar->permintaan->nama_pemesan ?? '' }}" readonly>
                </div>

                {{-- Tanggal Keluar --}}
                <div class="mb-3">
                    <label class="form-label">Tanggal Keluar</label>
                    <input type="date" name="tgl_keluar" class="form-control"
                        value="{{ date('Y-m-d', strtotime($barangKeluar->tgl_keluar)) }}" required>
                </div>

                {{-- Daftar Barang --}}
                <h5 class="mt-4">Daftar Barang</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangKeluar->detailBarangKeluar as $detail)
                            <tr>
                                <td>{{ $detail->barang->nama_barang }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ $detail->satuan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
