@extends('layouts.app')

@section('title', 'Menu Master Data')

@section('content')
<div class="container-fluid py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="text-center mb-5">
        <h1 class="h2 text-gray-800 font-weight-bold">TAMBAH MASTER DAN DATA MASTER</h1>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-4 g-4 justify-content-center">
        @can('manage-users')
        <div class="col">
            <a href="{{ route('master.create') }}" class="card-link text-decoration-none">
                <div class="card card-icon-shadow h-100 border-0 text-center py-4">
                    <div class="card-body">
                        <i class="fas fa-database fa-4x text-primary mb-3"></i>
                        <h6 class="font-weight-bold text-dark mb-0">Kelola Master</h6>
                    </div>
                </div>
            </a>
        </div>
        @endcan

        <div class="col">
            <a href="{{ route('master_data.create') }}" class="card-link text-decoration-none">
                <div class="card card-icon-shadow h-100 border-0 text-center py-4">
                    <div class="card-body">
                        <i class="fas fa-layer-group fa-4x text-success mb-3"></i>
                        <h6 class="font-weight-bold text-dark mb-0">Kelola Data Master</h6>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

@endsection