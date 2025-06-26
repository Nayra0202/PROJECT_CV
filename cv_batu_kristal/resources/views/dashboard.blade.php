@extends('layouts.main')
@section('content')

<div class="row mb-4">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="fw-bold mb-2">Tentang CV. Batu Kristal</h4>
        <p class="mb-0 fs-5">
          CV. Batu Kristal merupakan badan usaha milik swasta yang telah berdiri sejak 
          tahun 2001 dan bergerak di bidang pengadaan barang dan jasa. CV. Batu Kristal 
          berlokasi di Jalan Mayor Salim Batubara Gang Nurul Iman 1844/72 RT 006/02, Kota 
          Palembang, Sumatera Selatan.
        </p>
      </div>
    </div>
  </div>
</div>

@if(auth()->user()->role != 'Klien')
<!-- Tabel Permintaan Terbaru dan Ringkasan Data -->
<div class="row">
  <!-- Card 1: Tabel Permintaan Terbaru -->
  <div class="col-lg-8 mb-4">
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="mb-0">Tabel Permintaan Terbaru</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th>No</th>
                <th>ID Permintaan</th>
                <th>Tanggal Permintaan</th>
                <th>Nama Peminta</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($permintaans as $i => $permintaan)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>{{ $permintaan->id_permintaan }}</td>
                  <td>{{ $permintaan->tgl_permintaan }}</td>
                  <td>{{ $permintaan->nama_pemesan ?? '-' }}</td>
                  <td>{{ $permintaan->status ?? '-' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Belum ada data permintaan.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Card 2: Ringkasan Data -->
  <div class="col-lg-4 mb-4">
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Ringkasan Data</h5>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Total Pemesanan
            <span class="badge bg-primary rounded-pill">{{ $totalPermintaan }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Total Barang
            <span class="badge bg-success rounded-pill">{{ $totalBarang }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Total Barang Masuk
            <span class="badge bg-info rounded-pill">{{ $totalBarangMasuk }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Total Barang Keluar
            <span class="badge bg-warning rounded-pill">{{ $totalBarangKeluar }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endif

<h4 class="fw-bold px-4">--- KATALOG BARANG ---</h4>
<div class="row g-4 px-4 py-3" id="katalog-barang">
  @foreach($barangs as $i => $barang)
    <div class="col-sm-6 col-xl-3 d-flex katalog-item {{ $i >= 24 ? 'd-none extra-item' : '' }}">
      <div class="card overflow-hidden rounded-2 h-100 w-100">
        <div class="position-relative">
          <a href="javascript:void(0)">
            <img src="{{ asset('storage/barang/' . $barang->gambar) }}" class="card-img-top rounded-0" alt="{{ $barang->nama_barang }}" style="height:180px; object-fit:cover;">
          </a>
        </div>
        <div class="card-body pt-3 p-4">
          <h6 class="fw-semibold fs-4">{{ $barang->nama_barang }}</h6>
          <div class="d-flex align-items-center justify-content-between">
            <h6 class="fw-semibold fs-4 mb-0">Rp. {{ number_format($barang->harga, 0, ',', '.') }}</h6>
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

@if($barangs->count() > 24)
  <div class="text-center my-3">
    <button class="btn btn-outline-primary" id="toggleKatalog">Lihat Semua Barang</button>
  </div>
@endif

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleKatalog');
    if (toggleBtn) {
      toggleBtn.addEventListener('click', function () {
        const hiddenItems = document.querySelectorAll('.extra-item');
        hiddenItems.forEach(item => item.classList.toggle('d-none'));
        toggleBtn.innerText = toggleBtn.innerText === 'Lihat Semua Barang' ? 'Tampilkan 24 Barang Saja' : 'Lihat Semua Barang';
      });
    }
  });
</script>
@endpush
@endsection