@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Detail Perusahaan Penghasil Limbah</h1>
                <p class="text-slate-600">Informasi lengkap perusahaan penghasil limbah</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('perusahaan-penghasil.edit', $perusahaanPenghasil) }}" class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('perusahaan-penghasil.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-6 py-6 border-b border-slate-200">
            <h6 class="text-lg font-semibold text-slate-900 flex items-center">
                <i class="fas fa-building mr-2"></i>Informasi Detail
            </h6>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                        <h5 class="text-lg font-semibold text-slate-900 mb-4">Informasi Utama</h5>
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-600">Nama Perusahaan:</span>
                                <span class="text-sm text-slate-900 font-semibold text-right">{{ $perusahaanPenghasil->nama_perusahaan }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-600">Jenis Perusahaan:</span>
                                <span class="text-sm text-slate-900 text-right">
                                    @if($perusahaanPenghasil->jenis_perusahaan)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $perusahaanPenghasil->jenis_perusahaan }}</span>
                                    @else
                                        <span class="text-slate-500">Tidak ditentukan</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-600">Telepon:</span>
                                <span class="text-sm text-slate-900 text-right">
                                    @if($perusahaanPenghasil->telepon)
                                        <i class="fas fa-phone mr-1 text-slate-400"></i>{{ $perusahaanPenghasil->telepon }}
                                    @else
                                        <span class="text-slate-500">Tidak ada</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-600">Email:</span>
                                <span class="text-sm text-slate-900 text-right">
                                    @if($perusahaanPenghasil->email)
                                        <i class="fas fa-envelope mr-1 text-slate-400"></i>
                                        <a href="mailto:{{ $perusahaanPenghasil->email }}" class="text-blue-600 hover:text-blue-800">{{ $perusahaanPenghasil->email }}</a>
                                    @else
                                        <span class="text-slate-500">Tidak ada</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                        <h5 class="text-lg font-semibold text-slate-900 mb-4">Informasi Tambahan</h5>
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-600">Kota:</span>
                                <span class="text-sm text-slate-900 text-right">
                                    @if($perusahaanPenghasil->kota)
                                        <i class="fas fa-map-marker-alt mr-1 text-slate-400"></i>{{ $perusahaanPenghasil->kota }}
                                    @else
                                        <span class="text-slate-500">Tidak ditentukan</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-600">Penanggung Jawab:</span>
                                <span class="text-sm text-slate-900 text-right">
                                    @if($perusahaanPenghasil->person_in_charge)
                                        <i class="fas fa-user mr-1 text-slate-400"></i>{{ $perusahaanPenghasil->person_in_charge }}
                                    @else
                                        <span class="text-slate-500">Tidak ditentukan</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-600">Status:</span>
                                <span class="text-sm text-slate-900 text-right">
                                    @if($perusahaanPenghasil->status_aktif)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-600">Dibuat:</span>
                                <span class="text-sm text-slate-500 text-right">
                                    {{ $perusahaanPenghasil->created_at ? $perusahaanPenghasil->created_at->format('d/m/Y H:i') : '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-600">Diperbarui:</span>
                                <span class="text-sm text-slate-500 text-right">
                                    {{ $perusahaanPenghasil->updated_at ? $perusahaanPenghasil->updated_at->format('d/m/Y H:i') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                        <h5 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-slate-600"></i>Alamat Perusahaan
                        </h5>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $perusahaanPenghasil->alamat_perusahaan }}</p>
                    </div>

                    @if($perusahaanPenghasil->keterangan)
                        <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                            <h5 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-slate-600"></i>Keterangan
                            </h5>
                            <p class="text-sm text-slate-700 leading-relaxed">{{ $perusahaanPenghasil->keterangan }}</p>
                        </div>
                    @endif

                    <!-- Unit Pembangkit -->
                    @if($perusahaanPenghasil->unitPembangkit && $perusahaanPenghasil->unitPembangkit->count() > 0)
                        <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="text-lg font-semibold text-slate-900 flex items-center">
                                    <i class="fas fa-industry mr-2 text-slate-600"></i>Unit Pembangkit ({{ $perusahaanPenghasil->unitPembangkit->count() }})
                                </h5>
                                <a href="{{ route('unit-pembangkit.create', ['perusahaan_id' => $perusahaanPenghasil->perusahaan_id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-1"></i> Tambah Unit
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[600px]">
                                    <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white rounded-t-2xl">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Nama Unit</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Alamat</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Telepon</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach($perusahaanPenghasil->unitPembangkit as $unit)
                                            <tr class="hover:bg-slate-50/50 transition-all duration-200">
                                                <td class="px-4 py-3">
                                                    <div class="font-semibold text-slate-900 text-sm">{{ $unit->nama_unit }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-slate-600">
                                                    <div class="max-w-xs">{{ Str::limit($unit->alamat_unit, 50) }}</div>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-slate-600">
                                                    @if($unit->telepon_unit)
                                                        {{ $unit->telepon_unit }}
                                                    @else
                                                        <span class="text-slate-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if($unit->status_aktif)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    <a href="{{ route('unit-pembangkit.show', $unit) }}"
                                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors"
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
                        <div class="bg-slate-50 rounded-lg border border-slate-200 p-6 text-center">
                            <i class="fas fa-industry text-4xl text-slate-300 mb-3"></i>
                            <h6 class="text-lg font-medium text-slate-900 mb-2">Belum ada unit pembangkit</h6>
                            <p class="text-slate-500 mb-4">Tambahkan unit pembangkit pertama untuk perusahaan ini</p>
                            <a href="{{ route('unit-pembangkit.create', ['perusahaan_id' => $perusahaanPenghasil->perusahaan_id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus mr-1"></i> Tambah Unit Pembangkit Pertama
                            </a>
                        </div>
                    @endif

                    <!-- Log Penyimpanan Terkait -->
                    @if($perusahaanPenghasil->logPenyimpananLimbah && $perusahaanPenghasil->logPenyimpananLimbah->count() > 0)
                        <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                            <h5 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
                                <i class="fas fa-clipboard-list mr-2 text-slate-600"></i>Log Penyimpanan Limbah Terkait
                            </h5>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[700px]">
                                    <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white rounded-t-2xl">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Jenis Limbah</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Jumlah (Kg)</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                                            <th class="px-4 py-3 text-left text-sm font-semibold">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @foreach($perusahaanPenghasil->logPenyimpananLimbah->take(5) as $log)
                                            <tr class="hover:bg-slate-50/50 transition-all duration-200">
                                                <td class="px-4 py-3 text-sm text-slate-700">
                                                    {{ $log->tanggal_limbah_masuk ? \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if($log->jenisLimbah)
                                                        <div class="flex flex-col">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-1">{{ $log->jenisLimbah->kode_limbah }}</span>
                                                            <span class="text-xs text-slate-600">{{ $log->jenisLimbah->nama_limbah }}</span>
                                                        </div>
                                                    @else
                                                        <span class="text-slate-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                                    {{ number_format($log->jumlah_limbah_masuk, 2) }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if($log->status_log == 'Tersimpan')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $log->status_log }}</span>
                                                    @elseif($log->status_log == 'Diangkut')
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $log->status_log }}</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $log->status_log }}</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">
                                                    <a href="{{ route('log-penyimpanan.show', $log) }}"
                                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors"
                                                       title="Lihat Detail">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($perusahaanPenghasil->logPenyimpananLimbah->count() > 5)
                                <p class="text-slate-500 text-sm mt-3">Menampilkan 5 dari {{ $perusahaanPenghasil->logPenyimpananLimbah->count() }} log penyimpanan</p>
                            @endif
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center mt-6 pt-6 border-t border-slate-200">
                        <div>
                            <form action="{{ route('perusahaan-penghasil.destroy', $perusahaanPenghasil) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors"
                                         onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus perusahaan ini? Semua data terkait akan ikut terhapus.')">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('perusahaan-penghasil.edit', $perusahaanPenghasil) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <a href="{{ route('perusahaan-penghasil.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-list mr-1"></i> Daftar Perusahaan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
