@extends('layouts.app')

@section('title', 'Upload Dokumen Rahasia')

@section('content')
<div class="container py-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 mb-md-0 text-gray-800 fw-semibold">UPLOAD DOKUMEN RAHASIA</h1>
    </div>

    <div class="card-modern">
        <div class="card-header bg-warning fw-semibold">
            <i class="fas fa-lock me-2"></i>Form Upload Dokumen Rahasia
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

            <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data" id="encryptionForm">
                @csrf
                <input type="hidden" name="kategori" value="rahasia">

                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Dokumen Rahasia</strong> - File akan dienkripsi dengan algoritma AES-256. 
                    Kunci enkripsi akan dikirim via email ke semua penerima.
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
                        <input type="file" name="file" id="file" class="form-control" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls,.mp4,.avi,.mkv">
                        <small class="form-text text-muted">
                            Maks. 50MB | Format: PDF, DOC, DOCX, JPG, PNG, XLSX, MP4
                        </small>
                    </div>
                </div>

                <hr>

                <div class="mb-4">
                    <label class="form-label">Penerima <span class="text-danger">*</span></label>
                    <div id="penerima-container">
                        <div class="penerima-item mb-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-12 col-md-10">
                                    <select name="penerima[0][user_id]" class="form-select penerima-select" required>
                                        <option value="">Pilih User</option>
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
                        Uploader ({{ auth()->user()->nama }}) otomatis menerima email.
                    </small>
                </div>

                <hr>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat tentang file ini">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="kadaluarsa_pada" class="form-label">Kadaluarsa Pada (Opsional)</label>
                    <input type="datetime-local" name="kadaluarsa_pada" id="kadaluarsa_pada" class="form-control" value="{{ old('kadaluarsa_pada') }}">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin ada kadaluarsa.</small>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-lock me-1"></i> Enkripsi & Simpan
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