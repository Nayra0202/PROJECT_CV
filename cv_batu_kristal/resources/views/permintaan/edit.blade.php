@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold">Edit Data Pemesanan</div>

        <div class="card-body">
            {{-- ALERT STATUS --}}
            @php
                $status = strtolower($permintaan->status);
            @endphp

@if($permintaan->status == 'Sedang Proses')
    <div class="alert alert-warning mb-3">
        Permintaan tidak bisa diubah karena sedang diproses
    </div>
@elseif($permintaan->status == 'Sedang Perjalanan')
    <div class="alert alert-warning mb-3">
        Permintaan tidak bisa diubah karena sedang perjalanan
    </div>
@elseif($permintaan->status == 'Selesai')
    <div class="alert alert-success mb-3">
        Permintaan tidak bisa diubah karena permintaan telah selesai
    </div>
@endif


            {{-- FORM EDIT --}}
            <form action="{{ route('permintaan.update', $permintaan->id_permintaan) }}" method="POST">
                @csrf
                @method('PUT')

                <fieldset @if(in_array($status, ['sedang diproses', 'sedang perjalanan', 'selesai'])) disabled @endif>
                    <div class="mb-3">
                        <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                        <input type="text" name="nama_pemesan" id="nama_pemesan" class="form-control" value="{{ old('nama_pemesan', $permintaan->nama_pemesan) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ID Pemesanan</label>
                        <input type="text" class="form-control" value="{{ $permintaan->id_permintaan }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pemesan</label>
                        <input type="text" class="form-control" name="nama_pemesan" value="{{ old('nama_pemesan', $permintaan->nama_pemesan) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" class="form-control" name="alamat" value="{{ old('alamat', $permintaan->alamat) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pemesanan</label>
                        <input type="date" class="form-control" name="tgl_permintaan" value="{{ old('tgl_permintaan', $permintaan->tgl_permintaan) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Bayar</label>
                        <input type="text" class="form-control" value="Rp. {{ number_format($permintaan->total_bayar,0,',','.') }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="Menunggu Persetujuan" {{ $permintaan->status == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="Disetujui" {{ $permintaan->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Sedang Proses" {{ $permintaan->status == 'Sedang Proses' ? 'selected' : '' }}>Sedang Proses</option>
                            <option value="Sedang Perjalanan" {{ $permintaan->status == 'Sedang Perjalanan' ? 'selected' : '' }}>Sedang Perjalanan</option>
                            <option value="Selesai" {{ $permintaan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('permintaan.index') }}" class="btn btn-secondary">Batal</a>
                    <hr>
                    <h5>Detail Barang</h5>

                    @foreach ($permintaan->detailPermintaan as $i => $detail)
                    <div class="border p-3 mb-3 rounded">
                            {{-- No urut --}}
                    <div class="mb-2">
                        <strong>Barang No. {{ $loop->iteration }}</strong>
                    </div>
                        {{-- hidden-id agar tetap terkirim --}}
                        <input type="hidden" name="details[{{ $i }}][id_detail]"  value="{{ $detail->id_detail }}">
                        <input type="hidden" name="details[{{ $i }}][id_barang]" value="{{ $detail->id_barang }}">

                        {{-- Nama barang hanya tampil --}}
                        <div class="mb-2">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" value="{{ $detail->barang->nama_barang }}" disabled>
                        </div>

                        {{-- Jumlah readonly --}}
                        <div class="mb-2">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="details[{{ $i }}][jumlah]" value="{{ $detail->jumlah }}" readonly>
                        </div>

                        {{-- Satuan readonly --}}
                        <div class="mb-2">
                            <label class="form-label">Satuan</label>
                            <input type="text" class="form-control" name="details[{{ $i }}][satuan]" value="{{ $detail->barang->satuan }}" readonly>
                        </div>

                        {{-- Total harga per barang --}}
                        <div class="mb-2">
                            <label class="form-label">Total Harga Barang</label>
                            <input type="text" class="form-control" value="Rp. {{ number_format($detail->jumlah * $detail->barang->harga,0,',','.') }}" readonly>
                        </div>
                    </div>
                    @endforeach
                </fieldset>
            </form>
        </div>
    </div>
</div>
@endsection
