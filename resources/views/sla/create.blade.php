@extends('layouts.app')

@section('title', 'Buat SLA Baru')

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">BUAT SLA BARU</h1>
    </div>

    <div class="card bg-white shadow mb-4">
        <div class="card-body">
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form action="{{ route('sla.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="nama_customer_id">Nama Customer</label>
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
                    <label for="departemen_id">Departemen</label>
                    <select name="departemen_id" id="departemen_id" class="form-control select2-searchable" required>
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departements as $data)
                            <option value="{{ $data->id }}">{{ $data->data_master }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="service_type_id">Jenis Layanan</label>
                    <select name="service_type_id" id="service_type_id" class="form-control select2-searchable" required>
                        <option value="">-- Pilih Jenis Layanan --</option>
                        @foreach($serviceTypes as $data)
                            <option value="{{ $data->id }}">{{ $data->data_master }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="PIC_id">PIC</label>
                    <select name="PIC_id" id="PIC_id" class="form-control select2-searchable" required>
                        <option value="">-- Pilih PIC --</option>
                        @if(auth()->check())
                            <option value="{{ auth()->user()->id }}">
                                {{ auth()->user()->nama }}
                            </option>
                        @else
                            <option value="" disabled selected>-- Pengguna tidak terautentikasi --</option>
                        @endif
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <textarea name="lokasi" id="lokasi" class="form-control select2-searchable" rows="3">{{ old('lokasi') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control select2-searchable" rows="3">{{ old('keterangan') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="start">Tanggal Mulai</label>
                    <input type="date" name="start" id="start" class="form-control select2-searchable" value="{{ old('start') }}" required>
                </div>

                <div class="form-group">
                    <label for="deadline">Batas Waktu</label>
                    <input type="date" name="deadline" id="deadline" class="form-control select2-searchable" value="{{ old('deadline') }}" required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('sla.menu') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection