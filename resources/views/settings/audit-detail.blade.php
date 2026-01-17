@extends('layouts.app')

@section('content')
    <div class="min-h-screen p-4 sm:p-6 lg:p-8"
        style="background: linear-gradient(to bottom right, var(--bg-primary), var(--bg-tertiary), var(--bg-secondary));">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold tracking-tight" style="color: var(--text-primary);">
                    Detail Audit Log
                </h1>
                <p class="mt-2 text-sm font-medium" style="color: var(--text-secondary);">
                    ID Audit: #{{ $log->id }}
                </p>
            </div>
            <a href="{{ route('settings.audit.index') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Info Card -->
            <div class="rounded-2xl shadow-sm p-6"
                style="background-color: var(--card-bg); border: 1px solid var(--border-primary);">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-bold" style="color: var(--text-primary);">Informasi Umum</h3>
                </div>

                <dl class="space-y-4">
                    <div class="pb-4 border-b border-gray-100">
                        <dt class="text-sm font-medium" style="color: var(--text-secondary);">Waktu Kejadian</dt>
                        <dd class="mt-1 text-sm font-semibold" style="color: var(--text-primary);">
                            {{ $log->created_at->format('d F Y, H:i:s') }}</dd>
                    </div>
                    <div class="pb-4 border-b border-gray-100">
                        <dt class="text-sm font-medium" style="color: var(--text-secondary);">User</dt>
                        <dd class="mt-1 text-sm font-semibold" style="color: var(--text-primary);">
                            {{ $log->user->nama_lengkap ?? 'System' }}
                            <span class="text-xs font-normal text-gray-500 ml-1">(ID: {{ $log->user_id ?? 'N/A' }})</span>
                        </dd>
                    </div>
                    <div class="pb-4 border-b border-gray-100">
                        <dt class="text-sm font-medium" style="color: var(--text-secondary);">Aksi</dt>
                        <dd class="mt-1">
                            @if($log->action == 'create')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Create</span>
                            @elseif($log->action == 'update')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Update</span>
                            @elseif($log->action == 'delete')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Delete</span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $log->action }}</span>
                            @endif
                        </dd>
                    </div>
                    <div class="pb-4 border-b border-gray-100">
                        <dt class="text-sm font-medium" style="color: var(--text-secondary);">IP Address</dt>
                        <dd class="mt-1 text-sm font-mono" style="color: var(--text-primary);">{{ $log->ip_address ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium" style="color: var(--text-secondary);">User Agent</dt>
                        <dd class="mt-1 text-xs font-mono text-gray-500 break-all">{{ $log->user_agent ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Changes Card -->
            <div class="rounded-2xl shadow-sm p-6"
                style="background-color: var(--card-bg); border: 1px solid var(--border-primary);">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i class="fas fa-edit text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-bold" style="color: var(--text-primary);">Detail Perubahan</h3>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium" style="color: var(--text-secondary);">Kategori Setting</dt>
                            <dd class="mt-1 text-sm font-mono font-semibold" style="color: var(--text-primary);">
                                {{ $log->setting_category ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium" style="color: var(--text-secondary);">Setting Key</dt>
                            <dd class="mt-1 text-sm font-mono font-semibold" style="color: var(--text-primary);">
                                {{ $log->setting_key ?? 'N/A' }}</dd>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <h4 class="text-sm font-medium" style="color: var(--text-primary);">Perbandingan Nilai</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 bg-red-50 rounded-xl border border-red-100">
                                <label class="block text-xs font-bold text-red-700 uppercase mb-2">Sebelum Mengubah</label>
                                <pre
                                    class="text-sm text-red-900 whitespace-pre-wrap font-mono">{{ $log->old_value_text ?? '(Tidak ada nilai sebelumnya)' }}</pre>
                            </div>
                            <div class="p-4 bg-green-50 rounded-xl border border-green-100">
                                <label class="block text-xs font-bold text-green-700 uppercase mb-2">Sesudah
                                    Mengubah</label>
                                <pre
                                    class="text-sm text-green-900 whitespace-pre-wrap font-mono">{{ $log->new_value_text ?? '(Nilai dihapus)' }}</pre>
                            </div>
                        </div>
                    </div>

                    @if($log->table_name !== 'application_settings')
                        <div class="mt-4 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                            <p class="text-xs text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Log ini tercatat pada tabel <strong>{{ $log->table_name }}</strong>, bukan tabel settings
                                standar.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection