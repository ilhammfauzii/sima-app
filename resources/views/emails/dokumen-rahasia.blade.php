<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Rahasia - {{ config('app.name') }}</title>
    <style>
        body { font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background: #742a2a; color: white; padding: 24px; text-align: center; position: relative; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 600; }
        .confidential { position: absolute; top: 10px; right: 10px; background: #c53030; color: white; padding: 4px 8px; font-size: 11px; font-weight: 600; border-radius: 2px; }
        .content { padding: 32px; }
        .greeting { font-size: 16px; color: #2d3748; margin-bottom: 24px; }
        .warning { background: #fed7d7; border: 1px solid #fc8181; color: #742a2a; padding: 16px; margin: 16px 0; border-radius: 4px; }
        .warning-title { font-weight: 600; margin-bottom: 8px; }
        .info-box { background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 20px; margin: 20px 0; }
        .info-title { font-size: 16px; font-weight: 600; color: #2d3748; margin-bottom: 16px; }
        .info-item { display: flex; margin-bottom: 12px; }
        .info-label { min-width: 140px; font-weight: 500; color: #4a5568; }
        .info-value { color: #2d3748; }
        .encryption-key { background: #1a202c; color: white; font-family: 'Courier New', monospace; padding: 16px; margin: 20px 0; border-radius: 4px; text-align: center; font-size: 14px; word-break: break-all; }
        .key-label { font-size: 14px; font-weight: 600; margin-bottom: 8px; color: #cbd5e0; }
        .steps { background: #fed7d7; border-left: 4px solid #c53030; padding: 20px; margin: 24px 0; }
        .steps-title { font-size: 16px; font-weight: 600; color: #742a2a; margin-bottom: 12px; }
        .step { margin-bottom: 8px; }
        .security-notes { background: #fefcbf; border: 1px solid #f6e05e; padding: 16px; margin: 20px 0; border-radius: 4px; }
        .security-title { font-weight: 600; color: #744210; margin-bottom: 8px; }
        .footer { background: #f7fafc; border-top: 1px solid #e2e8f0; padding: 20px 32px; text-align: center; font-size: 12px; color: #718096; }
        .footer-contact { margin-top: 8px; font-size: 11px; }
        .footer-warning { color: #c53030; font-weight: 600; margin-top: 8px; }
        @media (max-width: 600px) {
            .content { padding: 20px; }
            .info-item { flex-direction: column; }
            .info-label { min-width: auto; margin-bottom: 4px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="confidential">RAHASIA</div>
            <h1>{{ config('app.name', 'Sistem Inventaris Manajemen Aset (SIMA)') }}</h1>
        </div>

        <div class="content">
            <div class="warning">
                <div class="warning-title">PERINGATAN: DOKUMEN RAHASIA</div>
                <p>Dokumen ini bersifat sangat rahasia. Jangan bagikan kunci enkripsi kepada siapapun.</p>
            </div>

            <div class="greeting">
                Kepada Yth. <strong>{{ $penerima['nama'] }}</strong>,
            </div>

            <p>Anda menerima dokumen rahasia yang telah dienkripsi:</p>

            <div class="info-box">
                <div class="info-title">Detail Dokumen</div>
                <div class="info-item">
                    <div class="info-label">ID Dokumen:</div>
                    <div class="info-value"><strong>{{ $file->id }}</strong></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nama File:</div>
                    <div class="info-value">{{ $file->nama_file_asli }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Jenis:</div>
                    <div class="info-value">{{ $file->label_jenis_dokumen ?? $file->jenis_dokumen }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Pengunggah:</div>
                    <div class="info-value">{{ $file->diuploadOleh->nama ?? 'Sistem' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Waktu:</div>
                    <div class="info-value">{{ $file->created_at->format('d/m/Y H:i') }}</div>
                </div>
                @if($file->kadaluarsa_pada)
                <div class="info-item">
                    <div class="info-label">Kadaluarsa:</div>
                    <div class="info-value">{{ $file->kadaluarsa_pada->format('d/m/Y H:i') }}</div>
                </div>
                @endif
            </div>

            <div class="encryption-key">
                <div class="key-label">KUNCI ENKRIPSI:</div>
                <div>{{ $kunci_enkripsi }}</div>
            </div>

            <div class="steps">
                <div class="steps-title">Proses Dekripsi:</div>
                <div class="step">1. Login ke Sistem Inventaris Manajemen Aset (SIMA)</div>
                <div class="step">2. Pilih menu "Dekripsi File"</div>
                <div class="step">3. Masukkan ID Dokumen: {{ $file->id }}</div>
                <div class="step">4. Tempelkan Kunci Enkripsi di atas</div>
                <div class="step">5. Klik "Dekripsi & Download"</div>
            </div>

            <div class="security-notes">
                <div class="security-title">Panduan Keamanan:</div>
                <ul style="margin: 0; padding-left: 20px; font-size: 13px;">
                    <li>Jangan bagikan kunci enkripsi melalui media apapun</li>
                    <li>Simpan kunci di tempat yang aman</li>
                    <li>Laporkan jika menduga kunci telah disalahgunakan</li>
                </ul>
            </div>

            <p>Kerahasiaan informasi adalah tanggung jawab bersama.</p>
        </div>

        <div class="footer">
            <div>Email ini dikirim otomatis oleh Sistem Inventaris Manajemen Aset (SIMA).</div>
            <div class="footer-warning">Email ini mengandung informasi rahasia.</div>
            <div class="footer-contact">
                Untuk masalah keamanan: {{ config('mail.security', 'sisteminventarismanajemenaset@gmail.com') }}
            </div>
            <div style="margin-top: 16px; color: #a0aec0;">
                © {{ date('Y') }} {{ config('app.name') }}
            </div>
        </div>
    </div>
</body>
</html>