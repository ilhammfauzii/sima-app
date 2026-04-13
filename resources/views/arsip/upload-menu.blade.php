@extends('layouts.app')

@section('title', 'Pilih Jenis Upload')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="h2 fw-semibold text-gray-800">Upload Dokumen</h1>
        <p class="text-muted mb-0">Pilih jenis dokumen yang ingin Anda unggah.</p>
    </div>

    <div class="row justify-content-center g-4">

        <div class="col-md-5">
            <div class="card card-modern h-100 text-center py-4">
                <div class="card-body px-4">
                    <i class="fas fa-lock fa-3x text-warning mb-3"></i>

                    <h5 class="fw-bold text-dark mb-2">Dokumen Rahasia</h5>
                    <p class="text-muted small mb-3">
                        Dokumen akan diamankan menggunakan enkripsi sehingga hanya penerima yang memiliki kunci akses yang dapat membukanya.
                    </p>

                    <a href="{{ route('arsip.create-rahasia') }}" class="btn btn-warning px-4">
                        <i class="fas fa-upload me-1"></i>
                        Upload Dokumen Rahasia
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card card-modern h-100 text-center py-4">
                <div class="card-body px-4">
                    <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>

                    <h5 class="fw-bold text-dark mb-2">Dokumen Biasa</h5>
                    <p class="text-muted small mb-3">
                        Dokumen disimpan tanpa enkripsi dan dapat diakses langsung oleh pengguna sistem.
                    </p>

                    <a href="{{ route('arsip.create-biasa') }}" class="btn btn-primary px-4">
                        <i class="fas fa-upload me-1"></i>
                        Upload Dokumen Biasa
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection