@extends('layouts.app')

@section('title', 'Upload Dokumen Biasa')

@section('content')
<div class="container py-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 mb-md-0 text-gray-800 fw-semibold">UPLOAD DOKUMEN BIASA</h1>
    </div>

    <div class="card-modern">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-file-alt me-2"></i>Form Upload Dokumen Biasa
        </div>
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kategori" value="tidak_rahasia">

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Dokumen Biasa</strong> - File disimpan tanpa enkripsi dan dapat diakses langsung oleh yang berwenang.
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="jenis_dokumen" class="form-label">
                            Jenis Dokumen <span class="text-danger">*</span>
                        </label>
                        <select name="jenis_dokumen" id="jenis_dokumen" class="form-select" required>
                            <option value="">Pilih Jenis Dokumen</option>
                            @foreach($jenisDokumen as $key => $value)
                                <option value="{{ $key }}" {{ old('jenis_dokumen') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="file" class="form-label">
                            File <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="file" id="file" class="form-control" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls">
                        <small class="form-text text-muted">
                            Maks. 10MB | Format: PDF, DOC, DOCX, JPG, PNG, XLSX
                        </small>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Penerima (Opsional)</label>
                    <div id="penerima-container">
                        <div class="penerima-item mb-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-12 col-md-10">
                                    <select name="penerima[0][user_id]" class="form-select penerima-select">
                                        <option value="">Pilih User (Opsional)</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-2 text-md-end mt-2 mt-md-0">
                                    <button type="button" class="btn btn-danger btn-sm w-100 w-md-auto hapus-penerima" style="display:none;">
                                        <i class="fas fa-times"></i> <span class="d-none d-sm-inline">Hapus</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="tambah-penerima" class="btn btn-outline-success mt-3">
                        <i class="fas fa-plus me-1"></i> Tambah Penerima
                    </button>

                    <small class="form-text text-muted mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>
                        Uploader ({{ auth()->user()->nama }}) otomatis menerima email. Pilih penerima lain jika ingin berbagi.
                    </small>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat tentang file ini">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="alert alert-light border mt-4">
                    <h6 class="fw-bold mb-2">Informasi Penyimpanan</h6>
                    <ul class="mb-0 ps-3">
                        <li>Dapat diakses langsung melalui menu Lihat Arsip</li>
                        <li>Notifikasi email dikirim ke penerima yang dipilih</li>
                        <li>Administrator dapat mengakses semua dokumen</li>
                    </ul>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i> Upload Dokumen
                    </button>
                    <a href="{{ route('arsip.upload-menu') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection