@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Data Karakteristik Limbah</h1>
                    <p class="text-slate-600">Kelola data karakteristik limbah dalam sistem</p>
                </div>
                <a href="{{ route('karakteristik-limbah.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i> Tambah Karakteristik
                </a>
            </div>
        </div>
    </div>
    
    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="p-8">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="50%">Nama Karakteristik</th>
                                    <th width="25%">Status</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($karakteristikLimbah as $index => $karakteristik)
                                    <tr>
                                        <td>{{ $karakteristikLimbah->firstItem() + $index }}</td>
                                        <td>{{ $karakteristik->nama_karakteristik }}</td>
                                        <td>
                                            @if($karakteristik->status_aktif)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('karakteristik-limbah.show', $karakteristik) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('karakteristik-limbah.edit', $karakteristik) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('karakteristik-limbah.destroy', $karakteristik) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus karakteristik limbah ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data karakteristik limbah</p>
                                                <a href="{{ route('karakteristik-limbah.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Karakteristik Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($karakteristikLimbah->hasPages())
                        <div class="flex justify-center mt-6">
                            {{ $karakteristikLimbah->links() }}
                        </div>
                    @endif
        </div>
    </div>
</div>
@endsection