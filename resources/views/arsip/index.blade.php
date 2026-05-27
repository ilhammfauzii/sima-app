@extends('layouts.app')

@section('title', 'Daftar Arsip Dokumen')

@section('content')
<div class="container py-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 mb-md-0 text-gray-800 fw-semibold">
            @if($tipeFilter == 'dikirim')
                <i class="fas fa-paper-plane me-2"></i>Dokumen Yang Saya Kirim
            @elseif($tipeFilter == 'diterima')
                <i class="fas fa-inbox me-2"></i>Dokumen Yang Saya Terima
            @else
                <i class="fas fa-archive me-2"></i>Daftar Arsip Dokumen
            @endif
        </h1>
    </div>

    <div class="card-modern shadow-sm mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <select name="tipe" id="tipe" class="form-select form-select-sm">
                        <option value="semua" {{ $tipeFilter == 'semua' ? 'selected' : '' }}>Semua File Saya</option>
                        <option value="dikirim" {{ $tipeFilter == 'dikirim' ? 'selected' : '' }}>Yang Saya Kirim</option>
                        <option value="diterima" {{ $tipeFilter == 'diterima' ? 'selected' : '' }}>Yang Saya Terima</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="kategori" id="kategori" class="form-select form-select-sm">
                        <option value="semua" {{ $kategoriFilter == 'semua' ? 'selected' : '' }}>Semua Kategori</option>
                        <option value="rahasia" {{ $kategoriFilter == 'rahasia' ? 'selected' : '' }}> Rahasia</option>
                        <option value="tidak_rahasia" {{ $kategoriFilter == 'tidak_rahasia' ? 'selected' : '' }}>Biasa</option>
                    </select>
                </div>
                <div class="col-md-5 text-md-end">
                    <div class="small text-muted">
                        <i class="fas fa-user me-1"></i>{{ auth()->user()->nama }}
                        <span class="badge bg-light text-dark ms-1">
                            {{ auth()->user()->role?->nama_roles }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-modern shadow-sm">
        <div class="card-header bg-primary text-white fw-bold py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    @if($tipeFilter == 'dikirim')
                        <i class="fas fa-paper-plane me-2"></i>File yang Anda Kirim
                    @elseif($tipeFilter == 'diterima')
                        <i class="fas fa-inbox me-2"></i>File yang Anda Terima
                    @else
                        <i class="fas fa-archive me-2"></i>Semua File Anda
                    @endif
                    <span class="badge bg-light text-dark ms-2">{{ $files->total() }} dokumen</span>
                </div>
            </div>
        </div>
        
        <div class="card-body p-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($files->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <h5 class="mb-2">Tidak ada dokumen</h5>
                    <p class="small mb-4">
                        @if($tipeFilter == 'dikirim')
                            Anda belum mengirim dokumen apapun
                        @elseif($tipeFilter == 'diterima')
                            Tidak ada dokumen yang dikirimkan kepada Anda
                        @else
                            Anda tidak memiliki dokumen dalam arsip
                        @endif
                    </p>
                    <a href="{{ route('arsip.upload-menu') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-upload me-1"></i> Upload Dokumen Pertama
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>NO</th>
                                <th>Nama File</th>
                                <th>Jenis</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Dikirim Oleh</th>
                                <th>Tanggal Upload</th>
                                <th class="text-center" style="min-width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $file)
                                <tr>
                                    <td>{{ ($files->currentPage() - 1) * $files->perPage() + $loop->iteration }}</td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas 
                                                @if(str_contains($file->tipe_file, 'pdf')) fa-file-pdf text-danger
                                                @elseif(str_contains($file->tipe_file, 'word')) fa-file-word text-primary
                                                @elseif(str_contains($file->tipe_file, 'excel') || str_contains($file->tipe_file, 'spreadsheet')) fa-file-excel text-success
                                                @elseif(str_contains($file->tipe_file, 'image')) fa-file-image text-warning
                                                @else fa-file text-secondary
                                                @endif
                                                me-2"></i>
                                            <div>
                                                <div class="fw-bold small">{{ \Illuminate\Support\Str::limit($file->nama_file_asli, 30) }}</div>
                                                <small class="text-muted">{{ $file->ukuran_file_formatted }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge bg-light text-dark">{{ $file->jenis_dokumen }}</span>
                                    </td>

                                    <td>
                                        @if($file->kategori == 'rahasia')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-lock me-1"></i> Rahasia
                                            </span>
                                        @else
                                            <span class="badge bg-info text-dark">
                                                <i class="fas fa-file me-1"></i> Biasa
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $isExpired = $file->kadaluarsa_pada && now()->greaterThan($file->kadaluarsa_pada);
                                        @endphp

                                        @if($file->kategori == 'rahasia')
                                            @if($isExpired)
                                                <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Kadaluarsa</span>
                                            @else
                                                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                                            @endif
                                        @else
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Aktif</span>
                                        @endif
                                    </td>

                                    <td>
                                        <small>{{ $file->diuploadOleh->nama ?? 'Unknown' }}</small>
                                    </td>

                                    <td>
                                        <small>{{ $file->created_at->format('d/m/Y H:i') }}</small>
                                    </td>

                                    <td class="d-flex justify-content-center align-items-center">

                                        @if($file->kategori == 'rahasia')

                                            @can('downloadEncrypted', $file)
                                                <a href="{{ route('arsip.download-encrypted', $file->id) }}" class="btn btn-sm btn-secondary mx-1" title="Download File Terenkripsi">
                                                    <i class="fas fa-lock"></i>
                                                </a>
                                            @endcan

                                            @can('decrypt', $file)
                                                <a href="{{ route('arsip.dekripsi-form') }}" class="btn btn-sm btn-warning mx-1" title="Dekripsi File">
                                                    <i class="fas fa-unlock"></i>
                                                </a>
                                            @endcan

                                        @else

                                            @can('download', $file)
                                                <a href="{{ route('arsip.download', $file->id) }}" class="btn btn-sm btn-primary mx-1" title="Download File">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endcan

                                        @endif

                                        @can('updateKadaluarsa', $file)
                                            @if($file->kategori === 'rahasia')
                                                <a href="{{ route('arsip.kadaluarsa.edit', $file->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-clock"></i>
                                                </a>
                                            @endif
                                        @endcan

                                        @can('delete', $file)
                                            <button type="button" class="btn btn-sm btn-danger mx-1" data-toggle="modal" data-target="#hapusModal{{ $file->id }}" data-turbo="false" title="Hapus File">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endcan

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $files->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

@if(!$files->isEmpty())
@foreach($files as $file)
@can('delete', $file)
<div class="modal fade" id="hapusModal{{ $file->id}}" tabindex="-1" role="dialog" aria-labelledby="hapusModalLabel{{ $file->id}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="hapusModalLabel{{ $file->id}}">Konfirmasi Hapus</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus arsip data **{{ $file->nama_file_asli }}** ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('arsip.destroy', $file->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan
@endforeach
@endif

@endsection