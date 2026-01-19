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
        title="Data Jenis Limbah"
        subtitle="Kelola dan pantau data jenis limbah dengan mudah"
        :create-route="route('jenis-limbah.create')"
        create-button-text="Tambah Jenis Limbah"
        create-button-icon="fas fa-plus-circle" />

    <div class="overflow-hidden rounded-2xl border shadow-sm"
         style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Kode Limbah</th>
                        <th class="min-w-[200px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Nama Limbah</th>
                        <th class="min-w-[150px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Karakteristik</th>
                        <th class="min-w-[150px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Kategori</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Masa Simpan</th>
                        <th class="min-w-[100px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Status</th>
                        <th class="min-w-[150px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Biaya Pengangkutan</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y"
                       style="border-color: var(--border-primary);">
                    @forelse($jenisLimbah as $index => $jenis)
                        <tr class="table-row-hover border-b"
                            style="border-color: var(--border-primary);">
                            <td class="px-4 py-4 text-center text-sm font-medium"
                                style="color: var(--text-secondary);">
                                {{ $jenisLimbah->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                      style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                    {{ $jenis->kode_limbah }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold"
                                     style="color: var(--text-primary);">
                                    {{ $jenis->nama_limbah }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($jenis->karakteristikLimbah)
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                          style="background-color: var(--secondary-bg-light); color: var(--text-secondary);">
                                        {{ $jenis->karakteristikLimbah->nama_karakteristik }}
                                    </span>
                                @else
                                    <span class="text-xs"
                                          style="color: var(--text-tertiary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($jenis->kategoriKegiatanSumber)
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                          style="background-color: var(--accent-bg); color: var(--accent-primary);">
                                        {{ $jenis->kategoriKegiatanSumber->nama_kategori }}
                                    </span>
                                @else
                                    <span class="text-xs"
                                          style="color: var(--text-tertiary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if(!empty($jenis->waktu_penyimpanan_hari))
                                    <span class="text-sm font-medium"
                                          style="color: var(--text-primary);">
                                        {{ $jenis->waktu_penyimpanan_hari }} hari
                                    </span>
                                @elseif(!empty($jenis->batas_penyimpanan_hari))
                                    <span class="text-sm font-medium"
                                          style="color: var(--text-primary);">
                                        {{ $jenis->batas_penyimpanan_hari }} hari
                                    </span>
                                @else
                                    <span class="text-xs"
                                          style="color: var(--text-tertiary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$jenis->status_aktif" />
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
                                <x-action-buttons
                                    :view-route="route('jenis-limbah.show', $jenis)"
                                    :edit-route="route('jenis-limbah.edit', $jenis)"
                                    :delete-route="route('jenis-limbah.destroy', $jenis)"
                                    delete-message="Anda yakin ingin menghapus jenis limbah {{ $jenis->nama_limbah }}?"
                                    :item-title="$jenis->nama_limbah" />
                            </td>
                        </tr>
                    @empty
                        <x-empty-state
                            icon="fas fa-trash-alt"
                            title="Belum ada data jenis limbah"
                            description="Mulai dengan menambahkan jenis limbah pertama Anda"
                            :action-route="route('jenis-limbah.create')"
                            action-text="Tambah Jenis Limbah Pertama"
                            action-icon="fas fa-plus" />
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($jenisLimbah->hasPages())
            <div class="border-t px-6 py-4"
                 style="border-color: var(--border-primary); background-color: var(--card-bg);">
                {{ $jenisLimbah->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
