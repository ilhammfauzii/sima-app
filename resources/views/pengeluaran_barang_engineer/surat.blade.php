<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengeluaran Alat Engineer</title>
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
            padding-bottom: 20px;
        }
        .header-logo {
            width: 150px;
            height: auto;
            margin-right: 20px;
        }
        .header-text {
            line-height: 1.2;
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
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .letter-title {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
        }
        .info-section p {
            margin: 3px 0;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
        }
        .item-table th, .item-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

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
        .signatures .signature-name {
            font-weight: normal;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-container">
            <img src="{{ url('img/logo-perusahaan-kop.png') }}" width="150" alt="Logo">
            <div class="header-text">
                <h2>PT. XYZ</h2>
                <p>Jl Raya Serang, Kota Serang</p>
                <p>Telp (0254)123456 | Email: info@perusahaanxyz.com</p>
            </div>
        </div>

        <hr class="header-line">

        <h3 class="letter-title">SURAT PENGELUARAN ALAT ENGINEER</h3>

        <div class="info-section">
            <table>
                <tr>
                    <td style="padding-right: 10px;">Nomor</td>
                    <td>: {{ 'SPTE-'.$pengeluaran->id.'/BE/'.\Carbon\Carbon::parse($pengeluaran->tanggal_keluar)->translatedFormat('m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding-right: 10px;">Tanggal</td>
                    <td>: {{ \Carbon\Carbon::parse($pengeluaran->tanggal_keluar)->translatedFormat('d F Y') }}</td>
                </tr>
            </table>
            <br>
            <p>Dengan ini surat dikeluarkan untuk pengeluaran tools engineer :</p>
            <p>Peminjam : {{ $pengeluaran->user?->nama ?? '' }}</p>
            @if($pengeluaran->keterangan)
            <p>Tujuan : {{ $pengeluaran->keterangan }}</p>
            <p>Lokasi : {{ optional($pengeluaran->customer)->alamat_lengkap ?? '-' }}</p>
            @endif
            <br>
            <p>Alat yang dikeluarkan adalah sebagai berikut :</p>
        </div>

        <table class="item-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Keluar</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($pengeluaran->items) && count($pengeluaran->items) > 0)
                    @foreach($pengeluaran->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->masterBarang->kode_barang }}</td>
                        <td>{{ $item->masterBarang->nama_barang }}</td>
                        <td>{{ $item->pivot->jumlah_keluar }}</td>
                        <td>{{ \Carbon\Carbon::parse($pengeluaran->tanggal_keluar)->format('d-m-Y') }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">Tidak ada barang yang dikeluarkan.</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <table class="signatures">
            <tr>
                <td></td> <td>Serang, {{ \Carbon\Carbon::parse($pengeluaran->tanggal_keluar)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>PIC Alat Engineer</td>
                <td>Peminjam Alat</td>
            </tr>
            <tr>
                <td><div class="signature-space"></div></td>
                <td><div class="signature-space"></div></td>
            </tr>
            <tr>
                <td class="signature-name">{{ $pengeluaran->pic?->nama ?? 'Super Admin' }}</td>
                <td class="signature-name">{{ $pengeluaran->user?->nama ?? 'Nama Peminjam' }}</td>
            </tr>
        </table>
    </div>
</body>
</html>