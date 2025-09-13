@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 px-2">
    <!-- Header Section -->
    <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-xl shadow-lg shadow-blue-500/10 p-4 mb-4">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                    <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-red-600 via-orange-600 to-yellow-600 bg-clip-text text-transparent">
                        Limbah Akan Kadaluarsa
                    </h1>
                </div>
                <p class="text-slate-600 text-sm font-medium">
                    Daftar limbah yang akan kadaluarsa dalam 30 hari ke depan
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="flex items-center gap-2 bg-gradient-to-r from-red-500 to-orange-600 text-white px-4 py-2 rounded-lg shadow-md text-sm">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span class="font-medium">{{ $nearExpiryWaste->total() }} Limbah</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sorting Controls -->
    <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-xl shadow-lg shadow-blue-500/10 p-4 mb-4">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h3 class="text-lg font-bold text-slate-800">Filter & Sorting</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dashboard.near-expiry', ['sort' => 'days_remaining', 'order' => 'asc']) }}" 
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $sortBy === 'days_remaining' && $sortOrder === 'asc' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="fas fa-sort-numeric-up mr-1"></i>Hari Tersisa (Terkecil)
                </a>
                <a href="{{ route('dashboard.near-expiry', ['sort' => 'days_remaining', 'order' => 'desc']) }}" 
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $sortBy === 'days_remaining' && $sortOrder === 'desc' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="fas fa-sort-numeric-down mr-1"></i>Hari Tersisa (Terbesar)
                </a>
                <a href="{{ route('dashboard.near-expiry', ['sort' => 'jumlah_limbah_masuk', 'order' => 'desc']) }}" 
                   class="px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $sortBy === 'jumlah_limbah_masuk' && $sortOrder === 'desc' ? 'bg-red-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <i class="fas fa-weight-hanging mr-1"></i>Jumlah Terbanyak
                </a>
            </div>
        </div>
    </div>

    <!-- Waste Table -->
    <div class="bg-white/80 backdrop-blur-xl border border-white/20 rounded-xl shadow-lg shadow-blue-500/10 overflow-hidden">
        @if($nearExpiryWaste->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-red-500 to-orange-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Jenis Limbah</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Perusahaan</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Unit Pembangkit</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Jumlah (Kg)</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Tanggal Kadaluarsa</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Hari Tersisa</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($nearExpiryWaste as $waste)
                        @php
                            // Use the calculated days_remaining from controller query
                            $daysRemaining = $waste->days_remaining;
                            $isExpired = $daysRemaining < 0;
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="font-semibold text-slate-800">{{ $waste->jenisLimbah->nama_limbah }}</div>
                                <div class="text-sm text-slate-600">{{ $waste->jenisLimbah->kode_limbah }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-slate-800">{{ $waste->perusahaanPenghasil->nama_perusahaan ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-slate-800">{{ $waste->unitPembangkit->nama_unit }}</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="font-semibold text-slate-800">{{ number_format($waste->jumlah_limbah_masuk, 2) }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="text-slate-800 font-medium">{{ \Carbon\Carbon::parse($waste->maksimal_penyimpanan_tanggal)->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($waste->maksimal_penyimpanan_tanggal)->format('H:i') }}</div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($isExpired)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Kadaluarsa
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                        {{ $daysRemaining <= 3 ? 'bg-red-100 text-red-800' : ($daysRemaining <= 7 ? 'bg-yellow-100 text-yellow-800' : 'bg-orange-100 text-orange-800') }}">
                                        <i class="fas fa-clock mr-1"></i>{{ $daysRemaining }} hari
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($isExpired)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Kritis
                                    </span>
                                @elseif($daysRemaining <= 3)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-500 text-white">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Kritis
                                    </span>
                                @elseif($daysRemaining <= 7)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500 text-white">
                                        <i class="fas fa-exclamation mr-1"></i>Peringatan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-500 text-white">
                                        <i class="fas fa-info-circle mr-1"></i>Perhatian
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-slate-200">
                {{ $nearExpiryWaste->appends(request()->query())->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-6xl text-emerald-500"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Tidak Ada Limbah Akan Kadaluarsa</h3>
                <p class="text-slate-600">Semua limbah tersimpan masih dalam batas waktu penyimpanan yang aman.</p>
            </div>
        @endif
    </div>
</div>
@endsection