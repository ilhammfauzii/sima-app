@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">DATA CUSTOMER</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Customer</h6>
            <a href="{{ route('customers.export') }}" class="btn btn-excel btn-sm">
                <i class="fas fa-file-excel mr-2"></i> Unduh
            </a>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div class="col-md-4 p-0">
                    <input type="text" id="searchInput"  class="form-control bg-light border-0 small" placeholder="Cari nama, ID PLN, atau marketing..." data-search-target="#customerTableBody" data-search-url="{{ route('customers.search') }}">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Customer</th>
                            <th>No. Telp</th>
                            <th>NIK</th>
                            <th>NPWP</th>
                            <th>Alamat Lengkap</th>
                            <th>ID PLN</th>
                            <th>Marketing</th>
                            <th>Referensi</th>
                            <th style="width: 100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="customerTableBody">
                        @forelse ($customers as $index => $customer)
                        <tr class="text-center">
                            <td>{{ $customers->firstItem() + $index }}</td>
                            <td>{{ $customer->nama_customer }}</td>
                            <td>{{ $customer->no_telp ?? '-' }}</td>
                            <td>{{ $customer->nik ?? '-' }}</td>
                            <td>{{ $customer->npwp ?? '-' }}</td>
                            <td class="text-left">{{ Str::limit($customer->alamat_lengkap, 50) }}</td>
                            <td>{{ $customer->id_pln ?? '-' }}</td>
                            <td>{{ $customer->marketing->nama ?? '-' }}</td>
                            <td>{{ $customer->referensi_reseller ?? '-' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('customers.edit', Crypt::encrypt($customer->id)) }}" class="btn btn-warning btn-sm mr-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @can('delete-customer')
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapusModal{{ $customer->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="hapusModal{{ $customer->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button class="close text-white" type="button" data-dismiss="modal">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-left">
                                        Apakah Anda yakin ingin menghapus customer <strong>{{ $customer->nama_customer }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                        <form action="{{ route('customers.destroy', Crypt::encrypt($customer->id)) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada data customer yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $customers->appends(['query' => request('query')])->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection