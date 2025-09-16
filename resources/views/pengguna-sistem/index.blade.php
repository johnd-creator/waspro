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
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Pengguna Sistem</h1>
                <p style="color: var(--text-secondary);">Kelola dan pantau data pengguna sistem</p>
            </div>
            <div>
                <a href="{{ route('pengguna-sistem.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span>Tambah Pengguna</span>
                </a>
            </div>
        </div>
        <div class="px-6 py-6">
            <p style="color: var(--text-secondary);">Daftar pengguna sistem yang terdaftar dalam aplikasi</p>
        </div>
    </div>

    <!-- Tabel Pengguna Sistem -->
    <div class="overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[200px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Nama Lengkap</th>
                        <th class="min-w-[200px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Email</th>
                        <th class="min-w-[180px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Unit Pembangkit</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Peran</th>
                        <th class="min-w-[100px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Status</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-primary);">
                    @forelse($users as $index => $user)
                        <tr class="transition-colors duration-200 border-b" style="border-color: var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-4 text-center text-sm font-medium" style="color: var(--text-secondary);">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background-color: var(--accent-bg);">
                                        <span class="font-semibold text-sm" style="color: var(--accent-primary);">
                                            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold" style="color: var(--text-primary);">{{ $user->nama_lengkap }}</div>
                                        @if($user->username)
                                            <div class="mt-1 text-xs" style="color: var(--text-tertiary);">
                                                <i class="fas fa-user mr-1"></i> {{ $user->username }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm" style="color: var(--text-secondary);">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope mr-2" style="color: var(--text-tertiary);"></i>
                                    {{ $user->email }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium" style="color: var(--text-primary);">
                                @if($user->unitPembangkit)
                                    <div class="flex items-center">
                                        <i class="fas fa-building mr-2" style="color: var(--text-tertiary);"></i>
                                        {{ $user->unitPembangkit->nama_unit }}
                                    </div>
                                @else
                                    <span style="color: var(--text-tertiary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->peranPengguna && $user->peranPengguna->count() > 0)
                                    @foreach($user->peranPengguna as $peran)
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium mb-1 mr-1" style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                            {{ $peran->nama_peran }}
                                        </span>
                                    @endforeach
                                @else
                                    <span style="color: var(--text-tertiary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->status_aktif)
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
                                    <a href="{{ route('pengguna-sistem.show', $user) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors"
                                       style="color: var(--accent-primary); background-color: var(--accent-bg);"
                                       onmouseover="this.style.backgroundColor='var(--accent-primary)'; this.style.color='white';"
                                       onmouseout="this.style.backgroundColor='var(--accent-bg)'; this.style.color='var(--accent-primary)';"
                                       title="Lihat Detail ({{ $user->nama_lengkap }})"
                                       target="_self">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('pengguna-sistem.edit', $user) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors"
                                       style="color: var(--accent-secondary); background-color: var(--accent-bg-secondary);"
                                       onmouseover="this.style.backgroundColor='var(--accent-secondary)'; this.style.color='white';"
                                       onmouseout="this.style.backgroundColor='var(--accent-bg-secondary)'; this.style.color='var(--accent-secondary)';"
                                       title="Edit ({{ $user->nama_lengkap }})">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('pengguna-sistem.destroy', $user) }}"
                                          method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors"
                                                style="color: var(--danger-primary); background-color: var(--danger-bg);"
                                                onmouseover="this.style.backgroundColor='var(--danger-primary)'; this.style.color='white';"
                                                onmouseout="this.style.backgroundColor='var(--danger-bg)'; this.style.color='var(--danger-primary)';"
                                                title="Hapus ({{ $user->nama_lengkap }})"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center" style="color: var(--text-tertiary);">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-folder-open text-4xl mb-3 opacity-50"></i>
                                    <p class="text-lg font-medium mb-1">Belum ada data pengguna</p>
                                    <p class="text-sm">Silakan tambahkan pengguna baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="border-t px-6 py-4" style="border-color: var(--border-primary);">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection