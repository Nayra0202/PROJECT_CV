@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">Tambah Data Barang Keluar</div>
        <div class="card-body">
            <form action="{{ route('barang_keluar.store') }}" method="POST" id="formKeluar">
                @csrf

                {{-- ID Keluar --}}
                <div class="mb-3">
                    <label class="form-label">ID Keluar</label>
                    <input type="text" class="form-control" name="id_keluar" value="{{ $newIdKeluar }}" readonly>
                </div>

                {{-- Pilih Permintaan --}}
                <div class="mb-3">
                    <label class="form-label">Pilih Permintaan</label>
                    <select name="id_permintaan" id="id_permintaan" class="form-select" required>
                        <option value="">-- Pilih Permintaan --</option>
                        @foreach($permintaans as $permintaan)
                            <option value="{{ $permintaan->id_permintaan }}">
                                {{ $permintaan->id_permintaan }} - {{ $permintaan->nama_pemesan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Barang-barang dari permintaan --}}
                <div id="barangContainer"></div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('barang_keluar.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT AJAX --}}
<script>
    document.getElementById('id_permintaan').addEventListener('change', function () {
        const permintaanId = this.value;
        const container = document.getElementById('barangContainer');
        container.innerHTML = ''; // kosongkan dulu

        if (permintaanId) {
            fetch(`/api/permintaan/${permintaanId}/barangs`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let table = `
                            <h5 class="mt-4">Daftar Barang</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jumlah Keluar</th>
                                        <th>Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                        data.forEach((item, i) => {
                            table += `
                                <tr>
                                    <td>
                                        ${item.nama_barang}
                                        <input type="hidden" name="barang[${i}][id_barang]" value="${item.id_barang}">
                                    </td>
                                    <td><input type="number" name="barang[${i}][jumlah]" class="form-control" value="${item.jumlah}" readonly></td>
                                    <td><input type="text" name="barang[${i}][satuan]" class="form-control" value="${item.satuan}" readonly></td>
                                </tr>`;
                        });

                        table += `</tbody></table>`;
                        container.innerHTML = table;
                    }
                });
        }
    });
</script>
@endsection
