@php 
    function terbilang($angka) {
        $angka = abs($angka);
        $baca = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $hasil = "";

        if ($angka < 12) {
            $hasil = " " . $baca[$angka];
        } elseif ($angka < 20) {
            $hasil = terbilang($angka - 10) . " belas";
        } elseif ($angka < 100) {
            $hasil = terbilang(floor($angka / 10)) . " puluh" . terbilang($angka % 10);
        } elseif ($angka < 200) {
            $hasil = " seratus" . terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $hasil = terbilang(floor($angka / 100)) . " ratus" . terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $hasil = " seribu" . terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $hasil = terbilang(floor($angka / 1000)) . " ribu" . terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $hasil = terbilang(floor($angka / 1000000)) . " juta" . terbilang($angka % 1000000);
        }

        return trim($hasil);
    }
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Faktur Permintaan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 13px;
            margin: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .kanan-atas {
            text-align: right;
            margin-bottom: 30px;
        }

        .kanan-atas p {
            margin: 0;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <div class="kanan-atas">
        <p>Palembang, {{ \Carbon\Carbon::now()->translatedFormat('d F \'y') }}</p>
        <p>Kepada Yth,</p>
        <p>{{ $permintaan->nama_pemesan }}</p>
        <p>Di -</p>
        <p>{{ $permintaan->alamat ?? 'Palembang' }}</p>
    </div>

    <p><strong>Faktur Barang:</strong></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Banyak</th>
                <th>Satuan</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaan->detailPermintaan as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>{{ $detail->barang->satuan }}</td>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td>Rp {{ number_format($detail->barang->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" class="text-right"><strong>Jumlah</strong></td>
                <td><strong>Rp {{ number_format($permintaan->total_bayar, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top: 20px;">
        Terbilang: <strong>{{ ucwords(terbilang($permintaan->total_bayar)) }} rupiah</strong>
    </p>

</body>
</html>