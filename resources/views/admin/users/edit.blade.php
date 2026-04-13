@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Pengguna : {{ $user->nama }}</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="nomor_pegawai">Nomor Pegawai</label>
                    <input type="text" class="form-control @error('nomor_pegawai') is-invalid @enderror" id="nomor_pegawai" name="nomor_pegawai" value="{{ old('nomor_pegawai', $user->nomor_pegawai) }}" required>
                    @error('nomor_pegawai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="id_roles">Role Pengguna</label>
                    <select name="id_roles" id="id_roles" class="form-control @error('id_roles') is-invalid @enderror" required>
                        <option value="">-- Pilih Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('id_roles', $user->id_roles) == $role->id ? 'selected' : '' }}>
                                {{ $role->nama_roles }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_roles')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr>
                <p class="text-muted">Ganti Password (Opsional)</p>
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                
                <hr>

                <div class="form-group">
                    <label for="jabatan">Jabatan (Opsional)</label>
                    <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}">
                    @error('jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="departemen_id">Departemen (Opsional)</label>
                    <select class="form-control @error('departemen_id') is-invalid @enderror" 
                            id="departemen_id" 
                            name="departemen_id">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departemenList as $departemen)
                            <option value="{{ $departemen->id }}" 
                                {{ old('departemen_id', $user->departemen_id) == $departemen->id ? 'selected' : '' }}>
                                {{ $departemen->data_master }}
                            </option>
                        @endforeach
                    </select>
                    @error('departemen_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection