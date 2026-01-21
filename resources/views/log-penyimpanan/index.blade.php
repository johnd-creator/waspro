@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    @if(session('success'))
        <div class="mb-6 flex items-center rounded-xl border p-4"
            style="background-color: var(--accent-bg-secondary); border-color: var(--border-secondary); color: var(--accent-secondary);"
            role="alert" data-auto-dismiss="2500">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto transition-opacity hover:opacity-75" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="mb-6 rounded-2xl border shadow-sm"
        style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="flex items-center justify-between border-b px-6 py-6" style="border-color: var(--border-primary);">
            <div>
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Log Penyimpanan Limbah</h1>
                <p style="color: var(--text-secondary);">Kelola dan pantau data penyimpanan limbah dengan mudah</p>
            </div>
            <a href="{{ route('log-penyimpanan.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700">
                <i class="fas fa-plus-circle mr-2"></i>
                <span>Tambah Log</span>
            </a>
        </div>
        <div class="px-6 py-6">
            <form method="GET" action="{{ route('log-penyimpanan.index') }}" class="flex flex-col gap-4">
                <div class="flex flex-col gap-4 md:flex-row items-center">
                    <div class="relative flex-1">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fas fa-search" style="color: var(--text-tertiary);"></i>
                        </div>
                        <input type="text"
                            class="w-full rounded-xl border py-3 pl-12 pr-4 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                            name="search" value="{{ request('search') }}"
                            placeholder="Cari kode identitas, jenis limbah, perusahaan, uraian pekerjaan, penginput...">
                    </div>

                    @if(isset($isSuperAdmin) && $isSuperAdmin && isset($unitPembangkit) && $unitPembangkit->count() > 0)
                        <div class="w-full md:w-64 relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <i class="fas fa-industry" style="color: var(--text-tertiary);"></i>
                            </div>
                            <select name="search_unit_id"
                                class="w-full rounded-xl border py-3 pl-10 pr-8 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500 appearance-none outline-none cursor-pointer"
                                style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                onchange="this.form.submit()">
                                <option value="">Semua Unit</option>
                                @foreach($unitPembangkit as $unit)
                                    <option value="{{ $unit->unit_id }}" {{ request('search_unit_id') == $unit->unit_id ? 'selected' : '' }}>
                                        {{ $unit->nama_unit }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                <i class="fas fa-chevron-down text-xs" style="color: var(--text-tertiary);"></i>
                            </div>
                        </div>
                    @endif

                    <div class="flex gap-3">
                        <button type="submit"
                            class="rounded-xl bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-700">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>
                        @if(request()->hasAny(['search', 'search_status', 'search_unit_id']))
                            <a href="{{ route('log-penyimpanan.index') }}"
                                class="rounded-xl px-6 py-3 font-medium transition-colors"
                                style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    @php($baseQuery = request()->except('page', 'search_status'))
                    <a href="{{ route('log-penyimpanan.index', array_merge($baseQuery, ['search_status' => ''])) }}"
                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors {{ !request('search_status') ? 'ring-2 ring-blue-500' : '' }}"
                        style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">
                        Semua
                    </a>
                    <a href="{{ route('log-penyimpanan.index', array_merge($baseQuery, ['search_status' => 'Tersimpan'])) }}"
                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors {{ request('search_status') == 'Tersimpan' ? 'ring-2 ring-blue-500' : '' }}"
                        style="background-color: var(--accent-bg); color: var(--accent-primary);">
                        Tersimpan
                    </a>
                    <a href="{{ route('log-penyimpanan.index', array_merge($baseQuery, ['search_status' => 'Kadaluarsa'])) }}"
                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors {{ request('search_status') == 'Kadaluarsa' ? 'ring-2 ring-blue-500' : '' }}"
                        style="background-color: var(--danger-bg); color: var(--danger-primary);">
                        Kadaluarsa
                    </a>
                    <a href="{{ route('log-penyimpanan.index', array_merge($baseQuery, ['search_status' => 'Diangkut'])) }}"
                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors {{ request('search_status') == 'Diangkut' ? 'ring-2 ring-blue-500' : '' }}"
                        style="background-color: var(--accent-bg-secondary); color: var(--accent-secondary);">
                        Diangkut
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border shadow-sm"
        style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Tanggal Masuk</th>
                        <th class="min-w-[150px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Jenis Limbah</th>
                        <th class="min-w-[180px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Uraian Pekerjaan</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Jumlah (Kg)</th>
                        <th class="min-w-[100px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Status</th>
                        <th class="min-w-[140px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Sisa Waktu</th>
                        <th class="min-w-[180px] px-6 py-4 text-left text-sm font-semibold"
                            style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-primary);">
                    @forelse($logs as $index => $log)
                    <tr class="transition-colors duration-200 border-b" style="border-color: var(--border-primary);"
                        onmouseover="this.style.backgroundColor='var(--hover-bg)'"
                        onmouseout="this.style.backgroundColor='transparent'">
                        <td class="px-4 py-4 text-center text-sm font-medium" style="color: var(--text-secondary);">
                            {{ $logs->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium" style="color: var(--text-secondary);">
                            {{ \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold" style="color: var(--text-primary);">
                                {{ $log->jenisLimbah->nama_limbah ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm leading-relaxed" style="color: var(--text-secondary);">
                            <div class="max-w-[180px]" title="{{ $log->uraian_pekerjaan ?? '-' }}">
                                {{ Str::limit($log->uraian_pekerjaan ?? '-', 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-bold" style="color: var(--text-primary);">
                            <span class="rounded-lg px-3 py-1"
                                style="background-color: var(--accent-bg); color: var(--accent-primary);">{{ number_format($log->jumlah_limbah_masuk, 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$log->status_log" />
                        </td>
                        <td class="px-6 py-4">
                            @php($daysLeft = $log->getDaysUntilExpiry())
                            @if(($log->status_log->value ?? $log->status_log) == 'Tersimpan' && $daysLeft !== null)
                                @if($daysLeft <= 0)
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                        style="background-color: var(--danger-bg); color: var(--danger-primary);">Kadaluarsa</span>
                                @elseif($daysLeft <= 7)
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                        style="background-color: var(--accent-bg-secondary); color: var(--accent-secondary);">H-{{ $daysLeft }}</span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                        style="background-color: var(--accent-bg); color: var(--accent-primary);">H-{{ $daysLeft }}</span>
                                @endif
                            @elseif(($log->status_log->value ?? $log->status_log) == 'Kadaluarsa')
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                    style="background-color: var(--danger-bg); color: var(--danger-primary);">Kadaluarsa</span>
                            @else
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                    style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <x-action-buttons :view-route="route('log-penyimpanan.show', $log)"
                                :edit-route="route('log-penyimpanan.edit', $log)"
                                :delete-route="route('log-penyimpanan.destroy', $log)"
                                delete-message="Anda yakin ingin menghapus log ini?"
                                :item-title="$log->jenisLimbah->nama_limbah ?? ''" />
                        </td>
                    </tr>
                    @empty
                    <x-empty-state icon="fas fa-clipboard-list" title="Belum ada data"
                        description="Tidak ada data log penyimpanan limbah yang tersedia saat ini." />
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="border-t p-4" style="border-color: var(--border-primary); background-color: var(--card-bg);">
                {{ $logs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection