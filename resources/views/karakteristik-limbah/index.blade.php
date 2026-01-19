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
        title="Data Karakteristik Limbah"
        subtitle="Kelola dan pantau data karakteristik limbah dengan mudah"
        :create-route="route('karakteristik-limbah.create')"
        create-button-text="Tambah Karakteristik"
        create-button-icon="fas fa-plus-circle" />

    <div class="overflow-hidden rounded-2xl border shadow-sm"
         style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[300px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Nama Karakteristik</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Status</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y"
                       style="border-color: var(--border-primary);">
                    @forelse($karakteristikLimbah as $index => $karakteristik)
                        <tr class="table-row-hover border-b"
                            style="border-color: var(--border-primary);">
                            <td class="px-4 py-4 text-center text-sm font-medium"
                                style="color: var(--text-secondary);">
                                {{ $karakteristikLimbah->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full"
                                         style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                        <i class="fas fa-atom"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold"
                                             style="color: var(--text-primary);">
                                            {{ $karakteristik->nama_karakteristik }}
                                        </div>
                                        <div class="mt-1 text-xs"
                                             style="color: var(--text-tertiary);">
                                            ID: {{ $karakteristik->karakteristik_limbah_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$karakteristik->status_aktif" />
                            </td>
                            <td class="px-6 py-4">
                                <x-action-buttons
                                    :view-route="route('karakteristik-limbah.show', $karakteristik)"
                                    :edit-route="route('karakteristik-limbah.edit', $karakteristik)"
                                    :delete-route="route('karakteristik-limbah.destroy', $karakteristik)"
                                    delete-message="Anda yakin ingin menghapus karakteristik limbah {{ $karakteristik->nama_karakteristik }}?"
                                    :item-title="$karakteristik->nama_karakteristik" />
                            </td>
                        </tr>
                    @empty
                        <x-empty-state
                            icon="fas fa-atom"
                            title="Belum ada data karakteristik limbah"
                            description="Mulai dengan menambahkan karakteristik limbah pertama Anda"
                            :action-route="route('karakteristik-limbah.create')"
                            action-text="Tambah Karakteristik Pertama"
                            action-icon="fas fa-plus" />
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($karakteristikLimbah->hasPages())
            <div class="border-t px-6 py-4"
                 style="border-color: var(--border-primary); background-color: var(--card-bg);">
                {{ $karakteristikLimbah->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
