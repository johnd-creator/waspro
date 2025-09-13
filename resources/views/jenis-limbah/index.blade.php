@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    @if(session('success'))
        <div class="mb-4">
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
                <button type="button" class="text-green-600 hover:text-green-800 focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Data Jenis Limbah</h1>
                <p class="text-slate-600">Kelola dan pantau data jenis limbah dengan mudah</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('jenis-limbah.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i> Tambah Jenis Limbah
                </a>
            </div>
        </div>
        <div class="px-8 py-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Limbah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Limbah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Karakteristik</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masa Penyimpanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($jenisLimbah as $index => $jenis)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jenisLimbah->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $jenis->kode_limbah }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jenis->nama_limbah }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($jenis->karakteristikLimbah)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $jenis->karakteristikLimbah->nama_karakteristik }}</span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($jenis->batas_penyimpanan_hari)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">{{ $jenis->batas_penyimpanan_hari }} hari</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">Tidak ditentukan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($jenis->status_aktif)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('jenis-limbah.show', $jenis) }}" 
                                           class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors duration-200" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('jenis-limbah.edit', $jenis) }}" 
                                           class="inline-flex items-center px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded transition-colors duration-200" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('jenis-limbah.destroy', $jenis) }}" 
                                              method="POST" class="inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis limbah ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors duration-200" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-500 mb-4">Belum ada data jenis limbah</p>
                                        <a href="{{ route('jenis-limbah.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                            <i class="fas fa-plus mr-2"></i> Tambah Jenis Limbah Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                        </table>
                    </div>
                    
            @if($jenisLimbah->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <div class="flex justify-center">
                        {{ $jenisLimbah->links() }}
                    </div>
                </div>
            @endif
                </div>
 </div>
@endsection