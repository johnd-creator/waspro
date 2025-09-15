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

<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Perusahaan Penghasil Limbah</h1>
                <p class="text-slate-600">Kelola dan pantau data perusahaan penghasil limbah</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('perusahaan-penghasil.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i> Tambah Perusahaan
                </a>
            </div>
        </div>
        <div class="px-6 py-6">
            <p class="text-slate-600 text-sm">Daftar perusahaan penghasil limbah yang terdaftar dalam sistem</p>
        </div>
    </div>

    <!-- Tabel Perusahaan Penghasil -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px]">
                <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                    <tr>
                        <th class="px-4 py-4 text-left text-sm font-semibold w-16">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[220px]">Nama Perusahaan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[320px]">Alamat</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[160px]">Kota</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[120px]">Kontak</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[100px]">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold min-w-[160px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($perusahaanPenghasil as $index => $perusahaan)
                        <tr class="hover:bg-slate-50/50 transition-all duration-200 border-b border-slate-100 last:border-b-0">
                            <td class="px-4 py-4 text-sm font-medium text-slate-700 text-center">
                                {{ $perusahaanPenghasil->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900 text-sm">{{ $perusahaan->nama_perusahaan }}</div>
                                @if($perusahaan->jenis_perusahaan)
                                    <div class="text-xs text-slate-500 mt-1 flex items-center">
                                        <i class="fas fa-industry mr-1"></i> {{ $perusahaan->jenis_perusahaan }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 leading-relaxed">
                                <div class="max-w-xl truncate" title="{{ $perusahaan->alamat_perusahaan ?? '-' }}">
                                    {{ $perusahaan->alamat_perusahaan ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-700">
                                {{ $perusahaan->kota ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                @if($perusahaan->telepon)
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-phone mr-2 text-slate-400"></i>
                                        <span class="text-xs">{{ $perusahaan->telepon }}</span>
                                    </div>
                                @endif
                                @if($perusahaan->email)
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope mr-2 text-slate-400"></i>
                                        <span class="text-xs">{{ Str::limit($perusahaan->email, 20) }}</span>
                                    </div>
                                @endif
                                @if(!$perusahaan->telepon && !$perusahaan->email)
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($perusahaan->status_aktif)
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
                                    <a href="{{ route('perusahaan-penghasil.show', $perusahaan) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors"
                                       title="Lihat Detail ({{ $perusahaan->nama_perusahaan }})"
                                       target="_self">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('perusahaan-penghasil.edit', $perusahaan) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-600 transition-colors"
                                       title="Edit ({{ $perusahaan->nama_perusahaan }})">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('perusahaan-penghasil.destroy', $perusahaan) }}"
                                          method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors"
                                                title="Hapus ({{ $perusahaan->nama_perusahaan }})"
                                                onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus perusahaan {{ $perusahaan->nama_perusahaan }}?')">
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
                                    <i class="fas fa-building text-6xl text-slate-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-slate-900 mb-2">Belum ada data perusahaan penghasil limbah</h3>
                                    <p class="text-slate-500 mb-4">Mulai dengan menambahkan perusahaan penghasil limbah pertama Anda</p>
                                    <a href="{{ route('perusahaan-penghasil.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                                        <i class="fas fa-plus mr-2"></i> Tambah Perusahaan Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($perusahaanPenghasil->hasPages())
        <div class="flex justify-center mt-8">
            <div class="bg-white rounded-xl border border-slate-200 px-6 py-4">
                {{ $perusahaanPenghasil->links() }}
            </div>
        </div>
    @endif
</div>
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
