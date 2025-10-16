@extends('layouts.app')

@section('title', 'Laporan Bulanan/Tahunan')

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
            <h3 class="text-xl font-semibold" style="color: var(--text-primary);">Laporan Bulanan/Tahunan</h3>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2" style="background-color: var(--border-primary); color: var(--text-secondary);">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
        <div class="p-6">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('reports.monthly') }}" class="mb-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label for="year" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Tahun</label>
                        <select name="year" id="year" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                            @for($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="month" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Bulan (Opsional)</label>
                        <select name="month" id="month" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                            <option value="">Semua Bulan</option>
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label for="unit_id" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Unit Pembangkit</label>
                        <select name="unit_id" id="unit_id" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->unit_id }}" {{ (request('unit_id') ?? $unitId) == $unit->unit_id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
                            @endforeach
                        </select>
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
                <a href="{{ route('reports.monthly.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3 py-2 text-white hover:bg-red-700">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('reports.monthly.export', array_merge(request()->all(), ['format' => 'excel'])) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-700">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <div class="rounded-2xl p-4" style="background-color: var(--border-primary);">
                    <div class="text-xs font-medium" style="color: var(--text-secondary);">Total Log</div>
                    <div class="mt-1 text-2xl font-semibold" style="color: var(--text-primary);">{{ $totalLogs }}</div>
                </div>
                <div class="rounded-2xl p-4" style="background-color: var(--border-primary);">
                    <div class="text-xs font-medium" style="color: var(--text-secondary);">Diangkut</div>
                    <div class="mt-1 text-2xl font-semibold" style="color: var(--text-primary);">{{ $totalTransported }}</div>
                </div>
                <div class="rounded-2xl p-4" style="background-color: var(--border-primary);">
                    <div class="text-xs font-medium" style="color: var(--text-secondary);">Tersimpan</div>
                    <div class="mt-1 text-2xl font-semibold" style="color: var(--text-primary);">{{ $wasteStored }}</div>
                </div>
                <div class="rounded-2xl p-4" style="background-color: var(--border-primary);">
                    <div class="text-xs font-medium" style="color: var(--text-secondary);">Total (Ton)</div>
                    <div class="mt-1 text-2xl font-semibold" style="color: var(--text-primary);">{{ number_format($totalWaste, 2) }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if($hasData)
    <!-- Data Table -->
    <div class="mt-2 overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <table class="min-w-full w-full table-hover-rows">
            <thead style="background-color: var(--border-primary);">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Tanggal Masuk</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Jenis Limbah</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Kode Limbah</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Perusahaan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Unit</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Jumlah (Kg)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Tanggal Pengangkutan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                <tr class="border-b" style="border-color: var(--border-primary);">
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-primary-var">{{ $log->jenisLimbah->nama_limbah ?? 'Unknown' }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $log->kode_limbah }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal' }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $log->unitPembangkit->nama_unit ?? 'Unknown' }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-primary);">{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                    <td class="px-4 py-3">
                        @php $isTransported = strtoupper($log->status_log) === 'DIANGKUT'; @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $isTransported ? 'status-pill-secondary' : 'status-pill-danger' }}">
                            {{ $log->status_log }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $log->tanggal_pengangkutan ? \Carbon\Carbon::parse($log->tanggal_pengangkutan)->format('d M Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-6 text-center text-sm" style="color: var(--text-secondary);">Tidak ada data untuk periode yang dipilih</td>
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
