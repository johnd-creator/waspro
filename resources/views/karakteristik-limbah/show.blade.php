@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Detail Karakteristik Limbah</h1>
                    <p class="text-slate-600">Informasi lengkap tentang karakteristik limbah</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('karakteristik-limbah.edit', $karakteristikLimbah) }}" class="inline-flex items-center px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('karakteristik-limbah.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-1/3">
                                <span class="text-sm font-medium text-slate-700">Kode Karakteristik</span>
                            </div>
                            <div class="w-8 text-center">
                                <span class="text-slate-500">:</span>
                            </div>
                            <div class="flex-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">{{ $karakteristikLimbah->kode_karakteristik }}</span>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-1/3">
                                <span class="text-sm font-medium text-slate-700">Nama Karakteristik</span>
                            </div>
                            <div class="w-8 text-center">
                                <span class="text-slate-500">:</span>
                            </div>
                            <div class="flex-1">
                                <span class="text-slate-900">{{ $karakteristikLimbah->nama_karakteristik }}</span>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-1/3">
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </div>
                            <div class="w-8 text-center">
                                <span class="text-slate-500">:</span>
                            </div>
                            <div class="flex-1">
                                @if($karakteristikLimbah->status_aktif)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-1/3">
                                <span class="text-sm font-medium text-slate-700">Dibuat</span>
                            </div>
                            <div class="w-8 text-center">
                                <span class="text-slate-500">:</span>
                            </div>
                            <div class="flex-1">
                                <span class="text-slate-900">{{ $karakteristikLimbah->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-1/3">
                                <span class="text-sm font-medium text-slate-700">Diperbarui</span>
                            </div>
                            <div class="w-8 text-center">
                                <span class="text-slate-500">:</span>
                            </div>
                            <div class="flex-1">
                                <span class="text-slate-900">{{ $karakteristikLimbah->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div class="bg-slate-50 rounded-xl p-6 text-center">
                        <i class="fas fa-flask text-4xl text-blue-600 mb-4"></i>
                        <h5 class="text-lg font-semibold text-slate-900 mb-2">Karakteristik Limbah</h5>
                        <p class="text-slate-600">{{ $karakteristikLimbah->nama_karakteristik }}</p>
                    </div>
                </div>
            </div>

            <!-- Related Jenis Limbah -->
            @if($karakteristikLimbah->jenisLimbah && $karakteristikLimbah->jenisLimbah->count() > 0)
                <div class="mt-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
                        <div class="px-8 py-6 border-b border-slate-200">
                            <h5 class="text-lg font-semibold text-slate-900">Jenis Limbah Terkait</h5>
                        </div>
                        <div class="p-8">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kode</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Limbah</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-200">
                                        @foreach($karakteristikLimbah->jenisLimbah->take(5) as $jenisLimbah)
                                            <tr class="hover:bg-slate-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-800">{{ $jenisLimbah->kode_limbah }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $jenisLimbah->nama_limbah }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $jenisLimbah->kategori ?? '-' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($jenisLimbah->status_aktif)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Aktif</span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <a href="{{ route('jenis-limbah.show', $jenisLimbah) }}" 
                                                       class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($karakteristikLimbah->jenisLimbah->count() > 5)
                                <div class="text-center mt-4">
                                    <p class="text-sm text-slate-600">Menampilkan 5 dari {{ $karakteristikLimbah->jenisLimbah->count() }} jenis limbah</p>
                                </div>
                            @endif
                        </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Delete Form -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="card-title mb-0">Zona Berbahaya</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Menghapus karakteristik limbah ini akan mempengaruhi semua jenis limbah yang terkait. Pastikan Anda yakin sebelum melanjutkan.</p>
                                    <form action="{{ route('karakteristik-limbah.destroy', $karakteristikLimbah) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus karakteristik limbah ini? Tindakan ini tidak dapat dibatalkan!')">
                                            <i class="fas fa-trash"></i> Hapus Karakteristik Limbah
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    </div>
</div>
@endsection