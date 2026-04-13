@extends('layouts.app')

@section('title', 'Tambah Data Master')

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">TAMBAH DATA MASTER</h1>
    </div>

    <div class="card bg- shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('master_data.store') }}">
                @csrf
                <div class="form-group">
                    <label for="master">Pilih Master</label>
                    <select name="master_id" id="master" class="form-control select" required>
                        <option value="">-- Pilih Master --</option>
                        @foreach($master as $master_item)
                            <option value="{{ $master_item->id }}">{{ $master_item->nama_master }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label>Data Master</label>
                    <input type="text" name="data_master" class="form-control @error('data_master') is-invalid @enderror" value="{{ old('data_master') }}" required>
                    @error('data_master')
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