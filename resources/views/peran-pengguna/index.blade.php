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
        title="Peran Pengguna"
        subtitle="Kelola dan pantau peran pengguna sistem"
        :create-route="route('peran-pengguna.create')"
        create-button-text="Tambah Peran"
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
                            style="color: var(--text-secondary);">Nama Peran</th>
                        <th class="min-w-[300px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Deskripsi</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Status</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y"
                       style="border-color: var(--border-primary);">
                    @forelse($peranPengguna as $index => $peran)
                        <tr class="table-row-hover border-b"
                            style="border-color: var(--border-primary);">
                            <td class="px-4 py-4 text-center text-sm font-medium"
                                style="color: var(--text-secondary);">
                                {{ $peranPengguna->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full"
                                         style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold"
                                             style="color: var(--text-primary);">
                                            {{ $peran->nama_peran }}
                                        </div>
                                        <div class="mt-1 text-xs"
                                             style="color: var(--text-tertiary);">
                                            ID: {{ $peran->peran_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm"
                                style="color: var(--text-secondary);">
                                <div class="max-w-md">
                                    {{ $peran->deskripsi ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$peran->is_active" />
                            </td>
                            <td class="px-6 py-4">
                                <x-action-buttons
                                    :view-route="route('peran-pengguna.show', $peran->peran_id)"
                                    :edit-route="route('peran-pengguna.edit', $peran->peran_id)"
                                    :delete-route="route('peran-pengguna.destroy', $peran->peran_id)"
                                    delete-message="Apakah Anda yakin ingin menghapus peran {{ $peran->nama_peran }}?"
                                    :item-title="$peran->nama_peran" />
                            </td>
                        </tr>
                    @empty
                        <x-empty-state
                            icon="fas fa-folder-open"
                            title="Belum ada data peran pengguna"
                            description="Silakan tambahkan peran pengguna baru"
                            :action-route="route('peran-pengguna.create')"
                            action-text="Tambah Peran"
                            action-icon="fas fa-plus" />
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($peranPengguna->hasPages())
            <div class="border-t px-6 py-4"
                 style="border-color: var(--border-primary); background-color: var(--card-bg);">
                {{ $peranPengguna->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
