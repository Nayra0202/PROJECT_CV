@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Edit Data Barang
        </div>
        <div class="card-body">
            <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                        value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                </div>

                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="text" class="form-control" id="harga" name="harga"
                        value="{{ old('harga', $barang->harga) }}" @if($barang->status === 'Disetujui') readonly @endif required>
                    @if($barang->status === 'Disetujui')
                        <input type="hidden" name="harga" value="{{ old('harga', $barang->harga) }}">
                    @endif
                </div>

                <div class="mb-3">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" class="form-control" id="satuan" name="satuan"
                        value="{{ old('satuan', $barang->satuan) }}"
                        @if($barang->status === 'Disetujui') readonly @endif required>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan"
                        @if($barang->status === 'Disetujui') readonly @endif>{{ old('keterangan', $barang->keterangan) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="gambar" class="form-label">Ganti Gambar (opsional)</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                    @if ($barang->gambar)
                        <div class="mt-2">
                            <img src="{{ asset('storage/barang/' . $barang->gambar) }}" alt="Gambar Barang" class="img-thumbnail" width="150">
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    @if($barang->status === 'Disetujui')
                        <input type="text" class="form-control" value="Disetujui" readonly>
                        <input type="hidden" name="status" value="Disetujui">
                        <div class="alert alert-info mt-2">
                            Status sudah <b>Disetujui</b> dan tidak dapat diubah lagi.<br>
                            <b>Hanya bisa ubah nama barang.</b>
                        </div>
                    @else
                        <select class="form-control" id="status" name="status" required>
                            <option value="Menunggu" {{ old('status', $barang->status) == 'Menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="Disetujui" {{ old('status', $barang->status) == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Ditolak" {{ old('status', $barang->status) == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hargaInput = document.getElementById('harga');
    const statusSelect = document.getElementById('status');
    const keteranganTextarea = document.getElementById('keterangan');

    hargaInput.addEventListener('input', function(e) {
        let value = this.value.replace(/[^0-9]/g, '');
        if (value) {
            this.value = 'Rp ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        } else {
            this.value = '';
        }
    });

    hargaInput.form.addEventListener('submit', function() {
        hargaInput.value = hargaInput.value.replace(/[^0-9]/g, '');
    });

    statusSelect.addEventListener('change', function () {
        if (this.value === 'Disetujui') {
            keteranganTextarea.value = 'Sudah Oke';
        } else if (keteranganTextarea.value === 'Sudah Oke') {
            keteranganTextarea.value = '';
        }
    });
});
</script>
@endsection