@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">EDIT DATA CUSTOMER</h1>
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

            <form action="{{ route('customers.update', Crypt::encrypt($customer->id)) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="nama_customer">Nama Customer <span class="text-danger">*</span></label>
                    <input type="text" name="nama_customer" id="nama_customer" class="form-control" value="{{ old('nama_customer', $customer->nama_customer) }}" required>
                </div>

                <div class="form-group">
                    <label for="no_telp">No. Telepon</label>
                    <input type="text" name="no_telp" id="no_telp" class="form-control" value="{{ old('no_telp', $customer->no_telp) }}">
                </div>

                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="text" name="nik" id="nik" class="form-control" value="{{ old('nik', $customer->nik) }}">
                </div>

                <div class="form-group">
                    <label for="npwp">NPWP</label>
                    <input type="text" name="npwp" id="npwp" class="form-control" value="{{ old('npwp', $customer->npwp) }}">
                </div>

                <div class="form-group">
                    <label for="alamat_lengkap">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-control" rows="3">{{ old('alamat_lengkap', $customer->alamat_lengkap) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="id_pln">ID PLN</label>
                    <input type="text" name="id_pln" id="id_pln" class="form-control" value="{{ old('id_pln', $customer->id_pln) }}">
                </div>

                <div class="form-group">
                    <label for="marketing_id">Marketing</label>
                    <select name="marketing_id" id="marketing_id" class="form-control select2-searchable" required>
                        <option value="">-- Pilih Marketing --</option>
                        @foreach($marketings as $marketing)
                            <option value="{{ $marketing->id }}"
                                {{ old('marketing_id', $customer->marketing_id ?? null) == $marketing->id ? 'selected' : '' }}>
                                {{ $marketing->nama }}
                            </option>
                        @endforeach
                    </select>
                    @if(count($marketings) === 1)
                        <input type="hidden" name="marketing_id" value="{{ auth()->id() }}">
                    @endif
                </div>

                <div class="form-group">
                    <label for="referensi_reseller">Referensi Reseller</label>
                    <input type="text" name="referensi_reseller" id="referensi_reseller" class="form-control" value="{{ old('referensi_reseller', $customer->referensi_reseller) }}">
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning">Update</button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection