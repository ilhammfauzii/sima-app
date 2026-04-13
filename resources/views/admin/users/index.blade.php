@extends('layouts.app')

@section('title', 'Data Pengguna')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">DATA PENGGUNA</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" width="100%">
                    <thead class="bg-light">
                        <tr class="text-center text-uppercase small text-muted">
                            <th width="50">No</th>
                            <th class="text-left">Nama</th>
                            <th width="160">Nomor Pegawai</th>
                            <th class="text-left">Email</th>
                            <th width="140">Role</th>
                            @can('manage-users')
                                <th width="110">Aksi</th>
                            @endcan
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-center">
                                    {{ $users->firstItem() + $loop->index }}
                                </td>

                                <td class="font-weight-bold">
                                    {{ $user->nama }}
                                </td>

                                <td class="text-center">
                                    {{ $user->nomor_pegawai }}
                                </td>

                                <td>
                                    {{ $user->email }}
                                </td>

                                <td class="text-center">
                                    <span class="badge badge-info px-3 py-2">
                                        {{ $user->role?->nama_roles ?? 'N/A' }}
                                    </span>
                                </td>

                                @can('manage-users')
                                <td class="text-center">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="btn btn-sm btn-outline-warning"
                                       title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button class="btn btn-sm btn-outline-danger"
                                            data-toggle="modal"
                                            data-target="#hapusModal{{ $user->id }}"
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $users->links() }}
            </div>

        </div>
    </div>
</div>

@foreach ($users as $user)
<div class="modal fade" id="hapusModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    Konfirmasi Hapus
                </h5>
                <button class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin menghapus akun
                <strong>{{ $user->nama }}</strong>?
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-dismiss="modal">
                    Batal
                </button>

                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endforeach
@endsection