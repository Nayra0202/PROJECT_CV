@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h4>Edit Barang Masuk (ID: {{ $barangMasuk->id_masuk }})</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Gagal menyimpan:</strong>
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('barang_masuk.update', $barangMasuk->id_masuk) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
            <input type="date" name="tgl_masuk" class="form-control" value="{{ old('tgl_masuk', $barangMasuk->tgl_masuk) }}" required>
        </div>

        <h5>Detail Barang</h5>
        @foreach($barangMasuk->detailBarangMasuk as $index => $detail)
            <input type="hidden" name="detail[{{ $index }}][id_detail_masuk]" value="{{ $detail->id_detail_masuk }}">

            <div class="mb-2">
                <label>Nama Barang</label>
                <select name="detail[{{ $index }}][id_barang]" class="form-control" required>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id_barang }}" {{ $barang->id_barang == $detail->id_barang ? 'selected' : '' }}>
                            {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <label>Jumlah</label>
                <input type="number" name="detail[{{ $index }}][jumlah]" class="form-control" value="{{ $detail->jumlah }}" min="1" required>
            </div>

            <div class="mb-3">
                <label>Satuan</label>
                <input type="text" name="detail[{{ $index }}][satuan]" class="form-control" value="{{ $detail->satuan }}" required>
            </div>
            <hr>
        @endforeach

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('barang_masuk.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection