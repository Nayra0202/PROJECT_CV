<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Barang</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .filter-info {
            margin-top: 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/logos/logocv-removebg-preview.png') }}" alt="Logo">
        <h1>CV Batu Kristal</h1>
        <p>JL. May Salim Batubara Gg. Nurul Iman 72/1844 RT 006/02 Sekip Jaya, Palembang</p>
    </div>

    <h2 style="text-align: center; margin-top: 30px;">Laporan Barang</h2> 

    @if($filter && $value)
        <p class="filter-info"><strong>Filter:</strong> {{ ucfirst($filter) }} - {{ $value }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID Barang</th>
                <th>Nama Barang</th>
                <th>Stok Awal</th>
                <th>Tanggal Masuk • Jumlah Masuk</th>
                <th>Tanggal Keluar • Jumlah Keluar</th>
                <th>Stok Saat Ini</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $barang)
                <tr>
                    <td>{{ $barang['id_barang'] }}</td>
                    <td>{{ $barang['nama_barang'] }}</td>
                    <td>{{ $barang['stok_awal'] }}</td>
                    <td>
                        {{ $barang['tgl_masuk'] ?? '' }}
                        @if($barang['jumlah_masuk'] > 0)
                            • {{ $barang['jumlah_masuk'] }} {{ $barang['satuan'] }}
                        @endif
                    </td>
                    <td>
                        {{ $barang['tgl_keluar'] ?? '' }}
                        @if($barang['jumlah_keluar'] > 0)
                            • {{ $barang['jumlah_keluar'] }} {{ $barang['satuan'] }}
                        @endif
                    </td>
                    <td>{{ $barang['stok_akhir'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>
