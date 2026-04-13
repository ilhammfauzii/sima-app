@extends('layouts.app')

@section('title', 'Menu Pengeluaran Material Gudang')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">MENU PENGELUARAN MATERIAL INSTALASI</h1>
    </div>

    <div class="row">
        @can('create-pengeluaran')
        <div class="col-md-6 mb-4">
            <a href="{{ route('pengeluaran_barang_gudang.create') }}" class="card shadow h-100 py-4 text-decoration-none border-0 menu-card">
                <div class="card-body text-center">
                    <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                    <h5 class="font-weight-bold text-dark">Tambah Pengeluaran</h5>
                    <p class="text-muted small mb-2">Tambahkan data pengeluaran material baru.</p>
                    <span class="btn btn-primary btn-sm px-4">Tambah</span>
                </div>
            </a>
        </div>
        @endcan
        
        <div class="col-md-6 mb-4">
            <a href="{{ route('pengeluaran_barang_gudang.index') }}"class="card shadow h-100 py-4 text-decoration-none border-0 menu-card">
                <div class="card-body text-center">
                    <i class="fas fa-database fa-3x text-success mb-3"></i>
                    <h5 class="font-weight-bold text-dark">Data Pengeluaran</h5>
                    <p class="text-muted small mb-2">Lihat semua data pengeluaran material gudang.</p>
                    <span class="btn btn-success btn-sm px-4">Lihat Data</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection