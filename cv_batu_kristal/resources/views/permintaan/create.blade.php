@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">
            Tambah Data Permintaan
        </div>

        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card-body">
            <form action="{{ route('permintaan.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">ID Permintaan</label>
                    <input type="text" class="form-control" name="id_permintaan" value="{{ $newIdPermintaan }}" readonly required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Pemesan</label>
                    <input type="text" class="form-control" name="nama_pemesan" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Permintaan</label>
                    <input type="date" class="form-control" name="tgl_permintaan" value="{{ date('Y-m-d') }}" required>
                </div>

                <hr>
                <h5>Daftar Barang</h5>
                <table class="table table-bordered" id="barangTable">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Satuan</th>
                            <th>Stok</th>
                            <th>Jumlah</th>
                            <th>Total Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select class="form-control" name="barang[0][id_barang]" onchange="setBarangInfo(this)">
                                    <option value="">-- Pilih --</option>
                                    @foreach($barangs as $barang)
                                        <option value="{{ $barang->id_barang }}" 
                                            data-satuan="{{ $barang->satuan }}" 
                                            data-harga="{{ $barang->harga }}"
                                            data-stok="{{ $barang->stok }}">
                                            {{ $barang->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" class="form-control" name="barang[0][satuan]" readonly></td>
                            <td><input type="text" class="form-control" name="barang[0][stok]" readonly></td>
                            <td><input type="number" class="form-control" name="barang[0][jumlah]" min="1" oninput="hitungTotalHarga(this)"></td>
                            <td><input type="text" class="form-control" name="barang[0][total_harga]" readonly></td>
                            <td><button type="button" class="btn btn-danger" onclick="hapusBaris(this)">Hapus</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary mb-3" onclick="tambahBaris()">+ Tambah Barang</button>

                <div class="mb-3">
                    <label class="form-label">Total Bayar</label>
                    <input type="text" class="form-control" id="total_bayar" name="total_bayar" readonly required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('permintaan.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
    let index = 1;

    function tambahBaris() {
        const table = document.getElementById('barangTable').getElementsByTagName('tbody')[0];
        const row = table.rows[0].cloneNode(true);
        row.querySelectorAll('input, select').forEach(function(input) {
            let name = input.getAttribute('name');
            if (name) {
                name = name.replace(/\[\d+\]/, `[${index}]`);
                input.setAttribute('name', name);
                if (input.tagName === 'SELECT') input.selectedIndex = 0;
                else input.value = '';
            }
        });
        table.appendChild(row);
        index++;
    }

    function hapusBaris(btn) {
        const row = btn.closest('tr');
        const table = row.closest('tbody');
        if (table.rows.length > 1) {
            row.remove();
            hitungTotalBayar();
        }
    }

    function setBarangInfo(select) {
        const row = select.closest('tr');
        const satuan = select.options[select.selectedIndex].getAttribute('data-satuan');
        const harga = select.options[select.selectedIndex].getAttribute('data-harga');
        const stok = select.options[select.selectedIndex].getAttribute('data-stok');

        row.querySelector('input[name$="[satuan]"]').value = satuan || '';
        row.querySelector('input[name$="[stok]"]').value = stok || '';
        row.setAttribute('data-harga', harga || 0);
        hitungTotalHarga(row.querySelector('input[name$="[jumlah]"]'));
    }

    function hitungTotalHarga(inputJumlah) {
    const row = inputJumlah.closest('tr');
    const harga = parseInt(row.getAttribute('data-harga')) || 0;
    const jumlah = parseInt(inputJumlah.value) || 0;

    const select = row.querySelector('select[name$="[id_barang]"]');
    const stokAwal = parseInt(select.options[select.selectedIndex].getAttribute('data-stok')) || 0;
    const sisaStok = stokAwal - jumlah;

    // Validasi jika jumlah melebihi stok
    if (jumlah > stokAwal) {
        alert('Jumlah melebihi stok yang tersedia!');
        inputJumlah.value = '';
        row.querySelector('input[name$="[total_harga]"]').value = '';
        row.querySelector('input[name$="[stok]"]').value = stokAwal;
        return;
    }

    row.querySelector('input[name$="[stok]"]').value = sisaStok;
    row.querySelector('input[name$="[total_harga]"]').value = harga * jumlah;

    hitungTotalBayar();
}

    function hitungTotalBayar() {
        let totalBayar = 0;
        document.querySelectorAll('input[name$="[total_harga]"]').forEach(input => {
            totalBayar += parseInt(input.value) || 0;
        });
        document.getElementById('total_bayar').value = totalBayar;
    }
</script>
@endsection
