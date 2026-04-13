@extends('layouts.app')

@section('title', 'Edit Pengeluaran Engineer')

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">EDIT PENGELUARAN ALAT ENGINEER</h1>
    </div>

    <div class="card bg-white shadow mb-4">
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengeluaran_barang_engineer.update', Crypt::encrypt($pengeluaran->id)) }}"method="POST"enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="pic_id">PIC Penanggung Jawab</label>
                    <select name="pic_id" id="pic_id" class="form-control select2-pic" required>
                        <option value="">-- Pilih PIC --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $pengeluaran->pic_id == $user->id ? 'selected' : '' }}>
                                {{ $user->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="user_id">Nama Peminjam</label>
                    <select name="user_id" id="user_id" class="form-control select2-borrower" required>
                        <option value="">-- Pilih Peminjam --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ $pengeluaran->user_id == $user->id ? 'selected' : '' }}>
                                {{ $user->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Keluar</label>
                    <input type="date" name="tanggal_keluar" class="form-control" value="{{ $pengeluaran->tanggal_keluar }}" required>
                </div>

                <div class="form-group">
                    <label for="customer_id">Customer</label>
                    <select name="customer_id" id="customer_id" class="form-control select2-searchable" required>
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}"
                                {{ $pengeluaran->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->nama_customer }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3">{{ $pengeluaran->keterangan }}</textarea>
                </div>

                <hr>
                <h5>List Alat yang Dikeluarkan</h5>

                <div id="items-container">
                    @foreach($pengeluaran->items as $index => $item)
                        <div class="item-row row mb-2 align-items-end">
                            <div class="col-md-5">
                                <label>Nama Barang - Kode Barang</label>
                                <select name="items[{{ $index }}][barang_engineer_id]" class="form-control select2-item" required>
                                    <option value="">-- Pilih Alat --</option>
                                    @foreach($barang_engineer as $b)
                                        <option value="{{ $b->id }}"
                                            {{ $item->pivot->barang_engineer_id == $b->id ? 'selected' : '' }}>
                                            {{ $b->masterBarang->kode_barang }} - {{ $b->masterBarang->nama_barang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-5">
                                <label>Jumlah Keluar</label>
                                <input type="number" name="items[{{ $index }}][jumlah_keluar]" class="form-control" min="1" value="{{ $item->pivot->jumlah_keluar }}" required>
                            </div>

                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-item-btn">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-item-btn" class="btn btn-success mt-3">
                    <i class="fas fa-plus"></i> Tambah Alat
                </button>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('pengeluaran_barang_engineer.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection