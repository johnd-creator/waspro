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
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Data Kategori Kegiatan Sumber</h1>
                <p style="color: var(--text-secondary);">Kelola dan pantau data kategori kegiatan sumber dengan mudah</p>
            </div>
            <div>
                <a href="{{ route('kategori-kegiatan-sumber.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span>Tambah Kategori</span>
                </a>
            </div>
        </div>
        <div class="px-6 py-6">
            <p style="color: var(--text-secondary);">Daftar kategori kegiatan sumber yang terdaftar dalam sistem</p>
        </div>
    </div>

    <!-- Tabel Kategori Kegiatan Sumber -->
    <div class="overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[400px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Nama Kategori</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-primary);">
                    @forelse($kategoriKegiatanSumber as $index => $kategori)
                        <tr class="transition-colors duration-200 border-b" style="border-color: var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-4 text-center text-sm font-medium" style="color: var(--text-secondary);">
                                {{ $kategoriKegiatanSumber->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                        <i class="fas fa-list-alt"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold" style="color: var(--text-primary);">{{ $kategori->nama_kategori }}</div>
                                        <div class="mt-1 text-xs" style="color: var(--text-tertiary);">
                                            ID: {{ $kategori->kategori_kegiatan_sumber_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('kategori-kegiatan-sumber.show', $kategori) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--accent-primary); background-color: var(--accent-bg);" onmouseover="this.style.backgroundColor='var(--accent-primary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--accent-bg)'; this.style.color='var(--accent-primary)';" title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('kategori-kegiatan-sumber.edit', $kategori) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--accent-secondary); background-color: var(--accent-bg-secondary);" onmouseover="this.style.backgroundColor='var(--accent-secondary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--accent-bg-secondary)'; this.style.color='var(--accent-secondary)';" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('kategori-kegiatan-sumber.destroy', $kategori) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--danger-primary); background-color: var(--danger-bg);" onmouseover="this.style.backgroundColor='var(--danger-primary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--danger-bg)'; this.style.color='var(--danger-primary)';" title="Hapus" onclick="return confirm('Anda yakin ingin menghapus kategori kegiatan sumber {{ $kategori->nama_kategori }}?')">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-list-alt mb-4 text-6xl" style="color: var(--text-tertiary);"></i>
                                    <h3 class="mb-2 text-lg font-medium" style="color: var(--text-primary);">Belum ada data kategori kegiatan sumber</h3>
                                    <p class="mb-4" style="color: var(--text-secondary);">Mulai dengan menambahkan kategori kegiatan sumber pertama Anda</p>
                                    <a href="{{ route('kategori-kegiatan-sumber.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-700">
                                        <i class="fas fa-plus mr-2"></i> Tambah Kategori Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kategoriKegiatanSumber->hasPages())
            <div class="border-t p-4" style="border-color: var(--border-primary); background-color: var(--card-bg);">
                {{ $kategoriKegiatanSumber->links() }}
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