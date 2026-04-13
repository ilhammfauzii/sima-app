@extends('layouts.app')

@section('title', 'Edit Master Barang')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">EDIT MASTER BARANG</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Master Barang</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('master_barang.update', Crypt::encrypt($masterBarang->id)) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="kategori_barang_id">Kategori Barang</label>
                    <select name="kategori_barang_id" id="kategori_barang_id" class="form-control @error('kategori_barang_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_barang_id', $masterBarang->kategori_barang_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_barang_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="kode_barang">Kode Barang</label>
                    <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" id="kode_barang" name="kode_barang" value="{{ old('kode_barang', $masterBarang->kode_barang) }}" required>
                    @error('kode_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang" name="nama_barang" value="{{ old('nama_barang', $masterBarang->nama_barang) }}" required>
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="satuan">Satuan (Contoh: Pcs, Unit, Meter)</label>
                    <input type="text" name="satuan" id="satuan" class="form-control @error('satuan') is-invalid @enderror" value="{{ old('satuan', $masterBarang->satuan) }}" required>
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="tanggal_beli">Tanggal Beli</label>
                    <input type="date" name="tanggal_beli" id="tanggal_beli" class="form-control @error('tanggal_beli') is-invalid @enderror" value="{{ old('tanggal_beli', \Carbon\Carbon::parse($masterBarang->tanggal_beli)->format('Y-m-d')) }}" required>
                    @error('tanggal_beli')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Barang</button>
                <a href="{{ route('master_barang.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection