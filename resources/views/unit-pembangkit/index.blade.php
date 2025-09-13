@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Unit Pembangkit</h1>
                    <p class="text-slate-600">Kelola informasi unit pembangkit listrik</p>
                </div>
                <div>
                    <a href="{{ route('unit-pembangkit.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>Tambah Unit Pembangkit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-8 py-6 border-b border-slate-200">
            <h6 class="text-lg font-semibold text-slate-900 flex items-center">
                <i class="fas fa-bolt mr-2"></i>Daftar Unit Pembangkit
            </h6>
        </div>
        <div class="px-8 py-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="25%">Nama Unit</th>
                                    <th width="30%">Alamat</th>
                                    <th width="15%">Kota</th>
                                    <th width="10%">Kode Pos</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unitPembangkit as $index => $unit)
                                    <tr>
                                        <td>{{ $unitPembangkit->firstItem() + $index }}</td>
                                        <td>{{ $unit->nama_unit }}</td>
                                        <td>{{ $unit->alamat_unit ?? '-' }}</td>
                                        <td>{{ $unit->kota ?? '-' }}</td>
                                        <td>{{ $unit->kode_pos ?? '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('unit-pembangkit.show', $unit) }}" 
                                                   class="btn btn-info btn-sm" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('unit-pembangkit.edit', $unit) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('unit-pembangkit.destroy', $unit) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"
                                                            onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus unit pembangkit ini?')">
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
                                                <p class="text-muted">Belum ada data unit pembangkit</p>
                                                <a href="{{ route('unit-pembangkit.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Unit Pembangkit Pertama
                        </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($unitPembangkit->hasPages())
                        <div class="flex justify-center mt-6">
                            {{ $unitPembangkit->links() }}
                        </div>
                    @endif
                </div>
            </div>
    </div>
</div>
@endsection