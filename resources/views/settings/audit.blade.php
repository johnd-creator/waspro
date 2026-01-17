@extends('layouts.app')

@section('content')
    <div class="min-h-screen p-4 sm:p-6 lg:p-8"
        style="background: linear-gradient(to bottom right, var(--bg-primary), var(--bg-tertiary), var(--bg-secondary));">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold tracking-tight" style="color: var(--text-primary);">
                    Audit Log Pengaturan
                </h1>
                <p class="mt-2 text-sm font-medium" style="color: var(--text-secondary);">
                    Riwayat perubahan pengaturan sistem
                </p>
            </div>
            <a href="{{ route('application-settings.index') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Kembali ke Pengaturan
            </a>
        </div>

        <div class="rounded-2xl shadow-sm mb-8"
            style="background-color: var(--card-bg); border: 1px solid var(--border-primary);">
            <div class="p-0 overflow-hidden rounded-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead style="background-color: var(--border-primary);">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                    Waktu</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                    User</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                    Aksi</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                    Kategori</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                    Setting Key</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                    Perubahan (Lama &rarr; Baru)</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="border-color: var(--border-primary);">
                            @forelse($logs as $log)
                                <tr class="transition-colors duration-200"
                                    onmouseover="this.style.backgroundColor='var(--hover-bg)'"
                                    onmouseout="this.style.backgroundColor='transparent'">
                                    <td class="px-6 py-4 text-sm" style="color: var(--text-secondary);">
                                        {{ $log->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 text-sm" style="color: var(--text-primary);">
                                        {{ $log->user->nama_lengkap ?? 'System' }}</td>
                                    <td class="px-6 py-4 text-sm">
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
                                    </td>
                                    <td class="px-6 py-4 text-sm" style="color: var(--text-secondary);">
                                        {{ $log->setting_category }}</td>
                                    <td class="px-6 py-4 text-sm font-mono" style="color: var(--text-primary);">
                                        {{ $log->setting_key }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="text-red-500 font-mono text-xs bg-red-50 px-1 rounded max-w-[150px] truncate"
                                                title="{{ $log->old_value_text }}">{{ Str::limit($log->old_value_text ?? '-', 20) }}</span>
                                            <i class="fas fa-arrow-right text-gray-400 text-xs"></i>
                                            <span
                                                class="text-green-500 font-mono text-xs bg-green-50 px-1 rounded max-w-[150px] truncate"
                                                title="{{ $log->new_value_text }}">{{ Str::limit($log->new_value_text ?? '-', 20) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('settings.audit.show', $log->id) }}"
                                            class="text-blue-600 hover:text-blue-900">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-history mb-4 text-4xl" style="color: var(--text-tertiary);"></i>
                                            <h3 class="mb-2 text-lg font-medium" style="color: var(--text-primary);">Belum ada
                                                data audit</h3>
                                            <p style="color: var(--text-secondary);">Belum ada perubahan pengaturan yang
                                                tercatat.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t" style="border-color: var(--border-primary);">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection