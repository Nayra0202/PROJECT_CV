@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Tambah Data Barang
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('barang.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="id_barang" class="form-label">ID Barang</label>
                    <input type="text" class="form-control" id="id_barang" name="id_barang" value="{{ $newIdBarang ?? '' }}" readonly required>
                </div>

                <div class="mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                </div>

                <div class="mb-3">
                    <label for="satuan" class="form-label">Satuan</label>
                    <select class="form-control" id="satuan" name="satuan" required>
                        <option value="">-- Pilih Satuan --</option>
                        <option value="Kotak">Kotak</option>
                        <option value="Rim">Rim</option>
                        <option value="Roll">Roll</option>
                        <option value="Buah">Buah</option>
                        <option value="Pack">Pack</option>
                        <option value="Botol">Botol</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="text" class="form-control" id="harga" name="harga" value="Rp. " required>
                </div>

                <!-- tgl_input otomatis di controller, tidak perlu diinput user -->
                <!-- stok tidak diinput manual, akan bertambah otomatis lewat Barang Masuk -->

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection