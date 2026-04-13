@extends('layouts.app')

@section('title', 'Monitoring SLA Pegawai')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">MONITORING SLA PEGAWAI</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('sla.performance') }}" method="GET">
                <div class="form-row">

                    <div class="form-group col-md-4 d-flex align-items-center">
                        <label for="pic_filter" class="mr-2 mb-0" style="white-space: nowrap;">Nama Pegawai :</label>
                        <select name="pic_filter" id="pic_filter" class="form-control select2-searchable">
                            <option value="">-- Semua Pegawai --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ (string)$selectedPicId === (string)$user->id ? 'selected' : '' }}>
                                    {{ $user->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4 d-flex align-items-center">
                        <label for="customer_filter" class="mr-2 mb-0" style="white-space: nowrap;">Customer :</label>
                        <select name="customer_filter" id="customer_filter" class="form-control select2-searchable">
                            <option value="">-- Semua Customer --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (string)$selectedCustomerId === (string)$customer->id ? 'selected' : '' }}>
                                    {{ $customer->nama_customer }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4 d-flex align-items-center">
                        <label for="date_from" class="mr-2 mb-0" style="white-space: nowrap;">
                            Dari Tanggal :
                        </label>
                        <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}"
                        >
                    </div>

                    <div class="form-group col-md-4 d-flex align-items-center">
                        <label for="date_to" class="mr-2 mb-0" style="white-space: nowrap;">
                            Sampai Tanggal :
                        </label>
                        <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}"
                        >
                    </div>

                    <div class="form-group col-md-4 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-2">Lihat</button>

                        @if ($selectedPicId || $selectedCustomerId || $statusFilter || request('date_from') ||request('date_to'))
                            <a href="{{ route('sla.performance') }}" class="btn btn-secondary">Reset</a>
                        @endif
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 dashboard-card"
                data-url="{{ route('sla.performance', ['status' => 'ONGOING']) }}">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">ONGOING</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ongoingCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 dashboard-card"
                data-url="{{ route('sla.performance', ['status' => 'LATE']) }}">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">LATE</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lateCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 dashboard-card"
                data-url="{{ route('sla.performance', ['status' => 'ONTIME']) }}">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">ONTIME</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ontimeCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 dashboard-card"
                data-url="{{ route('sla.performance', ['status' => 'ALL']) }}">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">TOTAL SLA</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Service Level Agreement</h6>
            <div>
                <a href="{{ route('sla.export', request()->query()) }}" class="btn btn-excel btn-sm">
                    <i class="fas fa-file-excel mr-2"></i> Unduh
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">

                @php $showActionColumn = false; @endphp

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
                                @if(($sla->status == 'ONGOING' && auth()->check() && $sla->PIC_id == auth()->id()) || Gate::allows('manage-master'))
                                    @php $showActionColumn = true; break; @endphp
                                @endif
                            @endforeach
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
                                <td>{{ $sla->finish ? \Carbon\Carbon::parse($sla->finish)->format('d-m-Y') : '-' }}</td>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $showActionColumn ? '15' : '14' }}" class="text-center">
                                    Tidak ada data SLA yang ditemukan.
                                </td>
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
@endsection