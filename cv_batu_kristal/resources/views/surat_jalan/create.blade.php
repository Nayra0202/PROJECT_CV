@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Buat Surat Jalan
        </div>
        <div class="card-body">
            <form action="{{ route('surat_jalan.store') }}" method="POST">
                @csrf
                                <div class="mb-3">
                    <label for="id_surat_jalan" class="form-label">ID Surat Jalan</label>
                    <input type="text" name="id_surat_jalan" id="id_surat_jalan" class="form-control" value="{{ $newId }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="id_permintaan" class="form-label">Pilih Pemesanan</label>
                    <select class="form-select" id="id_permintaan" name="id_permintaan" required>
                        <option value="">-- Pilih Pemesanan --</option>
                        @foreach($permintaans as $permintaan)
                            <option value="{{ $permintaan->id_permintaan }}">
                                {{ $permintaan->nama_pemesan }} - Permintaan #{{ $permintaan->id_permintaan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Surat Jalan</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Buat Surat Jalan</button>
                <a href="{{ route('surat_jalan.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection