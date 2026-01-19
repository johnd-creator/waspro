@extends('layouts.app')

@section('content')
    <div class="min-h-screen p-4 sm:p-6 lg:p-8"
        style="background: linear-gradient(to bottom right, var(--bg-primary), var(--bg-tertiary), var(--bg-secondary));">
        <!-- Professional Header Section -->
        <div class="rounded-2xl shadow-sm mb-8"
            style="background-color: var(--card-bg); border: 1px solid var(--border-primary);">
            <div class="px-6 py-8 lg:px-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <!-- Dashboard Title Section -->
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/25">
                                <i class="fas fa-tachometer-alt text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-3xl lg:text-4xl font-bold tracking-tight" style="color: var(--text-primary);">
                                Dashboard
                            </h1>
                            <div class="flex items-center mt-2">
                                <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                <p class="ml-2 text-sm font-medium" style="color: var(--text-secondary);">
                                    Sistem Manajemen Limbah Terintegrasi
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Super Admin Unit Filter -->
                    @if(isset($isSuperAdmin) && $isSuperAdmin && isset($units))
                        <div class="flex-1 max-w-xs mx-auto lg:mx-0">
                            <form action="{{ route('dashboard') }}" method="GET">
                                <div class="relative group">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <i
                                            class="fas fa-building text-blue-500 group-hover:text-blue-600 transition-colors"></i>
                                    </div>
                                    <select name="unit_id" onchange="this.form.submit()"
                                        class="block w-full rounded-xl border-0 py-2.5 pl-10 pr-10 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6 shadow-sm transition-all cursor-pointer hover:bg-gray-50"
                                        style="background-color: var(--input-bg); color: var(--input-text);">
                                        <option value="">Global Data (Semua Unit)</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->unit_id }}" {{ (isset($selectedUnitId) && $selectedUnitId == $unit->unit_id) ? 'selected' : '' }}>
                                                {{ $unit->nama_unit }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <i
                                            class="fas fa-chevron-down text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Date & Time Cards Section -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Calendar Card -->
                        <div class="group relative rounded-2xl p-5 hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-0.5"
                            style="background: linear-gradient(to bottom right, var(--bg-tertiary), var(--bg-secondary)); border: 1px solid var(--border-primary);">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                        <i class="fas fa-calendar-alt text-white text-lg"></i>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-1">Tanggal</p>
                                    <div class="flex flex-col">
                                        <p class="text-lg font-bold" id="currentDate" style="color: var(--text-primary);">
                                            {{ now()->format('d M Y') }}</p>
                                        <p class="text-xs" id="currentDay" style="color: var(--text-secondary);">
                                            {{ now()->format('l') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Clock Card -->
                        <div class="group relative rounded-2xl p-5 hover:shadow-lg hover:shadow-purple-500/10 transition-all duration-300 hover:-translate-y-0.5"
                            style="background: linear-gradient(to bottom right, var(--bg-tertiary), var(--bg-secondary)); border: 1px solid var(--border-primary);">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                                        <i class="fas fa-clock text-white text-lg"></i>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-semibold text-purple-700 uppercase tracking-wider mb-1">Waktu</p>
                                    <div class="flex flex-col">
                                        <p class="text-lg font-bold font-mono" id="currentTime"
                                            style="color: var(--text-primary);">--:--:--</p>
                                        <p class="text-xs" style="color: var(--text-secondary);">WIB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3 mb-4">
            <!-- Total Log Card -->
            <div style="background: var(--card-bg); border-color: var(--border-primary);"
                class="group backdrop-blur-xl border rounded-xl p-4 shadow-lg shadow-blue-500/10 hover:shadow-xl hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-1"
                data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-clipboard-list text-lg text-white"></i>
                    </div>
                    <div class="text-right">
                        <div style="color: var(--text-primary);" class="text-2xl font-bold">{{ number_format($totalLogs) }}
                        </div>
                        <div style="color: var(--text-secondary);" class="font-medium text-sm">Total Log</div>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-emerald-600">
                    <i class="fas fa-arrow-up text-xs"></i>
                    <span class="text-xs font-semibold">+12% dari bulan lalu</span>
                </div>
            </div>

            <!-- Estimated Cost Card -->
            <div style="background: var(--card-bg); border-color: var(--border-primary);"
                class="group backdrop-blur-xl border rounded-xl p-4 shadow-lg shadow-amber-500/10 hover:shadow-xl hover:shadow-amber-500/20 transition-all duration-300 hover:-translate-y-1"
                data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-coins text-lg text-white"></i>
                    </div>
                    <div class="text-right">
                        <div style="color: var(--text-primary);" class="text-2xl font-bold">
                            Rp {{ number_format($totalEstimatedCost, 0, ',', '.') }}
                        </div>
                        <div style="color: var(--text-secondary);" class="font-medium text-sm">Perkiraan Biaya</div>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-amber-600">
                    <i class="fas fa-calculator text-xs"></i>
                    <span class="text-xs font-semibold">Estimasi limbah tersimpan</span>
                </div>
            </div>

            <!-- Stored Logs Card -->
            <div style="background: var(--card-bg); border-color: var(--border-primary);"
                class="group backdrop-blur-xl border rounded-xl p-4 shadow-lg shadow-cyan-500/10 hover:shadow-xl hover:shadow-cyan-500/20 transition-all duration-300 hover:-translate-y-1"
                data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="p-3 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-archive text-lg text-white"></i>
                    </div>
                    <div class="text-right">
                        <div style="color: var(--text-primary);" class="text-2xl font-bold">
                            {{ number_format($totalStoredLogs) }}</div>
                        <div style="color: var(--text-secondary);" class="font-medium text-sm">Limbah Tersimpan</div>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-emerald-600">
                    <i class="fas fa-arrow-up text-xs"></i>
                    <span class="text-xs font-semibold">+8% dari bulan lalu</span>
                </div>
            </div>

            <!-- Transported Waste Card -->
            <div style="background: var(--card-bg); border-color: var(--border-primary);"
                class="group backdrop-blur-xl border rounded-xl p-4 shadow-lg shadow-amber-500/10 hover:shadow-xl hover:shadow-amber-500/20 transition-all duration-300 hover:-translate-y-1"
                data-aos="fade-up" data-aos-delay="400">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="p-3 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-truck text-lg text-white"></i>
                    </div>
                    <div class="text-right">
                        <div style="color: var(--text-primary);" class="text-2xl font-bold">
                            {{ number_format($totalTransported) }}</div>
                        <div style="color: var(--text-secondary);" class="font-medium text-sm">Limbah Diangkut</div>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-emerald-600">
                    <i class="fas fa-arrow-up text-xs"></i>
                    <span class="text-xs font-semibold">+3% dari bulan lalu</span>
                </div>
            </div>

            <!-- Near Expiry Waste Card -->
            <div style="background: var(--card-bg); border-color: var(--border-primary);"
                class="group backdrop-blur-xl border rounded-xl p-4 shadow-lg shadow-red-500/10 hover:shadow-xl hover:shadow-red-500/20 transition-all duration-300 hover:-translate-y-1 cursor-pointer"
                data-aos="fade-up" data-aos-delay="500" data-href="{{ route('dashboard.near-expiry') }}">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-exclamation-triangle text-lg text-white"></i>
                    </div>
                    <div class="text-right">
                        <div style="color: var(--text-primary);" class="text-2xl font-bold">
                            {{ number_format($totalNearExpiry) }}</div>
                        <div style="color: var(--text-secondary);" class="font-medium text-sm">Akan Kadaluarsa</div>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-red-600">
                    <i class="fas fa-clock text-xs"></i>
                    <span class="text-xs font-semibold">Dalam {{ $warningDays }} hari</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="mb-4">
            <div class="text-center mb-4 transition-all duration-300 hover:transform hover:-translate-y-1">
                <h2 style="color: var(--text-primary);"
                    class="text-2xl font-bold mb-1 transition-colors duration-200 hover:text-blue-600">Analisis Data</h2>
                <p style="color: var(--text-secondary);" class="text-sm transition-colors duration-200">Visualisasi data
                    limbah dan tren penyimpanan</p>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-5 gap-4">
                <!-- Monthly Chart -->
                <div style="background: var(--card-bg); border-color: var(--border-primary);"
                    class="xl:col-span-3 backdrop-blur-xl border rounded-xl p-4 shadow-lg shadow-blue-500/10"
                    data-aos="fade-up" data-aos-delay="500">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                        <div>
                            <h3 style="color: var(--text-primary);" class="text-lg font-bold mb-1">Penyimpanan Limbah
                                Bulanan</h3>
                            <p style="color: var(--text-secondary);" class="text-sm">Tren penyimpanan limbah sepanjang tahun
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors"
                                data-period="month">Bulan</button>
                            <button
                                class="px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-md"
                                data-period="year">Tahun</button>
                        </div>
                    </div>
                    <div class="relative h-80">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>

                <!-- Status Chart -->
                <div style="background: var(--card-bg); border-color: var(--border-primary);"
                    class="xl:col-span-2 backdrop-blur-xl border rounded-xl p-4 shadow-lg shadow-purple-500/10"
                    data-aos="fade-up" data-aos-delay="600">
                    <div class="mb-4">
                        <h3 style="color: var(--text-primary);" class="text-lg font-bold mb-1">Status Limbah</h3>
                        <p style="color: var(--text-secondary);" class="text-sm">Distribusi status limbah saat ini</p>
                    </div>
                    <div class="relative h-48 mb-4">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="space-y-2">
                        @forelse($statusSummary as $status)
                            <div style="background: var(--card-secondary-bg);"
                                class="flex items-center justify-between p-2 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full {{ $status['dot_class'] ?? 'bg-slate-400' }}"></div>
                                    <span style="color: var(--text-primary);"
                                        class="font-medium text-sm">{{ $status['label'] }}</span>
                                </div>
                                <span
                                    class="font-bold text-sm {{ $status['text_class'] ?? 'text-slate-600' }}">{{ $status['percentage'] }}%</span>
                            </div>
                        @empty
                            <div style="background: var(--card-secondary-bg); color: var(--text-secondary);"
                                class="p-3 rounded-lg text-sm">
                                Belum ada data status limbah.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tables and Activities Section -->
        <div class="mb-4">
            <!-- Section Headers -->
            <!-- Data Teratas dan Aktivitas Terbaru -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Data Teratas -->
                <section class="flex flex-col space-y-4 transition-all duration-300 hover:transform hover:-translate-y-1"
                    aria-labelledby="data-teratas-heading">
                    <!-- Section Header -->
                    <header class="text-center mb-2 transition-all duration-300 hover:transform hover:-translate-y-1">
                        <h2 id="data-teratas-heading" style="color: var(--text-primary);"
                            class="text-2xl font-bold mb-1 transition-colors duration-200 hover:text-blue-600">Data Teratas
                        </h2>
                        <p style="color: var(--text-secondary);"
                            class="text-sm leading-relaxed transition-colors duration-200">Peringkat limbah berdasarkan
                            volume penyimpanan</p>
                    </header>

                    <!-- Main Card -->
                    <article style="background: var(--card-bg); border-color: var(--border-primary);"
                        class="rounded-2xl border shadow-xl shadow-slate-900/5 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-slate-900/10">
                        <!-- Card Header -->
                        <header
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-6 pb-4 bg-green-600 border-b border-green-700">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-white mb-1">Top 10 Jenis Limbah</h3>
                                <p class="text-green-100 text-sm">Berdasarkan total volume penyimpanan</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                    class="inline-flex items-center justify-center w-9 h-9 text-white hover:text-green-600 hover:bg-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2"
                                    aria-label="Download data">
                                    <i class="fas fa-download text-sm" aria-hidden="true"></i>
                                </button>
                                <button type="button"
                                    class="inline-flex items-center justify-center w-9 h-9 text-white hover:text-green-600 hover:bg-white rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2"
                                    aria-label="Filter data">
                                    <i class="fas fa-filter text-sm" aria-hidden="true"></i>
                                </button>
                            </div>
                        </header>

                        <!-- Table Container -->
                        <div class="overflow-x-auto">
                            <table class="w-full" role="table" aria-label="Top 10 jenis limbah">
                                <!-- Table Header -->
                                <thead style="background: var(--card-secondary-bg);">
                                    <tr>
                                        <th scope="col" style="color: var(--text-primary);"
                                            class="px-6 py-2 text-left text-xs font-semibold uppercase tracking-wider">
                                            <span class="flex items-center gap-2">
                                                <span class="w-6 text-center">#</span>
                                                <span>Nama Limbah</span>
                                            </span>
                                        </th>
                                        <th scope="col" style="color: var(--text-primary);"
                                            class="px-6 py-2 text-center text-xs font-semibold uppercase tracking-wider">
                                            Total (Ton)
                                        </th>
                                        <th scope="col" style="color: var(--text-primary);"
                                            class="px-6 py-2 text-center text-xs font-semibold uppercase tracking-wider">
                                            Jumlah Log
                                    </tr>
                                </thead>

                                <!-- Table Body -->
                                <tbody style="background: var(--card-bg); border-color: var(--border-primary);"
                                    class="divide-y">
                                    @forelse($topWasteTypes as $index => $waste)
                                        <tr class="group transition-colors duration-200"
                                            onmouseover="this.style.background='var(--hover-bg)'"
                                            onmouseout="this.style.background='transparent'">
                                            <!-- Rank & Name -->
                                            <td class="px-6 py-2">
                                                <div class="flex items-center gap-3">
                                                    <!-- Rank Badge -->
                                                    <div class="flex-shrink-0">
                                                        <span
                                                            class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold text-white bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full shadow-sm">
                                                            {{ $index + 1 }}
                                                        </span>
                                                    </div>
                                                    <!-- Waste Name -->
                                                    <div class="flex-1 min-w-0">
                                                        <p style="color: var(--text-primary);"
                                                            class="text-sm font-semibold truncate group-hover:text-blue-600 transition-colors duration-200">
                                                            {{ $waste['nama_limbah'] }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Total Quantity -->
                                            <td class="px-6 py-2 text-center">
                                                <span style="color: var(--text-primary);" class="text-sm font-bold">
                                                    {{ number_format($waste['total_jumlah'], 2) }}
                                                </span>
                                            </td>

                                            <!-- Log Count -->
                                            <td class="px-6 py-2 text-center">
                                                <span
                                                    style="background: var(--accent-bg); color: var(--accent-primary); border-color: var(--border-primary);"
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border">
                                                    {{ number_format($waste['total_logs']) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center gap-3">
                                                    <div style="background: var(--card-secondary-bg);"
                                                        class="w-12 h-12 rounded-full flex items-center justify-center">
                                                        <i style="color: var(--text-tertiary);" class="fas fa-inbox text-lg"
                                                            aria-hidden="true"></i>
                                                    </div>
                                                    <div>
                                                        <p style="color: var(--text-primary);" class="text-sm font-medium">Tidak
                                                            ada data</p>
                                                        <p style="color: var(--text-secondary);" class="text-xs mt-1">Belum ada
                                                            data limbah yang tersimpan</p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>
                </section>

                <!-- Aktivitas Terbaru -->
                <div class="space-y-6 transition-all duration-300 hover:transform hover:-translate-y-1">
                    <header class="text-center mb-2 transition-all duration-300 hover:transform hover:-translate-y-1">
                        <h2 style="color: var(--text-primary);"
                            class="text-2xl font-bold mb-1 transition-colors duration-200 hover:text-blue-600">Aktivitas
                            Terbaru</h2>
                        <p style="color: var(--text-secondary);"
                            class="text-sm leading-relaxed transition-colors duration-200">10 log aktivitas terbaru dalam
                            sistem</p>
                    </header>

                    <div style="background: var(--card-bg); border-color: var(--border-primary);"
                        class="backdrop-blur-xl border rounded-xl shadow-lg shadow-blue-500/10 overflow-hidden h-fit">
                        <!-- Header -->
                        <header class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 pb-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-white">Log Aktivitas</h3>
                                    <p class="text-blue-100 text-sm mt-1">Klik untuk melihat detail lengkap</p>
                                </div>
                                <div class="text-blue-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </header>

                        <!-- Activities List -->
                        <div class="divide-y divide-gray-100">
                            @if($recentActivities->count() > 0)
                                @foreach($recentActivities as $activity)
                                    @php
                                        $logId = $activity['log_id'] ?? null;
                                    @endphp
                                    <div class="p-4 transition-colors duration-200 cursor-pointer"
                                        onmouseover="this.style.background='var(--hover-bg)'"
                                        onmouseout="this.style.background='transparent'"
                                        @if($logId) data-href="{{ route('log-penyimpanan.show', $logId) }}" @endif>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-4">
                                                <!-- Status Icon -->
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                                        style="@if($activity['status_log'] == 'Tersimpan') background: var(--accent-bg); color: var(--accent-primary); @elseif($activity['status_log'] == 'Diangkut') background: var(--accent-bg-secondary); color: var(--accent-secondary); @elseif($activity['status_log'] == 'Diproses') background: #fef3c7; color: #d97706; @else background: var(--card-secondary-bg); color: var(--text-secondary); @endif">
                                                        @if($activity['status_log'] == 'Tersimpan')
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4">
                                                                </path>
                                                            </svg>
                                                        @elseif($activity['status_log'] == 'Diangkut')
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        @elseif($activity['status_log'] == 'Diproses')
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                                </path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Activity Info -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <p style="color: var(--text-primary);" class="text-sm font-medium truncate">
                                                            {{ $activity['jenis_limbah']['nama_limbah'] ?? 'N/A' }}
                                                        </p>
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                                            style="@if($activity['status_log'] == 'Tersimpan') background: var(--accent-bg); color: var(--accent-primary); @elseif($activity['status_log'] == 'Diangkut') background: var(--accent-bg-secondary); color: var(--accent-secondary); @elseif($activity['status_log'] == 'Diproses') background: #fef3c7; color: #d97706; @else background: var(--card-secondary-bg); color: var(--text-secondary); @endif">
                                                            {{ $activity['status_log'] }}
                                                        </span>
                                                    </div>
                                                    <div style="color: var(--text-secondary);"
                                                        class="flex items-center text-xs space-x-4">
                                                        <span><i
                                                                class="fas fa-building mr-1"></i>{{ $activity['unit_pembangkit']['nama_unit'] ?? 'N/A' }}</span>
                                                        <span><i
                                                                class="fas fa-weight mr-1"></i>{{ number_format($activity['jumlah_limbah_masuk'], 2) }}
                                                            Kg</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Time -->
                                            <div class="text-right">
                                                <p style="color: var(--text-secondary);" class="text-xs">
                                                    {{ \Carbon\Carbon::parse($activity['created_at'])->diffForHumans() }}</p>
                                                <p style="color: var(--text-tertiary);" class="text-xs">
                                                    {{ \Carbon\Carbon::parse($activity['created_at'])->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-8 text-center">
                                    <div style="color: var(--text-tertiary);" class="mb-2">
                                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                    </div>
                                    <p style="color: var(--text-secondary);" class="text-sm">Belum ada aktivitas log</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Near Expiry Alert -->
        @if($nearExpiryWaste->count() > 0)
            <div class="mb-8">
                <div style="background: var(--card-bg); border-color: var(--border-primary);"
                    class="backdrop-blur-xl border rounded-3xl p-8 shadow-xl shadow-red-500/10" data-aos="fade-up"
                    data-aos-delay="900">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6">
                        <div class="p-4 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg">
                            <i class="fas fa-exclamation-triangle text-2xl text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h3 style="color: var(--text-primary);" class="text-2xl font-bold mb-1">Peringatan Limbah Kritis
                            </h3>
                            <p style="color: var(--text-secondary);">{{ $nearExpiryWaste->count() }} limbah mendekati atau
                                melewati batas penyimpanan</p>
                        </div>
                        <button
                            class="flex items-center gap-2 px-6 py-3 bg-red-500 text-white rounded-2xl font-semibold hover:bg-red-600 transition-colors shadow-lg">
                            <i class="fas fa-bell"></i>
                            Notifikasi
                        </button>
                    </div>
                    <div class="space-y-4">
                            @foreach($nearExpiryWaste as $waste)
                            @php
                                $expiryDate = \Carbon\Carbon::parse($waste['tanggal_kadaluarsa'] ?? $waste['maksimal_penyimpanan_tanggal']);
                                $daysRemaining = $expiryDate ? (int) \Carbon\Carbon::now()->diffInDays($expiryDate, false) : null;
                                $isExpired = $daysRemaining !== null && $daysRemaining < 0;
                            @endphp
                            <div class="p-6 rounded-2xl border-l-4 transition-all hover:shadow-lg {{ $isExpired ? 'border-l-red-500' : 'border-l-amber-500' }}"
                                style="background: var(--card-secondary-bg);">
                                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                                        <div class="space-y-2">
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                                <h4 style="color: var(--text-primary);" class="font-bold text-lg">
                                                    {{ $waste['jenis_limbah']['nama_limbah'] ?? 'N/A' }}</h4>
                                                <span
                                                    style="color: var(--text-secondary);">{{ $waste['perusahaan_penghasil']['nama_perusahaan'] ?? '-' }}</span>
                                            </div>
                                            <div style="color: var(--text-secondary);" class="flex flex-col sm:flex-row gap-4 text-sm">
                                                <span><i class="fas fa-building mr-2"></i>{{ $waste['unit_pembangkit']['nama_unit'] ?? 'N/A' }}</span>
                                                <span><i
                                                        class="fas fa-weight mr-2"></i>{{ number_format($waste['jumlah_limbah_masuk'], 2) }}
                                                    Kg</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                            <div class="text-right">
                                                <div style="color: var(--text-secondary);" class="text-sm">Batas Penyimpanan</div>
                                                <div style="color: var(--text-primary);" class="font-bold">
                                                    {{ \Carbon\Carbon::parse($waste['maksimal_penyimpanan_tanggal'])->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        <span
                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold {{ $isExpired ? 'bg-red-500 text-white' : 'bg-amber-500 text-white' }}">
                                            @if($isExpired)
                                                <i class="fas fa-times-circle"></i> Kadaluarsa
                                            @else
                                                <i class="fas fa-clock"></i> Sisa {{ $daysRemaining }} hari
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('scripts')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        const dashboardChartData = {
            monthlyLabels: {!! json_encode($monthlyChartLabels ?? []) !!},
            monthlyValues: {!! json_encode($monthlyChartValues ?? []) !!},
            statusLabels: {!! json_encode($statusChartLabels ?? []) !!},
            statusValues: {!! json_encode($statusChartValues ?? []) !!},
            statusBackgroundColors: {!! json_encode($statusChartBackgroundColors ?? []) !!},
            statusBorderColors: {!! json_encode($statusChartBorderColors ?? []) !!},
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                offset: 100
            });

            // Handle data-href clicks
            document.querySelectorAll('[data-href]').forEach(function (element) {
                element.addEventListener('click', function () {
                    window.location.href = this.getAttribute('data-href');
                });
            });

            // Wait a bit for Chart.js to be fully loaded
            setTimeout(function () {
                if (typeof Chart !== 'undefined') {
                    initializeCharts();
                }
            }, 500);
        });

        function initializeCharts() {
            // Update time widget
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                const timeWidget = document.querySelector('#currentTime');
                if (timeWidget) {
                    timeWidget.innerHTML = `<i class="fas fa-clock text-lg"></i><span class="font-semibold">${timeString}</span>`;
                }
            }

            // Update time every second
            setInterval(updateTime, 1000);
            updateTime();

            // Monthly Chart
            const monthlyCtx = document.getElementById('monthlyChart');
            if (monthlyCtx && typeof Chart !== 'undefined') {
                const monthlyLabels = Array.isArray(dashboardChartData.monthlyLabels) && dashboardChartData.monthlyLabels.length
                    ? dashboardChartData.monthlyLabels
                    : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                const monthlyValues = (Array.isArray(dashboardChartData.monthlyValues) && dashboardChartData.monthlyValues.length
                    ? dashboardChartData.monthlyValues
                    : new Array(monthlyLabels.length).fill(0)).map(function (value) {
                        const numeric = Number(value);
                        return Number.isFinite(numeric) ? Number(numeric.toFixed(2)) : 0;
                    });

                new Chart(monthlyCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                            label: 'Total Limbah (Kg)',
                            data: monthlyValues,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: 'white',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const value = Number(context.parsed.y ?? 0);
                                        return `${context.dataset.label}: ${value.toLocaleString('id-ID', { maximumFractionDigits: 2 })} Kg`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#64748b'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f1f5f9'
                                },
                                ticks: {
                                    color: '#64748b'
                                }
                            }
                        }
                    }
                });
            }

            // Status Chart
            const statusCtx = document.getElementById('statusChart');

            if (statusCtx && typeof Chart !== 'undefined') {
                const statusLabels = Array.isArray(dashboardChartData.statusLabels) && dashboardChartData.statusLabels.length
                    ? dashboardChartData.statusLabels
                    : ['Tersimpan', 'Diangkut', 'Kadaluarsa'];

                const statusValues = (Array.isArray(dashboardChartData.statusValues) && dashboardChartData.statusValues.length
                    ? dashboardChartData.statusValues
                    : new Array(statusLabels.length).fill(0)).map(function (value) {
                        return Number.isFinite(Number(value)) ? Number(value) : 0;
                    });

                const backgroundColors = Array.isArray(dashboardChartData.statusBackgroundColors) && dashboardChartData.statusBackgroundColors.length
                    ? dashboardChartData.statusBackgroundColors
                    : ['rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)', 'rgba(239, 68, 68, 0.8)'];

                const borderColors = Array.isArray(dashboardChartData.statusBorderColors) && dashboardChartData.statusBorderColors.length
                    ? dashboardChartData.statusBorderColors
                    : ['rgba(59, 130, 246, 1)', 'rgba(16, 185, 129, 1)', 'rgba(239, 68, 68, 1)'];

                try {
                    new Chart(statusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: statusLabels,
                            datasets: [{
                                data: statusValues,
                                backgroundColor: backgroundColors,
                                borderColor: borderColors,
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            const total = context.dataset.data.reduce(function (sum, value) {
                                                const numeric = Number(value);
                                                return sum + (Number.isFinite(numeric) ? numeric : 0);
                                            }, 0);
                                            const value = Number(context.parsed ?? 0);
                                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                            return `${context.label}: ${value.toLocaleString('id-ID')} log (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    // Handle chart creation error silently
                }
            }

            // Real-time clock and date function
            function updateDateTime() {
                const now = new Date();

                // Update time with seconds
                const timeString = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });

                // Update date
                const dateString = now.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });

                // Update day name
                const dayString = now.toLocaleDateString('id-ID', {
                    weekday: 'long'
                });

                // Update DOM elements
                const clockElement = document.getElementById('currentTime');
                const dateElement = document.getElementById('currentDate');
                const dayElement = document.getElementById('currentDay');

                if (clockElement) {
                    clockElement.textContent = timeString;
                }

                if (dateElement) {
                    dateElement.textContent = dateString;
                }

                if (dayElement) {
                    dayElement.textContent = dayString;
                }
            }

            // Update date and time immediately only (no continuous updates)
            updateDateTime();
            // setInterval(updateDateTime, 1000); // Disabled to prevent constant movement
        }
    </script>
@endpush

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@endpush
