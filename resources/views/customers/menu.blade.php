@extends('layouts.app')

@section('title', 'Menu Customer')

@section('content')
<div class="container-fluid py-5">
    <div class="text-center mb-4">
        <h1 class="h2 text-gray-800 font-weight-bold">MENU CUSTOMER</h1>
        <p class="lead text-muted mb-0">Pilih salah satu opsi di bawah ini.</p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
        <div class="col-md-4">
            <a href="{{ route('customers.create') }}" class="card-link text-decoration-none">
                <div class="card card-hover-shadow-lg text-center h-100 py-4">
                    <div class="card-body">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                        <h5 class="font-weight-bold text-dark mb-0">Tambah Customer</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('customers.index') }}" class="card-link text-decoration-none">
                <div class="card card-hover-shadow-lg text-center h-100 py-4">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-success mb-3"></i>
                        <h5 class="font-weight-bold text-dark mb-0">Data Customer</h5>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('customers.import.form') }}" class="card-link text-decoration-none">
                <div class="card card-hover-shadow-lg text-center h-100 py-4">
                    <div class="card-body">
                        <i class="fas fa-file-import fa-3x text-warning mb-3"></i>
                        <h5 class="font-weight-bold text-dark mb-0">Import Customer</h5>
                        <small class="text-muted d-block mt-1">
                            Upload data via Excel
                        </small>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>
@endsection