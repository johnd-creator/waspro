@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    @if(session('success'))
        <div class="mb-6 flex items-center rounded-xl border p-4"
             style="background-color: var(--accent-bg-secondary); border-color: var(--border-secondary); color: var(--accent-secondary);"
             role="alert"
             data-auto-dismiss="2500">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
            <button type="button"
                    class="ml-auto transition-opacity hover:opacity-75"
                    onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <x-page-header-simple
        title="Pengguna Sistem"
        subtitle="Kelola dan pantau data pengguna sistem"
        :create-route="route('pengguna-sistem.create')"
        create-button-text="Tambah Pengguna"
        create-button-icon="fas fa-plus-circle" />

    <div class="overflow-hidden rounded-2xl border shadow-sm"
         style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[200px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Nama Lengkap</th>
                        <th class="min-w-[180px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Unit Pembangkit</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Peran</th>
                        <th class="min-w-[100px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Status</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y"
                       style="border-color: var(--border-primary);">
                    @forelse($users as $index => $user)
                        <tr class="table-row-hover border-b"
                            style="border-color: var(--border-primary);">
                            <td class="px-4 py-4 text-center text-sm font-medium"
                                style="color: var(--text-secondary);">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full"
                                         style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                        <span class="font-semibold text-sm">
                                            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold"
                                             style="color: var(--text-primary);">
                                            {{ $user->nama_lengkap }}
                                        </div>
                                        @if($user->username)
                                            <div class="mt-1 text-xs"
                                                 style="color: var(--text-tertiary);">
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $user->username }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium"
                                style="color: var(--text-primary);">
                                @if($user->unit_id === null && $user->peranPengguna()->where('nama_peran', 'Super Admin')->exists())
                                    <div class="flex items-center">
                                        <i class="fas fa-globe mr-2"
                                           style="color: var(--accent-primary);"></i>
                                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold"
                                              style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                            Global (Super Admin)
                                        </span>
                                    </div>
                                @elseif($user->unitPembangkit)
                                    <div class="flex items-center">
                                        <i class="fas fa-building mr-2"
                                           style="color: var(--text-tertiary);"></i>
                                        {{ $user->unitPembangkit->nama_unit }}
                                    </div>
                                @else
                                    <span style="color: var(--text-tertiary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->peranPengguna && $user->peranPengguna->count() > 0)
                                    @foreach($user->peranPengguna as $peran)
                                        <span class="mb-1 mr-1 inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                              style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                            {{ $peran->nama_peran }}
                                        </span>
                                    @endforeach
                                @else
                                    <span style="color: var(--text-tertiary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$user->aktif" />
                            </td>
                            <td class="px-6 py-4">
                                <x-action-buttons
                                    :view-route="route('pengguna-sistem.show', $user)"
                                    :edit-route="route('pengguna-sistem.edit', $user)"
                                    :delete-route="route('pengguna-sistem.destroy', $user)"
                                    delete-message="Apakah Anda yakin ingin menghapus pengguna ini?"
                                    :item-title="$user->nama_lengkap" />
                            </td>
                        </tr>
                    @empty
                        <x-empty-state
                            icon="fas fa-folder-open"
                            title="Belum ada data pengguna"
                            description="Silakan tambahkan pengguna baru"
                            :action-route="route('pengguna-sistem.create')"
                            action-text="Tambah Pengguna"
                            action-icon="fas fa-plus" />
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="border-t px-6 py-4"
                 style="border-color: var(--border-primary); background-color: var(--card-bg);">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
