@extends('layouts.app')

@section('title', 'Atur Kadaluarsa Dokumen')

@section('content')
<div class="container py-3">

    <h3 class="mb-4">Atur Tanggal Kadaluarsa</h3>

    <div class="card-modern">
        <div class="card-header bg-warning text-dark">
            <i class="fas fa-clock me-2"></i>Atur Kadaluarsa
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

            <form action="{{ route('arsip.kadaluarsa.update', $file->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama File</label>
                    <input type="text" class="form-control" value="{{ $file->nama_file_asli }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="kadaluarsa_pada" class="form-label">Tanggal & Waktu Kadaluarsa (Opsional)</label>
                    <input type="datetime-local" name="kadaluarsa_pada" id="kadaluarsa_pada" class="form-control" value="{{ $file->kadaluarsa_pada ? \Carbon\Carbon::parse($file->kadaluarsa_pada)->format('Y-m-d\TH:i') : '' }}">
                    <small class="text-muted d-block mt-2">Kosongkan jika ingin dokumen <strong>tidak kadaluarsa</strong>.</small>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="{{ route('arsip.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection