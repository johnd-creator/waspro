@extends('layouts.app')

@section('title', 'Pengangkutan Limbah')

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
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Pengangkutan Limbah</h1>
                <p style="color: var(--text-secondary);">Kelola proses pengangkutan limbah dengan mudah</p>
            </div>
            <a href="{{ route('pengangkutan-limbah.diangkut') }}"
                class="inline-flex items-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700">
                <i class="fas fa-truck mr-2"></i>
                <span>Limbah Diangkut</span>
            </a>
        </div>
        <div class="px-6 py-6">
            <form method="GET" action="{{ route('pengangkutan-limbah.index') }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fas fa-search" style="color: var(--text-tertiary);"></i>
                        </div>
                        <input type="text" name="kode_identitas" value="{{ request('kode_identitas') }}"
                            placeholder="Cari Kode Identitas..."
                            class="w-full rounded-xl border py-3 pl-12 pr-4 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" />
                    </div>
                </div>
                <div>
                    <select name="status_diangkut"
                        class="w-full rounded-xl border px-3 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                        style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                        <option value="">Semua Status</option>
                        <option value="0" {{ request('status_diangkut') == '0' ? 'selected' : '' }}>Belum Diangkut</option>
                        <option value="1" {{ request('status_diangkut') == '1' ? 'selected' : '' }}>Sudah Diangkut</option>
                    </select>
                </div>
                <div class="flex items-stretch gap-3">
                    <button type="submit"
                        class="rounded-xl bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-700">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    @if(request()->hasAny(['kode_identitas', 'status_diangkut']))
                        <a href="{{ route('pengangkutan-limbah.index') }}"
                            class="rounded-xl px-6 py-3 font-medium transition-colors"
                            style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border shadow-sm"
        style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <form id="bulk-angkut-form" method="POST" action="{{ route('pengangkutan-limbah.bulk-approve') }}">
            @csrf
            <div class="overflow-x-auto">
                <table class="min-w-full w-full">
                    <thead style="background-color: var(--border-primary);">
                        <tr>
                            <th class="w-12 px-4 py-4 text-left text-sm font-semibold"
                                style="color: var(--text-secondary);">
                                <input type="checkbox" id="select-all"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    style="background-color: var(--input-bg); border-color: var(--border-secondary);">
                            </th>
                            <th class="w-16 px-4 py-4 text-left text-sm font-semibold"
                                style="color: var(--text-secondary);">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                Tanggal Masuk</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                Jenis Limbah</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                Uraian Pekerjaan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                Jumlah (Kg)</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                Sisa Waktu</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logPenyimpanan as $key => $log)
                        <tr class="border-b" style="border-color: var(--border-primary);"
                            onmouseover="this.style.backgroundColor='var(--hover-bg)'"
                            onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="selected_logs[]" value="{{ $log->log_id ?? $log->id }}"
                                    class="log-checkbox h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    style="background-color: var(--input-bg); border-color: var(--border-secondary);">
                            </td>
                            <td class="px-4 py-3 text-center text-sm font-medium" style="color: var(--text-secondary);">
                                {{ $logPenyimpanan->firstItem() + $key }}</td>
                            <td class="px-6 py-3 text-sm" style="color: var(--text-secondary);">
                                {{ \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d M Y') }}</td>
                            <td class="px-6 py-3 text-sm" style="color: var(--text-secondary);">
                                {{ $log->jenisLimbah->nama_limbah }}</td>
                            <td class="px-6 py-3 text-sm leading-relaxed" style="color: var(--text-secondary);">
                                <div class="max-w-[180px]" title="{{ $log->uraian_pekerjaan ?? '-' }}">
                                    {{ Str::limit($log->uraian_pekerjaan ?? '-', 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-3 text-right text-sm font-bold" style="color: var(--text-primary);">
                                <span class="rounded-lg px-3 py-1"
                                    style="background-color: var(--accent-bg); color: var(--accent-primary);">{{ number_format($log->jumlah_limbah_masuk, 2) }}</span>
                            </td>
                            <td class="px-6 py-3">
                                @if(strtolower($log->status_log) === 'diangkut')
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                        style="background-color: var(--accent-bg-secondary); color: var(--accent-secondary);">Sudah Diangkut</span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                        style="background-color: var(--danger-bg); color: var(--danger-primary);">Belum Diangkut</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                @php($daysLeft = $log->getDaysUntilExpiry())
                                @if($log->status_log == 'Tersimpan' && $daysLeft !== null)
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
                                @elseif($log->status_log == 'Kadaluarsa')
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                        style="background-color: var(--danger-bg); color: var(--danger-primary);">Kadaluarsa</span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                        style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                @if(strtolower($log->status_log) !== 'diangkut')
                                    <button type="button"
                                        class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700 angkut-btn transition-colors"
                                        data-id="{{ $log->log_id ?? $log->id }}">
                                        <i class="fas fa-truck mr-2"></i> Angkut
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full"
                                         style="background-color: var(--hover-bg);">
                                        <i class="fas fa-truck-loading text-4xl"
                                           style="color: var(--text-tertiary);"></i>
                                    </div>
                                    <h3 class="mb-2 text-lg font-medium" style="color: var(--text-primary);">Tidak ada data</h3>
                                    <p class="text-sm" style="color: var(--text-secondary);">Tidak ada data pengangkutan limbah yang tersedia.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between gap-3 p-4"
                style="background-color: var(--card-bg); border-top: 1px solid var(--border-primary);">
                <button type="submit"
                    class="inline-flex items-center rounded-lg bg-emerald-600 px-5 py-2.5 font-medium text-white hover:bg-emerald-700 transition-colors"
                    id="bulk-angkut-btn">
                    <i class="fas fa-check mr-2"></i> Angkut Terpilih
                </button>
                <div>
                    {{ $logPenyimpanan->links() }}
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.getElementById('select-all').onclick = function () {
            var checkboxes = document.querySelectorAll('.log-checkbox');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }

        document.querySelectorAll('.angkut-btn').forEach(button => {
            button.addEventListener('click', function () {
                let logId = this.getAttribute('data-id');
                let form = document.getElementById('bulk-angkut-form');
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_logs[]';
                input.value = logId;
                form.appendChild(input);
                form.submit();
            });
        });
    </script>
@endpush
@endsection
