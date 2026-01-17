@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div style="background-color: var(--card-bg); border: 1px solid var(--border-primary);" class="rounded-2xl shadow-sm mb-6">
        <div style="border-bottom: 1px solid var(--border-primary);" class="px-8 py-6 flex justify-between items-center">
            <div>
                <h1 style="color: var(--text-primary);" class="text-2xl font-bold mb-2">Detail Log Penyimpanan Limbah</h1>
                <p style="color: var(--text-secondary);">Informasi lengkap data penyimpanan limbah</p>
            </div>
            <div class="flex gap-3">
                 <a href="{{ route('log-penyimpanan.edit', $logPenyimpanan) }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--accent-primary); color: white;"
                   onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                   onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                    <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);"
                   onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';"
                   onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
            </div>
        </div>
        <div class="px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-4">Informasi Limbah</h5>
                            <div class="space-y-4">
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Tanggal Masuk:</span>
                                    <span style="color: var(--text-primary);">{{ $logPenyimpanan->tanggal_limbah_masuk }}</span>
                                </div>
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Jenis Limbah:</span>
                                    <div class="text-right">
                                        <div style="color: var(--text-primary);">{{ $logPenyimpanan->jenisLimbah->nama_limbah ?? 'N/A' }}</div>
                                        <small style="color: var(--text-tertiary);">Kode: {{ $logPenyimpanan->kode_limbah }}</small>
                                    </div>
                                </div>
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Jumlah Masuk:</span>
                                    <span style="color: var(--text-primary);">{{ number_format($logPenyimpanan->jumlah_limbah_masuk, 2) }} Kg</span>
                                </div>
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Uraian Pekerjaan:</span>
                                    <div style="width: 60%; text-align: right;">
                                        @if($logPenyimpanan->uraian_pekerjaan)
                                            <div class="rounded-lg p-3 text-sm" style="background-color: var(--secondary-bg-light);">{{ $logPenyimpanan->uraian_pekerjaan }}</div>
                                        @else
                                            <span style="color: var(--text-tertiary);">-</span>
                                        @endif
                                    </div>
                                </div>
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Maksimal Penyimpanan:</span>
                                    <span style="color: var(--text-primary);">
                                        @php
                                            $tanggalMasuk = \Carbon\Carbon::parse($logPenyimpanan->tanggal_limbah_masuk);
                                            $waktuPenyimpanan = $logPenyimpanan->jenisLimbah->waktu_penyimpanan_hari ?? 0;
                                            $maksimalPenyimpanan = $tanggalMasuk->addDays($waktuPenyimpanan);
                                        @endphp
                                        {{ $maksimalPenyimpanan->format('Y-m-d H:i:s') }}
                                    </span>
                                </div>
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Status:</span>
                                    <div>
                                        @if($logPenyimpanan->status_log == 'Tersimpan')
                                            <span style="background-color: var(--warning-bg); color: var(--warning-text);" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">{{ $logPenyimpanan->status_log }}</span>
                                        @elseif($logPenyimpanan->status_log == 'Diangkut')
                                            <span style="background-color: var(--success-bg); color: var(--success-text);" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">{{ $logPenyimpanan->status_log }}</span>
                                        @else
                                            <span style="background-color: var(--danger-bg); color: var(--danger-text);" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">{{ $logPenyimpanan->status_log }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($logPenyimpanan->status_log == 'Tersimpan')
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Status Kadaluarsa:</span>
                                    <div class="text-right">
                                        @if($logPenyimpanan->expiry_status)
                                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $logPenyimpanan->getExpiryStatusBadgeClass() }}">
                                                {{ $logPenyimpanan->getExpiryStatusText() }}
                                            </span>
                                            @if($logPenyimpanan->tanggal_kadaluarsa)
                                                <div style="color: var(--text-tertiary);" class="text-sm mt-1">
                                                    <i class="fas fa-calendar-alt"></i> 
                                                    Tanggal Kadaluarsa: {{ \Carbon\Carbon::parse($logPenyimpanan->tanggal_kadaluarsa)->format('d F Y') }}
                                                </div>
                                                @php
                                                    $daysUntilExpiry = $logPenyimpanan->getDaysUntilExpiry();
                                                @endphp
                                                @if($daysUntilExpiry !== null)
                                                    <div style="color: var(--text-tertiary);" class="text-sm">
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
                                            <span style="background-color: var(--secondary-bg); color: var(--text-primary);" class="inline-flex px-3 py-1 text-sm font-semibold rounded-full">Belum Dihitung</span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if($logPenyimpanan->dokumen_path)
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium block">Dokumen Pendukung:</span>
                                    <div class="mt-2 rounded-lg border px-4 py-3 text-sm" style="background-color: var(--input-bg); border-color: var(--border-primary);">
                                        <div class="flex items-center justify-between gap-4">
                                            <div>
                                                <p class="font-medium" style="color: var(--text-primary);">{{ $logPenyimpanan->dokumen_original_name ?? basename($logPenyimpanan->dokumen_path) }}</p>
                                                <p class="text-xs" style="color: var(--text-secondary);">Ukuran: {{ number_format(($logPenyimpanan->dokumen_size ?? 0) / 1024, 2) }} KB · Diunggah {{ optional($logPenyimpanan->dokumen_uploaded_at)->diffForHumans() }}</p>
                                            </div>
                                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($logPenyimpanan->dokumen_path) }}" target="_blank" class="inline-flex items-center rounded-lg border px-3 py-1 text-xs font-medium transition-all duration-200" style="border-color: var(--border-primary); color: var(--text-primary);">
                                                <i class="fas fa-download mr-1"></i> Unduh
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-4">Informasi Perusahaan/Vendor</h5>
                            <div class="space-y-4">
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Perusahaan:</span>
                                    <span style="color: var(--text-primary);">{{ $logPenyimpanan->perusahaanPenghasil->nama_perusahaan ?? 'Tidak ada' }}</span>
                                </div>
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Unit Pembangkit:</span>
                                    <span style="color: var(--text-primary);">{{ $logPenyimpanan->unitPembangkit->nama_unit ?? 'N/A' }}</span>
                                </div>
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Alamat Unit:</span>
                                    <span style="color: var(--text-primary);">{{ $logPenyimpanan->unitPembangkit->alamat_unit ?? 'N/A' }}</span>
                                </div>
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Diinput oleh:</span>
                                    <span style="color: var(--text-primary);">{{ $logPenyimpanan->penggunaSistem->nama_lengkap ?? 'N/A' }}</span>
                                </div>
                                <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                    <span style="color: var(--text-secondary);" class="font-medium">Waktu Input:</span>
                                    <span style="color: var(--text-primary);">{{ $logPenyimpanan->created_at }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-4">Detail Sumber Kegiatan Limbah</h5>
                        <div style="background-color: var(--secondary-bg-light); border: 1px solid var(--border-secondary);" class="rounded-lg">
                            <div class="p-4">
                                <p style="color: var(--text-primary);">{{ $logPenyimpanan->detail_sumber_limbah }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($logPenyimpanan->status_log == 'Diangkut')
                    <div class="mt-8">
                        <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-4">Informasi Pengangkutan</h5>
                        <div class="space-y-4">
                            <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                <span style="color: var(--text-secondary);" class="font-medium">Tanggal Pengangkutan:</span>
                                <span style="color: var(--text-primary);">{{ $logPenyimpanan->tanggal_pengangkutan }}</span>
                            </div>
                            <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                <span style="color: var(--text-secondary);" class="font-medium">Jumlah Diangkut:</span>
                                <span style="color: var(--text-primary);">{{ number_format($logPenyimpanan->jumlah_diangkut, 2) }} Kg</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if($logPenyimpanan->jenisLimbah)
                    <div class="mt-8">
                        <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-4">Informasi Jenis Limbah</h5>
                        <div class="space-y-4">
                            <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                <span style="color: var(--text-secondary);" class="font-medium">Nama Limbah:</span>
                                <span style="color: var(--text-primary);">{{ $logPenyimpanan->jenisLimbah->nama_limbah }}</span>
                            </div>
                            <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                <span style="color: var(--text-secondary);" class="font-medium">Kemasan:</span>
                                <span style="color: var(--text-primary);">{{ $logPenyimpanan->jenisLimbah->kemasan }}</span>
                            </div>
                            <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                <span style="color: var(--text-secondary);" class="font-medium">Waktu Penyimpanan:</span>
                                <span style="color: var(--text-primary);">{{ $logPenyimpanan->jenisLimbah->waktu_penyimpanan_hari }} hari</span>
                            </div>
                            <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                                <span style="color: var(--text-secondary);" class="font-medium">Karakteristik:</span>
                                <span style="color: var(--text-primary);">{{ $logPenyimpanan->jenisLimbah->karakteristik->nama_karakteristik ?? 'N/A' }}</span>
                            </div>

                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div style="border-top: 1px solid var(--border-secondary);" class="flex justify-between items-center mt-8 pt-6">
                        <a href="{{ route('log-penyimpanan.index') }}" style="background-color: var(--secondary-bg); color: white; transition: all 0.2s;" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md hover:opacity-90">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                        </a>
                        <div class="flex space-x-3">
                            <a href="{{ route('log-penyimpanan.edit', $logPenyimpanan) }}" style="background-color: var(--warning-primary); color: white; transition: all 0.2s;" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md hover:opacity-90">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <form action="{{ route('log-penyimpanan.destroy', $logPenyimpanan) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log penyimpanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background-color: var(--danger-primary); color: white; transition: all 0.2s;" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md hover:opacity-90">
                                    <i class="fas fa-trash mr-2"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
        </div>
    </div>
</div>
@endsection
