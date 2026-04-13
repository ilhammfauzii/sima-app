@extends('layouts.app')

@section('title', 'Dekripsi File')

@section('content')
<div class="container py-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 mb-md-0 text-gray-800 fw-semibold">DEKRIPSI FILE RAHASIA
    </div>

    <div class="card-modern">
        <div class="card-header bg-warning text-dark">
            <i class="fas fa-unlock me-2"></i>Form Dekripsi File
        </div>
        <div class="card-body p-4">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('arsip.proses-dekripsi') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="file_id" class="form-label fw-semibold">ID Dokumen</label>
                    <input type="text" name="file_id" id="file_id" class="form-control" placeholder="Masukkan ID dokumen" value="{{ old('file_id') }}" required autofocus>
                    <div class="form-text">
                        ID dokumen bisa dilihat di email notifikasi atau di daftar arsip
                    </div>
                    @error('file_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kunci_enkripsi" class="form-label fw-semibold">Kunci Enkripsi</label>
                    <input type="text" name="kunci_enkripsi" id="kunci_enkripsi" class="form-control text-uppercase" placeholder="Masukkan kunci enkripsi (16 karakter)" value="{{ old('kunci_enkripsi') }}" required maxlength="16" pattern="[A-Z2-9]{16}" title="Hanya huruf A-Z dan angka 2-9, 16 karakter">
                    <div class="form-text">
                        Masukkan kunci enkripsi 16 karakter yang dikirim via email
                    </div>
                    @error('kunci_enkripsi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-warning">
                    <small>
                        <strong>Informasi Dekripsi:</strong><br>
                        • Pastikan ID dokumen dan kunci enkripsi sesuai dengan yang dikirim via email<br>
                        • File akan langsung terunduh setelah berhasil didekripsi<br>
                        • Hanya dokumen dengan kategori <strong>Rahasia</strong> yang perlu didekripsi
                    </small>
                </div>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-unlock me-1"></i> Dekripsi & Download
                    </button>
                    <a href="{{ route('arsip.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card-modern mt-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-question-circle me-2"></i>Cara Mendapatkan ID Dokumen & Kunci
        </div>
        <div class="card-body">
            <ol class="mb-0 ps-3">
                <li>Buka email notifikasi enkripsi yang dikirim sistem</li>
                <li>Cari bagian <strong>"ID Dokumen"</strong> dalam email - catat ID tersebut</li>
                <li>Salin <strong>"Kunci Enkripsi"</strong> dari email</li>
                <li>Masukkan kedua informasi tersebut ke form di atas</li>
            </ol>
        </div>
    </div>
</div>
@endsection