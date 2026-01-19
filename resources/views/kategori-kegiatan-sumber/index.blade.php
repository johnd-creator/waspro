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
        title="Data Kategori Kegiatan Sumber"
        subtitle="Kelola dan pantau data kategori kegiatan sumber dengan mudah"
        :create-route="route('kategori-kegiatan-sumber.create')"
        create-button-text="Tambah Kategori"
        create-button-icon="fas fa-plus-circle" />

    <div class="overflow-hidden rounded-2xl border shadow-sm"
         style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[400px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Nama Kategori</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y"
                       style="border-color: var(--border-primary);">
                    @forelse($kategoriKegiatanSumber as $index => $kategori)
                        <tr class="table-row-hover border-b"
                            style="border-color: var(--border-primary);">
                            <td class="px-4 py-4 text-center text-sm font-medium"
                                style="color: var(--text-secondary);">
                                {{ $kategoriKegiatanSumber->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-full"
                                         style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                        <i class="fas fa-list-alt"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold"
                                             style="color: var(--text-primary);">
                                            {{ $kategori->nama_kategori }}
                                        </div>
                                        <div class="mt-1 text-xs"
                                             style="color: var(--text-tertiary);">
                                            ID: {{ $kategori->kategori_kegiatan_sumber_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <x-action-buttons
                                    :view-route="route('kategori-kegiatan-sumber.show', $kategori)"
                                    :edit-route="route('kategori-kegiatan-sumber.edit', $kategori)"
                                    :delete-route="route('kategori-kegiatan-sumber.destroy', $kategori)"
                                    delete-message="Anda yakin ingin menghapus kategori kegiatan sumber {{ $kategori->nama_kategori }}?"
                                    :item-title="$kategori->nama_kategori" />
                            </td>
                        </tr>
                    @empty
                        <x-empty-state
                            icon="fas fa-list-alt"
                            title="Belum ada data kategori kegiatan sumber"
                            description="Mulai dengan menambahkan kategori kegiatan sumber pertama Anda"
                            :action-route="route('kategori-kegiatan-sumber.create')"
                            action-text="Tambah Kategori Pertama"
                            action-icon="fas fa-plus" />
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kategoriKegiatanSumber->hasPages())
            <div class="border-t px-6 py-4"
                 style="border-color: var(--border-primary); background-color: var(--card-bg);">
                {{ $kategoriKegiatanSumber->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
