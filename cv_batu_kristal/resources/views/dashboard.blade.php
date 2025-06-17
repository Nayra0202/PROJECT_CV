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


        <!-- Tabel Permintaan Terbaru -->
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
    <!-- Card 2: Summary -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ringkasan Data</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total Permintaan
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

        <div class="row">
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body p-4">
                <div class="mb-4">
                  <h5 class="card-title fw-semibold">Recent Transactions</h5>
                </div>
                <ul class="timeline-widget mb-0 position-relative mb-n5">
                  <li class="timeline-item d-flex position-relative overflow-hidden">
                    <div class="timeline-time text-dark flex-shrink-0 text-end">09:30</div>
                    <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                      <span class="timeline-badge border-2 border border-primary flex-shrink-0 my-8"></span>
                      <span class="timeline-badge-border d-block flex-shrink-0"></span>
                    </div>
                    <div class="timeline-desc fs-3 text-dark mt-n1">Payment received from John Doe of $385.90</div>
                  </li>
                  <li class="timeline-item d-flex position-relative overflow-hidden">
                    <div class="timeline-time text-dark flex-shrink-0 text-end">10:00 am</div>
                    <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                      <span class="timeline-badge border-2 border border-info flex-shrink-0 my-8"></span>
                      <span class="timeline-badge-border d-block flex-shrink-0"></span>
                    </div>
                    <div class="timeline-desc fs-3 text-dark mt-n1 fw-semibold">New sale recorded <a
                        href="javascript:void(0)" class="text-primary d-block fw-normal">#ML-3467</a>
                    </div>
                  </li>
                  <li class="timeline-item d-flex position-relative overflow-hidden">
                    <div class="timeline-time text-dark flex-shrink-0 text-end">12:00 am</div>
                    <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                      <span class="timeline-badge border-2 border border-success flex-shrink-0 my-8"></span>
                      <span class="timeline-badge-border d-block flex-shrink-0"></span>
                    </div>
                    <div class="timeline-desc fs-3 text-dark mt-n1">Payment was made of $64.95 to Michael</div>
                  </li>
                  <li class="timeline-item d-flex position-relative overflow-hidden">
                    <div class="timeline-time text-dark flex-shrink-0 text-end">09:30 am</div>
                    <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                      <span class="timeline-badge border-2 border border-warning flex-shrink-0 my-8"></span>
                      <span class="timeline-badge-border d-block flex-shrink-0"></span>
                    </div>
                    <div class="timeline-desc fs-3 text-dark mt-n1 fw-semibold">New sale recorded <a
                        href="javascript:void(0)" class="text-primary d-block fw-normal">#ML-3467</a>
                    </div>
                  </li>
                  <li class="timeline-item d-flex position-relative overflow-hidden">
                    <div class="timeline-time text-dark flex-shrink-0 text-end">09:30 am</div>
                    <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                      <span class="timeline-badge border-2 border border-danger flex-shrink-0 my-8"></span>
                      <span class="timeline-badge-border d-block flex-shrink-0"></span>
                    </div>
                    <div class="timeline-desc fs-3 text-dark mt-n1 fw-semibold">New arrival recorded 
                    </div>
                  </li>
                  <li class="timeline-item d-flex position-relative overflow-hidden">
                    <div class="timeline-time text-dark flex-shrink-0 text-end">12:00 am</div>
                    <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                      <span class="timeline-badge border-2 border border-success flex-shrink-0 my-8"></span>
                    </div>
                    <div class="timeline-desc fs-3 text-dark mt-n1">Payment Done</div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-4">Recent Transactions</h5>
                <div class="table-responsive">
                  <table class="table text-nowrap mb-0 align-middle">
                    <thead class="text-dark fs-4">
                      <tr>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Id</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Assigned</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Name</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Priority</h6>
                        </th>
                        <th class="border-bottom-0">
                          <h6 class="fw-semibold mb-0">Budget</h6>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0">1</h6></td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-1">Sunil Joshi</h6>
                            <span class="fw-normal">Web Designer</span>                          
                        </td>
                        <td class="border-bottom-0">
                          <p class="mb-0 fw-normal">Elite Admin</p>
                        </td>
                        <td class="border-bottom-0">
                          <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-3 fw-semibold">Low</span>
                          </div>
                        </td>
                        <td class="border-bottom-0">
                          <h6 class="fw-semibold mb-0 fs-4">$3.9</h6>
                        </td>
                      </tr> 
                      <tr>
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0">2</h6></td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-1">Andrew McDownland</h6>
                            <span class="fw-normal">Project Manager</span>                          
                        </td>
                        <td class="border-bottom-0">
                          <p class="mb-0 fw-normal">Real Homes WP Theme</p>
                        </td>
                        <td class="border-bottom-0">
                          <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary rounded-3 fw-semibold">Medium</span>
                          </div>
                        </td>
                        <td class="border-bottom-0">
                          <h6 class="fw-semibold mb-0 fs-4">$24.5k</h6>
                        </td>
                      </tr> 
                      <tr>
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0">3</h6></td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-1">Christopher Jamil</h6>
                            <span class="fw-normal">Project Manager</span>                          
                        </td>
                        <td class="border-bottom-0">
                          <p class="mb-0 fw-normal">MedicalPro WP Theme</p>
                        </td>
                        <td class="border-bottom-0">
                          <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger rounded-3 fw-semibold">High</span>
                          </div>
                        </td>
                        <td class="border-bottom-0">
                          <h6 class="fw-semibold mb-0 fs-4">$12.8k</h6>
                        </td>
                      </tr>      
                      <tr>
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0">4</h6></td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-1">Nirav Joshi</h6>
                            <span class="fw-normal">Frontend Engineer</span>                          
                        </td>
                        <td class="border-bottom-0">
                          <p class="mb-0 fw-normal">Hosting Press HTML</p>
                        </td>
                        <td class="border-bottom-0">
                          <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success rounded-3 fw-semibold">Critical</span>
                          </div>
                        </td>
                        <td class="border-bottom-0">
                          <h6 class="fw-semibold mb-0 fs-4">$2.4k</h6>
                        </td>
                      </tr>                       
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

