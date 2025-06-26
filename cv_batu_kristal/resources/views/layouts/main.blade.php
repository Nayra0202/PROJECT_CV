<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CV Batu Kristal</title>
  <link rel="shortcut icon" href="{{ url('images/logos/logocv-removebg-preview.png') }}" />
  <link rel="stylesheet" href="{{ url('css/styles.min.css') }}" />
  
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="index.html" class="text-nowrap logo-img mb-2">
            <img src="{{ asset ('images/logos/logocv.png') }}" width="50" alt="logo" />
          </a>
          <span class="fw-bold fs-5 text-dark text-center mb-2">CV BATU KRISTAL</span>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Home</span>
            </li>
            {{-- Dashboard: Semua role dapat --}}
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('dashboard') }}">
                <span><i class="ti ti-layout-dashboard"></i></span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>

            {{-- Klien: Dashboard & Pemesanan --}}
            @if(auth()->user()->role == 'Klien')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('permintaan.index') }}">
                        <span><i class="ti ti-cards"></i></span>
                        <span class="hide-menu">Pemesanan</span>
                    </a>
                </li>
            @endif

            {{-- Direktur: Dashboard, Barang, Pemesanan, Laporan Barang, Laporan Pemesanan --}}
            @if(auth()->user()->role == 'Direktur')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('barang.index') }}">
                        <span><i class="ti ti-article"></i></span>
                        <span class="hide-menu">Barang</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('permintaan.index') }}">
                        <span><i class="ti ti-cards"></i></span>
                        <span class="hide-menu">Pemesanan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('laporan.barang') }}">
                        <span><i class="ti ti-report"></i></span>
                        <span class="hide-menu">Laporan Barang</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('laporan.permintaan') }}">
                        <span><i class="ti ti-file-invoice"></i></span>
                        <span class="hide-menu">Laporan Pemesanan</span>
                    </a>
                </li>
            @endif

            {{-- Sales Manager: Dashboard, Barang, Laporan Pemesanan --}}
            @if(auth()->user()->role == 'Sales Manager')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('barang.index') }}">
                        <span><i class="ti ti-article"></i></span>
                        <span class="hide-menu">Barang</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('laporan.permintaan') }}">
                        <span><i class="ti ti-file-invoice"></i></span>
                        <span class="hide-menu">Laporan Pemesanan</span>
                    </a>
                </li>
            @endif

            {{-- Sales Marketing: Dashboard, Barang, Pemesanan --}}
            @if(auth()->user()->role == 'Sales Marketing')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('barang.index') }}">
                        <span><i class="ti ti-article"></i></span>
                        <span class="hide-menu">Barang</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('permintaan.index') }}">
                        <span><i class="ti ti-cards"></i></span>
                        <span class="hide-menu">Pemesanan</span>
                    </a>
                </li>
            @endif

            {{-- Sekretaris: Dashboard, Barang, Pemesanan, Laporan Barang, Laporan Pemesanan --}}
            @if(auth()->user()->role == 'Sekretaris')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('barang.index') }}">
                        <span><i class="ti ti-article"></i></span>
                        <span class="hide-menu">Barang</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('permintaan.index') }}">
                        <span><i class="ti ti-cards"></i></span>
                        <span class="hide-menu">Pemesanan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('laporan.barang') }}">
                        <span><i class="ti ti-report"></i></span>
                        <span class="hide-menu">Laporan Barang</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('laporan.permintaan') }}">
                        <span><i class="ti ti-file-invoice"></i></span>
                        <span class="hide-menu">Laporan Pemesanan</span>
                    </a>
                </li>
            @endif

            {{-- Bagian Gudang: Dashboard, Barang, Barang Masuk, Barang Keluar, Surat Jalan --}}
            @if(auth()->user()->role == 'Bagian Gudang')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('barang.index') }}">
                        <span><i class="ti ti-article"></i></span>
                        <span class="hide-menu">Barang</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('barang_masuk.index') }}">
                        <span><i class="ti ti-alert-circle"></i></span>
                        <span class="hide-menu">Barang Masuk</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('barang_keluar.index') }}">
                        <span><i class="ti ti-file-description"></i></span>
                        <span class="hide-menu">Barang Keluar</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('surat_jalan.index') }}">
                        <span><i class="ti ti-typography"></i></span>
                        <span class="hide-menu">Surat Jalan</span>
                    </a>
                </li>
            @endif

            {{-- Bagian Pengiriman: Dashboard, Pemesanan, Surat Jalan --}}
            @if(auth()->user()->role == 'Bagian Pengiriman')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('permintaan.index') }}">
                        <span><i class="ti ti-cards"></i></span>
                        <span class="hide-menu">Pemesanan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('surat_jalan.index') }}">
                        <span><i class="ti ti-typography"></i></span>
                        <span class="hide-menu">Surat Jalan</span>
                    </a>
                </li>
            @endif
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <span id="clock" class="mx-3 fw-bold"></span>
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="{{ asset('images/profile/user.png') }}" alt="profil" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="{{ route('profile.show') }}" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf

                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                                                        this.closest('form').submit();"
                                            class="dropdown-item">

                                            <i class="mdi mdi-logout"></i>
                                            <span class="ml-2">Logout </span>
                                        </x-dropdown-link>
                                    </form>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
      <div class="container-fluid">
        <div class="row mb-4">
          <div class="col-12">
            <div class="card bg-primary text-white shadow">
              <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                  <h3 class="fw-bold mb-1">Selamat Datang di Dashboard CV. Batu Kristal</h3>
                  <p class="mb-0 fs-5">Sistem Informasi Pemesanan Barang Secara Online </p>
                </div>
                <div>
                  <img src="{{ asset('images/logos/logocv-removebg-preview.png') }}" alt="Logo" style="width:70px;">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 col-xl-12 d-flex">
            <div class="card overflow-hidden rounded-2 h-100 w-100">
              <div class="position-relative">
                </div>
                  @yield('content')
                </div>
              </div>
            </div>
          </div>
        </div>

        
        <div class="py-6 px-6 text-center d-flex justify-content-center align-items-center" style="min-height:80px;">
          <p class="mb-0 fs-4">Aplikasi dibuat oleh Nayra Alya Denita - Universitas MDP</p>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/sidebarmenu.j') }}s"></script>
  <script src="{{ asset('js/app.min.js') }}"></script>
  <script src="{{ asset('libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('js/dashboard.js') }}"></script>
  <script>
  function updateClock() {
    const now = new Date();
    const jam = now.getHours().toString().padStart(2, '0');
    const menit = now.getMinutes().toString().padStart(2, '0');
    const detik = now.getSeconds().toString().padStart(2, '0');
    document.getElementById('clock').textContent = jam + ':' + menit + ':' + detik;
  }
  setInterval(updateClock, 1000);
  updateClock();
</script>
</body>

</html>