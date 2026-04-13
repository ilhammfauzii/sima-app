@extends('layouts.app')

@section('title', 'Data Master Barang')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">MASTER BARANG</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php 
        use Illuminate\Support\Facades\Crypt; 
    @endphp

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="m-0 font-weight-bold text-primary">Data Master Barang</h6>

            <div class="col-md-4 mt-2 mt-md-0">
                <input type="text" class="form-control" placeholder="🔍 Cari nama atau kode barang..."data-search-url="{{ route('master_barang.search') }}"data-search-target="#data-barang">
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Tanggal Pembelian</th>
                            @can('manage-barang')
                                <th>Aksi</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody id="data-barang">
                        @forelse($masterBarang as $mb)
                            <tr class="text-center">
                                <td>{{ $masterBarang->firstItem() + $loop->index }}</td>
                                <td>{{ $mb->kode_barang }}</td>
                                <td>{{ $mb->nama_barang }}</td>
                                <td>{{ $mb->kategoriBarang->nama_kategori ?? 'Tidak Ada' }}</td>
                                <td>{{ $mb->satuan }}</td>
                                <td>{{ \Carbon\Carbon::parse($mb->tanggal_beli)->format('d M Y') }}</td>
                                
                                @can('manage-barang')
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('master_barang.edit', Crypt::encrypt($mb->id)) }}" class="btn btn-warning btn-sm mr-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @can('delete-barang')
                                        <button type="button" class="btn btn-danger btn-sm mr-1" data-toggle="modal" data-target="#hapusMasterModal{{ $mb->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                                @endcan

                                <div class="modal fade" id="hapusMasterModal{{ $mb->id }}" tabindex="-1" role="dialog" aria-labelledby="hapusMasterModalLabel{{ $mb->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title" id="hapusMasterModalLabel{{ $mb->id }}">Konfirmasi Hapus</h5>
                                                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus Master Barang <strong>{{ $mb->nama_barang }}</strong>?
                                                <p class="mt-2 text-danger">Data stok (Engineer/Gudang/Safety) yang terkait juga mungkin akan terpengaruh/terhapus.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                                <form action="{{ route('master_barang.destroy', $mb->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data Master Barang yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $masterBarang->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection