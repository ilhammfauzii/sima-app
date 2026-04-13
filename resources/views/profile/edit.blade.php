@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-4 border-0">
                    <h3 class="mb-0 text-center font-weight-bold">Edit Profil</h3>
                </div>
                <div class="card-body p-5">

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Nama Lengkap</label>
                                <input type="text" class="form-control" value="{{ $user->nama }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold">Nomor Pegawai</label>
                                <input type="text" class="form-control" value="{{ $user->nomor_pegawai }}" readonly>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="form-group mb-4">
                            <label for="email" class="font-weight-bold">Alamat Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <p class="text-muted font-weight-bold">Ganti Password (Opsional)</p>
                        <small class="form-text text-muted mb-3">Kosongkan kedua field di bawah ini jika Anda tidak ingin mengubah password.</small>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-block">Simpan Perubahan</button>
                            <a href="{{ route('profile.index') }}" class="btn btn-light btn-block mt-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection