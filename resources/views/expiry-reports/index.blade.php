@extends('layouts.app')

@section('title', 'Laporan Kadaluarsa')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6 rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="flex items-center justify-between border-b px-6 py-5" style="border-color: var(--border-primary);">
            <h3 class="text-xl font-semibold" style="color: var(--text-primary);"><i class="fas fa-exclamation-triangle mr-2"></i>Laporan Kadaluarsa</h3>
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-2" style="background-color: var(--border-primary); color: var(--text-secondary);">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
                <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3 py-2 text-white hover:bg-red-700" onclick="exportData('pdf')">
                    <i class="fas fa-file-pdf"></i>
                    <span>Export PDF</span>
                </button>
                <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-white hover:bg-emerald-700" onclick="exportData('excel')">
                    <i class="fas fa-file-excel"></i>
                    <span>Export Excel</span>
                </button>
            </div>
        </div>
        <div class="p-6">
            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 mb-6">
                <a href="{{ route('expiry-reports.index', ['expiry_status' => 'Expired']) }}" class="rounded-lg p-4 transition-transform hover:scale-105 cursor-pointer" style="background-color: var(--danger-bg);">
                    <div class="flex items-center">
                        <div class="mr-4 text-2xl" style="color: var(--danger-primary);"><i class="fas fa-times-circle"></i></div>
                        <div>
                            <div class="text-sm font-medium" style="color: var(--danger-primary);">Kadaluarsa</div>
                            <div class="text-2xl font-bold" style="color: var(--text-primary);">{{ $summary['expired'] }}</div>
                            <div class="text-xs mt-1" style="color: var(--danger-primary);">Limbah sudah melewati batas</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('expiry-reports.index', ['expiry_status' => 'Critical']) }}" class="rounded-lg p-4 transition-transform hover:scale-105 cursor-pointer" style="background-color: var(--critical-bg);">
                    <div class="flex items-center">
                        <div class="mr-4 text-2xl" style="color: var(--critical-primary);"><i class="fas fa-exclamation-triangle"></i></div>
                        <div>
                            <div class="text-sm font-medium" style="color: var(--critical-primary);">Kritis</div>
                            <div class="text-2xl font-bold" style="color: var(--text-primary);">{{ $summary['critical'] }}</div>
                            <div class="text-xs mt-1" style="color: var(--critical-primary);">Tersisa < 7 hari (H-1 s/d H-7)</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('expiry-reports.index', ['expiry_status' => 'Warning']) }}" class="rounded-lg p-4 transition-transform hover:scale-105 cursor-pointer" style="background-color: var(--warning-bg);">
                    <div class="flex items-center">
                        <div class="mr-4 text-2xl" style="color: var(--warning-primary);"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="text-sm font-medium" style="color: var(--warning-primary);">Peringatan</div>
                            <div class="text-2xl font-bold" style="color: var(--text-primary);">{{ $summary['warning'] }}</div>
                            <div class="text-xs mt-1" style="color: var(--warning-primary);">Tersisa 8-30 hari</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('expiry-reports.index', ['expiry_status' => 'Safe']) }}" class="rounded-lg p-4 transition-transform hover:scale-105 cursor-pointer" style="background-color: var(--success-bg);">
                    <div class="flex items-center">
                        <div class="mr-4 text-2xl" style="color: var(--success-primary);"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <div class="text-sm font-medium" style="color: var(--success-primary);">Aman</div>
                            <div class="text-2xl font-bold" style="color: var(--text-primary);">{{ $summary['safe'] }}</div>
                            <div class="text-xs mt-1" style="color: var(--success-primary);">Tersisa > 30 hari</div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('expiry-reports.index') }}" id="filterForm" class="mb-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
                    <div>
                        <label for="expiry_status" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Status Kadaluarsa</label>
                        <select name="expiry_status" id="expiry_status" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                            <option value="">Semua Status</option>
                            <option value="Expired" {{ request('expiry_status') == 'Expired' ? 'selected' : '' }}>Kadaluarsa</option>
                            <option value="Critical" {{ request('expiry_status') == 'Critical' ? 'selected' : '' }}>Kritis (< 7 hari)</option>
                            <option value="Warning" {{ request('expiry_status') == 'Warning' ? 'selected' : '' }}>Peringatan (8-30 hari)</option>
                            <option value="Safe" {{ request('expiry_status') == 'Safe' ? 'selected' : '' }}>Aman (> 30 hari)</option>
                        </select>
                    </div>
                    <div>
                        <label for="jenis_limbah_id" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Jenis Limbah</label>
                        <select name="jenis_limbah_id" id="jenis_limbah_id" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                            <option value="" selected>Semua Jenis</option>
                            @foreach($jenisLimbah as $jenis)
                                <option value="{{ $jenis->id }}" {{ request('jenis_limbah_id') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama_limbah }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="perusahaan_id" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Perusahaan</label>
                        <select name="perusahaan_id" id="perusahaan_id" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                            <option value="" selected>Semua Perusahaan</option>
                            @foreach($perusahaan as $company)
                                <option value="{{ $company->id }}" {{ request('perusahaan_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->nama_perusahaan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date_from" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Tanggal Masuk Dari</label>
                        <input type="date" name="date_from" id="date_from" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" value="{{ request('date_from') }}">
                    </div>
                    <div>
                        <label for="date_to" class="mb-1 block text-sm font-medium" style="color: var(--text-secondary);">Tanggal Masuk Sampai</label>
                        <input type="date" name="date_to" id="date_to" class="mt-1 block w-full rounded-lg border shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white shadow-sm hover:bg-blue-700">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('expiry-reports.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border px-4 py-2 shadow-sm" style="border-color: var(--border-primary); color: var(--text-secondary);">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    @php $hasData = isset($logs) && count($logs) > 0; @endphp
    @if($hasData)
    <!-- Data Table -->
    <div class="mt-2 overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Tgl Masuk</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Jenis Limbah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Jumlah (Kg)</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Perusahaan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Tanggal Kadaluarsa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Sisa Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Status Limbah</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr class="border-b" style="border-color: var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                        <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $logs->firstItem() + $index }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-sm font-medium" style="color: var(--text-primary);">{{ $log->kode_limbah }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $log->jenisLimbah->nama_limbah ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm font-medium" style="color: var(--text-primary);">{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm" style="color: var(--text-secondary);">
                            @if($log->tanggal_kadaluarsa)
                                {{ \Carbon\Carbon::parse($log->tanggal_kadaluarsa)->format('d/m/Y') }}
                            @else
                                <span style="color: var(--text-muted);">N/A</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @php($daysLeft = $log->getDaysUntilExpiry())
                            @if($daysLeft !== null)
                                @if($daysLeft <= 0)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" style="background-color: var(--danger-bg); color: var(--danger-primary);">Kadaluarsa</span>
                                @elseif($daysLeft <= 7)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" style="background-color: var(--critical-bg); color: var(--critical-primary);">H-{{ $daysLeft }}</span>
                                @elseif($daysLeft <= 30)
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" style="background-color: var(--warning-bg); color: var(--warning-primary);">H-{{ $daysLeft }}</span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" style="background-color: var(--success-bg); color: var(--success-primary);">H-{{ $daysLeft }}</span>
                                @endif
                            @else
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $log->getStatusLogBadgeClass() }}">
                                {{ $log->status_log }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="{{ route('log-penyimpanan.show', $log) }}" class="inline-flex items-center justify-center rounded-lg p-2 hover:bg-gray-200 dark:hover:bg-gray-700">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-sm" style="color: var(--text-secondary);">
                            <div class="py-4">
                                <i class="fas fa-clipboard-list fa-3x mb-3" style="color: var(--text-muted);"></i>
                                <h5 style="color: var(--text-muted);">Tidak ada data yang ditemukan</h5>
                                <p style="color: var(--text-muted);">Silakan ubah filter atau tambah data baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="border-t p-4" style="border-color: var(--border-primary);">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
    @else
    <div class="mt-2 rounded-xl p-4" style="background-color: var(--accent-bg); color: var(--accent-primary);">
        <i class="fas fa-info-circle mr-2"></i> Tidak ada data untuk filter yang dipilih.
    </div>
    @endif
</div>

<script>
function exportData(format) {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    params.append('format', format);
    
    window.location.href = '{{ route("expiry-reports.export") }}?' + params.toString();
}
</script>
@endsection

