@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Data Kategori Kegiatan Sumber</h1>
                    <p class="text-slate-600">Kelola data kategori kegiatan sumber dalam sistem</p>
                </div>
                <a href="{{ route('kategori-kegiatan-sumber.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i> Tambah Kategori
                        </a>
                    </div>
                </div>
        </div>
    </div>
    
    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-8 py-6">
            <div class="overflow-x-auto">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="10%">No</th>
                                    <th width="70%">Nama Kategori</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategoriKegiatanSumber as $index => $kategori)
                                    <tr>
                                        <td>{{ $kategoriKegiatanSumber->firstItem() + $index }}</td>
                                        <td>{{ $kategori->nama_kategori }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('kategori-kegiatan-sumber.show', $kategori) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('kategori-kegiatan-sumber.edit', $kategori) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('kategori-kegiatan-sumber.destroy', $kategori) }}" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori kegiatan sumber ini?')">
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
                                        <td colspan="3" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data kategori kegiatan sumber</p>
                                                <a href="{{ route('kategori-kegiatan-sumber.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Tambah Kategori Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($kategoriKegiatanSumber->hasPages())
                        <div class="flex justify-center mt-6">
                            {{ $kategoriKegiatanSumber->links() }}
                        </div>
                    @endif
            </div>
        </div>
    </div>
</div>
@endsection