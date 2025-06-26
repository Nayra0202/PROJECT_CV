@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Barang Masuk</h1>

    <form action="{{ route('barang_masuk.update', $data->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="jumlah_masuk" class="form-label">Jumlah Masuk</label>
            <input type="number" name="jumlah_masuk" id="jumlah_masuk" class="form-control" value="{{ old('jumlah_masuk', $data->jumlah_masuk) }}" min="0" required>
            @error('jumlah_masuk')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', $data->tanggal_masuk) }}" required>
            @error('tanggal_masuk')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
    </form>
</div>
@endsection