@extends('layouts.app')

@section('title', 'Data Material Instalasi')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">MATERIAL INSTALASI</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Material Instalasi</h6>
            @can('manage-barang')
            <div>
                <a href="{{ route('barang_gudang.download.excel') }}" class="btn btn-excel btn-sm">
                    <i class="fas fa-file-excel mr-2"></i> Unduh
                </a>
            </div>
            @endcan
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div class="col-md-4 p-0">
                    <input type="text" id="searchInput" class="form-control bg-light border-0 small" placeholder="Cari nama atau kode barang..." data-search-target="#data-barang-gudang" data-search-url="{{ route('barang_gudang.search') }}">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Penempatan</th>
                            <th>Stok Sistem</th>
                            <th>Stok Fisik</th>
                            <th>Barang Keluar</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                            <th>Terakhir Update</th>
                            @can('manage-barang')
                                <th style="width: 100px">Aksi</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody id="data-barang-gudang">
                        @forelse($barang_gudang as $bg)
                        <tr class="text-center">
                            <td>{{ $barang_gudang->firstItem() + $loop->index }}</td>
                            <td>{{ $bg->masterBarang->kode_barang }}</td>
                            <td>{{ $bg->masterBarang->nama_barang }}</td>
                            <td>{{ $bg->penempatan }}</td>
                            <td>{{ $bg->stok_sistem_barang }}</td>
                            <td>{{ $bg->stok_fisik_barang }}</td>
                            <td><strong>{{ $bg->itemDikeluarkan->sum('jumlah_keluar') }}</strong></td>
                            <td>{{ $bg->masterBarang->satuan }}</td>
                            <td>{{ $bg->keterangan }}</td>
                            <td>{{ \Carbon\Carbon::parse($bg->updated_at)->format('d M Y') }}</td>
                            @canany(['edit-barang-full', 'edit-barang-terbatas'])
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('barang_gudang.edit', Crypt::encrypt($bg->id)) }}" class="btn btn-warning btn-sm mr-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @can('delete-barang')
                                    <button type="button" class="btn btn-danger btn-sm mr-1" data-toggle="modal" data-target="#hapusModal{{ $bg->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
                                </div>
                            </td>
                            @endcan
                        </tr>

                        <div class="modal fade" id="hapusModal{{ $bg->id }}" tabindex="-1" role="dialog" aria-labelledby="hapusModalLabel{{ $bg->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="hapusModalLabel{{ $bg->id }}">Konfirmasi Hapus</h5>
                                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus barang <strong>{{ $bg->masterBarang->nama_barang }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                        <form action="{{ route('barang_gudang.destroy', Crypt::encrypt($bg->id)) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada data barang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $barang_gudang->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <div class="card shadow mb-4 mt-5">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary mb-2 mb-md-0">Data Material yang Sudah Dikeluarkan</h6>
            @can('manage-barang')
            <div class="mb-2 mb-md-0">
                <a href="{{ route('barang_keluar.download.excel') }}" class="btn btn-excel btn-sm">
                    <i class="fas fa-file-excel mr-2"></i> Unduh
                </a>
            </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama PIC</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Alasan</th>
                            <th>Keterangan</th>
                            <th>Tanggal Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barangkeluar as $itemKeluar)
                        <tr class="text-center">
                            <td>{{ $barangkeluar->firstItem() + $loop->index }}</td>
                            <td>{{ $itemKeluar->pengeluaranGudang->user->nama }}</td>
                            <td>{{ $itemKeluar->barangGudang->masterBarang->kode_barang }}</td>
                            <td>{{ $itemKeluar->barangGudang->masterBarang->nama_barang }}</td>
                            <td>{{ $itemKeluar->jumlah }}</td>
                            <td>{{ $itemKeluar->barangGudang->masterBarang->satuan }}</td>
                            <td>{{ $itemKeluar->alasan }}</td>
                            <td>{{ $itemKeluar->pengeluaranGudang->keterangan }}</td>
                            <td>{{ \Carbon\Carbon::parse($itemKeluar->created_at)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data barang keluar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $barangkeluar->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection