@extends('layouts.app')

@section('title', 'Import Data Customer')

@section('content')
<div class="container">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">IMPORT DATA CUSTOMER</h1>

        <a href="{{ route('customers.template') }}" class="btn btn-excel btn-sm">
            <i class="fas fa-file-excel mr-2"></i> Unduh Template
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-4">
                    <label for="file">
                        File Excel Customer <span class="text-danger">*</span>
                    </label>
                    <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    <small class="text-muted">Format: xlsx / xls / csv</small>
                </div>

                <div class="alert alert-info mb-4">
                    <strong>Catatan:</strong>
                    <ul class="mb-0">
                        <li>Nama customer tidak boleh kosong</li>
                        <li>Marketing harus menggunakan Nama yang valid</li>
                        <li>Data duplikat akan di-skip</li>
                    </ul>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('customers.menu') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-import mr-1"></i> Import
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection