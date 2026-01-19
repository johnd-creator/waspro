@extends('layouts.app')

@section('title', 'Pusat Laporan - Sistem Manajemen Limbah')

@section('content')
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(to bottom right, var(--bg-primary), var(--bg-tertiary), var(--bg-secondary));">

    <!-- Professional Header Section -->
    <div class="rounded-2xl shadow-sm mb-8" style="background-color: var(--card-bg); border: 1px solid var(--border-primary);">
        <div class="px-6 py-8 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <!-- Page Title Section -->
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/25">
                            <i class="fas fa-file-alt text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-3xl lg:text-4xl font-bold tracking-tight" style="color: var(--text-primary);">
                            Pusat Laporan
                        </h1>
                        <p class="mt-2 text-sm font-medium" style="color: var(--text-secondary);">
                            Pilih dan lihat berbagai jenis laporan yang tersedia.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md transition-all duration-300 hover:-translate-y-0.5">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Log</span>
                    </a>
                    <button type="button" onclick="clearReportCache()" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-semibold transition-all duration-300 hover:-translate-y-0.5" style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary); ">
                        <i class="fas fa-sync-alt"></i>
                        <span>Refresh Data</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
    $reports = [
        [
            'route' => 'reports.monthly',
            'title' => 'Laporan Bulanan/Tahunan',
            'icon' => 'fa-calendar-alt',
            'description' => 'Laporan berdasarkan periode waktu tertentu dengan ringkasan statistik.',
            'color' => 'blue',
        ],
        [
            'route' => 'reports.status',
            'title' => 'Laporan Status',
            'icon' => 'fa-tasks',
            'description' => 'Laporan berdasarkan status limbah (Tersimpan, Diangkut, dll).',
            'color' => 'emerald',
        ],
        [
            'route' => 'reports.waste-type',
            'title' => 'Laporan Jenis Limbah',
            'icon' => 'fa-recycle',
            'description' => 'Laporan berdasarkan jenis limbah yang disimpan dengan kategorisasi.',
            'color' => 'amber',
        ],
        [
            'route' => 'reports.company',
            'title' => 'Laporan Perusahaan',
            'icon' => 'fa-building',
            'description' => 'Laporan berdasarkan perusahaan penghasil limbah dengan analisis performa.',
            'color' => 'purple',
        ],
        [
            'route' => 'reports.unit',
            'title' => 'Laporan Unit',
            'icon' => 'fa-industry',
            'description' => 'Laporan berdasarkan unit pembangkit dengan breakdown detail operasional.',
            'color' => 'indigo',
        ],
        [
            'route' => 'expiry-reports.index',
            'title' => 'Laporan Kadaluwarsa',
            'icon' => 'fa-exclamation-triangle',
            'description' => 'Laporan limbah yang mendekati atau telah melewati batas penyimpanan.',
            'color' => 'red',
        ],
    ];
    @endphp

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        @foreach ($reports as $report)
        <a href="{{ route($report['route']) }}"
           class="group block p-6 rounded-2xl shadow-lg transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-{{ $report['color'] }}-500/20"
           style="background-color: var(--card-bg); border: 1px solid var(--border-primary);">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-{{ $report['color'] }}-500 to-{{ $report['color'] }}-600 rounded-xl flex items-center justify-center shadow-md group-hover:scale-105 transition-transform duration-300">
                    <i class="fas {{ $report['icon'] }} text-white text-lg"></i>
                </div>
                <h3 class="text-lg font-bold" style="color: var(--text-primary);">{{ $report['title'] }}</h3>
            </div>
            <p class="text-sm mb-4" style="color: var(--text-secondary);">{{ $report['description'] }}</p>
            <div class="mt-auto">
                <span class="font-semibold text-{{ $report['color'] }}-600 group-hover:text-{{ $report['color'] }}-500 transition-colors duration-300">
                    Lihat Laporan &rarr;
                </span>
            </div>
        </a>
        @endforeach
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
                 Swal.fire({
                     title: 'Berhasil',
                     text: 'Cache laporan berhasil dihapus',
                     icon: 'success',
                     toast: true,
                     position: 'top-end',
                     timer: 2000,
                     timerProgressBar: true,
                     showConfirmButton: false,
                     customClass: {
                         container: 'swal2-container',
                         popup: 'swal2-popup swal2-toast'
                     },
                     background: 'var(--bg-primary)',
                     color: 'var(--text-primary)'
                 }).then(() => {
                     window.location.reload();
                 });
             } else {
                 // Jika tidak ada payload JSON, asumsikan sukses saat status OK
                 Swal.fire({
                     title: 'Berhasil',
                     text: 'Cache laporan berhasil dihapus',
                     icon: 'success',
                     toast: true,
                     position: 'top-end',
                     timer: 2000,
                     timerProgressBar: true,
                     showConfirmButton: false,
                     customClass: {
                         container: 'swal2-container',
                         popup: 'swal2-popup swal2-toast'
                     },
                     background: 'var(--bg-primary)',
                     color: 'var(--text-primary)'
                 }).then(() => {
                     window.location.reload();
                 });
             }
        })
         .catch((err) => {
             Swal.fire({
                 title: 'Gagal',
                 text: err && err.message ? err.message : 'Terjadi kesalahan saat menghapus cache',
                 icon: 'error',
                 toast: true,
                 position: 'top-end',
                 timer: 3000,
                 timerProgressBar: true,
                 showConfirmButton: false,
                 customClass: {
                     container: 'swal2-container',
                     popup: 'swal2-popup swal2-toast'
                 },
                 background: 'var(--bg-primary)',
                 color: 'var(--text-primary)'
             });
         });
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