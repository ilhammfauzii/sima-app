@extends('layouts.app')

@section('title', 'Import Alat Engineer')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800 font-weight-bold">
        IMPORT ALAT ENGINEER
    </h1>

    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="alert alert-info">
                <strong>Catatan:</strong>
                <ul class="mb-0">
                    <li>Gunakan <b>kode_barang</b></li>
                    <li>Kategori barang harus <b>Engineer</b></li>
                    <li>Jumlah wajib angka</li>
                </ul>
            </div>

            <div class="mb-3">
                <a href="{{ route('barang_engineer.template') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-download"></i> Download Template
                </a>
            </div>

            <form action="{{ route('barang_engineer.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label>File Excel</label>
                    <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".xlsx,.xls,.csv" required>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('barang_engineer.menu') }}" class="btn btn-secondary mr-2">
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Import Data
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection