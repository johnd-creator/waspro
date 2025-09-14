@extends('layouts.app')

@section('title', 'Dashboard Report - Sistem Manajemen Limbah')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="rounded-2xl overflow-hidden bg-gradient-to-tr from-indigo-500 to-purple-600 text-white shadow-sm mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
            <div class="lg:col-span-2">
                <h1 class="text-3xl font-bold mb-1">Dashboard Report</h1>
                <p class="text-indigo-100">Sistem Manajemen Limbah Terintegrasi</p>
                <p class="mt-2 text-indigo-50">Pantau dan kelola data limbah dengan mudah melalui dashboard komprehensif ini</p>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white backdrop-blur ring-1 ring-white/30">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Log Limbah</span>
                    </a>
                    <button type="button" onclick="clearReportCache()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white backdrop-blur ring-1 ring-white/30">
                        <i class="fas fa-sync-alt"></i>
                        <span>Refresh Data</span>
                    </button>
                </div>
            </div>
            <div>
                <div class="bg-white/95 dark:bg-gray-900/95 text-gray-900 dark:text-gray-100 rounded-2xl shadow-sm ring-1 ring-black/5 dark:ring-white/10 p-5">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <h3 class="text-indigo-600 dark:text-indigo-400 text-2xl font-semibold">{{ $totalLogs ?? 0 }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Log</p>
                        </div>
                        <div>
                            <h3 class="text-emerald-600 dark:text-emerald-400 text-2xl font-semibold">{{ $totalTransported ?? 0 }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Diangkut</p>
                        </div>
                        <div>
                            <h3 class="text-amber-600 dark:text-amber-400 text-2xl font-semibold">{{ $totalStored ?? 0 }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tersimpan</p>
                        </div>
                        <div>
                            <h3 class="text-sky-600 dark:text-sky-400 text-2xl font-semibold">{{ number_format($totalWaste ?? 0, 2) }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total (Ton)</p>
                        </div>
                    </div>
                    @if(($totalLogs ?? 0) > 0)
                    @php $progressWidth = round((($totalTransported ?? 0) / max(($totalLogs ?? 1),1)) * 100); @endphp
                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-1 text-xs text-gray-600 dark:text-gray-400">
                            <span>Progress Pengangkutan</span>
                            <span class="font-medium">{{ $progressWidth }}%</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400" data-bar-width="{{ $progressWidth }}"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        <!-- Monthly/Yearly -->
        <div class="rounded-2xl overflow-hidden shadow-sm ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900">
            <div class="px-5 py-4 bg-gradient-to-tr from-sky-500 to-cyan-400 text-white">
                <h5 class="font-semibold"><i class="fas fa-calendar-alt mr-2"></i>Laporan Bulanan/Tahunan</h5>
            </div>
            <div class="p-5">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Laporan berdasarkan periode waktu tertentu dengan ringkasan statistik komprehensif.</p>
                <a href="{{ route('reports.monthly') }}" class="inline-flex items-center justify-center gap-2 w-full px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white">
                    <i class="fas fa-eye"></i> Lihat Laporan
                </a>
            </div>
        </div>
        <!-- Status -->
        <div class="rounded-2xl overflow-hidden shadow-sm ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900">
            <div class="px-5 py-4 bg-gradient-to-tr from-emerald-500 to-teal-400 text-white">
                <h5 class="font-semibold"><i class="fas fa-tasks mr-2"></i>Laporan Status</h5>
            </div>
            <div class="p-5">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Laporan berdasarkan status limbah (Tersimpan, Diangkut, dll) dengan analisis mendalam.</p>
                <a href="{{ route('reports.status') }}" class="inline-flex items-center justify-center gap-2 w-full px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white">
                    <i class="fas fa-eye"></i> Lihat Laporan
                </a>
            </div>
        </div>
        <!-- Waste Type -->
        <div class="rounded-2xl overflow-hidden shadow-sm ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900">
            <div class="px-5 py-4 bg-gradient-to-tr from-amber-400 to-pink-500 text-white">
                <h5 class="font-semibold"><i class="fas fa-recycle mr-2"></i>Laporan Jenis Limbah</h5>
            </div>
            <div class="p-5">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Laporan berdasarkan jenis limbah yang disimpan dengan kategorisasi detail.</p>
                <a href="{{ route('reports.waste-type') }}" class="inline-flex items-center justify-center gap-2 w-full px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white">
                    <i class="fas fa-eye"></i> Lihat Laporan
                </a>
            </div>
        </div>
        <!-- Company -->
        <div class="rounded-2xl overflow-hidden shadow-sm ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900">
            <div class="px-5 py-4 bg-gradient-to-tr from-rose-500 to-orange-400 text-white">
                <h5 class="font-semibold"><i class="fas fa-building mr-2"></i>Laporan Perusahaan</h5>
            </div>
            <div class="p-5">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Laporan berdasarkan perusahaan penghasil limbah dengan analisis performa.</p>
                <a href="{{ route('reports.company') }}" class="inline-flex items-center justify-center gap-2 w-full px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white">
                    <i class="fas fa-eye"></i> Lihat Laporan
                </a>
            </div>
        </div>
        <!-- Unit -->
        <div class="rounded-2xl overflow-hidden shadow-sm ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900">
            <div class="px-5 py-4 bg-gradient-to-tr from-indigo-500 to-purple-600 text-white">
                <h5 class="font-semibold"><i class="fas fa-industry mr-2"></i>Laporan Unit</h5>
            </div>
            <div class="p-5">
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Laporan berdasarkan unit pembangkit dengan breakdown detail operasional.</p>
                <a href="{{ route('reports.unit') }}" class="inline-flex items-center justify-center gap-2 w-full px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white">
                    <i class="fas fa-eye"></i> Lihat Laporan
                </a>
            </div>
        </div>
        <!-- Quick Stats -->
        <div class="rounded-2xl overflow-hidden shadow-sm ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900">
            <div class="px-5 py-4 bg-gradient-to-tr from-fuchsia-500 to-pink-500 text-white">
                <h5 class="font-semibold"><i class="fas fa-chart-bar mr-2"></i>Statistik Cepat</h5>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-3">
                        <h4 class="text-indigo-600 dark:text-indigo-400 text-xl font-semibold">{{ $totalLogs ?? 0 }}</h4>
                        <small class="text-gray-500 dark:text-gray-400">Total Log</small>
                    </div>
                    <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-3">
                        <h4 class="text-emerald-600 dark:text-emerald-400 text-xl font-semibold">{{ $totalTransported ?? 0 }}</h4>
                        <small class="text-gray-500 dark:text-gray-400">Diangkut</small>
                    </div>
                    <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-3">
                        <h4 class="text-amber-600 dark:text-amber-400 text-xl font-semibold">{{ $totalStored ?? 0 }}</h4>
                        <small class="text-gray-500 dark:text-gray-400">Tersimpan</small>
                    </div>
                    <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-3">
                        <h4 class="text-sky-600 dark:text-sky-400 text-xl font-semibold">{{ number_format($totalWaste ?? 0, 2) }}</h4>
                        <small class="text-gray-500 dark:text-gray-400">Total (Ton)</small>
                    </div>
                </div>
                @if(($totalLogs ?? 0) > 0)
                @php $progressWidth2 = round((($totalTransported ?? 0) / max(($totalLogs ?? 1),1)) * 100); @endphp
                <div class="mt-4">
                    <div class="flex items-center justify-between mb-2 text-xs">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Progress Pengangkutan</span>
                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $progressWidth2 }}%</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400" data-bar-width="{{ $progressWidth2 }}"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function clearReportCache() {
    if (confirm('Apakah Anda yakin ingin menghapus cache laporan?')) {
        fetch('{{ route("reports.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({})
        })
        .then(async (r) => {
            const ct = r.headers.get('content-type') || '';
            let resp = null;
            if (ct.includes('application/json')) {
                try {
                    resp = await r.json();
                } catch (e) {
                    resp = null;
                }
            } else {
                // Fallback baca text bila server tidak mengembalikan JSON
                try {
                    const txt = await r.text();
                    if (txt && txt.length) {
                        resp = { message: txt };
                    }
                } catch (e) {
                    resp = null;
                }
            }

            if (!r.ok) {
                const msg = (resp && (resp.error || resp.message)) ? (resp.error || resp.message) : 'Gagal menghapus cache laporan';
                throw new Error(msg);
            }

            // Sukses
            if (resp && (resp.success === true || resp.status === 'ok')) {
                alert('Cache laporan berhasil dihapus');
                window.location.reload();
            } else {
                // Jika tidak ada payload JSON, asumsikan sukses saat status OK
                alert('Cache laporan berhasil dihapus');
                window.location.reload();
            }
        })
        .catch((err) => alert(err && err.message ? err.message : 'Terjadi kesalahan saat menghapus cache'));
    }
}

// Apply width for progress bars from data attribute to avoid inline templating inside style attribute
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-bar-width]').forEach(function (el) {
        var n = parseFloat(el.getAttribute('data-bar-width'));
        if (!isNaN(n)) {
            el.style.width = n + '%';
        }
    });
});
</script>
@endpush
@endsection