@extends('layouts.app')

@section('title', 'Expiry Settings')

@section('content')
    <div class="min-h-screen py-8" style="background-color: var(--bg-primary);">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">Pengaturan Status Kadaluarsa Limbah
                </h1>
                <p class="text-sm" style="color: var(--text-secondary);">Konfigurasi batas hari untuk status kadaluarsa
                    limbah.</p>
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

            @if($errors->any())
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
                            <ul class="text-sm font-medium text-red-800 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Info Card -->
            <div class="shadow rounded-lg mb-6 overflow-hidden border bg-blue-50 border-blue-200">
                <div class="p-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Informasi Pengaturan</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p><strong>Catatan:</strong> Hari peringatan > Hari kritis > Hari urgent. Sistem akan
                                    otomatis menghitung ulang status semua limbah.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('expiry-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Expiry Days Configuration -->
                <div class="shadow rounded-lg mb-6 overflow-hidden border"
                    style="background-color: var(--card-bg); border-color: var(--border-primary);">
                    <div class="px-6 py-4 border-b"
                        style="border-color: var(--border-primary); background-color: var(--hover-bg);">
                        <h3 class="text-lg font-medium" style="color: var(--text-primary);">Pengaturan Hari Kadaluarsa</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Tentukan batas hari untuk setiap level
                            status.</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Urgent Days -->
                            <div>
                                <label for="urgent_days" class="block text-sm font-medium"
                                    style="color: var(--text-primary);">
                                    <span class="inline-flex items-center">
                                        <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                                        Hari Urgent
                                    </span>
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="urgent_days" id="urgent_days" min="1" max="365"
                                        value="{{ old('urgent_days', $settings['urgent_days']) }}"
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border @error('urgent_days') border-red-500 @enderror"
                                        style="background-color: var(--card-bg); color: var(--text-primary);">
                                </div>
                                <p class="mt-1 text-xs" style="color: var(--text-secondary);">Status <strong>Urgent</strong>
                                    jika ≤ {{ $settings['urgent_days'] }} hari</p>
                                @error('urgent_days')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Critical Days -->
                            <div>
                                <label for="critical_days" class="block text-sm font-medium"
                                    style="color: var(--text-primary);">
                                    <span class="inline-flex items-center">
                                        <span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span>
                                        Hari Kritis
                                    </span>
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="critical_days" id="critical_days" min="1" max="365"
                                        value="{{ old('critical_days', $settings['critical_days']) }}"
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border @error('critical_days') border-red-500 @enderror"
                                        style="background-color: var(--card-bg); color: var(--text-primary);">
                                </div>
                                <p class="mt-1 text-xs" style="color: var(--text-secondary);">Status <strong>Kritis</strong>
                                    jika ≤ {{ $settings['critical_days'] }} hari</p>
                                @error('critical_days')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Warning Days -->
                            <div>
                                <label for="warning_days" class="block text-sm font-medium"
                                    style="color: var(--text-primary);">
                                    <span class="inline-flex items-center">
                                        <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                                        Hari Peringatan
                                    </span>
                                </label>
                                <div class="mt-1">
                                    <input type="number" name="warning_days" id="warning_days" min="1" max="365"
                                        value="{{ old('warning_days', $settings['warning_days']) }}"
                                        class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border @error('warning_days') border-red-500 @enderror"
                                        style="background-color: var(--card-bg); color: var(--text-primary);">
                                </div>
                                <p class="mt-1 text-xs" style="color: var(--text-secondary);">Status
                                    <strong>Peringatan</strong> jika ≤ {{ $settings['warning_days'] }} hari</p>
                                @error('warning_days')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Status -->
                <div class="shadow rounded-lg mb-6 overflow-hidden border"
                    style="background-color: var(--card-bg); border-color: var(--border-primary);">
                    <div class="px-6 py-4 border-b"
                        style="border-color: var(--border-primary); background-color: var(--hover-bg);">
                        <h3 class="text-lg font-medium" style="color: var(--text-primary);">Preview Status</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Gambaran range hari untuk setiap status.
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div class="p-4 rounded-lg bg-gray-800 text-white text-center">
                                <div class="text-sm font-medium">Kadaluarsa</div>
                                <div class="text-xs mt-1">≤ 0 hari</div>
                            </div>
                            <div class="p-4 rounded-lg bg-red-500 text-white text-center">
                                <div class="text-sm font-medium">Urgent</div>
                                <div class="text-xs mt-1" id="urgent_range">1 - {{ $settings['urgent_days'] }} hari</div>
                            </div>
                            <div class="p-4 rounded-lg bg-orange-500 text-white text-center">
                                <div class="text-sm font-medium">Kritis</div>
                                <div class="text-xs mt-1" id="critical_range">{{ $settings['urgent_days'] + 1 }} -
                                    {{ $settings['critical_days'] }} hari</div>
                            </div>
                            <div class="p-4 rounded-lg bg-yellow-500 text-white text-center">
                                <div class="text-sm font-medium">Peringatan</div>
                                <div class="text-xs mt-1" id="warning_range">{{ $settings['critical_days'] + 1 }} -
                                    {{ $settings['warning_days'] }} hari</div>
                            </div>
                            <div class="p-4 rounded-lg bg-green-500 text-white text-center">
                                <div class="text-sm font-medium">Aman</div>
                                <div class="text-xs mt-1" id="safe_range">> {{ $settings['warning_days'] }} hari</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Settings Display -->
                <div class="shadow rounded-lg mb-6 overflow-hidden border"
                    style="background-color: var(--card-bg); border-color: var(--border-primary);">
                    <div class="px-6 py-4 border-b"
                        style="border-color: var(--border-primary); background-color: var(--hover-bg);">
                        <h3 class="text-lg font-medium" style="color: var(--text-primary);">Pengaturan Saat Ini</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center p-4 rounded-lg border"
                                style="border-color: var(--border-primary);">
                                <div class="p-3 bg-red-100 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium" style="color: var(--text-primary);">Hari Urgent</p>
                                    <p class="text-2xl font-bold" style="color: var(--text-primary);">
                                        {{ $settings['urgent_days'] }} <span class="text-sm font-normal"
                                            style="color: var(--text-secondary);">hari</span></p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 rounded-lg border"
                                style="border-color: var(--border-primary);">
                                <div class="p-3 bg-orange-100 rounded-lg">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium" style="color: var(--text-primary);">Hari Kritis</p>
                                    <p class="text-2xl font-bold" style="color: var(--text-primary);">
                                        {{ $settings['critical_days'] }} <span class="text-sm font-normal"
                                            style="color: var(--text-secondary);">hari</span></p>
                                </div>
                            </div>
                            <div class="flex items-center p-4 rounded-lg border"
                                style="border-color: var(--border-primary);">
                                <div class="p-3 bg-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium" style="color: var(--text-primary);">Hari Peringatan</p>
                                    <p class="text-2xl font-bold" style="color: var(--text-primary);">
                                        {{ $settings['warning_days'] }} <span class="text-sm font-normal"
                                            style="color: var(--text-secondary);">hari</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pb-8">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Update preview when input changes
        document.getElementById('urgent_days').addEventListener('input', updatePreview);
        document.getElementById('critical_days').addEventListener('input', updatePreview);
        document.getElementById('warning_days').addEventListener('input', updatePreview);

        function updatePreview() {
            const urgentDays = parseInt(document.getElementById('urgent_days').value) || 0;
            const criticalDays = parseInt(document.getElementById('critical_days').value) || 0;
            const warningDays = parseInt(document.getElementById('warning_days').value) || 0;

            if (urgentDays > 0 && criticalDays > urgentDays && warningDays > criticalDays) {
                document.getElementById('urgent_range').textContent = `1 - ${urgentDays} hari`;
                document.getElementById('critical_range').textContent = `${urgentDays + 1} - ${criticalDays} hari`;
                document.getElementById('warning_range').textContent = `${criticalDays + 1} - ${warningDays} hari`;
                document.getElementById('safe_range').textContent = `> ${warningDays} hari`;
            }
        }

        updatePreview();
    </script>
@endsection