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
        title="Unit Pembangkit"
        subtitle="Kelola dan pantau data unit pembangkit listrik"
        :create-route="route('unit-pembangkit.create')"
        create-button-text="Tambah Unit Pembangkit"
        create-button-icon="fas fa-plus-circle" />

    <div class="overflow-hidden rounded-2xl border shadow-sm"
         style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[220px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Nama Unit</th>
                        <th class="min-w-[320px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Alamat</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Kota</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Kode Pos</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y"
                       style="border-color: var(--border-primary);">
                    @forelse($unitPembangkit as $index => $unit)
                        <tr class="table-row-hover border-b"
                            style="border-color: var(--border-primary);">
                            <td class="px-4 py-4 text-center text-sm font-medium"
                                style="color: var(--text-secondary);">
                                {{ $unitPembangkit->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold"
                                     style="color: var(--text-primary);">
                                    {{ $unit->nama_unit }}
                                </div>
                                @if($unit->kota)
                                    <div class="mt-1 flex items-center text-xs"
                                         style="color: var(--text-tertiary);">
                                        <i class="fas fa-city mr-1"></i>
                                        {{ $unit->kota }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm leading-relaxed"
                                style="color: var(--text-secondary);">
                                <div class="max-w-xl truncate"
                                     title="{{ $unit->alamat_unit ?? '-' }}">
                                    {{ $unit->alamat_unit ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium"
                                style="color: var(--text-primary);">
                                {{ $unit->kota ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium"
                                style="color: var(--text-primary);">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                      style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                    {{ $unit->kode_pos ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <x-action-buttons
                                    :view-route="route('unit-pembangkit.show', $unit)"
                                    :edit-route="route('unit-pembangkit.edit', $unit)"
                                    :delete-route="route('unit-pembangkit.destroy', $unit)"
                                    delete-message="Apakah Anda yakin ingin menghapus unit pembangkit ini?"
                                    :item-title="$unit->nama_unit" />
                            </td>
                        </tr>
                    @empty
                        <x-empty-state
                            icon="fas fa-folder-open"
                            title="Belum ada data unit pembangkit"
                            description="Silakan tambahkan unit pembangkit baru"
                            :action-route="route('unit-pembangkit.create')"
                            action-text="Tambah Unit Pembangkit"
                            action-icon="fas fa-plus" />
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($unitPembangkit->hasPages())
            <div class="border-t px-6 py-4"
                 style="border-color: var(--border-primary); background-color: var(--card-bg);">
                {{ $unitPembangkit->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
