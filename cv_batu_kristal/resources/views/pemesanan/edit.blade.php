@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">Edit Data Pemesanan</div>
        <div class="card-body">
            @php
                $userRole = strtolower(auth()->user()->role);
                $isClient = $userRole === 'klien';
                $status = strtolower($pemesanan->status);
                $isNonEditableStatus = in_array($status, ['sedang proses', 'sedang perjalanan', 'selesai']);
            @endphp

            {{-- ALERT STATUS --}}
            @if($pemesanan->status == 'Sedang Proses')
                <div class="alert alert-warning mb-3">Pemesanan tidak bisa diubah karena sedang diproses</div>
            @elseif($pemesanan->status == 'Sedang Perjalanan')
                <div class="alert alert-warning mb-3">Pemesanan tidak bisa diubah karena sedang perjalanan</div>
            @elseif($pemesanan->status == 'Selesai')
                <div class="alert alert-success mb-3">Pemesanan tidak bisa diubah karena pemesanan telah selesai</div>
            @endif

            <form action="{{ route('pemesanan.update', $pemesanan->id_pemesanan) }}" method="POST">
                @csrf
                @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">ID Pemesanan</label>
                        <input type="text" class="form-control" value="{{ $pemesanan->id_pemesanan }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pemesan</label>
                        <input type="text" class="form-control" name="nama_pemesan" value="{{ old('nama_pemesan', $pemesanan->nama_pemesan) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" class="form-control" name="alamat" value="{{ old('alamat', $pemesanan->alamat) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pemesanan</label>
                        <input type="date" class="form-control" name="tgl_pemesanan" value="{{ old('tgl_pemesanan', $pemesanan->tgl_pemesanan) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Bayar</label>
                        <input type="text" class="form-control" id="totalBayar" value="Rp. {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}" readonly>
                    </div>


@if($userRole !== 'klien')
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select name="status" id="status" class="form-select">
            <option value="Menunggu Persetujuan" {{ $pemesanan->status == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
            <option value="Disetujui" {{ $pemesanan->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="Sedang Proses" {{ $pemesanan->status == 'Sedang Proses' ? 'selected' : '' }}>Sedang Proses</option>
            <option value="Sedang Perjalanan" {{ $pemesanan->status == 'Sedang Perjalanan' ? 'selected' : '' }}>Sedang Perjalanan</option>
            <option value="Selesai" {{ $pemesanan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
    </div>
@endif

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('pemesanan.index') }}" class="btn btn-secondary">Batal</a>

                    <hr>
                    <h5>Detail Barang</h5>
                    @foreach ($pemesanan->detailPemesanan as $i => $detail)
                    <div class="border p-3 mb-3 rounded">
                        <div class="mb-2"><strong>Barang No. {{ $loop->iteration }}</strong></div>
                        <input type="hidden" name="details[{{ $i }}][id_detail_pemesanan]" value="{{ $detail->id_detail_pemesanan }}">
                        <input type="hidden" name="details[{{ $i }}][id_barang]" value="{{ $detail->id_barang }}">

                        <div class="mb-2">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" value="{{ $detail->barang->nama_barang }}" disabled>
                        </div>

                        <div class="mb-2">
    <label class="form-label">Jumlah</label>
    @if($userRole === 'klien' && $status === 'menunggu persetujuan')
<input 
  type="number" 
  class="form-control jumlah-input" 
  name="details[{{ $i }}][jumlah]" 
  value="{{ $detail->jumlah }}" 
  data-harga="{{ $detail->barang->harga }}" 
  data-index="{{ $i }}" 
  required>
    @else
        <input type="number" class="form-control" value="{{ $detail->jumlah }}" readonly>
        <input type="hidden" name="details[{{ $i }}][jumlah]" value="{{ $detail->jumlah }}">
    @endif
</div>

<div class="mb-2">
    <label class="form-label">Total Harga Barang</label>
<input 
  type="text" 
  class="form-control total-harga-barang" 
  id="totalHarga{{ $i }}" 
  value="Rp. {{ number_format($detail->jumlah * $detail->barang->harga, 0, ',', '.') }}" 
  readonly>
</div>
                    @endforeach
                </fieldset>
            </form>
        </div>
    </div>
</div>

@if($userRole === 'klien' && $status === 'menunggu persetujuan')
@verbatim
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputsJumlah = document.querySelectorAll('input[name^="details"][name$="[jumlah]"]');
    const hargaList = JSON.parse(document.getElementById('harga-list').textContent);
    const totalBayarInput = document.getElementById('totalBayar');

    function updateTotal() {
        let total = 0;
        inputsJumlah.forEach((input, index) => {
            const jumlah = parseInt(input.value) || 0;
            const harga = parseFloat(hargaList[index]) || 0;
            total += jumlah * harga;
        });

        totalBayarInput.value = 'Rp. ' + total.toLocaleString('id-ID');
    }

    inputsJumlah.forEach(input => {
        input.addEventListener('input', updateTotal);
    });

    updateTotal();
});
</script>
@endverbatim

{{-- Untuk menghindari @json di dalam @verbatim --}}
<script id="harga-list" type="application/json">
    {!! $pemesanan->detailPemesanan->pluck('barang.harga')->toJson() !!}
</script>
@endif
@endsection

@if($userRole === 'klien' && $status === 'menunggu persetujuan')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const jumlahInputs = document.querySelectorAll('.jumlah-input');
    const totalBayarInput = document.getElementById('totalBayar');

    function formatRupiah(number) {
        return 'Rp. ' + number.toLocaleString('id-ID');
    }

    function updateHarga() {
        let totalBayar = 0;

        jumlahInputs.forEach((input) => {
            const index = input.dataset.index;
            const harga = parseFloat(input.dataset.harga);
            const jumlah = parseInt(input.value) || 0;

            const totalHarga = harga * jumlah;
            totalBayar += totalHarga;

            const totalHargaInput = document.getElementById('totalHarga' + index);
            if (totalHargaInput) {
                totalHargaInput.value = formatRupiah(totalHarga);
            }
        });

        if (totalBayarInput) {
            totalBayarInput.value = formatRupiah(totalBayar);
        }
    }

    jumlahInputs.forEach((input) => {
        input.addEventListener('input', updateHarga);
    });

    updateHarga();
});
</script>
@endif
