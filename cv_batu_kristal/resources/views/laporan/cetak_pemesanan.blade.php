<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Pemesanan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 13px;
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }

        .header img {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }

        .header p {
            margin: 2px;
            font-size: 12px;
        }

        .tanggal-cetak {
            text-align: right;
            font-size: 12px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .filter-info {
            margin-top: 10px;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid black;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }

        ul {
            padding-left: 18px;
            margin: 0 0 5px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/logos/logocv-removebg-preview.png') }}" alt="Logo">
        <h1>CV Batu Kristal</h1>
        <p>JL. May Salim Batubara Gg. Nurul Iman 72/1844 RT 006/02 Sekip Jaya, Palembang</p>
    </div>

    <h2 style="text-align: center; margin-top: 30px;">Laporan Pemesanan Barang</h2>

    @if($filter && $value)
        <p class="filter-info"><strong>Filter:</strong> {{ ucfirst($filter) }} - {{ $value }}</p>
    @endif

    <div class="tanggal-cetak">
        Tanggal : {{ \Carbon\Carbon::now()->format('d-m-Y') }} <br>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Pemesanan</th>
                <th>Tanggal</th>
                <th>Nama Pemesan</th>
                <th>Alamat</th>
                <th>Daftar Barang</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pemesanans as $key => $pemesanan)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $pemesanan->id_pemesanan }}</td>
                <td>{{ \Carbon\Carbon::parse($pemesanan->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $pemesanan->nama_pemesan ?? '-' }}</td>
                <td>{{ $pemesanan->alamat ?? '-' }}</td>
                <td>
                    <ul>
                        @foreach ($pemesanan->detailPemesanan as $detail)
                            <li>
                                {{ $detail->barang->nama_barang ?? '-' }} - 
                                {{ $detail->jumlah }} {{ $detail->barang->satuan ?? '' }}
                                (Rp{{ number_format($detail->total_harga, 0, ',', '.') }})
                            </li>
                        @endforeach
                    </ul>
                    <strong>Total Bayar: Rp{{ number_format($pemesanan->total_bayar ?? 0, 0, ',', '.') }}</strong>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>