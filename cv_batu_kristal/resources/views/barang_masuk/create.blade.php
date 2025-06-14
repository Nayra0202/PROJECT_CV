@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Tambah Data Barang Masuk
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
            <form action="{{ route('barang_masuk.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="id_masuk" class="form-label">ID Masuk</label>
                    <input type="text" class="form-control" id="id_masuk" name="id_masuk" value="{{ $newIdMasuk ?? '' }}" readonly required>
                </div>
                <div class="mb-3">
                    <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                    <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" required value="{{ date('Y-m-d') }}">
                </div>
                <table class="table" id="barang-table">
                    <thead>
                        <tr>
                            <th>Pilih Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select class="form-control" name="barang[0][id_barang]" required onchange="setSatuan(this)">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->id_barang }}" data-satuan="{{ $barang->satuan }}">
                                            {{ $barang->id_barang }} - {{ $barang->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="barang[0][jumlah]" required min="1">
                            </td>
                            <td>
                                <input type="text" class="form-control satuan-field" name="barang[0][satuan]" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-success btn-sm" onclick="addRow()">Tambah Barang</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('barang_masuk.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
let rowCount = 1;

function addRow() {
    let table = document.getElementById('barang-table').getElementsByTagName('tbody')[0];
    let newRow = table.insertRow();

    newRow.innerHTML = `
        <td>
            <select class="form-control" name="barang[${rowCount}][id_barang]" required onchange="setSatuan(this)">
                <option value="">-- Pilih Barang --</option>
                @foreach($barangs as $barang)
                    <option value="{{ $barang->id_barang }}" data-satuan="{{ $barang->satuan }}">
                        {{ $barang->id_barang }} - {{ $barang->nama_barang }}
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" class="form-control" name="barang[${rowCount}][jumlah]" required min="1">
        </td>
        <td>
            <input type="text" class="form-control satuan-field" name="barang[${rowCount}][satuan]" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Hapus</button>
        </td>
    `;
    rowCount++;
}

function removeRow(button) {
    let row = button.closest('tr');
    row.remove();
}

function setSatuan(select) {
    let satuan = select.selectedOptions[0].dataset.satuan;
    let satuanInput = select.closest('tr').querySelector('.satuan-field');
    satuanInput.value = satuan || '';
}
</script>