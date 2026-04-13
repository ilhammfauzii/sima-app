@extends('layouts.app')

@section('title', 'Menu Pengadaan Alat Engineer')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
            MENU PENGADAAN ALAT ENGINEER
        </h1>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-6 mb-4">
            <a href="{{ route('barang_engineer.create') }}" class="card shadow h-100 py-4 text-decoration-none border-0 menu-card">
                <div class="card-body text-center">
                    <i class="fas fa-plus-square fa-3x text-primary mb-3"></i>
                    <h5 class="font-weight-bold text-dark">Tambah Stok Manual</h5>
                    <p class="text-muted small">
                        Input alat engineer dan stok secara manual
                    </p>
                    <span class="btn btn-primary btn-sm px-4">Pilih</span>
                </div>
            </a>
        </div>

        <div class="col-md-6 mb-4">
            <a href="{{ route('barang_engineer.import.form') }}" class="card shadow h-100 py-4 text-decoration-none border-0 menu-card">
                <div class="card-body text-center">
                    <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                    <h5 class="font-weight-bold text-dark">Import dari Excel</h5>
                    <p class="text-muted small">
                        Import stok alat engineer menggunakan file Excel
                    </p>
                    <span class="btn btn-success btn-sm px-4">Import</span>
                </div>
            </a>
        </div>

    </div>
</div>
@endsection