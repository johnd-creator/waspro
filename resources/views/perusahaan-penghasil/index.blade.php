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
        title="Perusahaan Penghasil Limbah"
        subtitle="Kelola dan pantau data perusahaan penghasil limbah"
        :create-route="route('perusahaan-penghasil.create')"
        create-button-text="Tambah Perusahaan"
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
                             style="color: var(--text-secondary);">Nama Perusahaan</th>
<!--                         <th class="min-w-[320px] px-6 py-4 text-left text-sm font-semibold"
                             style="color: var(--text-secondary);">Alamat</th> -->
                         <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold"
                             style="color: var(--text-secondary);">Kota</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Kontak</th>
                        <th class="min-w-[100px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Status</th>
                        <th class="min-w-[160px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y"
                       style="border-color: var(--border-primary);">
                    @forelse($perusahaanPenghasil as $index => $perusahaan)
                        <tr class="table-row-hover border-b"
                            style="border-color: var(--border-primary);">
                            <td class="px-4 py-4 text-center text-sm font-medium"
                                style="color: var(--text-secondary);">
                                {{ $perusahaanPenghasil->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold"
                                     style="color: var(--text-primary);">
                                    {{ $perusahaan->nama_perusahaan }}
                                </div>
                                @if($perusahaan->jenis_perusahaan)
                                    <div class="mt-1 flex items-center text-xs"
                                         style="color: var(--text-tertiary);">
                                        <i class="fas fa-industry mr-1"></i>
                                        {{ $perusahaan->jenis_perusahaan }}
                                    </div>
                                @endif
                             </td>
<!-- 
                             <td class="px-6 py-4 text-sm leading-relaxed"
                                 style="color: var(--text-secondary);">
                                 <div class="max-w-xl truncate"
                                      title="{{ $perusahaan->alamat_perusahaan ?? '-' }}">
                                     {{ $perusahaan->alamat_perusahaan ?? '-' }}
                                 </div>
                             </td>
-->
                             <td class="px-6 py-4 text-sm font-medium"
                                 style="color: var(--text-primary);">
                                 {{ $perusahaan->kota ?? '-' }}
                             </td>
                            <td class="px-6 py-4 text-sm"
                                style="color: var(--text-secondary);">
                                @if($perusahaan->telepon)
                                    <div class="flex items-center mb-1">
                                        <i class="fas fa-phone mr-2"
                                           style="color: var(--text-tertiary);"></i>
                                        <span class="text-xs">{{ $perusahaan->telepon }}</span>
                                    </div>
                                @endif
                                @if($perusahaan->email)
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope mr-2"
                                           style="color: var(--text-tertiary);"></i>
                                        <span class="text-xs">{{ Str::limit($perusahaan->email, 20) }}</span>
                                    </div>
                                @endif
                                @if(!$perusahaan->telepon && !$perusahaan->email)
                                    <span style="color: var(--text-tertiary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-status-badge :status="$perusahaan->status_aktif" />
                            </td>
                            <td class="px-6 py-4">
                                <x-action-buttons
                                    :view-route="route('perusahaan-penghasil.show', $perusahaan)"
                                    :edit-route="route('perusahaan-penghasil.edit', $perusahaan)"
                                    :delete-route="route('perusahaan-penghasil.destroy', $perusahaan)"
                                    delete-message="Anda yakin ingin menghapus perusahaan {{ $perusahaan->nama_perusahaan }}?"
                                    :item-title="$perusahaan->nama_perusahaan" />
                            </td>
                        </tr>
                    @empty
                        <x-empty-state
                            icon="fas fa-building"
                            title="Belum ada data perusahaan penghasil limbah"
                            description="Mulai dengan menambahkan perusahaan penghasil limbah pertama Anda"
                            :action-route="route('perusahaan-penghasil.create')"
                            action-text="Tambah Perusahaan Pertama"
                            action-icon="fas fa-plus" />
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($perusahaanPenghasil->hasPages())
            <div class="border-t px-6 py-4"
                 style="border-color: var(--border-primary); background-color: var(--card-bg);">
                {{ $perusahaanPenghasil->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
