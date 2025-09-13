@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Detail Log Penyimpanan Limbah</h1>
                <p class="text-slate-600">Informasi lengkap data penyimpanan limbah</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <a href="{{ route('log-penyimpanan.edit', $logPenyimpanan) }}" class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            </div>
        </div>
        <div class="px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h5 class="text-lg font-semibold text-gray-900 mb-4">Informasi Limbah</h5>
                            <div class="space-y-4">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Tanggal Masuk:</span>
                                    <span class="text-gray-900">{{ $logPenyimpanan->tanggal_limbah_masuk }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Jenis Limbah:</span>
                                    <div class="text-right">
                                        <div class="text-gray-900">{{ $logPenyimpanan->jenisLimbah->nama_limbah ?? 'N/A' }}</div>
                                        <small class="text-gray-500">Kode: {{ $logPenyimpanan->kode_limbah }}</small>
                                    </div>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Jumlah Masuk:</span>
                                    <span class="text-gray-900">{{ number_format($logPenyimpanan->jumlah_limbah_masuk, 2) }} Kg</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Maksimal Penyimpanan:</span>
                                    <span class="text-gray-900">
                                        @php
                                            $tanggalMasuk = \Carbon\Carbon::parse($logPenyimpanan->tanggal_limbah_masuk);
                                            $waktuPenyimpanan = $logPenyimpanan->jenisLimbah->waktu_penyimpanan_hari ?? 0;
                                            $maksimalPenyimpanan = $tanggalMasuk->addDays($waktuPenyimpanan);
                                        @endphp
                                        {{ $maksimalPenyimpanan->format('Y-m-d H:i:s') }}
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Status:</span>
                                    <div>
                                        @if($logPenyimpanan->status_log == 'Tersimpan')
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $logPenyimpanan->status_log }}</span>
                                        @elseif($logPenyimpanan->status_log == 'Diangkut')
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">{{ $logPenyimpanan->status_log }}</span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">{{ $logPenyimpanan->status_log }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($logPenyimpanan->status_log == 'Tersimpan')
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Status Kadaluarsa:</span>
                                    <div class="text-right">
                                        @if($logPenyimpanan->expiry_status)
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $logPenyimpanan->getExpiryStatusBadgeClass() }}">
                                                {{ $logPenyimpanan->getExpiryStatusText() }}
                                            </span>
                                            @if($logPenyimpanan->tanggal_kadaluarsa)
                                                <div class="text-sm text-gray-500 mt-1">
                                                    <i class="fas fa-calendar-alt"></i> 
                                                    Tanggal Kadaluarsa: {{ \Carbon\Carbon::parse($logPenyimpanan->tanggal_kadaluarsa)->format('d F Y') }}
                                                </div>
                                                @php
                                                    $daysUntilExpiry = $logPenyimpanan->getDaysUntilExpiry();
                                                @endphp
                                                @if($daysUntilExpiry !== null)
                                                    <div class="text-sm text-gray-500">
                                                        <i class="fas fa-clock"></i> 
                                                        @if($daysUntilExpiry > 0)
                                                            {{ $daysUntilExpiry }} hari lagi
                                                        @elseif($daysUntilExpiry == 0)
                                                            Kadaluarsa hari ini
                                                        @else
                                                            Sudah kadaluarsa {{ abs($daysUntilExpiry) }} hari
                                                        @endif
                                                    </div>
                                                @endif
                                            @endif
                                        @else
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Belum Dihitung</span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h5 class="text-lg font-semibold text-gray-900 mb-4">Informasi Perusahaan/Vendor</h5>
                            <div class="space-y-4">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Perusahaan:</span>
                                    <span class="text-gray-900">{{ $logPenyimpanan->perusahaanPenghasil->nama_perusahaan ?? 'Tidak ada' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Unit Pembangkit:</span>
                                    <span class="text-gray-900">{{ $logPenyimpanan->unitPembangkit->nama_unit ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Alamat Unit:</span>
                                    <span class="text-gray-900">{{ $logPenyimpanan->unitPembangkit->alamat_unit ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Diinput oleh:</span>
                                    <span class="text-gray-900">{{ $logPenyimpanan->penggunaSistem->nama_lengkap ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-700">Waktu Input:</span>
                                    <span class="text-gray-900">{{ $logPenyimpanan->created_at }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h5 class="text-lg font-semibold text-gray-900 mb-4">Detail Sumber Kegiatan Limbah</h5>
                        <div class="bg-gray-50 rounded-lg border border-gray-200">
                            <div class="p-4">
                                <p class="text-gray-900">{{ $logPenyimpanan->detail_sumber_limbah }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($logPenyimpanan->status_log == 'Diangkut')
                    <div class="mt-8">
                        <h5 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pengangkutan</h5>
                        <div class="space-y-4">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">Tanggal Pengangkutan:</span>
                                <span class="text-gray-900">{{ $logPenyimpanan->tanggal_pengangkutan }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">Jumlah Diangkut:</span>
                                <span class="text-gray-900">{{ number_format($logPenyimpanan->jumlah_diangkut, 2) }} Kg</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($logPenyimpanan->jenisLimbah)
                    <div class="mt-8">
                        <h5 class="text-lg font-semibold text-gray-900 mb-4">Informasi Jenis Limbah</h5>
                        <div class="space-y-4">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">Nama Limbah:</span>
                                <span class="text-gray-900">{{ $logPenyimpanan->jenisLimbah->nama_limbah }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">Kemasan:</span>
                                <span class="text-gray-900">{{ $logPenyimpanan->jenisLimbah->kemasan }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">Waktu Penyimpanan:</span>
                                <span class="text-gray-900">{{ $logPenyimpanan->jenisLimbah->waktu_penyimpanan_hari }} hari</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="font-medium text-gray-700">Karakteristik:</span>
                                <span class="text-gray-900">{{ $logPenyimpanan->jenisLimbah->karakteristik->nama_karakteristik ?? 'N/A' }}</span>
                            </div>

                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                        </a>
                        <div class="flex space-x-3">
                            <a href="{{ route('log-penyimpanan.edit', $logPenyimpanan) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <form action="{{ route('log-penyimpanan.destroy', $logPenyimpanan) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log penyimpanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                    <i class="fas fa-trash mr-2"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
        </div>
    </div>
</div>
@endsection