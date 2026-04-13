@extends('layouts.app')

@section('title', 'Edit Alat Safety')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">EDIT ALAT SAFETY</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Alat Safety</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('barang_safety.update', Crypt::encrypt($barang_safety->id)) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="kode_barang" class="font-weight-bold">Kode Barang</label>
                    <input type="text" class="form-control" id="kode_barang" value="{{ $barang_safety->masterBarang->kode_barang }}" readonly>
                </div>

                <div class="form-group">
                    <label for="nama_barang" class="font-weight-bold">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" value="{{ $barang_safety->masterBarang->nama_barang }}" readonly>
                </div>

                <div class="form-group">
                    <label for="stok_sistem_barang">Stok Sistem</label>

                    @can('edit-barang-full')
                        <input type="number" class="form-control" id="stok_sistem_barang" name="stok_sistem_barang" value="{{ $barang_safety->stok_sistem_barang }}">
                    @else
                        <input type="number" class="form-control" value="{{ $barang_safety->stok_sistem_barang }}" readonly>
                    @endcan
                </div>

                <div class="form-group">
                    <label for="stok_fisik_barang">Stok Fisik</label>

                    @can('edit-barang-full')
                        <input type="number" class="form-control" id="stok_fisik_barang" name="stok_fisik_barang" value="{{ $barang_safety->stok_fisik_barang }}">
                    @else
                        <input type="number" class="form-control" value="{{ $barang_safety->stok_fisik_barang }}" readonly>
                    @endcan
                </div>

                <div class="form-group">
                    <label for="keterangan" class="font-weight-bold">Keterangan</label>
                    <input type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" value="{{ old('keterangan', $barang_safety->keterangan) }}" required placeholder="Contoh: Kondisi Baik Atau Kurang Baik">
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Barang</button>
                <a href="{{ route('barang_safety.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection