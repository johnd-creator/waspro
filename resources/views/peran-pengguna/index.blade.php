@extends('layouts.app')

@section('content')
<style>
    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .action-button.primary {
        color: var(--accent-primary);
        background-color: var(--accent-bg);
    }
    .action-button.primary:hover {
        background-color: var(--accent-primary);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(var(--shadow-rgb), 0.1), 0 2px 4px -1px rgba(var(--shadow-rgb), 0.06);
    }
    .action-button.secondary {
        color: var(--accent-secondary);
        background-color: var(--accent-bg-secondary);
    }
    .action-button.secondary:hover {
        background-color: var(--accent-secondary);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(var(--shadow-rgb), 0.1), 0 2px 4px -1px rgba(var(--shadow-rgb), 0.06);
    }
    .action-button.danger {
        color: var(--danger-primary);
        background-color: var(--danger-bg);
    }
    .action-button.danger:hover {
        background-color: var(--danger-primary);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(var(--shadow-rgb), 0.1), 0 2px 4px -1px rgba(var(--shadow-rgb), 0.06);
    }
</style>
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
        <div class="px-6 py-6 flex justify-between items-center">
            <div>
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Peran Pengguna</h1>
                <p style="color: var(--text-secondary);">Kelola dan pantau peran pengguna sistem</p>
            </div>
            <div>
                <a href="{{ route('peran-pengguna.create') }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--accent-primary);" onmouseover="this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span>Tambah Peran</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Tabel Peran Pengguna -->
    <div class="overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[200px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Nama Peran</th>
                        <th class="min-w-[300px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Deskripsi</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Status</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-primary);">
                    @forelse($peranPengguna as $index => $peran)
                        <tr class="transition-colors duration-200 border-b" style="border-color: var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-4 text-center text-sm font-medium" style="color: var(--text-secondary);">
                                {{ $peranPengguna->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background-color: var(--accent-bg);">
                                        <i class="fas fa-user-tag" style="color: var(--accent-primary);"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold" style="color: var(--text-primary);">{{ $peran->nama_peran }}</div>
                                        <div class="mt-1 text-xs" style="color: var(--text-tertiary);">
                                            ID: {{ $peran->peran_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm" style="color: var(--text-secondary);">
                                <div class="max-w-md">
                                    {{ $peran->deskripsi ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($peran->is_active)
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
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('peran-pengguna.show', $peran->peran_id) }}"
                                       class="action-button primary"
                                       title="Lihat Detail ({{ $peran->nama_peran }})"
                                       target="_self">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('peran-pengguna.edit', $peran->peran_id) }}"
                                       class="action-button secondary"
                                       title="Edit ({{ $peran->nama_peran }})">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('peran-pengguna.destroy', $peran->peran_id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus peran {{ $peran->nama_peran }}?');"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="action-button danger"
                                                title="Hapus ({{ $peran->nama_peran }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center" style="color: var(--text-tertiary);">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-folder-open text-4xl mb-3 opacity-50"></i>
                                    <p class="text-lg font-medium mb-1">Belum ada data peran pengguna</p>
                                    <p class="text-sm">Silakan tambahkan peran pengguna baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($peranPengguna->hasPages())
            <div class="border-t px-6 py-4" style="border-color: var(--border-primary);">
                {{ $peranPengguna->links() }}
            </div>
        @endif
    </div>
</div>
@endsection