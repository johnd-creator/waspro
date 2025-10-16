@extends('layouts.app')

@section('title', 'Laporan Status Limbah')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <style>
        .table-hover-rows tr:hover { background-color: var(--hover-bg); }
        .status-pill-secondary { background-color: var(--accent-bg-secondary); color: var(--accent-secondary); }
        .status-pill-danger { background-color: var(--danger-bg); color: var(--danger-primary); }
        .expired-row { background-color: rgba(239,68,68,0.05); }
        .text-primary-var { color: var(--text-primary); }
        .text-danger-var { color: var(--danger-primary); }
    </style>
    <div class="mb-6 rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="flex items-center justify-between border-b px-6 py-5" style="border-color: var(--border-primary);">
            <h3 class="text-xl font-semibold" style="color: var(--text-primary);">Laporan Status Limbah</h3>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2" style="background-color: var(--border-primary); color: var(--text-secondary);">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
        <div class="p-6">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('reports.status') }}" class="mb-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div>
                        <label for="status" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                            <option value="">Semua Status</option>
                            <option value="Tersimpan" {{ request('status') === 'Tersimpan' ? 'selected' : '' }}>Tersimpan</option>
                            <option value="Diangkut" {{ request('status') === 'Diangkut' ? 'selected' : '' }}>Diangkut</option>
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
                <a href="{{ route('reports.status.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3 py-2 text-white hover:bg-red-700">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('reports.status.export', array_merge(request()->all(), ['format' => 'excel'])) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-700">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
                <div class="rounded-2xl p-4" style="background-color: var(--border-primary);">
                    <div class="text-xs font-medium" style="color: var(--text-secondary);">Tersimpan</div>
                    <div class="mt-1 text-2xl font-semibold" style="color: var(--text-primary);">{{ $statusDistribution['Tersimpan']['count'] ?? 0 }}</div>
                    <div class="text-xs" style="color: var(--text-secondary);">{{ number_format($statusDistribution['Tersimpan']['total_quantity'] ?? 0, 2) }} Kg</div>
                </div>
                <div class="rounded-2xl p-4" style="background-color: var(--border-primary);">
                    <div class="text-xs font-medium" style="color: var(--text-secondary);">Diangkut</div>
                    <div class="mt-1 text-2xl font-semibold" style="color: var(--text-primary);">{{ $statusDistribution['Diangkut']['count'] ?? 0 }}</div>
                    <div class="text-xs" style="color: var(--text-secondary);">{{ number_format($statusDistribution['Diangkut']['total_quantity'] ?? 0, 2) }} Kg</div>
                </div>
                <div class="rounded-2xl p-4" style="background-color: var(--border-primary);">
                    <div class="text-xs font-medium" style="color: var(--text-secondary);">Kadaluarsa</div>
                    <div class="mt-1 text-2xl font-semibold" style="color: var(--text-primary);">{{ $statusDistribution['Kadaluarsa']['count'] ?? 0 }}</div>
                    <div class="text-xs" style="color: var(--text-secondary);">Melewati batas penyimpanan</div>
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
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Perusahaan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Unit</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Jumlah (Kg)</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Tanggal Pengangkutan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Maksimal Penyimpanan</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Hari Tersisa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $index => $log)
                @php
                    $daysRemaining = null;
                    $isExpired = false;
                    if (strtoupper($log->status_log) === 'TERSIMPAN' && $log->maksimal_penyimpanan_tanggal) {
                        $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($log->maksimal_penyimpanan_tanggal), false);
                        $isExpired = $daysRemaining < 0;
                    }
                @endphp
                <tr class="border-b {{ $isExpired ? 'expired-row' : '' }}" style="border-color: var(--border-primary);">
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-primary-var">{{ $log->jenisLimbah->nama_limbah ?? 'Unknown' }}</td>
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
                    <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $log->maksimal_penyimpanan_tanggal ? \Carbon\Carbon::parse($log->maksimal_penyimpanan_tanggal)->format('d M Y') : '-' }}</td>
                    <td class="px-4 py-3 text-sm font-medium {{ $isExpired ? 'text-danger-var' : 'text-primary-var' }}">
                        @if($daysRemaining !== null)
                            @if($isExpired)
                                Expired
                            @else
                                {{ $daysRemaining }} hari
                            @endif
                        @else
                            -
                        @endif
                    </td>
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
