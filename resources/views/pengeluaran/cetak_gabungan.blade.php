<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Surat Pengeluaran Barang - {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
    </title>

    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt;
            color: #000;
        }

        .container {
            width: 95%;
            margin: 0 auto; 
        }

        .header-container {
            display: flex;
            align-items: center;
            padding-bottom: 5px;
        }

        .header-logo {
            width: 150px;
            height: auto;
            display: block;
            object-fit: contain;
            margin-right: 20px;
        }

        .header-text {
            line-height: 1.2;
            flex-grow: 1; 
        }

        .header-text h2 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold; 
        }

        .header-text p {
            margin: 0; 
            font-size: 12pt; 
        }

        .header-line {
            border: 0;
            border-top: 4px solid black; 
            margin: 5px 0 20px; 
        }

        .letter-title {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
        }

        .info-section table {
            width: auto; 
        }

        .info-section td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-section p {
            margin: 3px 0;
        }

        .item-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }

        .item-table th, 
        .item-table td { 
            border: 1px solid black; 
            padding: 8px; 
            text-align: center; 
            vertical-align: middle; 
        }

        .item-table th:nth-child(1) { width: 5%; }
        .item-table th:nth-child(2) { width: 25%; }
        .item-table th:nth-child(3) { width: 35%; }
        .item-table th:nth-child(4) { width: 20%; }
        .item-table th:nth-child(5) { width: 15%; }

        .signatures { 
            width: 100%; 
            margin-top: 40px; 
        }

        .signatures td { 
            text-align: center; 
            width: 50%; 
            vertical-align: top; 
        }

        .signature-space { 
            height: 80px; 
        }

        .signature-name { 
            font-weight: normal; 
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header-container">
        <img src="{{ url('img/logo-perusahaan-kop.png') }}" width="140">
        <div class="header-text">
            <h2>PT. XYZ</h2>
            <p>Jl Raya Serang, Kota Serang</p>
            <p>Telp (0254)123456 | Email: info@perusahaanxyz.com</p>
        </div>
    </div>

    <hr class="header-line">

    <h3 class="letter-title">SURAT PENGELUARAN BARANG</h3>

    <div class="info-section">
        <table>
            <tr>
                <td style="width:120px;">Nomor Surat</td>
                <td style="width:5px;">:</td>
                <td>{{ $nomor_surat }}</td>
            </tr>
            <tr>
                <td>Tanggal Keluar</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Peminjam / Proyek</td>
                <td>:</td>
                <td>{{ $peminjam }}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>{{ $keterangan }}</td>
            </tr>
            <tr>
                <td>Lokasi Tujuan</td>
                <td>:</td>
                <td>{{ $lokasi_batch }}</td>
            </tr>
        </table>

        <p style="margin-top:15px;">
            Daftar barang yang dikeluarkan adalah sebagai berikut :
        </p>
    </div>

    <table class="item-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gabungan as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data->kode_barang ?? '-' }}</td>
                <td>{{ $data->nama_barang ?? '-' }}</td>
                <td>{{ $data->kategori ?? '-' }}</td>
                <td>{{ $data->jumlah_keluar ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td></td>
            <td>Serang, {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>PIC Barang</td>
            <td>Penerima Barang</td>
        </tr>
        <tr>
            <td><div class="signature-space"></div></td>
            <td><div class="signature-space"></div></td>
        </tr>
        <tr>
            <td class="signature-name">{{ $pic_signature }}</td>
            <td class="signature-name">{{ $peminjam }}</td>
        </tr>
    </table>

</div>
</body>
</html>