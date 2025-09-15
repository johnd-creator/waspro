@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-600"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Detail Unit Pembangkit</h1>
                <p class="text-slate-600">Informasi lengkap unit pembangkit listrik</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('unit-pembangkit.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <a href="{{ route('unit-pembangkit.edit', $unitPembangkit) }}" class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            </div>
        </div>

        <!-- Content Section -->
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left: Informasi Unit -->
                <div>
                    <h5 class="text-lg font-semibold text-slate-900 mb-4">Informasi Unit</h5>
                    <div class="space-y-4">
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="font-medium text-slate-700">Nama Unit:</span>
                            <span class="text-slate-900">{{ $unitPembangkit->nama_unit }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="font-medium text-slate-700">Alamat:</span>
                            <span class="text-slate-900 text-right">{{ $unitPembangkit->alamat_unit ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="font-medium text-slate-700">Kota:</span>
                            <span class="text-slate-900">{{ $unitPembangkit->kota ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-slate-100">
                            <span class="font-medium text-slate-700">Kode Pos:</span>
                            <span class="text-slate-900">{{ $unitPembangkit->kode_pos ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Card Visual -->
                <div>
                    <div class="bg-slate-50 rounded-xl border border-slate-200 p-6 text-center h-full flex flex-col items-center justify-center">
                        <i class="fas fa-building text-5xl text-blue-500 mb-4"></i>
                        <h5 class="text-lg font-semibold text-slate-900">Unit Pembangkit</h5>
                        <p class="text-slate-500">{{ $unitPembangkit->nama_unit }}</p>
                    </div>
                </div>
            </div>

            @if(!empty($unitPembangkit->alamat_unit))
                <div class="mt-8">
                    <h5 class="text-lg font-semibold text-slate-900 mb-3">Alamat Unit Pembangkit</h5>
                    <div class="bg-slate-50 rounded-lg border border-slate-200">
                        <div class="p-4">
                            <p class="text-slate-900">{{ $unitPembangkit->alamat_unit }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(!empty($unitPembangkit->keterangan))
                <div class="mt-8">
                    <h5 class="text-lg font-semibold text-slate-900 mb-3">Keterangan</h5>
                    <div class="bg-slate-50 rounded-lg border border-slate-200">
                        <div class="p-4">
                            <p class="text-slate-900">{{ $unitPembangkit->keterangan }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(method_exists($unitPembangkit, 'logPenyimpananLimbah') && $unitPembangkit->logPenyimpananLimbah && $unitPembangkit->logPenyimpananLimbah->count() > 0)
                <div class="mt-8">
                    <h5 class="text-lg font-semibold text-slate-900 mb-4">Log Penyimpanan Limbah dari Unit Ini</h5>
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[700px]">
                                <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Tanggal Masuk</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Jenis Limbah</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Jumlah (Kg)</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach($unitPembangkit->logPenyimpananLimbah->take(5) as $log)
                                        <tr class="hover:bg-slate-50/50 transition-all">
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ $log->tanggal_limbah_masuk ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-700">{{ $log->jenisLimbah->nama_limbah ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-slate-900">
                                                <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-md">
                                                    {{ isset($log->jumlah_limbah_masuk) ? number_format($log->jumlah_limbah_masuk, 2) : '-' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php $status = $log->status_log ?? null; @endphp
                                                @if($status === 'Tersimpan')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $status }}</span>
                                                @elseif($status === 'Diangkut')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">{{ $status }}</span>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $status ?? '-' }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="{{ route('log-penyimpanan.show', $log) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors" title="Lihat Log">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($unitPembangkit->logPenyimpananLimbah->count() > 5)
                            <div class="text-center px-4 py-3 border-t border-slate-200 text-slate-500 text-sm">
                                Menampilkan 5 dari {{ $unitPembangkit->logPenyimpananLimbah->count() }} log penyimpanan
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Action Footer -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-200">
                <a href="{{ route('unit-pembangkit.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
                <div class="flex space-x-3">
                    <a href="{{ route('unit-pembangkit.edit', $unitPembangkit) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <form action="{{ route('unit-pembangkit.destroy', $unitPembangkit) }}" method="POST" class="inline" onsubmit="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus unit pembangkit ini? Tindakan ini tidak dapat dibatalkan!')">
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
