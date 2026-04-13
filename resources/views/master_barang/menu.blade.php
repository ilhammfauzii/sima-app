@extends('layouts.app')

@section('title', 'Menu Master Data Barang')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">MENU MASTER BARANG</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-xl-6 col-lg-6 mb-4">
            <a href="{{ route('master_barang.create') }}" class="menu-item-card d-flex align-items-center text-decoration-none p-4 rounded-lg shadow-sm border border-light">
                <div class="icon-container mr-4 flex-shrink-0">
                    <i class="fas fa-plus-square fa-3x text-primary"></i>
                </div>
                <div class="text-content flex-grow-1">
                    <h5 class="font-weight-bolder text-dark mb-1">Tambah Master Barang Baru</h5>
                    <p class="text-muted mb-0 small">Input data dasar (Kode, Nama, Satuan) barang baru ke sistem.</p>
                </div>
                <div class="arrow-icon ml-auto">
                    <i class="fas fa-chevron-right text-muted"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-6 col-lg-6 mb-4">
            <a href="{{ route('master_barang.index') }}" class="menu-item-card d-flex align-items-center text-decoration-none p-4 rounded-lg shadow-sm border border-light">
                <div class="icon-container mr-4 flex-shrink-0">
                    <i class="fas fa-clipboard-list fa-3x text-info"></i>
                </div>
                <div class="text-content flex-grow-1">
                    <h5 class="font-weight-bolder text-dark mb-1">Daftar & Kelola Master Barang</h5>
                    <p class="text-muted mb-0 small">Lihat, edit, dan hapus data Master Barang yang sudah ada.</p>
                </div>
                <div class="arrow-icon ml-auto">
                    <i class="fas fa-chevron-right text-muted"></i>
                </div>
            </a>
        </div>

        <div class="col-xl-6 col-lg-6 mb-4">
            <a href="{{ route('master_barang.import.form') }}"
               class="menu-item-card d-flex align-items-center text-decoration-none p-4 rounded-lg shadow-sm border border-light">
        
                <div class="icon-container mr-4 flex-shrink-0">
                    <i class="fas fa-file-import fa-3x text-warning"></i>
                </div>
        
                <div class="text-content flex-grow-1">
                    <h5 class="font-weight-bolder text-dark mb-1">Import Master Barang</h5>
                    <p class="text-muted mb-0 small">
                        Upload data master barang melalui Excel
                    </p>
                </div>
        
                <div class="arrow-icon ml-auto">
                    <i class="fas fa-chevron-right text-muted"></i>
                </div>
            </a>
        </div>        
    </div>
</div>
@endsection