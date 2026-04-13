@extends('layouts.app')

@section('title', 'Import Alat Safety')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800 font-weight-bold">
        IMPORT ALAT SAFETY
    </h1>

    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="alert alert-info">
                <strong>Catatan:</strong>
                <ul class="mb-0">
                    <li>Gunakan <b>kode_barang</b></li>
                    <li>Kategori harus <b>Safety</b></li>
                    <li>Jumlah wajib angka</li>
                </ul>
            </div>

            <div class="mb-3">
                <a href="{{ route('barang_safety.template') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-download"></i> Download Template
                </a>
            </div>

            <form action="{{ route('barang_safety.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label>File Excel</label>
                    <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('pengadaan_barang_safety.menu') }}" class="btn btn-secondary mr-2">
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