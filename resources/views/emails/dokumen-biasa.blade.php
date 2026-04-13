<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Dokumen - {{ config('app.name') }}</title>
    <style>
        body { font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background: #1a365d; color: white; padding: 24px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; font-weight: 600; }
        .content { padding: 32px; }
        .greeting { font-size: 16px; color: #2d3748; margin-bottom: 24px; }
        .info-box { background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 20px; margin: 20px 0; }
        .info-title { font-size: 16px; font-weight: 600; color: #2d3748; margin-bottom: 16px; }
        .info-item { display: flex; margin-bottom: 12px; }
        .info-label { min-width: 140px; font-weight: 500; color: #4a5568; }
        .info-value { color: #2d3748; }
        .steps { background: #edf2f7; border-left: 4px solid #2b6cb0; padding: 20px; margin: 24px 0; }
        .steps-title { font-size: 16px; font-weight: 600; color: #2d3748; margin-bottom: 12px; }
        .step { margin-bottom: 8px; }
        .note { background: #ebf8ff; border: 1px solid #bee3f8; padding: 16px; margin: 20px 0; border-radius: 4px; }
        .note-title { font-weight: 600; color: #2c5282; margin-bottom: 8px; }
        .footer { background: #f7fafc; border-top: 1px solid #e2e8f0; padding: 20px 32px; text-align: center; font-size: 12px; color: #718096; }
        .footer-contact { margin-top: 8px; font-size: 11px; }
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
            <h1>{{ config('app.name', 'Sistem Inventaris Manajemen Aset (SIMA)') }}</h1>
        </div>

        <div class="content">
            <div class="greeting">
                Kepada Yth. <strong>{{ $penerima['nama'] }}</strong>,
            </div>

            <p>Anda menerima dokumen biasa dari Sistem Inventaris Manajemen Aset (SIMA):</p>

            <div class="info-box">
                <div class="info-title">Detail Dokumen</div>
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
                @if($file->deskripsi)
                <div class="info-item">
                    <div class="info-label">Deskripsi:</div>
                    <div class="info-value">{{ $file->deskripsi }}</div>
                </div>
                @endif
            </div>

            <div class="steps">
                <div class="steps-title">Cara Akses Dokumen:</div>
                <div class="step">1. Login ke Sistem Inventaris Manajemen Aset (SIMA)</div>
                <div class="step">2. Pilih menu "Lihat Arsip"</div>
                <div class="step">3. Cari dokumen sesuai nama</div>
                <div class="step">4. Klik tombol Download</div>
            </div>

            <div class="note">
                <div class="note-title">Catatan:</div>
                <p>Dokumen ini bersifat tidak rahasia dan dapat diakses tanpa kunci enkripsi.</p>
            </div>

            <p>Terima kasih atas perhatiannya.</p>
        </div>

        <div class="footer">
            <div>Email ini dikirim otomatis oleh Sistem Inventaris Manajemen Aset (SIMA).</div>
            <div class="footer-contact">
                Untuk bantuan: {{ config('mail.support', 'sisteminventarismanajemenaset@gmail.com') }}
            </div>
            <div style="margin-top: 16px; color: #a0aec0;">
                © {{ date('Y') }} {{ config('app.name') }}
            </div>
        </div>
    </div>
</body>
</html>