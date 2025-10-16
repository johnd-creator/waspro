@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    @if(session('success'))
        <div class="mb-6 flex items-center rounded-xl border p-4" style="background-color: var(--accent-bg-secondary); border-color: var(--border-secondary); color: var(--accent-secondary);" role="alert" data-auto-dismiss="2500">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto transition-opacity hover:opacity-75" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-6 rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="flex items-center justify-between border-b px-6 py-6" style="border-color: var(--border-primary);">
            <div>
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Perusahaan Penghasil Limbah</h1>
                <p style="color: var(--text-secondary);">Kelola dan pantau data perusahaan penghasil limbah</p>
            </div>
            <div>
                <a href="{{ route('perusahaan-penghasil.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span>Tambah Perusahaan</span>
                </a>
            </div>
        </div>
        <div class="px-6 py-6">
            <p style="color: var(--text-secondary);">Daftar perusahaan penghasil limbah yang terdaftar dalam sistem</p>
        </div>
    </div>

    <!-- Tabel Perusahaan Penghasil -->
    <div class="overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[220px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Nama Perusahaan</th>
                        <th class="min-w-[320px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Alamat</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Kota</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Kontak</th>
                        <th class="min-w-[100px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Status</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-primary);">
                    @forelse($perusahaanPenghasil as $index => $perusahaan)
                        <tr class="transition-colors duration-200 border-b" style="border-color: var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-4 text-center text-sm font-medium" style="color: var(--text-secondary);">
                                {{ $perusahaanPenghasil->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold" style="color: var(--text-primary);">{{ $perusahaan->nama_perusahaan }}</div>
                                @if($perusahaan->jenis_perusahaan)
                                    <div class="mt-1 flex items-center text-xs" style="color: var(--text-tertiary);">
                                        <i class="fas fa-industry mr-1"></i> {{ $perusahaan->jenis_perusahaan }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm leading-relaxed" style="color: var(--text-secondary);">
                                <div class="max-w-xl truncate" title="{{ $perusahaan->alamat_perusahaan ?? '-' }}">
                                    {{ $perusahaan->alamat_perusahaan ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium" style="color: var(--text-primary);">
                                {{ $perusahaan->kota ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm" style="color: var(--text-secondary);">
                                @if($perusahaan->telepon)
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-phone mr-2" style="color: var(--text-tertiary);"></i>
                                        <span class="text-xs">{{ $perusahaan->telepon }}</span>
                                    </div>
                                @endif
                                @if($perusahaan->email)
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope mr-2" style="color: var(--text-tertiary);"></i>
                                        <span class="text-xs">{{ Str::limit($perusahaan->email, 20) }}</span>
                                    </div>
                                @endif
                                @if(!$perusahaan->telepon && !$perusahaan->email)
                                    <span style="color: var(--text-tertiary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($perusahaan->status_aktif)
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--accent-bg-secondary); color: var(--accent-secondary);">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--danger-bg); color: var(--danger-primary);">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('perusahaan-penghasil.show', $perusahaan) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--accent-primary); background-color: var(--accent-bg);" onmouseover="this.style.backgroundColor='var(--accent-primary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--accent-bg)'; this.style.color='var(--accent-primary)';" title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('perusahaan-penghasil.edit', $perusahaan) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--accent-secondary); background-color: var(--accent-bg-secondary);" onmouseover="this.style.backgroundColor='var(--accent-secondary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--accent-bg-secondary)'; this.style.color='var(--accent-secondary)';" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('perusahaan-penghasil.destroy', $perusahaan) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--danger-primary); background-color: var(--danger-bg);" onmouseover="this.style.backgroundColor='var(--danger-primary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--danger-bg)'; this.style.color='var(--danger-primary)';" title="Hapus" onclick="return confirm('Anda yakin ingin menghapus perusahaan {{ $perusahaan->nama_perusahaan }}?')">
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
                                    <i class="fas fa-building mb-4 text-6xl" style="color: var(--text-tertiary);"></i>
                                    <h3 class="mb-2 text-lg font-medium" style="color: var(--text-primary);">Belum ada data perusahaan penghasil limbah</h3>
                                    <p class="mb-4" style="color: var(--text-secondary);">Mulai dengan menambahkan perusahaan penghasil limbah pertama Anda</p>
                                    <a href="{{ route('perusahaan-penghasil.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-700">
                                        <i class="fas fa-plus mr-2"></i> Tambah Perusahaan Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($perusahaanPenghasil->hasPages())
            <div class="border-t p-4" style="border-color: var(--border-primary); background-color: var(--card-bg);">
                {{ $perusahaanPenghasil->links() }}
            </div>
        @endif
    </div>
</div>

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
@endsection
