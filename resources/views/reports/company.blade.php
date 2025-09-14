@extends('layouts.app')

@section('title', 'Laporan Perusahaan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Laporan Perusahaan Penghasil Limbah</h3>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg ring-1 ring-gray-300 dark:ring-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
        <div class="p-6">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('reports.company') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="perusahaan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Perusahaan</label>
                        <select name="perusahaan_id" id="perusahaan_id" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Perusahaan</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->perusahaan_id }}" {{ request('perusahaan_id') == $company->perusahaan_id ? 'selected' : '' }}>
                                    {{ $company->nama_perusahaan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dari Tanggal</label>
                        <input type="date" name="date_from" id="date_from" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ request('date_from') }}">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="date_to" class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" value="{{ request('date_to') }}">
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
                <a href="{{ route('reports.company.export', ['format' => 'pdf']) }}?{{ http_build_query(request()->all()) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('reports.company.export', ['format' => 'excel']) }}?{{ http_build_query(request()->all()) }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>

            <!-- Summary by Company -->
            @if(isset($companyStats) && count($companyStats) > 0)
            <div class="mb-6 overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/60 border-b border-gray-100 dark:border-gray-800">
                    <h5 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Ringkasan per Perusahaan</h5>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/60">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Perusahaan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Total Log</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Total Berat (Kg)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Rata-rata Bulanan (Kg)</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Tersimpan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Diangkut</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Jenis Limbah Terbanyak</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($companyStats as $stats)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $stats['nama_perusahaan'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $stats['total_logs'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ number_format($stats['total_quantity'], 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ number_format($stats['avg_monthly_quantity'], 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $stats['status_breakdown']['Tersimpan'] ?? 0 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $stats['status_breakdown']['Diangkut'] ?? 0 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">@php $top = $stats['waste_types']->first(); @endphp {{ $top['nama_limbah'] ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Data Table -->
            <div class="mt-2 overflow-hidden rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/60">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Perusahaan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Tanggal Masuk</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Jenis Limbah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Kode Limbah</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Jumlah (Kg)</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Tanggal Pengangkutan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">Sumber Limbah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($logs as $index => $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60">
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $log->tanggal_limbah_masuk }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $log->jenisLimbah->nama_limbah ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $log->kode_limbah }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $log->unitPembangkit->nama_unit ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                            <td class="px-4 py-3">
                                @php $isTransported = strtoupper($log->status_log) === 'DIANGKUT'; @endphp
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 {{ $isTransported ? 'ring-emerald-200/50 bg-emerald-50 text-emerald-600 dark:ring-emerald-400/30 dark:bg-emerald-400/10 dark:text-emerald-300' : 'ring-amber-200/50 bg-amber-50 text-amber-700 dark:ring-amber-400/30 dark:bg-amber-400/10 dark:text-amber-300' }}">
                                    {{ $log->status_log }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $log->tanggal_pengangkutan ?: '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($log->detail_sumber_limbah, 50) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data untuk filter yang dipilih</td>
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