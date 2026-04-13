@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">TAMBAH PENGGUNA BARU</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="nomor_pegawai">Nomor Pegawai</label>
                    <input type="text" class="form-control @error('nomor_pegawai') is-invalid @enderror" id="nomor_pegawai" name="nomor_pegawai" value="{{ old('nomor_pegawai') }}" required>
                    @error('nomor_pegawai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="id_roles">Role Pengguna</label>
                    <select name="id_roles" id="id_roles" class="form-control @error('id_roles') is-invalid @enderror" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('id_roles') == $role->id ? 'selected' : '' }}>
                                {{ $role->nama_roles }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_roles')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                
                <hr>
                <div class="form-group">
                    <label for="jabatan">Jabatan (Opsional)</label>
                    <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan') }}">
                    @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="departemen_id">Departemen</label>
                    <select class="form-control @error('departemen_id') is-invalid @enderror" 
                            id="departemen_id" 
                            name="departemen_id">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departemenList as $departemen)
                            <option value="{{ $departemen->id }}" 
                                {{ old('departemen_id') == $departemen->id ? 'selected' : '' }}>
                                {{ $departemen->data_master }}
                            </option>
                        @endforeach
                    </select>
                    @error('departemen_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.users.menu') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection