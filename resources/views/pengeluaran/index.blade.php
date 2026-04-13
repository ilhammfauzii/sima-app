@extends('layouts.app')

@section('title', 'Cetak Surat Pengeluaran Gabungan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cetak Surat Pengeluaran Gabungan</h1>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('pengeluaran.gabungan.cetak') }}" method="GET" target="_blank" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <label for="tanggal" class="form-label">Tanggal Pengeluaran</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label for="keterangan" class="form-label">Keterangan / Tujuan</label>
                        <input type="text" id="keterangan" name="keterangan" class="form-control" placeholder="Contoh: Maintenance, PLN Serang, dsb." required>
                    </div>

                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-print mr-2"></i>Cetak Surat Gabungan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-danger mt-4">
        <strong>Catatan:</strong> 
        Pilih tanggal dan keterangan yang sesuai dengan data pengeluaran dari kategori 
        <em>Engineer</em>, <em>Gudang</em>, maupun <em>Safety</em>. 
        Sistem akan otomatis menggabungkan data dari ketiga kategori dan menampilkan surat dalam bentuk PDF.
    </div>
</div>
@endsection