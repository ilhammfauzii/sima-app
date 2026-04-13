@extends('layouts.app')

@section('title', 'Menu Manajemen Pengguna')

@section('content')
<div class="container-fluid py-5">
    <div class="d-flex align-items-center justify-content-center mb-5">
        <h1 class="h2 mb-0 text-gray-800 font-weight-bold">
            <i class="fas fa-users-cog me-3 text-primary"></i>MANAJEMEN PENGGUNA
        </h1>
    </div>

    <div class="row justify-content-center">
        @can('manage-users')
        <div class="col-lg-5 col-md-6 mb-4">
            <a href="{{ route('admin.users.create') }}" class="card shadow-lg border-0 h-100 py-4 menu-card-modern text-decoration-none">
                <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="icon-circle bg-primary text-white mb-4">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-1">Tambah Pengguna Baru</h5>
                    <p class="text-muted small mb-3">Buat akun pengguna baru dengan hak akses yang sesuai.</p>
                    <span class="btn btn-primary btn-lg mt-auto px-5">Buat Pengguna</span>
                </div>
            </a>
        </div>
        @endcan

        <div class="col-lg-5 col-md-6 mb-4">
            <a href="{{ route('admin.users.index') }}" class="card shadow-lg border-0 h-100 py-4 menu-card-modern text-decoration-none">
                <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                    <div class="icon-circle bg-success text-white mb-4">
                        <i class="fas fa-user-friends fa-2x"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-1">Lihat Data Pengguna</h5>
                    <p class="text-muted small mb-3">Kelola dan lihat daftar semua pengguna yang terdaftar.</p>
                    <span class="btn btn-success btn-lg mt-auto px-5">Lihat Pengguna</span>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection