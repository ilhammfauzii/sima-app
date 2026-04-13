@extends('layouts.app')

@section('title', 'Import Master Barang')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">IMPORT MASTER BARANG</h1>

        <a href="{{ route('master_barang.template') }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-excel mr-1"></i> Unduh Template
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('master_barang.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label>File Excel <span class="text-danger">*</span></label>
                    <input type="file" name="file" class="form-control" required
                           accept=".xlsx,.xls,.csv">
                </div>

                <div class="alert alert-info">
                    <strong>Catatan:</strong>
                    <ul class="mb-0">
                        <li>Kode barang tidak boleh kosong</li>
                        <li>Kategori menggunakan <b>Nama kategori (Engineer, Material Instalasi, Safety)</b></li>
                        <li>Data duplikat akan di-update</li>
                    </ul>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('master_barang.menu') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button class="btn btn-primary">
                        <i class="fas fa-file-import mr-1"></i> Import
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection