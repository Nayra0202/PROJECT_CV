<!DOCTYPE html>
<html>
<head>
    <title>Surat Jalan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 13px;
            margin: 40px;
        }
        .header {
            text-align: left;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid black;
        }
        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .footer .ttd {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <p><strong>Palembang, {{ \Carbon\Carbon::parse($suratJalan->tanggal)->translatedFormat('d F Y') }}</strong></p>
        <p><strong>Tuan :</strong> {{ $suratJalan->nama_pemesan ?? 'nama_pemesan' }}</p>
        <p><strong>Alamat :</strong> {{ $suratJalan->alamat ?? 'alamat' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>BANYAKNYA</th>
                <th>NAMA BARANG</th>
                <th>HARGA</th>
                <th>JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suratJalan->permintaan->detailPermintaan as $detail)
                <tr>
                    <td>{{ $detail->jumlah }} {{ $detail->satuan }}</td>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td></td> <!-- Kosongkan harga kalau belum ada -->
                    <td></td> <!-- Kosongkan jumlah total kalau belum ada -->
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>
            <p><strong>Tanda Terima</strong></p>
        </div>
        <div class="ttd">
            <p><strong>Hormat Kami</strong></p>
            <br><br>
            <p>(_____________________)</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>