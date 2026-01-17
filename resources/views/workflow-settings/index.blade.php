@extends('layouts.app')

@section('title', 'Workflow & Approval Settings')

@section('content')
    <div class="min-h-screen py-8" style="background-color: var(--bg-primary);">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">Workflow & Approval Settings</h1>
                <p class="text-sm" style="color: var(--text-secondary);">Konfigurasi alur kerja persetujuan dan batasan log
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

            <form action="{{ route('workflow-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Approval Settings -->
                <div class="shadow rounded-lg mb-6 overflow-hidden border"
                    style="background-color: var(--card-bg); border-color: var(--border-primary);">
                    <div class="px-6 py-4 border-b"
                        style="border-color: var(--border-primary); background-color: var(--hover-bg);">
                        <h3 class="text-lg font-medium" style="color: var(--text-primary);">Approval Logic</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Aturan dasar untuk persetujuan log limbah.
                        </p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Approval Required -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="approval_required" name="approval_required" type="checkbox" value="1" {{ $settings['approval_required'] ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="approval_required" class="font-medium"
                                    style="color: var(--text-primary);">Wajibkan Approval Supervisor</label>
                                <p style="color: var(--text-secondary);">Jika tidak dicentang, log baru akan langsung
                                    disetujui (Approved) saat dibuat.</p>
                            </div>
                        </div>

                        <!-- Auto Approve Operator -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="auto_approve_operator" name="auto_approve_operator" type="checkbox" value="1" {{ $settings['auto_approve_operator'] ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="auto_approve_operator" class="font-medium"
                                    style="color: var(--text-primary);">Auto-Approve Operator Terpercaya</label>
                                <p style="color: var(--text-secondary);">Otomatis setujui log yang dibuat oleh user dengan
                                    role Operator (jika Approval Required aktif).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Constraints & Timeouts -->
                <div class="shadow rounded-lg mb-6 overflow-hidden border"
                    style="background-color: var(--card-bg); border-color: var(--border-primary);">
                    <div class="px-6 py-4 border-b"
                        style="border-color: var(--border-primary); background-color: var(--hover-bg);">
                        <h3 class="text-lg font-medium" style="color: var(--text-primary);">Batasan & Timeout</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Pengaturan waktu dan validasi.</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Approval Timeout -->
                        <div>
                            <label for="approval_timeout_hours" class="block text-sm font-medium"
                                style="color: var(--text-primary);">Timeout Approval (Jam)</label>
                            <div class="mt-1 relative rounded-md shadow-sm max-w-xs">
                                <input type="number" name="approval_timeout_hours" id="approval_timeout_hours" min="1"
                                    max="720" value="{{ $settings['approval_timeout_hours'] }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-2 border"
                                    style="background-color: var(--card-bg); color: var(--text-primary);">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="sm:text-sm" style="color: var(--text-secondary);">Jam</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm" style="color: var(--text-secondary);">Log dengan status "Pending" yang
                                melebihi batas waktu ini akan otomatis DITOLAK oleh sistem.</p>
                        </div>

                        <!-- Require Rejection Reason -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="require_rejection_reason" name="require_rejection_reason" type="checkbox"
                                    value="1" {{ $settings['require_rejection_reason'] ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="require_rejection_reason" class="font-medium"
                                    style="color: var(--text-primary);">Wajibkan Alasan Penolakan</label>
                                <p style="color: var(--text-secondary);">Supervisor harus mengisi alasan saat menolak log
                                    limbah.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="shadow rounded-lg mb-6 overflow-hidden border"
                    style="background-color: var(--card-bg); border-color: var(--border-primary);">
                    <div class="px-6 py-4 border-b"
                        style="border-color: var(--border-primary); background-color: var(--hover-bg);">
                        <h3 class="text-lg font-medium" style="color: var(--text-primary);">Izin Edit & Hapus</h3>
                        <p class="text-sm" style="color: var(--text-secondary);">Kontrol atas log yang sudah disetujui.</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Mengizinkan edit/hapus log yang sudah disetujui dapat mempengaruhi integritas data
                                        dan laporan yang sudah digenerate.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Approved Logs -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="edit_approved_logs" name="edit_approved_logs" type="checkbox" value="1" {{ $settings['edit_approved_logs'] ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="edit_approved_logs" class="font-medium"
                                    style="color: var(--text-primary);">Izinkan Edit Log Disetujui</label>
                                <p style="color: var(--text-secondary);">Bolehkan user mengedit data log meskipun statusnya
                                    sudah Approved.</p>
                            </div>
                        </div>

                        <!-- Delete Approved Logs -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="delete_approved_logs" name="delete_approved_logs" type="checkbox" value="1" {{ $settings['delete_approved_logs'] ? 'checked' : '' }}
                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="delete_approved_logs" class="font-medium"
                                    style="color: var(--text-primary);">Izinkan Hapus Log Disetujui</label>
                                <p style="color: var(--text-secondary);">Bolehkan user menghapus data log meskipun statusnya
                                    sudah Approved.</p>
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