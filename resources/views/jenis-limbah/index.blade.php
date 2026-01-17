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
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Data Jenis Limbah</h1>
                <p style="color: var(--text-secondary);">Kelola dan pantau data jenis limbah dengan mudah</p>
            </div>
            <div>
                <a href="{{ route('jenis-limbah.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span>Tambah Jenis Limbah</span>
                </a>
            </div>
        </div>
        <div class="px-6 py-6">
            <p style="color: var(--text-secondary);">Daftar jenis limbah yang terdaftar dalam sistem</p>
        </div>
    </div>

    <!-- Tabel Jenis Limbah -->
    <div class="overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Kode Limbah</th>
                         <th class="min-w-[200px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Nama Limbah</th>
                        <th class="min-w-[150px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Karakteristik</th>
                        <th class="min-w-[150px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Kategori</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Masa Simpan</th>
                        <th class="min-w-[100px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Status</th>
                        <th class="min-w-[150px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Biaya Pengangkutan</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-primary);">
                    @forelse($jenisLimbah as $index => $jenis)
                        <tr class="transition-colors duration-200 border-b" style="border-color: var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-4 text-center text-sm font-medium" style="color: var(--text-secondary);">
                                {{ $jenisLimbah->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                    {{ $jenis->kode_limbah }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold" style="color: var(--text-primary);">{{ $jenis->nama_limbah }}</div>
                            </td>
                             <td class="px-6 py-4">
                                 @if($jenis->karakteristikLimbah)
                                     <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--secondary-bg-light); color: var(--text-secondary);">
                                         {{ $jenis->karakteristikLimbah->nama_karakteristik }}
                                     </span>
                                 @else
                                     <span class="text-xs" style="color: var(--text-tertiary);">-</span>
                                 @endif
                             </td>
                             
                             <td class="px-6 py-4">
                                 @if($jenis->kategoriKegiatanSumber)
                                     <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                         {{ $jenis->kategoriKegiatanSumber->nama_kategori }}
                                     </span>
                                 @else
                                     <span class="text-xs" style="color: var(--text-tertiary);">-</span>
                                 @endif
                             </td>
                            
                            <td class="px-6 py-4">
                                @if(!empty($jenis->waktu_penyimpanan_hari))
                                    <span class="text-sm font-medium" style="color: var(--text-primary);">{{ $jenis->waktu_penyimpanan_hari }} hari</span>
                                @elseif(!empty($jenis->batas_penyimpanan_hari))
                                    <span class="text-sm font-medium" style="color: var(--text-primary);">{{ $jenis->batas_penyimpanan_hari }} hari</span>
                                @else
                                    <span class="text-xs" style="color: var(--text-tertiary);">-</span>
                                @endif
                            </td>
                             <td class="px-6 py-4">
                                 @if($jenis->status_aktif)
                                     <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--success-bg); color: var(--success-primary);">
                                         Aktif
                                     </span>
                                 @else
                                     <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--danger-bg); color: var(--danger-primary);">
                                         Tidak Aktif
                                     </span>
                                 @endif
                             </td>

                             <td class="px-6 py-4">
                                 @if($jenis->biaya_pengangkutan_per_kg)
                                     <span style="font-size: 0.875rem; color: var(--text-primary);">
                                         Rp {{ number_format($jenis->biaya_pengangkutan_per_kg, 0, ',', '.') }}/kg
                                     </span>
                                 @else
                                     <span style="font-size: 0.875rem; color: var(--text-tertiary);">-</span>
                                 @endif
                             </td>

                             <td class="px-6 py-4">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('jenis-limbah.show', $jenis) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--accent-primary); background-color: var(--accent-bg);" onmouseover="this.style.backgroundColor='var(--accent-primary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--accent-bg)'; this.style.color='var(--accent-primary)';" title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('jenis-limbah.edit', $jenis) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--accent-secondary); background-color: var(--accent-bg-secondary);" onmouseover="this.style.backgroundColor='var(--accent-secondary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--accent-bg-secondary)'; this.style.color='var(--accent-secondary)';" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('jenis-limbah.destroy', $jenis) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--danger-primary); background-color: var(--danger-bg);" onmouseover="this.style.backgroundColor='var(--danger-primary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--danger-bg)'; this.style.color='var(--danger-primary)';" title="Hapus" onclick="return confirm('Anda yakin ingin menghapus jenis limbah {{ $jenis->nama_limbah }}?')">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-trash-alt mb-4 text-6xl" style="color: var(--text-tertiary);"></i>
                                    <h3 class="mb-2 text-lg font-medium" style="color: var(--text-primary);">Belum ada data jenis limbah</h3>
                                    <p class="mb-4" style="color: var(--text-secondary);">Mulai dengan menambahkan jenis limbah pertama Anda</p>
                                    <a href="{{ route('jenis-limbah.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-700">
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
            <div class="border-t p-4" style="border-color: var(--border-primary); background-color: var(--card-bg);">
                {{ $jenisLimbah->links() }}
            </div>
        @endif
    </div>
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
