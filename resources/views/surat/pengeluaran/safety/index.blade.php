@extends('layouts.app')

@section('title', 'Cetak Surat Pengeluaran Safety')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-print mr-2 text-danger"></i>
                Cetak Surat Pengeluaran Alat Safety
            </h1>
            <small class="text-muted">
                Pilih data pengeluaran alat safety untuk mencetak surat resmi.
            </small>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-1"></i>
                Daftar Pengeluaran Alat Safety
            </h6>

            <span class="small text-muted">
                Total: {{ $pengeluaran->total() }} data
            </span>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" width="100%">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr class="text-center">
                            <th width="50">No</th>
                            <th>Peminjam</th>
                            <th>PIC</th>
                            <th width="130">Tanggal</th>
                            <th>Customer</th>
                            <th>Keterangan</th>
                            <th width="70">Cetak</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($pengeluaran as $index => $pbs)
                            <tr>
                                <td class="text-center">{{ $pengeluaran->firstItem() + $index }}</td>
                                <td class="font-weight-bold">{{ $pbs->user->nama ?? '-' }}</td>
                                <td class="text-muted">{{ $pbs->pic->nama ?? '-' }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($pbs->tanggal_keluar)->format('d M Y') }}</td>
                                <td>{{ $pbs->customer?->nama_customer ?? '-' }}</td>
                                <td class="text-muted">{{ $pbs->keterangan ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('surat.pengeluaran.safety.cetak', $pbs->id) }}" class="btn btn-sm btn-outline-danger" target="_blank" title="Cetak Surat">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-folder-open fa-2x mb-2"></i><br>
                                    Tidak ada data pengeluaran safety.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $pengeluaran->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </div>
</div>
@endsection