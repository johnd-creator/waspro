@extends('layouts.app')

@section('title', 'Report & Scheduling Settings')

@section('content')
    <div class="min-h-screen py-8" style="background-color: var(--bg-primary);">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">Report & Scheduling Settings</h1>
                <p class="text-sm" style="color: var(--text-secondary);">Konfigurasi pembuatan laporan otomatis, format, dan
                    batasan ekspor.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('report-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Export Configuration -->
                <div class="shadow rounded-lg mb-6 overflow-hidden border"
                    style="background-color: var(--card-bg); border-color: var(--border-primary);">
                    <div class="px-6 py-4 border-b"
                        style="border-color: var(--border-primary); background-color: var(--hover-bg);">
                        <h3 class="text-lg font-medium" style="color: var(--text-primary);">Konfigurasi Ekspor</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Pengaturan format dan batasan data.</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Default Format -->
                            <div>
                                <label for="default_format" class="block text-sm font-medium"
                                    style="color: var(--text-primary);">Format Default</label>
                                <select id="default_format" name="default_format"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md border"
                                    style="background-color: var(--card-bg); color: var(--text-primary);">
                                    <option value="pdf" {{ $settings['default_format'] === 'pdf' ? 'selected' : '' }}>PDF
                                        Document (.pdf)</option>
                                    <option value="excel" {{ $settings['default_format'] === 'excel' ? 'selected' : '' }}>
                                        Excel Spreadsheet (.xlsx)</option>
                                </select>
                            </div>

                            <!-- Max Export Rows -->
                            <div>
                                <label for="max_export_rows" class="block text-sm font-medium"
                                    style="color: var(--text-primary);">Batasan Export (Baris)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="max_export_rows" id="max_export_rows" min="100" max="100000"
                                        value="{{ $settings['max_export_rows'] }}"
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                        style="background-color: var(--card-bg); color: var(--text-primary);">
                                </div>
                                <p class="mt-2 text-sm" style="color: var(--text-secondary);">Maksimum 100,000 baris untuk
                                    mencegah server overload.</p>
                            </div>
                        </div>

                        <!-- Include Charts -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="include_charts" name="include_charts" type="checkbox" value="1" {{ $settings['include_charts'] ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="include_charts" class="font-medium" style="color: var(--text-primary);">Sertakan
                                    Grafik</label>
                                <p style="color: var(--text-secondary);">Tampilkan visualisasi grafik pada laporan PDF.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Automation & Scheduling -->
                <div class="shadow rounded-lg mb-6 overflow-hidden border"
                    style="background-color: var(--card-bg); border-color: var(--border-primary);">
                    <div class="px-6 py-4 border-b"
                        style="border-color: var(--border-primary); background-color: var(--hover-bg);">
                        <h3 class="text-lg font-medium" style="color: var(--text-primary);">Otomatisasi & Jadwal</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Pengaturan pembuatan laporan otomatis.</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Auto Generate Monthly -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="auto_generate_monthly" name="auto_generate_monthly" type="checkbox" value="1" {{ $settings['auto_generate_monthly'] ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="auto_generate_monthly" class="font-medium"
                                    style="color: var(--text-primary);">Aktifkan Laporan Bulanan Otomatis</label>
                                <p style="color: var(--text-secondary);">Jika aktif, sistem akan membuat laporan bulan
                                    sebelumnya secara otomatis.</p>
                            </div>
                        </div>

                        <!-- Monthly Generation Day -->
                        <div>
                            <label for="monthly_generation_day" class="block text-sm font-medium"
                                style="color: var(--text-primary);">Tanggal Generate (Setiap Bulan)</label>
                            <div class="mt-1 relative rounded-md shadow-sm max-w-xs">
                                <input type="number" name="monthly_generation_day" id="monthly_generation_day" min="1"
                                    max="28" value="{{ $settings['monthly_generation_day'] }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                    style="background-color: var(--card-bg); color: var(--text-primary);">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="sm:text-sm" style="color: var(--text-secondary);">Tgl</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm" style="color: var(--text-secondary);">Contoh: 1 = Tiap tanggal 1 awal
                                bulan.</p>
                        </div>

                        <!-- Cache Duration -->
                        <div>
                            <label for="cache_duration_minutes" class="block text-sm font-medium"
                                style="color: var(--text-primary);">Durasi Cache (Menit)</label>
                            <div class="mt-1 relative rounded-md shadow-sm max-w-xs">
                                <input type="number" name="cache_duration_minutes" id="cache_duration_minutes" min="0"
                                    max="10080" value="{{ $settings['cache_duration_minutes'] }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                    style="background-color: var(--card-bg); color: var(--text-primary);">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="sm:text-sm" style="color: var(--text-secondary);">Menit</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm" style="color: var(--text-secondary);">Waktu simpan data laporan dalam
                                cache untuk mempercepat akses.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pb-8">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection