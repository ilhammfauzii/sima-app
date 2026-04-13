@extends('layouts.app')

@section('title', 'Data Service Level Agreement')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">DATA SERVICE LEVEL AGREEMENT</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Service Level Agreement</h6>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div class="col-md-4 p-0">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Cari customer, PIC,  lokasi, status..." data-search-url="{{ route('sla.search') }}" data-search-target=".table-responsive">
                </div>
            </div>

            <div class="table-responsive">
                @php
                    $showActionColumn = false;
                @endphp

                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Customer</th>
                            <th>Lokasi</th>
                            <th>Departemen</th>
                            <th>PIC</th>
                            <th>Layanan</th>
                            <th>Keterangan</th>
                            <th>Deadline</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                            <th>Durasi</th>
                            <th>Status</th>
                            <th>Link/File</th> 
                            <th>Masalah</th>
                            @foreach($slas as $sla)
                                @if (($sla->status == 'ONGOING' && auth()->check() && $sla->PIC_id == auth()->id()) || Gate::allows('manage-sla'))
                                    @php $showActionColumn = true; break; @endphp
                                @endif
                            @endforeach

                            @if ($showActionColumn)
                                <th>Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($slas as $index => $sla)
                        <tr class="text-center align-middle">
                            <td>{{ $slas->firstItem() + $index }}</td>
                            <td>{{ $sla->customer->nama_customer ?? '-' }}</td>
                            <td>{{ $sla->lokasi ?? '-' }}</td>
                            <td>{{ $sla->departemen->data_master ?? '-' }}</td>
                            <td>{{ $sla->pic->nama ?? '-' }}</td>
                            <td>{{ $sla->serviceType->data_master ?? '-' }}</td>
                            <td>{{ $sla->keterangan ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($sla->deadline)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($sla->start)->format('d-m-Y') }}</td>
                            <td>
                                @if ($sla->finish)
                                    {{ \Carbon\Carbon::parse($sla->finish)->format('d-m-Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($sla->finish)
                                    {{ \Carbon\Carbon::parse($sla->start)->diffInDays(\Carbon\Carbon::parse($sla->finish)) + 1 }} hari
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($sla->status == 'ONTIME')
                                    <span class="badge badge-success">{{ $sla->status }}</span>
                                @elseif ($sla->status == 'LATE')
                                    <span class="badge badge-danger">{{ $sla->status }}</span>
                                @else
                                    <span class="badge badge-warning">{{ $sla->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($sla->file)
                                    @if (str_starts_with($sla->file, 'http://') || str_starts_with($sla->file, 'https://'))
                                        <a href="{{ $sla->file }}" target="_blank">Lihat File</a>
                                    @else
                                        <a href="{{ Storage::disk('public')->url($sla->file) }}" target="_blank">Lihat File</a>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $sla->problem ?? '-' }}</td>
                            
                            @if ($showActionColumn)
                                <td>
                                    <div class="btn-group" role="group">
                                        @if ($sla->status == 'ONGOING' && auth()->check() && $sla->PIC_id == auth()->id())
                                            <button type="button" class="btn btn-sm btn-success mr-1" data-toggle="modal" data-target="#selesaikanModal{{ $sla->id }}" title="Selesaikan">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif

                                        @can('manage-sla')
                                        <a href="{{ route('sla.edit', Crypt::encrypt($sla->id)) }}" class="btn btn-warning btn-sm mr-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        
                                        @can('delete-sla')
                                        <button type="button" class="btn btn-sm btn-danger mr-1" data-toggle="modal" data-target="#hapusModal{{ $sla->id }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                        
                                        @if (!($sla->status == 'ONGOING' && auth()->check() && $sla->PIC_id == auth()->id()) && !Gate::allows('manage-sla'))
                                            -
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $showActionColumn ? '15' : '14' }}" class="text-center">Tidak ada data SLA yang ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $slas->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

@foreach($slas as $sla)
    <div class="modal fade" id="hapusModal{{ $sla->id }}" tabindex="-1" role="dialog" aria-labelledby="hapusModalLabel{{ $sla->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="hapusModalLabel{{ $sla->id }}">Konfirmasi Hapus</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus data SLA Nomor {{ $sla->id }} ini? Tindakan ini tidak dapat dibatalkan.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <form action="{{ route('sla.destroy', Crypt::encrypt($sla->id)) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($sla->status == 'ONGOING' && auth()->check() && $sla->PIC_id == auth()->id())
    <div class="modal fade" id="selesaikanModal{{ $sla->id }}" tabindex="-1" role="dialog" aria-labelledby="selesaikanModalLabel{{ $sla->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('sla.finish', Crypt::encrypt($sla->id)) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="selesaikanModalLabel{{ $sla->id }}">Selesaikan SLA</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Anda akan menyelesaikan data SLA Nomor {{ $sla->id }}. Silakan isi detail berikut.</p>
                        
                        <div class="form-group">
                            <label for="finish_{{ $sla->id }}">Tanggal Selesai</label>
                            <input type="date" name="finish" id="finish_{{ $sla->id }}" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="link_{{ $sla->id }}">Link File/Dokumen</label>
                            <input type="url" name="link" id="link_{{ $sla->id }}" class="form-control" placeholder="Masukkan URL file, contoh: https://docs.google.com/..." value="{{ old('link', $sla->file) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="problem_{{ $sla->id }}">Masalah/Problem</label>
                            <textarea name="problem" id="problem_{{ $sla->id }}" class="form-control" rows="3" placeholder="Deskripsikan masalah yang terjadi..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Selesaikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endsection