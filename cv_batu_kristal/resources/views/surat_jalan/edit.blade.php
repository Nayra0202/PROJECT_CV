@extends('layouts.main')

@section('content')
<div class="container">
    <h3>Edit Surat Jalan</h3>
    <form action="{{ route('surat_jalan.update', $suratJalan->id_surat_jalan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="id_surat" class="form-label">ID Surat Jalan</label>
            <input type="text" name="id_surat_jalan" id="id_surat_jalan" class="form-control" value="{{ old('id_surat_jalan', $suratJalan->id_surat_jalan) }}" readonly>
            @error('id_surat_jalan')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tgl_surat" class="form-label">Tanggal Surat Jalan</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ old('tanggal', $suratJalan->tanggal) }}" required>
            @error('tgl_surat')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
            <input type="text" name="nama_pemesan" id="nama_pemesan" class="form-control" value="{{ old('nama_pemesan', $suratJalan->nama_pemesan) }}" required>
            @error('nama_pemesan')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" id="alamat" class="form-control" rows="2" required>{{ old('alamat', $suratJalan->alamat) }}</textarea>
            @error('alamat')
                <div class="alert alert-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('surat_jalan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection