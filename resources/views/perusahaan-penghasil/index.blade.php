@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Data Perusahaan Penghasil Limbah</h1>
                    <p class="text-slate-600">Kelola informasi perusahaan penghasil limbah</p>
                </div>
                <div>
                    <a href="{{ route('perusahaan-penghasil.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>Tambah Perusahaan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-8 py-6 border-b border-slate-200">
            <h6 class="text-lg font-semibold text-slate-900 flex items-center">
                <i class="fas fa-building mr-2"></i>Daftar Perusahaan
            </h6>
        </div>
        <div class="px-8 py-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Nama Perusahaan</th>
                                    <th width="30%">Alamat</th>
                                    <th width="20%">Kontak</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perusahaanPenghasil as $index => $perusahaan)
                                    <tr>
                                        <td>{{ $perusahaanPenghasil->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ $perusahaan->nama_perusahaan }}</strong>
                                            @if($perusahaan->jenis_perusahaan)
                                                <br><small class="text-muted">{{ $perusahaan->jenis_perusahaan }}</small>
                                            @endif
                                        </td>

                                        <td>
                                            <small>
                                                {{ Str::limit($perusahaan->alamat_perusahaan, 50) }}
                                                @if($perusahaan->kota)
                                                    <br><strong>{{ $perusahaan->kota }}</strong>
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            @if($perusahaan->telepon)
                                                <small><i class="fas fa-phone"></i> {{ $perusahaan->telepon }}</small><br>
                                            @endif
                                            @if($perusahaan->email)
                                                <small><i class="fas fa-envelope"></i> {{ $perusahaan->email }}</small>
                                            @endif
                                        </td>

                                        <td>
                                            @if($perusahaan->status_aktif)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('perusahaan-penghasil.show', $perusahaan) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('perusahaan-penghasil.edit', $perusahaan) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('perusahaan-penghasil.destroy', $perusahaan) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                            onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus perusahaan ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data perusahaan penghasil limbah</p>
                                                <a href="{{ route('perusahaan-penghasil.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Perusahaan Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($perusahaanPenghasil->hasPages())
                        <div class="flex justify-center mt-6">
                            {{ $perusahaanPenghasil->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection