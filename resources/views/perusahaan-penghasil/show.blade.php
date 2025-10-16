@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div style="background-color: var(--card-bg); border: 1px solid var(--border-primary);" class="rounded-2xl shadow-sm mb-6">
        <div style="border-bottom: 1px var(--border-primary);" class="px-6 py-6 flex justify-between items-center">
            <div>
                <h1 style="color: var(--text-primary);" class="text-2xl font-bold mb-2">Detail Perusahaan Penghasil Limbah</h1>
                <p style="color: var(--text-secondary);">Informasi lengkap perusahaan penghasil limbah</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('perusahaan-penghasil.edit', $perusahaanPenghasil) }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--accent-primary); color: white;"
                   onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                   onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('perusahaan-penghasil.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);"
                   onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';"
                   onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6 border-b" style="border-color: var(--border-primary);">
            <h6 class="text-lg font-semibold flex items-center" style="color: var(--text-primary);">
                <i class="fas fa-building mr-2"></i>Informasi Detail
            </h6>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div style="background-color: var(--secondary-bg-light); border: 1px solid var(--border-secondary);" class="rounded-lg p-4">
                        <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-4">Informasi Utama</h5>
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <span style="color: var(--text-secondary);" class="text-sm font-medium">Nama Perusahaan:</span>
                                <span style="color: var(--text-primary);" class="text-sm font-semibold text-right">{{ $perusahaanPenghasil->nama_perusahaan }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--text-secondary);" class="text-sm font-medium">Jenis Perusahaan:</span>
                                <span style="color: var(--text-primary);" class="text-sm text-right">
                                    @if($perusahaanPenghasil->jenis_perusahaan)
                                        <span style="background-color: var(--accent-bg); color: var(--accent-primary);" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">{{ $perusahaanPenghasil->jenis_perusahaan }}</span>
                                    @else
                                        <span style="color: var(--text-tertiary);">Tidak ditentukan</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--text-secondary);" class="text-sm font-medium">Telepon:</span>
                                <span style="color: var(--text-primary);" class="text-sm text-right">
                                    @if($perusahaanPenghasil->telepon)
                                        <i style="color: var(--text-tertiary);" class="fas fa-phone mr-1"></i>{{ $perusahaanPenghasil->telepon }}
                                    @else
                                        <span style="color: var(--text-tertiary);">Tidak ada</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--text-secondary);" class="text-sm font-medium">Email:</span>
                                <span style="color: var(--text-primary);" class="text-sm text-right">
                                    @if($perusahaanPenghasil->email)
                                        <i style="color: var(--text-tertiary);" class="fas fa-envelope mr-1"></i>
                                        <a href="mailto:{{ $perusahaanPenghasil->email }}" style="color: var(--accent-primary); transition: all 0.2s;" class="hover:opacity-80">{{ $perusahaanPenghasil->email }}</a>
                                    @else
                                        <span style="color: var(--text-tertiary);">Tidak ada</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div style="background-color: var(--secondary-bg-light); border: 1px solid var(--border-secondary);" class="rounded-lg p-4">
                        <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-4">Informasi Tambahan</h5>
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <span style="color: var(--text-secondary);" class="text-sm font-medium">Kota:</span>
                                <span style="color: var(--text-primary);" class="text-sm text-right">
                                    @if($perusahaanPenghasil->kota)
                                        <i style="color: var(--text-tertiary);" class="fas fa-map-marker-alt mr-1"></i>{{ $perusahaanPenghasil->kota }}
                                    @else
                                        <span style="color: var(--text-tertiary);">Tidak ditentukan</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--text-secondary);" class="text-sm font-medium">Penanggung Jawab:</span>
                                <span style="color: var(--text-primary);" class="text-sm text-right">
                                    @if($perusahaanPenghasil->person_in_charge)
                                        <i style="color: var(--text-tertiary);" class="fas fa-user mr-1"></i>{{ $perusahaanPenghasil->person_in_charge }}
                                    @else
                                        <span style="color: var(--text-tertiary);">Tidak ditentukan</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--text-secondary);" class="text-sm font-medium">Status:</span>
                                <span style="color: var(--text-primary);" class="text-sm text-right">
                                    @if($perusahaanPenghasil->status_aktif)
                                        <span style="background-color: var(--success-bg); color: var(--success-text);" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">Aktif</span>
                                    @else
                                        <span style="background-color: var(--danger-bg); color: var(--danger-text);" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">Tidak Aktif</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--text-secondary);" class="text-sm font-medium">Dibuat:</span>
                                <span style="color: var(--text-tertiary);" class="text-sm text-right">
                                    {{ $perusahaanPenghasil->created_at ? $perusahaanPenghasil->created_at->format('d/m/Y H:i') : '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--text-secondary);" class="text-sm font-medium">Diperbarui:</span>
                                <span style="color: var(--text-tertiary);" class="text-sm text-right">
                                    {{ $perusahaanPenghasil->updated_at ? $perusahaanPenghasil->updated_at->format('d/m/Y H:i') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div style="background-color: var(--secondary-bg-light); border: 1px solid var(--border-secondary);" class="rounded-lg p-4">
                        <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-4 flex items-center">
                            <i style="color: var(--text-secondary);" class="fas fa-map-marker-alt mr-2"></i>Alamat Perusahaan
                        </h5>
                        <p style="color: var(--text-primary);" class="text-sm leading-relaxed">{{ $perusahaanPenghasil->alamat_perusahaan }}</p>
                    </div>

                    @if($perusahaanPenghasil->keterangan)
                        <div style="background-color: var(--secondary-bg-light); border: 1px solid var(--border-secondary);" class="rounded-lg p-4">
                            <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-4 flex items-center">
                                <i style="color: var(--text-secondary);" class="fas fa-info-circle mr-2"></i>Keterangan
                            </h5>
                            <p style="color: var(--text-primary);" class="text-sm leading-relaxed">{{ $perusahaanPenghasil->keterangan }}</p>
                        </div>
                    @endif

                    <!-- Unit Pembangkit -->
                    @if($perusahaanPenghasil->unitPembangkit && $perusahaanPenghasil->unitPembangkit->count() > 0)
                        <div style="background-color: var(--secondary-bg-light); border: 1px solid var(--border-secondary);" class="rounded-lg p-4">
                            <div class="flex justify-between items-center mb-4">
                                <h5 style="color: var(--text-primary);" class="text-lg font-semibold flex items-center">
                                    <i style="color: var(--text-secondary);" class="fas fa-industry mr-2"></i>Unit Pembangkit ({{ $perusahaanPenghasil->unitPembangkit->count() }})
                                </h5>
                                <a href="{{ route('unit-pembangkit.create', ['perusahaan_id' => $perusahaanPenghasil->perusahaan_id]) }}" style="background-color: var(--accent-primary); color: white; transition: all 0.2s;" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:opacity-90">
                                    <i class="fas fa-plus mr-1"></i> Tambah Unit
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[600px]">
                                    <thead style="background: linear-gradient(to right, var(--table-header-start), var(--table-header-end)); color: white;" class="rounded-t-2xl">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Nama Unit</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Alamat</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Telepon</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody style="border-color: var(--border-secondary);" class="divide-y">
                                        @foreach($perusahaanPenghasil->unitPembangkit as $unit)
                                            <tr style="transition: all 0.2s;" class="hover:opacity-80">
                                                <td class="px-4 py-3">
                                                    <div style="color: var(--text-primary);" class="font-semibold text-sm">{{ $unit->nama_unit }}</div>
                                                </td>
                                                <td style="color: var(--text-secondary);" class="px-4 py-3 text-sm">
                                                    <div class="max-w-xs">{{ Str::limit($unit->alamat_unit, 50) }}</div>
                                                </td>
                                                <td style="color: var(--text-secondary);" class="px-4 py-3 text-sm">
                                                    @if($unit->telepon_unit)
                                                        {{ $unit->telepon_unit }}
                                                    @else
                                                        <span style="color: var(--text-tertiary);">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if($unit->status_aktif)
                                                        <span style="background-color: var(--success-bg); color: var(--success-text);" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">Aktif</span>
                                                    @else
                                                        <span style="background-color: var(--danger-bg); color: var(--danger-text);" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    <a href="{{ route('unit-pembangkit.show', $unit) }}"
                                                       style="background-color: var(--accent-bg); color: var(--accent-primary); transition: all 0.2s;"
                                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg hover:opacity-80"
                                                       title="Lihat Detail ({{ $unit->nama_unit }})">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div style="background-color: var(--secondary-bg-light); border: 1px solid var(--border-secondary);" class="rounded-lg p-6 text-center">
                            <i style="color: var(--text-tertiary);" class="fas fa-industry text-4xl mb-3"></i>
                            <h6 style="color: var(--text-primary);" class="text-lg font-medium mb-2">Belum ada unit pembangkit</h6>
                            <p style="color: var(--text-tertiary);" class="mb-4">Tambahkan unit pembangkit pertama untuk perusahaan ini</p>
                            <a href="{{ route('unit-pembangkit.create', ['perusahaan_id' => $perusahaanPenghasil->perusahaan_id]) }}" style="background-color: var(--accent-primary); color: white; transition: all 0.2s;" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:opacity-90">
                                <i class="fas fa-plus mr-1"></i> Tambah Unit Pembangkit Pertama
                            </a>
                        </div>
                    @endif



                    <!-- Action Buttons -->
                    <div style="border-top: 1px solid var(--border-secondary);" class="flex justify-between items-center mt-6 pt-6">
                        <div>
                            <form action="{{ route('perusahaan-penghasil.destroy', $perusahaanPenghasil) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background-color: var(--danger-primary); color: white; transition: all 0.2s;" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:opacity-90"
                                         onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus perusahaan ini? Semua data terkait akan ikut terhapus.')">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('perusahaan-penghasil.edit', $perusahaanPenghasil) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 shadow-lg"
                               style="background-color: var(--accent-primary); color: white;"
                               onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                               onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <a href="{{ route('perusahaan-penghasil.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 shadow-lg"
                               style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);"
                               onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';"
                               onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-list mr-1"></i> Daftar Perusahaan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
