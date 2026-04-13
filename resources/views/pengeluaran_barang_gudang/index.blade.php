@extends('layouts.app')

@section('title', 'Pengeluaran Material Instalasi')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">PENGELUARAN MATERIAL INSTALASI</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pengeluaran Material Instalasi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>PIC</th>
                            <th>Tanggal Keluar</th>
                            <th>Barang (Jumlah)</th>
                            <th>Customer</th>
                            <th>Lokasi</th>
                            <th>Keterangan</th>
                            <th>Bukti Pengeluaran</th>
                            <th>Bukti Pengembalian</th>
                            <th>Status</th>
                            @can('manage-pengeluaran')
                                <th>Aksi</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengeluaran_gudang as $pbg)
                        <tr class="text-center align-middle">
                            <td>{{ $pengeluaran_gudang->firstItem() + $loop->index }}</td>
                            <td>{{ $pbg->pic->nama ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pbg->tanggal_keluar)->format('d M Y') }}</td>
                            <td>
                                @foreach($pbg->items as $itemDetail)
                                    {{ $itemDetail->masterBarang->nama_barang }} ({{ $itemDetail->pivot->jumlah_keluar }} {{ $itemDetail->masterBarang->satuan }})<br>
                                @endforeach
                            </td>
                            <td>{{ $pbg->customer->nama_customer ?? '-' }}</td>
                            <td>{{ $pbg->customer->alamat_lengkap ?? '-' }}</td>
                            <td>{{ $pbg->keterangan ?? '-' }}</td>
                            <td>
                                @if($pbg->bukti_keluar)
                                    <a href="{{ Storage::disk('public')->url($pbg->bukti_keluar) }}" target="_blank">Lihat File</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($pbg->bukti_kembali)
                                    <a href="{{ Storage::disk('public')->url($pbg->bukti_kembali) }}" target="_blank">Lihat File</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($pbg->status == 'dikeluarkan')
                                    <span class="badge badge-warning">Dikeluarkan</span>
                                @elseif($pbg->status == 'sudahkeluar')
                                    <span class="badge badge-success">Sudah Keluar</span>
                                @else
                                    <span class="badge badge-danger">{{ ucfirst($pbg->status) }}</span>
                                @endif
                            </td>
                            @can('manage-pengeluaran')
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($pbg->status === 'dikeluarkan' && ($pbg->pic_id == auth()->id() || Gate::allows('manage-pengeluaran')))
                                            <button type="button" class="btn btn-sm btn-success mr-1" data-toggle="modal" data-target="#kembalikanModal{{ $pbg->id }}" title="Selesaikan Pengeluaran">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                        
                                        @can('edit-pengeluaran')
                                            <a href="{{ route('pengeluaran_barang_gudang.edit', Crypt::encrypt($pbg->id)) }}"class="btn btn-sm btn-warning mr-1"title="Edit Pengeluaran">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('delete-pengeluaran')
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal{{ $pbg->id }}" title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            @endcan
                        </tr>
                        
                        <div class="modal fade" id="hapusModal{{ $pbg->id }}" tabindex="-1" role="dialog" aria-labelledby="hapusModalLabel{{ $pbg->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="hapusModalLabel{{ $pbg->id }}">Konfirmasi Hapus</h5>
                                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus data pengeluaran ini ?
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                        <form action="{{ route('pengeluaran_barang_gudang.destroy', $pbg->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($pbg->status === 'dikeluarkan')
                        <div class="modal fade" id="kembalikanModal{{ $pbg->id }}" tabindex="-1" role="dialog" aria-labelledby="kembalikanModalLabel{{ $pbg->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="kembalikanModalLabel{{ $pbg->id }}">Konfirmasi Pengeluaran Material</h5>
                                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('pengeluaran_barang_gudang.kembalikan', $pbg->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="bukti_kembali_{{ $pbg->id }}">Bukti Foto Pengembalian</label>
                                                <input type="file" class="form-control-file" id="bukti_kembali_{{ $pbg->id }}" name="bukti_kembali" required>
                                            </div>
                                            @foreach($pbg->items as $itemDetail)
                                                <p>Material : <strong>{{ $itemDetail->masterBarang->nama_barang }}</strong> (Total dikeluarkan: {{ $itemDetail->pivot->jumlah_keluar }})</p>
                                                <div class="form-group">
                                                    <label for="jumlah_kembali_{{ $itemDetail->id }}">Jumlah material yang tersisa atau lebih :</label>
                                                    <input type="number" name="items_kembali[{{ $itemDetail->id }}][jumlah_kembali]" id="jumlah_kembali_{{ $itemDetail->id }}" class="form-control" min="0" max="{{ $itemDetail->pivot->jumlah_keluar }}" value="{{ $itemDetail->pivot->jumlah_keluar }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="alasan_{{ $itemDetail->id }}">Keterangan Material yang sudah keluar :</label>
                                                    <textarea name="items_kembali[{{ $itemDetail->id }}][alasan]" id="alasan_{{ $itemDetail->id }}" class="form-control" rows="2"></textarea>
                                                </div>
                                                <hr>
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Proses Pengeluaran</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @empty
                        <tr>
                            <td colspan="13" class="text-center">Tidak ada data pengeluaran yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $pengeluaran_gudang->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection