@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    @if(session('success'))
        <div class="mb-6 flex items-center rounded-xl border p-4" style="background-color: var(--accent-bg-secondary); border-color: var(--border-secondary); color: var(--accent-secondary);">
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
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Unit Pembangkit</h1>
                <p style="color: var(--text-secondary);">Kelola dan pantau data unit pembangkit listrik</p>
            </div>
            <div>
                <a href="{{ route('unit-pembangkit.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span>Tambah Unit Pembangkit</span>
                </a>
            </div>
        </div>
        <div class="px-6 py-6">
            <p style="color: var(--text-secondary);">Daftar unit pembangkit yang terdaftar dalam sistem</p>
        </div>
    </div>

    <!-- Tabel Unit Pembangkit -->
    <div class="overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[220px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Nama Unit</th>
                        <th class="min-w-[320px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Alamat</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Kota</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Kode Pos</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-primary);">
                    @forelse($unitPembangkit as $index => $unit)
                        <tr class="transition-colors duration-200 border-b" style="border-color: var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-4 text-center text-sm font-medium" style="color: var(--text-secondary);">
                                {{ $unitPembangkit->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold" style="color: var(--text-primary);">{{ $unit->nama_unit }}</div>
                                @if($unit->kota)
                                    <div class="mt-1 flex items-center text-xs" style="color: var(--text-tertiary);">
                                        <i class="fas fa-city mr-1"></i> {{ $unit->kota }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm leading-relaxed" style="color: var(--text-secondary);">
                                <div class="max-w-xl truncate" title="{{ $unit->alamat_unit ?? '-' }}">
                                    {{ $unit->alamat_unit ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium" style="color: var(--text-primary);">
                                {{ $unit->kota ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium" style="color: var(--text-primary);">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                    {{ $unit->kode_pos ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('unit-pembangkit.show', $unit) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors"
                                       style="color: var(--accent-primary); background-color: var(--accent-bg);"
                                       onmouseover="this.style.backgroundColor='var(--accent-primary)'; this.style.color='white';"
                                       onmouseout="this.style.backgroundColor='var(--accent-bg)'; this.style.color='var(--accent-primary)';"
                                       title="Lihat Detail ({{ $unit->nama_unit }})"
                                       target="_self">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('unit-pembangkit.edit', $unit) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors"
                                       style="color: var(--accent-secondary); background-color: var(--accent-bg-secondary);"
                                       onmouseover="this.style.backgroundColor='var(--accent-secondary)'; this.style.color='white';"
                                       onmouseout="this.style.backgroundColor='var(--accent-bg-secondary)'; this.style.color='var(--accent-secondary)';"
                                       title="Edit ({{ $unit->nama_unit }})">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('unit-pembangkit.destroy', $unit) }}"
                                          method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors"
                                                style="color: var(--danger-primary); background-color: var(--danger-bg);"
                                                onmouseover="this.style.backgroundColor='var(--danger-primary)'; this.style.color='white';"
                                                onmouseout="this.style.backgroundColor='var(--danger-bg)'; this.style.color='var(--danger-primary)';"
                                                title="Hapus ({{ $unit->nama_unit }})"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus unit pembangkit ini?')">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center" style="color: var(--text-tertiary);">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-folder-open text-4xl mb-3 opacity-50"></i>
                                    <p class="text-lg font-medium mb-1">Belum ada data unit pembangkit</p>
                                    <p class="text-sm">Silakan tambahkan unit pembangkit baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($unitPembangkit->hasPages())
            <div class="border-t px-6 py-4" style="border-color: var(--border-primary);">
                {{ $unitPembangkit->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
