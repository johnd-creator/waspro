@extends('layouts.app')

@section('title', 'Document & Upload Settings')

@section('content')
    <div class="min-h-screen py-8" style="background-color: var(--bg-primary);">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">Document & Upload Settings</h1>
                <p class="text-sm" style="color: var(--text-secondary);">Konfigurasi batasan file upload dan persyaratan
                    dokumen.</p>
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

            <form action="{{ route('upload-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- File Limits -->
                <div class="shadow rounded-lg mb-6 overflow-hidden border"
                    style="background-color: var(--card-bg); border-color: var(--border-primary);">
                    <div class="px-6 py-4 border-b"
                        style="border-color: var(--border-primary); background-color: var(--hover-bg);">
                        <h3 class="text-lg font-medium" style="color: var(--text-primary);">Batasan File</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Ukuran dan tipe file yang diizinkan sistem.
                        </p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Max File Size -->
                        <div>
                            <label for="max_file_size_kb" class="block text-sm font-medium"
                                style="color: var(--text-primary);">Maksimal Ukuran File (KB)</label>
                            <div class="mt-1 relative rounded-md shadow-sm max-w-xs">
                                <input type="number" name="max_file_size_kb" id="max_file_size_kb" min="100" max="51200"
                                    value="{{ $settings['max_file_size_kb'] }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                    style="background-color: var(--card-bg); color: var(--text-primary);">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="sm:text-sm" style="color: var(--text-secondary);">KB</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm" style="color: var(--text-secondary);">10240 KB = 10 MB. Maksimum yang
                                disarankan 50 MB.</p>
                        </div>

                        <!-- Allowed Extensions -->
                        <div>
                            <label for="allowed_extensions" class="block text-sm font-medium"
                                style="color: var(--text-primary);">Ekstensi yang Diizinkan</label>
                            <div class="mt-1">
                                @php
                                    $extensions = $settings['allowed_extensions'];
                                    if (is_array($extensions)) {
                                        $extensionsString = implode(', ', $extensions);
                                    } else {
                                        $extensionsString = is_string($extensions) ? $extensions : '';
                                    }
                                @endphp
                                <input type="text" name="allowed_extensions" id="allowed_extensions"
                                    value="{{ $extensionsString }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                    style="background-color: var(--card-bg); color: var(--text-primary);">
                            </div>
                            <p class="mt-2 text-sm" style="color: var(--text-secondary);">Pisahkan dengan koma (contoh: pdf,
                                doc, jpg, png).</p>
                        </div>
                    </div>
                </div>

                <!-- Upload Requirements -->
                <div class="shadow rounded-lg mb-6 overflow-hidden border"
                    style="background-color: var(--card-bg); border-color: var(--border-primary);">
                    <div class="px-6 py-4 border-b"
                        style="border-color: var(--border-primary); background-color: var(--hover-bg);">
                        <h3 class="text-lg font-medium" style="color: var(--text-primary);">Persyaratan Dokumen</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Kapan dokumen wajib diupload.</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Require for Transport -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="require_document_for_transport" name="require_document_for_transport"
                                    type="checkbox" value="1" {{ $settings['require_document_for_transport'] ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="require_document_for_transport" class="font-medium"
                                    style="color: var(--text-primary);">Wajibkan Dokumen Saat Pengangkutan</label>
                                <p style="color: var(--text-secondary);">Jika aktif, status tidak bisa diubah ke "Diangkut"
                                    tanpa melampirkan bukti dokumen (manifest).</p>
                            </div>
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