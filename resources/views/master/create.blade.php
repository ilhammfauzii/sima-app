@extends('layouts.app')

@section('title', 'Buat Master')

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">BUAT MASTER</h1>
    </div>

    <div class="card bg- shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('master.store') }}">
                @csrf
                <div class="mb-3">
                    <label>Nama Master</label>
                    <input type="text" name="nama_master" class="form-control @error('nama_master') is-invalid @enderror" value="{{ old('nama_master') }}" required>
                    @error('nama_master')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('master.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection