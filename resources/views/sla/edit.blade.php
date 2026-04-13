@extends('layouts.app')

@section('title', 'Edit SLA')

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">EDIT SERVICE LEVEL AGREEMENT (SLA)</h1>
    </div>

    <div class="card bg-white shadow mb-4">
        <div class="card-body">
            <form action="{{ route('sla.update', $encryptedId) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nama Customer</label>
                    @if($isSuperAdmin)
                        <select name="customer_id" class="form-control select2-searchable" required>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $sla->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->nama_customer }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control bg-light" value="{{ $sla->customer->nama_customer }}" readonly>
                    @endif
                </div>

                <div class="form-group">
                    <label>Departemen</label>
                    @if($isSuperAdmin)
                        <select name="departemen_id" class="form-control select2-searchable" required>
                            @foreach($departements as $dept)
                                <option value="{{ $dept->id }}" {{ $sla->departemen_id == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->data_master }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control bg-light" value="{{ $sla->departemen->data_master }}" readonly>
                    @endif
                </div>

                <div class="form-group">
                    <label>Jenis Layanan</label>
                    @if($isSuperAdmin)
                        <select name="service_type_id" class="form-control select2-searchable" required>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type->id }}" {{ $sla->service_type_id == $type->id ? 'selected' : '' }}>
                                    {{ $type->data_master }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control bg-light" value="{{ $sla->serviceType->data_master }}" readonly>
                    @endif
                </div>

                <div class="form-group">
                    <label>PIC</label>
                    @if($isSuperAdmin)
                        <select name="PIC_id" class="form-control select2-searchable" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $sla->PIC_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->nama }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control bg-light" value="{{ $sla->pic->nama }}" readonly>
                    @endif
                </div>

                <div class="form-group">
                    <label>Lokasi</label>
                    <textarea name="lokasi" class="form-control" rows="3" {{ !$isSuperAdmin ? 'readonly' : '' }}>{{ old('lokasi', $sla->lokasi) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" {{ !$isSuperAdmin ? 'readonly' : '' }}>{{ old('keterangan', $sla->keterangan) }}</textarea>
                </div>

                <div class="form-group">
                    <label>Link File / Dokumen</label>
                    <input type="url" name="file" class="form-control" value="{{ old('file', $sla->file) }}" placeholder="https://...">
                </div>

                <div class="form-group">
                    <label for="start">Tanggal Mulai</label>
                    <input type="date"  id="start"  class="form-control bg-light"  value="{{ \Carbon\Carbon::parse($sla->start)->format('Y-m-d') }}"  readonly>
                </div>

                <div class="form-group">
                    <label for="deadline" class="font-weight-bold text-danger">Batas Waktu (Deadline)</label>
                    <input type="date"  name="deadline"  id="deadline"  class="form-control @error('deadline') is-invalid @enderror"  value="{{ old('deadline', \Carbon\Carbon::parse($sla->deadline)->format('Y-m-d')) }}"  required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update SLA</button>
                    <a href="{{ route('sla.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection