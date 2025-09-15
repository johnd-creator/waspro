@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-600"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center">
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
        <div class="px-6 py-6">
            <p class="text-slate-600 text-sm">Daftar jenis limbah yang terdaftar dalam sistem</p>
        </div>
    </div>

    <!-- Tabel Jenis Limbah -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1200px]">
                <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                    <tr>
                        <th class="px-4 py-4 text-left text-sm font-semibold w-16">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[150px]">Kode Limbah</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[250px]">Nama Limbah</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[180px]">Karakteristik</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[160px]">Masa Penyimpanan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[120px]">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[160px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($jenisLimbah as $index => $jenis)
                        <tr class="hover:bg-slate-50/50 transition-all duration-200 border-b border-slate-100 last:border-b-0">
                            <td class="px-4 py-4 text-sm font-medium text-slate-700 text-center">
                                {{ $jenisLimbah->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-flask text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-900 text-sm">{{ $jenis->kode_limbah }}</div>
                                        <div class="text-xs text-slate-500 mt-1">
                                            ID: {{ $jenis->jenis_limbah_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900 text-sm">{{ $jenis->nama_limbah }}</div>
                                @if($jenis->deskripsi)
                                    <div class="text-xs text-slate-500 mt-1 max-w-xs truncate">{{ $jenis->deskripsi }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($jenis->karakteristikLimbah)
                                    <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-lg text-xs font-medium">
                                        {{ $jenis->karakteristikLimbah->nama_karakteristik }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($jenis->batas_penyimpanan_hari)
                                    <span class="bg-orange-50 text-orange-700 px-3 py-1 rounded-lg text-xs font-medium">
                                        {{ $jenis->batas_penyimpanan_hari }} hari
                                    </span>
                                @else
                                    <span class="bg-slate-50 text-slate-600 px-3 py-1 rounded-lg text-xs font-medium">
                                        Tidak ditentukan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($jenis->status_aktif)
                                    <span class="bg-green-50 text-green-700 px-3 py-1 rounded-lg text-xs font-medium">
                                        Aktif
                                    </span>
                                @else
                                    <span class="bg-red-50 text-red-700 px-3 py-1 rounded-lg text-xs font-medium">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('jenis-limbah.show', $jenis) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors"
                                       title="Lihat Detail ({{ $jenis->nama_limbah }})"
                                       target="_self">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('jenis-limbah.edit', $jenis) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-600 transition-colors"
                                       title="Edit ({{ $jenis->nama_limbah }})">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('jenis-limbah.destroy', $jenis) }}"
                                          method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors"
                                                title="Hapus ({{ $jenis->nama_limbah }})"
                                                onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus jenis limbah {{ $jenis->nama_limbah }}?')">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-flask text-6xl text-slate-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-slate-900 mb-2">Belum ada data jenis limbah</h3>
                                    <p class="text-slate-500 mb-4">Mulai dengan menambahkan jenis limbah pertama Anda</p>
                                    <a href="{{ route('jenis-limbah.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                                        <i class="fas fa-plus mr-2"></i> Tambah Jenis Limbah Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($jenisLimbah->hasPages())
        <div class="flex justify-center mt-8">
            <div class="bg-white rounded-xl border border-slate-200 px-6 py-4">
                {{ $jenisLimbah->links() }}
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    function handleDeleteConfirm(event, message) {
        event.preventDefault();
        if (confirm(message)) {
            event.target.closest('form').submit();
        }
        return false;
    }
</script>
@endpush