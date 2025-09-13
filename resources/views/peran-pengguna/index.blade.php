@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Manajemen Peran Pengguna</h1>
                    <p class="text-slate-600">Kelola peran dan hak akses pengguna sistem</p>
                </div>
                <div>
                    <a href="{{ route('peran-pengguna.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>Tambah Peran
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('peran-pengguna.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Cari nama peran atau deskripsi..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="status" class="form-control">
                                        <option value="">Semua Status</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-info mr-2">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <a href="{{ route('peran-pengguna.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <x-session-messages />

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-8 py-6 border-b border-slate-200">
            <h6 class="text-lg font-semibold text-slate-900 flex items-center">
                <i class="fas fa-list mr-2"></i>Daftar Peran Pengguna
                <span class="ml-2 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">{{ $peranPengguna->count() }}</span>
            </h6>
        </div>
        <div class="px-8 py-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Peran</th>
                                    <th>Deskripsi</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peranPengguna as $index => $peran)
                                    <tr>
                                        <td>{{ $peranPengguna->firstItem() + $index }}</td>
                                        <td>{{ $peran->nama_peran }}</td>
                                        <td>{{ $peran->deskripsi ?? '-' }}</td>
                                        <td>
                                            @if($peran->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('peran-pengguna.show', $peran->peran_id) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('peran-pengguna.edit', $peran->peran_id) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('peran-pengguna.toggle-status', $peran->peran_id) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-{{ $peran->is_active ? 'secondary' : 'success' }} btn-sm" 
                                                            title="{{ $peran->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                            onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin @if($peran->is_active) menonaktifkan @else mengaktifkan @endif peran ini?')">
                                                        <i class="fas fa-{{ $peran->is_active ? 'times' : 'check' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('peran-pengguna.destroy', $peran->peran_id) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            title="Hapus"
                                                            onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus peran ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data peran pengguna</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-between items-center mt-6">
                        <div class="text-sm text-slate-600">
                            Menampilkan {{ $peranPengguna->firstItem() ?? 0 }} sampai {{ $peranPengguna->lastItem() ?? 0 }} 
                            dari {{ $peranPengguna->total() }} data
                        </div>
                        <div>
                            {{ $peranPengguna->appends(request()->query())->links() }}
                        </div>
                    </div>
        </div>
    </div>
</div>
@endsection