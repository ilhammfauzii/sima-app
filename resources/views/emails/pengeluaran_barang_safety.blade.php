<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pengeluaran Barang Safety {{ config('app.name') }}</title>
    <style>
        body { 
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; 
            line-height: 1.6; 
            color: #2d3748; 
            margin: 0; 
            padding: 0; 
            background-color: #f5f7fa; 
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: #ffffff; 
            border-radius: 6px;
            overflow: hidden;
        }
        .header { 
            background: #2b6cb0; 
            color: white; 
            padding: 24px; 
            text-align: center; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 20px; 
            font-weight: 600; 
            letter-spacing: 0.3px;
        }
        .sub-header {
            font-size: 13px;
            opacity: 0.9;
            margin-top: 4px;
        }
        .content { 
            padding: 32px; 
        }
        .greeting { 
            font-size: 16px; 
            color: #2d3748; 
            margin-bottom: 20px; 
        }
        .highlight {
            background: #ebf8ff;
            border-left: 4px solid #3182ce;
            padding: 14px 16px;
            margin: 16px 0 24px;
            border-radius: 4px;
            font-size: 14px;
            color: #2c5282;
        }
        .info-box { 
            background: #f8fafc; 
            border: 1px solid #e2e8f0; 
            border-radius: 6px; 
            padding: 20px; 
            margin: 20px 0; 
        }
        .info-title { 
            font-size: 16px; 
            font-weight: 600; 
            color: #2b6cb0; 
            margin-bottom: 16px; 
        }
        .info-item { 
            display: flex; 
            margin-bottom: 10px; 
            font-size: 14px;
        }
        .info-label { 
            min-width: 150px; 
            font-weight: 500; 
            color: #4a5568; 
        }
        .info-value { 
            color: #2d3748; 
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13px;
        }
        .item-table th, .item-table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }
        .item-table th {
            background: #edf2f7;
            font-weight: 600;
            color: #2d3748;
        }
        .footer { 
            background: #f7fafc; 
            border-top: 1px solid #e2e8f0; 
            padding: 20px 32px; 
            text-align: center; 
            font-size: 12px; 
            color: #718096; 
        }
        .footer-contact { 
            margin-top: 6px; 
            font-size: 11px; 
        }
        @media (max-width: 600px) {
            .content { padding: 20px; }
            .info-item { flex-direction: column; }
            .info-label { min-width: auto; margin-bottom: 2px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name', 'Sistem Inventaris') }}</h1>
            <div class="sub-header">Notifikasi Pengeluaran Barang Safety</div>
        </div>

        <div class="content">
            <div class="greeting">
                Kepada Yth. <strong>{{ $pic->nama ?? 'PIC Barang Safety' }}</strong>,
            </div>

            <div class="highlight">
                Anda tercatat sebagai <strong>PIC/Admin</strong> untuk pengeluaran barang <strong>Safety</strong> pada Sistem Inventaris Manajemen Aset.
            </div>

            <p>
                Berikut adalah ringkasan data pengeluaran barang Safety yang telah disetujui:
            </p>

            <div class="info-box">
                <div class="info-title">Informasi Pengeluaran</div>
                <div class="info-item">
                    <div class="info-label">Tanggal Keluar</div>
                    <div class="info-value">: {{ \Carbon\Carbon::parse($pengeluaran->tanggal_keluar)->translatedFormat('d F Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nama Peminjam</div>
                    <div class="info-value">: {{ $pengeluaran->user->nama ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Customer / Proyek</div>
                    <div class="info-value">: {{ $pengeluaran->customer->nama_customer ?? '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Lokasi</div>
                    <div class="info-value">: {{ $pengeluaran->customer->alamat_lengkap ?? '-' }}</div>
                </div>
                @if($pengeluaran->keterangan)
                <div class="info-item">
                    <div class="info-label">Keterangan</div>
                    <div class="info-value">: {{ $pengeluaran->keterangan }}</div>
                </div>
                @endif
            </div>

            <table class="item-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengeluaran->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->masterBarang->kode_barang ?? '-' }}</td>
                        <td>{{ $item->masterBarang->nama_barang ?? '-' }}</td>
                        <td>{{ $item->pivot->jumlah_keluar }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p style="margin-top: 20px;">
                Apabila terdapat ketidaksesuaian data atau kendala terkait pengeluaran barang Safety ini, 
                mohon segera melakukan pengecekan melalui sistem.
            </p>

            <p style="margin-top: 24px;">
                Terima kasih atas kerja sama dan tanggung jawab Anda.
            </p>

            <p>
                Hormat kami,<br>
                <strong>{{ config('app.name', 'Sistem Inventaris Manajemen Aset (SIMA)') }}</strong>
            </p>
        </div>

        <div class="footer">
            <div>Email ini dikirim otomatis oleh sistem.</div>
            <div class="footer-contact">
                Untuk bantuan: {{ config('mail.support', 'sisteminventarismanajemenaset@gmail.com') }}
            </div>
            <div style="margin-top: 12px; color: #a0aec0;">
                © {{ date('Y') }} {{ config('app.name') }}
            </div>
        </div>
    </div>
</body>
</html>