<div class="row">
          <div class="col-sm-6 col-xl-3 d-flex">
            <div class="card overflow-hidden rounded-2 h-100 w-100">
              <div class="position-relative">
                <a href="javascript:void(0)">
                  <img src="{{ asset('images/products/bukufolio.jpg') }}" class="card-img-top rounded-0" alt="logo" style="height:180px; object-fit:cover;">
                </a>
                <a href="javascript:void(0)" class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart">
                  <i class="ti ti-basket fs-4"></i>
                </a>
              </div>
              <div class="card-body pt-3 p-4">
                <h6 class="fw-semibold fs-4">Buku Folio</h6>
                <div class="d-flex align-items-center justify-content-between">
                  <h6 class="fw-semibold fs-4 mb-0">Rp. 65.000</h6>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3 d-flex">
            <div class="card overflow-hidden rounded-2 h-100 w-100">
              <div class="position-relative">
                <a href="javascript:void(0)">
                  <img src="{{ asset('images/products/bukukas.jpg') }}" class="card-img-top rounded-0" alt="c" style="height:180px; object-fit:cover;">
                </a>
                <a href="javascript:void(0)" class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart">
                  <i class="ti ti-basket fs-4"></i>
                </a>
              </div>
              <div class="card-body pt-3 p-4">
                <h6 class="fw-semibold fs-4">Buku Kas 3 Kolom</h6>
                <div class="d-flex align-items-center justify-content-between">
                  <h6 class="fw-semibold fs-4 mb-0">Rp. 28.500</h6>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3 d-flex">
            <div class="card overflow-hidden rounded-2 h-100 w-100">
              <div class="position-relative">
                <a href="javascript:void(0)">
                  <img src="{{ asset('images/products/kertasA4.jpg') }}" class="card-img-top rounded-0" alt="b" style="height:180px; object-fit:cover;">
                </a>
                <a href="javascript:void(0)" class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart">
                  <i class="ti ti-basket fs-4"></i>
                </a>
              </div>
              <div class="card-body pt-3 p-4">
                <h6 class="fw-semibold fs-4">Kertas A4</h6>
                <div class="d-flex align-items-center justify-content-between">
                  <h6 class="fw-semibold fs-4 mb-0">Rp. 55.000</h6>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3 d-flex">
            <div class="card overflow-hidden rounded-2 h-100 w-100">
              <div class="position-relative">
                <a href="javascript:void(0)">
                  <img src="{{ asset('images/products/kertasF4.jpg') }}" class="card-img-top rounded-0" alt="a" style="height:180px; object-fit:cover;">
                </a>
                <a href="javascript:void(0)" class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart">
                  <i class="ti ti-basket fs-4"></i>
                </a>
              </div>
              <div class="card-body pt-3 p-4">
                <h6 class="fw-semibold fs-4">Kertas F4 70gr Sidu</h6>
                <div class="d-flex align-items-center justify-content-between">
                  <h6 class="fw-semibold fs-4 mb-0">Rp. 59.000</h6>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3 d-flex">
            <div class="card overflow-hidden rounded-2 h-100 w-100">
              <div class="position-relative">
                <a href="javascript:void(0)">
                  <img src="{{ asset('images/products/flashdisk.jpg') }}" class="card-img-top rounded-0" alt="logo" style="height:180px; object-fit:cover;">
                </a>
                <a href="javascript:void(0)" class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart">
                  <i class="ti ti-basket fs-4"></i>
                </a>
              </div>
              <div class="card-body pt-3 p-4">
                <h6 class="fw-semibold fs-4">Flashdisk 64 Gb Sandisk</h6>
                <div class="d-flex align-items-center justify-content-between">
                  <h6 class="fw-semibold fs-4 mb-0">Rp. 115.000</h6>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3 d-flex">
            <div class="card overflow-hidden rounded-2 h-100 w-100">
              <div class="position-relative">
                <a href="javascript:void(0)">
                  <img src="{{ asset('images/products/tintaprint.jpg') }}" class="card-img-top rounded-0" alt="c" style="height:180px; object-fit:cover;">
                </a>
                <a href="javascript:void(0)" class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart">
                  <i class="ti ti-basket fs-4"></i>
                </a>
              </div>
              <div class="card-body pt-3 p-4">
                <h6 class="fw-semibold fs-4">Tinta Printer 200 ML Black</h6>
                <div class="d-flex align-items-center justify-content-between">
                  <h6 class="fw-semibold fs-4 mb-0">Rp. 60.000</h6>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3 d-flex">
            <div class="card overflow-hidden rounded-2 h-100 w-100">
              <div class="position-relative">
                <a href="javascript:void(0)">
                  <img src="{{ asset('images/products/tintaprinter003black.jpg') }}" class="card-img-top rounded-0" alt="b" style="height:180px; object-fit:cover;">
                </a>
                <a href="javascript:void(0)" class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart">
                  <i class="ti ti-basket fs-4"></i>
                </a>
              </div>
              <div class="card-body pt-3 p-4">
                <h6 class="fw-semibold fs-4">Tinta Epson 003 Black</h6>
                <div class="d-flex align-items-center justify-content-between">
                  <h6 class="fw-semibold fs-4 mb-0">Rp. 88.000</h6>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3 d-flex">
            <div class="card overflow-hidden rounded-2 h-100 w-100">
              <div class="position-relative">
                <a href="javascript:void(0)">
                  <img src="{{ asset('images/products/isolasikertas.jpg') }}" class="card-img-top rounded-0" alt="a" style="height:180px; object-fit:cover;">
                </a>
                <a href="javascript:void(0)" class="bg-primary rounded-circle p-2 text-white d-inline-flex position-absolute bottom-0 end-0 mb-n3 me-3"
                   data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Add To Cart">
                  <i class="ti ti-basket fs-4"></i>
                </a>
              </div>
              <div class="card-body pt-3 p-4">
                <h6 class="fw-semibold fs-4">Isolasi Kertas 1 inch</h6>
                <div class="d-flex align-items-center justify-content-between">
                  <h6 class="fw-semibold fs-4 mb-0">Rp. 8.000</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
</div>

@endsection
