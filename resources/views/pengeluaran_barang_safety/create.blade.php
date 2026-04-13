@extends('layouts.app')

@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">TAMBAH PENGELUARAN ALAT SAFETY</h1>
    </div>

    <div class="card bg-white shadow mb-4">
        <div class="card-body">
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form action="{{ route('pengeluaran_barang_safety.store') }}" method="POST" enctype="multipart/form-data" data-item-name="barang_safety_id">
                @csrf

                <div class="form-group">
                    <label for="pic_id">PIC Penanggung Jawab</label>
                    <select name="pic_id" id="pic_id" class="form-control select2-pic" required>
                        <option value="">-- Pilih PIC --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="user_id">Nama Peminjam</label>
                    <select name="user_id" id="user_id" class="form-control select2-borrower" required>
                        <option value="">-- Pilih Peminjam --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="tanggal_keluar">Tanggal Keluar</label>
                    <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-control select2-searchable" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->nama_customer }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan (Tujuan Penggunaan Alat)</label>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Contoh: Untuk maintenance PV di PT"></textarea>
                </div>
                
                <div class="form-group mt-3">
                    <label for="bukti_pinjam">Bukti Foto Peminjaman</label>
                    <input type="file" class="form-control-file" id="bukti_pinjam" name="bukti_pinjam" required>
                    @error('bukti_pinjam')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <hr>
                <h5>List Alat yang Dikeluarkan</h5>
                <div id="items-container">
                    <div class="item-row row mb-2 align-items-end">
                        <div class="col-md-5">
                            <label>Nama Barang - Kode Barang</label>
                            <select name="items[0][barang_safety_id]" id="item-select-0" class="form-control select2-item" data-placeholder="-- Pilih Alat --" required>
                                <option value=""></option>
                                @foreach($barang_safety as $b)
                                    <option value="{{ $b->id }}">
                                        {{ $b->masterBarang->kode_barang ?? 'Kode Tidak Tersedia' }} - {{ $b->masterBarang->nama_barang ?? 'Nama Tidak Tersedia' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label>Jumlah Keluar</label>
                            <input type="number" name="items[0][jumlah_keluar]" class="form-control" min="1" value="1" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-item-btn" style="display: none;">Hapus</button>
                        </div>
                    </div>
                </div>
                
                <button type="button" id="add-item-btn" class="btn btn-success mt-3"><i class="fas fa-plus"></i> Tambah Alat</button>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('pengeluaran_barang_safety.menu') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection