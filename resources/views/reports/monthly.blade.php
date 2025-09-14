@extends('layouts.app')

@section('title', 'Laporan Bulanan/Tahunan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Laporan Bulanan/Tahunan</h3>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg ring-1 ring-gray-300 dark:ring-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
        <div class="p-6">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('reports.monthly') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun</label>
                        <select name="year" id="year" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @for($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bulan (Opsional)</label>
                        <select name="month" id="month" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Pembangkit</label>
                        <select name="unit_id" id="unit_id" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->unit_id }}" {{ (request('unit_id') ?? $unitId) == $unit->unit_id ? 'selected' : '' }}>
                                    {{ $unit->nama_unit }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex md:items-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            @php $hasData = isset($logs) && count($logs) > 0; @endphp
            @if($hasData)
            <!-- Export Buttons -->
            <div class="mb-4 flex items-center gap-2">
                <a href="{{ route('reports.monthly.export', ['format' => 'pdf']) }}?{{ http_build_query(request()->all()) }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('reports.monthly.export', ['format' => 'excel']) }}?{{ http_build_query(request()->all()) }}" 
                   class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white">
                    <i class="fas a-file-excel"></i> Export Excel
                </a>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="rounded-2xl ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900 p-4">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Log</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalLogs }}</div>
                </div>
                <div class="rounded-2xl ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900 p-4">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Diangkut</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalTransported }}</div>
                </div>
                <div class="rounded-2xl ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900 p-4">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Tersimpan</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $wasteStored }}</div>
                </div>
                <div class="rounded-2xl ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900 p-4">
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">Total (Ton)</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($totalWaste, 2) }}</div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="mt-2 overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/60">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Tanggal Masuk</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Jenis Limbah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Kode Limbah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Perusahaan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Jumlah (Kg)</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Tanggal Pengangkutan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($logs as $index => $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60">
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $log->tanggal_limbah_masuk }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $log->jenisLimbah->nama_limbah ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $log->kode_limbah }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $log->unitPembangkit->nama_unit ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                            <td class="px-4 py-3">
                                @php $isTransported = strtoupper($log->status_log) === 'DIANGKUT'; @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 {{ $isTransported ? 'ring-emerald-200/50 bg-emerald-50 text-emerald-600 dark:ring-emerald-400/30 dark:bg-emerald-400/10 dark:text-emerald-300' : 'ring-amber-200/50 bg-amber-50 text-amber-700 dark:ring-amber-400/30 dark:bg-amber-400/10 dark:text-amber-300' }}">
                                    {{ $log->status_log }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $log->tanggal_pengangkutan ?: '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data untuk periode yang dipilih</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($logs, 'links'))
                <div class="flex justify-center mt-6">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            @endif
            @else
            <div class="mt-2 rounded-xl ring-1 ring-blue-200/60 dark:ring-blue-400/20 bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-200 px-4 py-3">
                <i class="fas fa-info-circle mr-2"></i> Silakan pilih filter untuk menampilkan laporan.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection