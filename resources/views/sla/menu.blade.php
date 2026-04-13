@extends('layouts.app')

@section('title', 'Menu SLA')

@section('content')
<div class="container-fluid py-5">
    <div class="text-center mb-4">
        <h1 class="h2 text-gray-800 font-weight-bold">MENU SLA</h1>
        <p class="lead text-muted mb-0">Pilih salah satu opsi di bawah ini.</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-4 justify-content-center">
        
        @can('create-pengeluaran')
        <div class="col-md-4">
            <a href="{{ route('sla.create') }}" class="card-link text-decoration-none">
                <div class="card card-hover-shadow-lg text-center h-100 py-4">
                    <div class="card-body">
                        <i class="fas fa-file-contract fa-3x text-primary mb-3"></i>
                        <h5 class="font-weight-bold text-dark mb-0">Buat SLA Baru</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        <div class="col-md-4">
            <a href="{{ route('sla.index') }}" class="card-link text-decoration-none">
                <div class="card card-hover-shadow-lg text-center h-100 py-4">
                    <div class="card-body">
                        <i class="fas fa-tasks fa-3x text-success mb-3"></i>
                        <h5 class="font-weight-bold text-dark mb-0">Lihat Data SLA</h5>
                    </div>
                </div>
            </a>
        </div>

        @can('manage-sla')
        <div class="col-md-4">
            <a href="{{ route('sla.performance') }}" class="card-link text-decoration-none">
                <div class="card card-hover-shadow-lg text-center h-100 py-4">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                        <h5 class="font-weight-bold text-dark mb-0">Monitoring SLA</h5>
                    </div>
                </div>
            </a>
        </div>
        @endcan
    </div>
</div>
@endsection