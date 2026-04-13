@extends('layouts.app')

@section('title', 'Cara Pengguna')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">PANDUAN PENGGUNA</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Panduan Pengguna SIMA</h6>
                </div>
                <div class="card-body">
                    <p>Selamat datang di SIMA (Sistem Inventaris Manajemen Aset), Panduan Pengguna ini akan memandu Anda untuk memahami setiap fitur dan fungsi di dalam sistem. Silahkan baca isi file di bawah ini untuk memahami setiap fitur dan fungsi di dalam sistem.</p>

                    <p class="mt-3">
                        <a href="{{ asset('storage/Panduan_Pengguna_BEAM.pdf') }}" download="Panduan_Pengguna_BEAM.pdf" class="btn btn-info">
                            <i class="fas fa-download"></i> Unduh Panduan Lengkap (PDF)
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection