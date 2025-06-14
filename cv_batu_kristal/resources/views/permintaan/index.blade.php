@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            Daftar Permintaan Barang
            <a href="{{ route('permintaan.create') }}" class="btn btn-primary btn-sm">Tambah Permintaan</a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Permintaan</th>
                            <th>Daftar Barang</th>
                            <th>Tanggal Permintaan</th>
                            <th>Nama Pemesan</th>
                            <th>Alamat </th>
                            <th>Total Bayar</th>
                            <th>Status </th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permintaans as $permintaan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $permintaan->id_permintaan }}</td>
                                <td>
                                    <ul>
                                        @foreach($permintaan->detailPermintaan as $detail)
                                            <li>{{ $detail->barang->nama_barang }} - {{ $detail->jumlah }} {{ $detail->barang->satuan }} (Rp{{ number_format($detail->total_harga, 0, ',', '.') }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $permintaan->tgl_permintaan }}</td>
                                <td>{{ $permintaan->nama_pemesan }}</td>
                                <td>{{ $permintaan->alamat }}</td>
                                <td>Rp{{ number_format($permintaan->total_bayar, 0, ',', '.') }}</td>
                                <td>
                                    @if($permintaan->status == 'Menunggu Persetujuan')
                                        <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                    @elseif($permintaan->status == 'Disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($permintaan->status == 'Sedang Proses')
                                        <span class="badge bg-danger">Sedang Proses</span>
                                    @elseif($permintaan->status == 'Sedang Perjalanan')
                                        <span class="badge bg-success">Sedang Perjalanan</span>
                                    @elseif($permintaan->status == 'Selesai')
                                        <span class="badge bg-danger">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $permintaan->status }}</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-1">
                                    <a href="{{ route('permintaan.edit', $permintaan->id_permintaan) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('permintaan.destroy', $permintaan->id_permintaan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin hapus semua barang di permintaan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Data permintaan belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
