@extends('layouts.app')

@section('title', 'Laporan Perusahaan')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <style>
        .table-hover-rows tr:hover { background-color: var(--hover-bg); }
        .status-pill-secondary { background-color: var(--accent-bg-secondary); color: var(--accent-secondary); }
        .status-pill-danger { background-color: var(--danger-bg); color: var(--danger-primary); }
        .text-primary-var { color: var(--text-primary); }
    </style>
    <div class="mb-6 rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="flex items-center justify-between border-b px-6 py-5" style="border-color: var(--border-primary);">
            <h3 class="text-xl font-semibold" style="color: var(--text-primary);">Laporan Perusahaan Penghasil Limbah</h3>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2" style="background-color: var(--border-primary); color: var(--text-secondary);">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
        <div class="p-6">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('reports.company') }}" class="mb-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label for="perusahaan_id" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Perusahaan</label>
                        <select name="perusahaan_id" id="perusahaan_id" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                            <option value="">Semua Perusahaan</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->perusahaan_id }}" {{ request('perusahaan_id') == $company->perusahaan_id ? 'selected' : '' }}>
                                    {{ $company->nama_perusahaan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date_from" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Dari Tanggal</label>
                        <input type="date" name="date_from" id="date_from" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" value="{{ request('date_from') }}">
                    </div>
                    <div>
                        <label for="date_to" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="date_to" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" value="{{ request('date_to') }}">
                    </div>
                    <div class="flex md:items-end">
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white shadow-sm hover:bg-blue-700">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            @php $hasData = isset($logs) && count($logs) > 0; @endphp
            @if($hasData)
            <!-- Export Buttons -->
            <div class="mb-4 flex items-center gap-2">
                <a href="{{ route('reports.company.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3 py-2 text-white hover:bg-red-700">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('reports.company.export', array_merge(request()->all(), ['format' => 'excel'])) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-700">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
            @endif
        </div>
    </div>

    @if($hasData)
    <!-- Summary by Company -->
    @if(isset($companyStats) && count($companyStats) > 0)
    <div class="mb-6 overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="border-b px-4 py-3" style="background-color: var(--border-primary); border-color: var(--border-primary);">
            <h5 class="text-sm font-semibold" style="color: var(--text-primary);">Ringkasan per Perusahaan</h5>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full w-full table-hover-rows">
                <thead style="background-color: var(--card-secondary-bg);">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Perusahaan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Total Log</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Total Berat (Kg)</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Rata-rata Bulanan (Kg)</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Tersimpan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Diangkut</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Jenis Limbah Terbanyak</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companyStats as $stats)
                    <tr class="border-b" style="border-color: var(--border-primary);">
                        <td class="px-4 py-3 text-sm font-medium text-primary-var">{{ $stats['nama_perusahaan'] }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $stats['total_logs'] }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ number_format($stats['total_quantity'], 2) }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ number_format($stats['avg_monthly_quantity'], 2) }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $stats['status_breakdown']['Tersimpan'] ?? 0 }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $stats['status_breakdown']['Diangkut'] ?? 0 }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">@php $top = $stats['waste_types']->first(); @endphp {{ $top['nama_limbah'] ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="mt-2 overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <table class="min-w-full w-full table-hover-rows">
            <thead style="background-color: var(--border-primary);">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Perusahaan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Tanggal Masuk</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Jenis Limbah</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Kode Limbah</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Unit</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Jumlah (Kg)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Tanggal Pengangkutan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Sumber Limbah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                <tr class="border-b" style="border-color: var(--border-primary);">
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-primary-var">{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal' }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $log->jenisLimbah->nama_limbah ?? 'Unknown' }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $log->kode_limbah }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $log->unitPembangkit->nama_unit ?? 'Unknown' }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-primary);">{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                    <td class="px-4 py-3">
                        @php $isTransported = strtoupper($log->status_log) === 'DIANGKUT'; @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $isTransported ? 'status-pill-secondary' : 'status-pill-danger' }}">
                            {{ $log->status_log }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $log->tanggal_pengangkutan ? \Carbon\Carbon::parse($log->tanggal_pengangkutan)->format('d M Y') : '-' }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ Str::limit($log->detail_sumber_limbah, 50) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-4 py-6 text-center text-sm" style="color: var(--text-secondary);">Tidak ada data untuk filter yang dipilih</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if(method_exists($logs, 'links'))
            <div class="border-t p-4" style="border-color: var(--border-primary);">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
    @else
    <div class="mt-2 rounded-xl p-4" style="background-color: var(--accent-bg); color: var(--accent-primary);">
        <i class="fas fa-info-circle mr-2"></i> Silakan pilih filter untuk menampilkan laporan.
    </div>
    @endif
</div>
@endsection
