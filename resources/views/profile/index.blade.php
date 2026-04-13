@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="text-center mb-5">
                        <img src="{{ asset('img/undraw_profile.svg') }}"{{ $user->id }} alt="Foto Profil" 
                            class="rounded-circle mb-3" width="120" height="120">
                        <h2 class="font-weight-bold mb-0">{{ $user->nama }}</h2>
                        <p class="text-muted">{{ $user->role->nama_roles ?? 'User' }}</p>
                    </div>

                    <div>
                        <div class="row mb-3"><div class="col-sm-4"><h6 class="mb-0 font-weight-bold">Nomor Pegawai</h6></div><div class="col-sm-8 text-secondary">{{ $user->nomor_pegawai }}</div></div><hr>
                        <div class="row mb-3"><div class="col-sm-4"><h6 class="mb-0 font-weight-bold">Email</h6></div><div class="col-sm-8 text-secondary">{{ $user->email }}</div></div><hr>
                        <div class="row mb-3"><div class="col-sm-4"><h6 class="mb-0 font-weight-bold">Jabatan</h6></div><div class="col-sm-8 text-secondary">{{ $user->jabatan ?? '-' }}</div></div><hr>
                        <div class="row mb-3"><div class="col-sm-4"><h6 class="mb-0 font-weight-bold">Departemen</h6></div><div class="col-sm-8 text-secondary">{{ $user->departemen->data_master ?? '-' }}</div></div><hr>
                    </div>
                    
                    <div class="mt-5 text-center">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection