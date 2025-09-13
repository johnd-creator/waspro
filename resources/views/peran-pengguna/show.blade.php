@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Detail Peran Pengguna</h1>
                    <p class="text-slate-600">Informasi lengkap peran: {{ $peranPengguna->nama_peran }}</p>
                </div>
                <div>
                    <a href="{{ route('peran-pengguna.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-8 py-6 border-b border-slate-200">
            <h6 class="text-lg font-semibold text-slate-900 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>Informasi Detail
            </h6>
        </div>
        <div class="px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">ID Peran:</th>
                                    <td>{{ $peranPengguna->peran_id }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Peran:</th>
                                    <td>{{ $peranPengguna->nama_peran }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($peranPengguna->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat:</th>
                                    <td>{{ $peranPengguna->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Diperbarui:</th>
                                    <td>{{ $peranPengguna->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Deskripsi:</strong></label>
                                <div class="border p-3 bg-light">
                                    {{ $peranPengguna->deskripsi ?? 'Tidak ada deskripsi' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Users with this role -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Pengguna dengan Peran Ini</h5>
                            @if($peranPengguna->penggunaSistem->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pengguna</th>
                                                <th>Email</th>
                                                <th>Ditambahkan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($peranPengguna->penggunaSistem as $index => $user)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $user->nama_lengkap }}</td>
                                                    <td>{{ $user->email_address }}</td>
                                                    <td>{{ $user->pivot->created_at ? $user->pivot->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Belum ada pengguna yang memiliki peran ini.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <a href="{{ route('peran-pengguna.edit', $peranPengguna->peran_id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    
                    <form action="{{ route('peran-pengguna.toggle-status', $peranPengguna->peran_id) }}" 
                          method="POST" style="display: inline;" class="ml-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="btn btn-{{ $peranPengguna->is_active ? 'secondary' : 'success' }}" 
                                onclick="return confirm('Apakah Anda yakin ingin @if($peranPengguna->is_active) menonaktifkan @else mengaktifkan @endif peran ini?')">
                            <i class="fas fa-{{ $peranPengguna->is_active ? 'times' : 'check' }}"></i>
                            {{ $peranPengguna->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    
                    @if($peranPengguna->penggunaSistem->count() == 0)
                        <form action="{{ route('peran-pengguna.destroy', $peranPengguna->peran_id) }}" 
                              method="POST" style="display: inline;" class="ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus peran ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    @else
                        <button type="button" class="btn btn-danger ml-2" disabled title="Tidak dapat dihapus karena masih digunakan">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    @endif
        </div>
    </div>
</div>
@endsection