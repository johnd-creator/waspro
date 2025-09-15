@extends('layouts.app')

@section('title', 'Limbah Diangkut')

@section('content')
<div class="px-2 py-4">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-600"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Header & Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Limbah Diangkut</h1>
                <p class="text-slate-600">Daftar limbah yang sudah diangkut dari penyimpanan</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('pengangkutan-limbah.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-all duration-200 shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
        <!-- Filter -->
        <div class="px-6 py-6">
            <form method="GET" action="{{ route('pengangkutan-limbah.diangkut') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                        <input type="text" name="kode_identitas" value="{{ request('kode_identitas') }}" placeholder="Cari Kode Identitas..." class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                    </div>
                </div>
                <div>
                    <select name="jenis_limbah" class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Jenis Limbah</option>
                        @foreach($jenisLimbah as $jenis)
                            <option value="{{ $jenis->kode_limbah }}" {{ request('jenis_limbah') == $jenis->kode_limbah ? 'selected' : '' }}>{{ $jenis->nama_limbah }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="perusahaan" class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Perusahaan</option>
                        @foreach($perusahaan as $p)
                            <option value="{{ $p->perusahaan_id }}" {{ request('perusahaan') == $p->perusahaan_id ? 'selected' : '' }}>{{ $p->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>
                <div class="md:col-span-2 lg:col-span-4 flex items-stretch gap-3">
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    @if(request()->hasAny(['kode_identitas','jenis_limbah','perusahaan','tanggal_mulai','tanggal_akhir']))
                        <a href="{{ route('pengangkutan-limbah.diangkut') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px]">
                <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                    <tr>
                        <th class="px-4 py-4 text-left text-sm font-semibold w-16">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Kode Identitas</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Perusahaan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Jenis Limbah</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Diangkut</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Jumlah (Kg)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logPenyimpanan as $key => $limbah)
                        <tr class="hover:bg-slate-50/50 transition-all duration-200">
                            <td class="px-4 py-3 text-sm font-medium text-slate-700 text-center">{{ $logPenyimpanan->firstItem() + $key }}</td>
                            <td class="px-6 py-3 text-sm text-slate-700">{{ $limbah->kode_identitas }}</td>
                            <td class="px-6 py-3 text-sm text-slate-700">{{ optional($limbah->perusahaanPenghasil)->nama_perusahaan ?? optional($limbah->perusahaan)->nama_perusahaan ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm text-slate-700">{{ optional($limbah->jenisLimbah)->nama_limbah ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm text-slate-700">{{ $limbah->tanggal_pengangkutan ? \Carbon\Carbon::parse($limbah->tanggal_pengangkutan)->format('d/m/Y') : '-' }}</td>
                            <td class="px-6 py-3 text-sm font-bold text-slate-900 text-right"><span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg">{{ number_format($limbah->jumlah_diangkut ?? 0, 2) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-slate-500">Tidak ada data yang cocok dengan filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between gap-3 p-4">
            <div class="text-sm text-slate-600">Menampilkan {{ $logPenyimpanan->firstItem() }} - {{ $logPenyimpanan->lastItem() }} dari {{ $logPenyimpanan->total() }} data</div>
            <div>
                {{ $logPenyimpanan->appends(request()->query())->links() }}
            </div>
        </div>
    </div>


</div>
@endsection