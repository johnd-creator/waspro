@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Detail Jenis Limbah</h1>
                    <p class="text-slate-600">Informasi lengkap tentang jenis limbah</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('jenis-limbah.edit', $jenisLimbah) }}" class="inline-flex items-center px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('jenis-limbah.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-700 w-2/5">Kode Limbah:</span>
                            <div class="w-3/5">
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">{{ $jenisLimbah->kode_limbah }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-700 w-2/5">Nama Limbah:</span>
                            <div class="w-3/5">
                                <span class="text-sm font-semibold text-gray-900">{{ $jenisLimbah->nama_limbah }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-700 w-2/5">Karakteristik:</span>
                            <div class="w-3/5">
                                @if($jenisLimbah->karakteristikLimbah)
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">{{ $jenisLimbah->karakteristikLimbah->nama_karakteristik }}</span>
                                @else
                                    <span class="text-sm text-gray-500">Tidak ada karakteristik</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-700 w-2/5">Batas Penyimpanan:</span>
                            <div class="w-3/5">
                                @if($jenisLimbah->batas_penyimpanan_hari)
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">{{ $jenisLimbah->batas_penyimpanan_hari }} hari</span>
                                @else
                                    <span class="text-sm text-gray-500">Tidak ditentukan</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-700 w-2/5">Status:</span>
                            <div class="w-3/5">
                                @if($jenisLimbah->status_aktif)
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-700 w-2/5">Dibuat:</span>
                            <div class="w-3/5">
                                <span class="text-sm text-gray-500">
                                    {{ $jenisLimbah->created_at ? $jenisLimbah->created_at->format('d/m/Y H:i') : '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-700 w-2/5">Diperbarui:</span>
                            <div class="w-3/5">
                                <span class="text-sm text-gray-500">
                                    {{ $jenisLimbah->updated_at ? $jenisLimbah->updated_at->format('d/m/Y H:i') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($jenisLimbah->deskripsi_limbah)
                <div class="mt-8">
                    <h5 class="text-lg font-semibold text-gray-900 mb-4">Deskripsi Limbah</h5>
                    <div class="bg-gray-50 rounded-lg border border-gray-200">
                        <div class="p-4">
                            <p class="text-sm text-gray-700 mb-0">{{ $jenisLimbah->deskripsi_limbah }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Related Log Penyimpanan -->
            @if($jenisLimbah->logPenyimpananLimbah && $jenisLimbah->logPenyimpananLimbah->count() > 0)
                <div class="mt-8">
                    <h5 class="text-lg font-semibold text-gray-900 mb-4">Log Penyimpanan Terkait</h5>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Masuk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah (Kg)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perusahaan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                             <tbody class="bg-white divide-y divide-gray-200">
                                 @foreach($jenisLimbah->logPenyimpananLimbah->take(5) as $log)
                                     <tr class="hover:bg-gray-50">
                                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $log->tanggal_limbah_masuk ? \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d/m/Y') : '-' }}</td>
                                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                                         <td class="px-6 py-4 whitespace-nowrap">
                                             @if($log->status_log == 'Tersimpan')
                                                 <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ $log->status_log }}</span>
                                             @elseif($log->status_log == 'Diangkut')
                                                 <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">{{ $log->status_log }}</span>
                                             @else
                                                 <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ $log->status_log }}</span>
                                             @endif
                                         </td>
                                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                             @if($log->perusahaanPenghasil)
                                                 {{ $log->perusahaanPenghasil->nama_perusahaan }}
                                             @else
                                                 <span class="text-gray-500">-</span>
                                             @endif
                                         </td>
                                         <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                             <a href="{{ route('log-penyimpanan.show', $log) }}" 
                                                class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors duration-200" title="Lihat Detail">
                                                 <i class="fas fa-eye"></i>
                                             </a>
                                         </td>
                                     </tr>
                                 @endforeach
                             </tbody>
                         </table>
                     </div>
                     @if($jenisLimbah->logPenyimpananLimbah->count() > 5)
                         <p class="text-sm text-gray-500 mt-4">Menampilkan 5 dari {{ $jenisLimbah->logPenyimpananLimbah->count() }} log penyimpanan</p>
                     @endif
                 </div>
             @endif

            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                <div>
                    <form action="{{ route('jenis-limbah.destroy', $jenisLimbah) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis limbah ini? Semua data terkait akan ikut terhapus.')">
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