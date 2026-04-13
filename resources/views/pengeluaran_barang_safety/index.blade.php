@extends('layouts.app')

@section('title', 'Pengeluaran Alat Safety')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">PENGELUARAN ALAT SAFETY</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Pengeluaran Alat Safety</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>PIC</th>
                            <th>Tanggal Keluar</th>
                            <th>Barang (Jumlah)</th>
                            <th>Customer</th>
                            <th>Lokasi</th>
                            <th>Keterangan</th>
                            <th>Bukti Peminjaman</th>
                            <th>Tanggal Kembali</th>
                            <th>Pengembali</th>
                            <th>Bukti Pengembalian</th>
                            <th>Status</th>
                            @can('manage-pengeluaran')
                                <th>Aksi</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengeluaran_safety as $pbs)
                        <tr class="text-center align-middle">
                            <td>{{ $pengeluaran_safety->firstItem() + $loop->index }}</td>
                            <td>{{ $pbs->user->nama ?? '-' }}</td>
                            <td>{{ $pbs->pic->nama ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pbs->tanggal_keluar)->format('d M Y') }}</td>
                            <td>
                                @foreach($pbs->items as $itemDetail)
                                    {{ $itemDetail->masterBarang->nama_barang }} ({{ $itemDetail->pivot->jumlah_keluar }} {{ $itemDetail->masterBarang->satuan }})<br>
                                @endforeach
                            </td>
                            <td>{{ $pbs->customer->nama_customer ?? '-' }}</td>
                            <td>{{ $pbs->customer->alamat_lengkap ?? '-' }}</td>
                            <td>{{ $pbs->keterangan ?? '-' }}</td>
                            <td>
                                @if($pbs->bukti_pinjam)
                                    <a href="{{ Storage::disk('public')->url($pbs->bukti_pinjam) }}" target="_blank">Lihat File</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $pbs->tanggal_kembali ? \Carbon\Carbon::parse($pbs->tanggal_kembali)->format('d M Y') : '-' }}</td>
                            <td>{{ $pbs->returner->nama ?? '-' }}</td>
                            <td>
                                @if($pbs->bukti_kembali)
                                    <a href="{{ Storage::disk('public')->url($pbs->bukti_kembali) }}" target="_blank">Lihat File</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($pbs->status == 'dipinjam')
                                    <span class="badge badge-warning">Dipinjam</span>
                                @elseif($pbs->status == 'dikembalikan')
                                    <span class="badge badge-success">Dikembalikan</span>
                                @else
                                    <span class="badge badge-danger">{{ ucfirst($pbs->status) }}</span>
                                @endif
                            </td>
                            @can('manage-pengeluaran')
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($pbs->status === 'dipinjam' && ($pbs->pic_id == auth()->id() || Gate::allows('manage-pengeluaran')))
                                            <button type="button" class="btn btn-sm btn-success mr-1" data-toggle="modal" data-target="#kembalikanModal{{ $pbs->id }}" title="Kembalikan Alat">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif

                                        @can('edit-pengeluaran')
                                            <a href="{{ route('pengeluaran_barang_safety.edit', Crypt::encrypt($pbs->id)) }}"class="btn btn-sm btn-warning mr-1"title="Edit Pengeluaran">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('delete-pengeluaran')
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#hapusModal{{ $pbs->id }}" title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            @endcan
                        </tr>
                        
                        <div class="modal fade" id="hapusModal{{ $pbs->id }}" tabindex="-1" role="dialog" aria-labelledby="hapusModalLabel{{ $pbs->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="hapusModalLabel{{ $pbs->id }}">Konfirmasi Hapus</h5>
                                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus data pengeluaran ini ?
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                        <form action="{{ route('pengeluaran_barang_safety.destroy', $pbs->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($pbs->status === 'dipinjam')
                        <div class="modal fade" id="kembalikanModal{{ $pbs->id }}" tabindex="-1" role="dialog" aria-labelledby="kembalikanModalLabel{{ $pbs->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title" id="kembalikanModalLabel{{ $pbs->id }}">Konfirmasi Pengembalian Alat</h5>
                                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('pengeluaran_barang_safety.kembalikan', $pbs->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="pengembali_id_{{ $pbs->id }}">Nama Pengembali :</label>
                                                <select name="pengembali_id" id="pengembali_id_{{ $pbs->id }}" class="form-control" required>
                                                    <option value="">-- Pilih Pengembali --</option>
                                                    @foreach($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="bukti_kembali_{{ $pbs->id }}">Bukti Foto Pengembalian</label>
                                                <input type="file" class="form-control-file" id="bukti_kembali_{{ $pbs->id }}" name="bukti_kembali" required>
                                            </div>
                                            @foreach($pbs->items as $itemDetail)
                                                <p>Alat : <strong>{{ $itemDetail->masterBarang->nama_barang }}</strong> (Total dipinjam: {{ $itemDetail->pivot->jumlah_keluar }})</p>
                                                <div class="form-group">
                                                    <label for="jumlah_kembali_{{ $itemDetail->id }}">Jumlah alat yang dikembalikan dengan baik :</label>
                                                    <input type="number" name="items_kembali[{{ $itemDetail->id }}][jumlah_kembali]" id="jumlah_kembali_{{ $itemDetail->id }}" class="form-control" min="0" max="{{ $itemDetail->pivot->jumlah_keluar }}" value="{{ $itemDetail->pivot->jumlah_keluar }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="alasan_{{ $itemDetail->id }}">Alasan jika ada alat rusak/hilang (opsional):</label>
                                                    <textarea name="items_kembali[{{ $itemDetail->id }}][alasan]" id="alasan_{{ $itemDetail->id }}" class="form-control" rows="2"></textarea>
                                                </div>
                                                <hr>
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Proses Pengembalian</button>
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
                {{ $pengeluaran_safety->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection