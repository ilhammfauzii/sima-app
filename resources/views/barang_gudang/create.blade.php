@extends('layouts.app')

@section('title', 'Penambahan Material dan stok Material Instalasi')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800 font-weight-bold text-center text-md-left">TAMBAH MATERIAL DAN STOK MATERIAL INSTALASI</h1>
    <form method="POST" action="{{ route('barang_gudang.store') }}"> 
        @csrf
        <div class="card shadow mb-4">
            <div class="card-body">

                <div class="form-group">
                    <label for="master_barang_id">Pilih Material :</label>
                    <select name="master_barang_id" id="item-select-1" class="form-control select2-item"data-placeholder="-- Pilih Material --"required>
                        <option value=""></option>
                        @foreach ($master_barang as $barang)
                            <option value="{{ $barang->id }}">
                                {{ $barang->kode_barang }} - {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                    @error('master_barang_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jumlah_tambah">Jumlah Stok Ditambahkan :</label>
                    <input type="number" name="jumlah_tambah" id="jumlah_tambah" class="form-control @error('jumlah_tambah') is-invalid @enderror" min="1" required>
                    @error('jumlah_tambah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="penempatan">Penempatan :</label>
                    <input type="text" name="penempatan" id="penempatan" class="form-control @error('penempatan') is-invalid @enderror" value="{{ old('penempatan') }}" required>
                    @error('penempatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan :</label>
                    <input type="text" name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" value="{{ old('keterangan') }}" required>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                    <a href="{{ route('pengadaan_barang_gudang.menu') }}" class="btn btn-secondary">Kembali</a>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection