@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    @if(session('success'))
        <div class="mb-6 flex items-center rounded-xl border p-4" style="background-color: var(--accent-bg-secondary); border-color: var(--border-secondary); color: var(--accent-secondary);" role="alert" data-auto-dismiss="2500">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto transition-opacity hover:opacity-75" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-6 rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="flex items-center justify-between border-b px-6 py-6" style="border-color: var(--border-primary);">
            <div>
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Log Penyimpanan Limbah</h1>
                <p style="color: var(--text-secondary);">Kelola dan pantau data penyimpanan limbah dengan mudah</p>
            </div>
            <div>
                <a href="{{ route('log-penyimpanan.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span>Tambah Log</span>
                </a>
            </div>
        </div>
        <!-- Search & Filter Section -->
        <div class="px-6 py-6">
            <form method="GET" action="{{ route('log-penyimpanan.index') }}" class="flex flex-col gap-4">
                <div class="flex flex-col gap-4 md:flex-row">
                    <div class="relative flex-1">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fas fa-search" style="color: var(--text-tertiary);"></i>
                        </div>
                        <input type="text" class="w-full rounded-xl border py-3 pl-12 pr-4 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                               style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                               name="search_kode_identitas"
                               value="{{ request('search_kode_identitas') }}"
                               placeholder="Cari Kode Identitas Limbah">
                    </div>
                    <input type="text" name="search_jenis" value="{{ request('search_jenis') }}" placeholder="Jenis/Kode Limbah"
                           class="w-full md:w-60 rounded-xl border px-4 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                           style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                    <input type="text" name="search_perusahaan" value="{{ request('search_perusahaan') }}" placeholder="Perusahaan Penghasil"
                           class="w-full md:w-60 rounded-xl border px-4 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                           style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                    @if(auth()->user() && method_exists(auth()->user(), 'isSuperAdmin') && auth()->user()->isSuperAdmin())
                        <input type="text" name="search_penginput" value="{{ request('search_penginput') }}" placeholder="Penginput Data (nama/email)"
                               class="w-full md:w-60 rounded-xl border px-4 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                               style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                    @endif
                    <select name="search_status" class="w-full md:w-52 rounded-xl border px-4 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                            style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                        <option value="">Semua Status</option>
                        <option value="Tersimpan" {{ request('search_status') == 'Tersimpan' ? 'selected' : '' }}>Tersimpan</option>
                        <option value="Kadaluarsa" {{ request('search_status') == 'Kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                        <option value="Diangkut" {{ request('search_status') == 'Diangkut' ? 'selected' : '' }}>Diangkut</option>
                    </select>
                </div>

                <div class="flex flex-col gap-4 md:flex-row items-center">
                    <div class="flex items-center gap-3">
                        <div class="text-sm font-medium" style="color: var(--text-secondary);">Rentang Tanggal Masuk</div>
                        <input type="date" name="search_tanggal_mulai" value="{{ request('search_tanggal_mulai') }}"
                               class="rounded-xl border px-4 py-2 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                               style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                        <span style="color: var(--text-secondary);">s/d</span>
                        <input type="date" name="search_tanggal_akhir" value="{{ request('search_tanggal_akhir') }}"
                               class="rounded-xl border px-4 py-2 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                               style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                    </div>


                    <div class="ml-auto flex gap-3">
                        <button type="submit" class="rounded-xl bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-700">
                            <i class="fas fa-filter mr-2"></i>Terapkan Filter
                        </button>
                        <a href="{{ route('log-penyimpanan.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="rounded-xl bg-rose-600 px-6 py-3 font-medium text-white transition-colors hover:bg-rose-700">
                            <i class="fas fa-file-pdf mr-2"></i>Export PDF
                        </a>
                        <a href="{{ route('log-penyimpanan.export', array_merge(request()->all(), ['format' => 'excel'])) }}" class="rounded-xl bg-emerald-600 px-6 py-3 font-medium text-white transition-colors hover:bg-emerald-700">
                            <i class="fas fa-file-excel mr-2"></i>Export Excel
                        </a>
                        @if(request()->hasAny(['search_kode_identitas','search_jenis','search_perusahaan','search_status','search_tanggal','search_tanggal_mulai','search_tanggal_akhir','search_penginput','expiry_days_min','expiry_days_max']))
                            <a href="{{ route('log-penyimpanan.index') }}" class="rounded-xl px-6 py-3 font-medium transition-colors" style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">
                                <i class="fas fa-times mr-2"></i>Reset
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Quick status tabs -->
                <div class="mt-2 flex flex-wrap gap-2">
                    @php($baseQuery = request()->except('page'))
                    <a href="{{ route('log-penyimpanan.index', array_merge($baseQuery, ['search_status' => ''])) }}"
                       class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium" style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">
                        Semua
                    </a>
                    <a href="{{ route('log-penyimpanan.index', array_merge($baseQuery, ['search_status' => 'Tersimpan'])) }}"
                       class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium" style="background-color: var(--accent-bg); color: var(--accent-primary);">
                        Tersimpan
                    </a>
                    <a href="{{ route('log-penyimpanan.index', array_merge($baseQuery, ['search_status' => 'Kadaluarsa'])) }}"
                       class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium" style="background-color: var(--danger-bg); color: var(--danger-primary);">
                        Kadaluarsa
                    </a>
                    <a href="{{ route('log-penyimpanan.index', array_merge($baseQuery, ['search_status' => 'Diangkut'])) }}"
                       class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium" style="background-color: var(--accent-bg-secondary); color: var(--accent-secondary);">
                        Diangkut
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Log Penyimpanan -->
    <div class="overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">No</th>
                        <th class="min-w-[140px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Kode Identitas</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Tanggal Masuk</th>
                        <th class="min-w-[150px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Jenis Limbah</th>
                        <th class="min-w-[200px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Sumber Limbah</th>
                        <th class="min-w-[120px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Jumlah (Kg)</th>
                        <th class="min-w-[150px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Perusahaan</th>
                        <th class="min-w-[100px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Status</th>
                        <th class="min-w-[140px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Sisa Waktu</th>
                        <th class="min-w-[150px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Penginput Data</th>
                        <th class="min-w-[180px] px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--border-primary);">
                    @forelse($logs as $index => $log)
                        <tr class="transition-colors duration-200 border-b" style="border-color: var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-4 text-center text-sm font-medium" style="color: var(--text-secondary);">{{ $logs->firstItem() + $index }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold" style="color: var(--accent-primary);" title="Kode identitas: {{ $log->kode_identitas ?? 'Belum Ada' }}">{{ $log->kode_identitas ?? 'Belum Ada' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium" style="color: var(--text-secondary);">{{ \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold" style="color: var(--text-primary);">{{ $log->jenisLimbah->nama_limbah ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm leading-relaxed" style="color: var(--text-secondary);">
                                <div class="max-w-xs">{{ Str::limit($log->detail_sumber_limbah, 60) }}</div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-bold" style="color: var(--text-primary);">
                                <span class="rounded-lg px-3 py-1" style="background-color: var(--accent-bg); color: var(--accent-primary);">{{ number_format($log->jumlah_limbah_masuk, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm" style="color: var(--text-secondary);">
                                <div class="max-w-[140px] truncate font-medium" title="{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'N/A' }}">{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($log->status_log == 'Tersimpan')
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--accent-bg); color: var(--accent-primary);">{{ $log->status_log }}</span>
                                @elseif($log->status_log == 'Diangkut')
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--accent-bg-secondary); color: var(--accent-secondary);">{{ $log->status_log }}</span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--danger-bg); color: var(--danger-primary);">{{ $log->status_log }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php($daysLeft = $log->getDaysUntilExpiry())
                                @if($log->status_log == 'Tersimpan' && $daysLeft !== null)
                                    @if($daysLeft <= 0)
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--danger-bg); color: var(--danger-primary);">Kadaluarsa</span>
                                    @elseif($daysLeft <= 7)
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--accent-bg-secondary); color: var(--accent-secondary);">H-{{ $daysLeft }}</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--accent-bg); color: var(--accent-primary);">H-{{ $daysLeft }}</span>
                                    @endif
                                @elseif($log->status_log == 'Kadaluarsa')
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--danger-bg); color: var(--danger-primary);">Kadaluarsa</span>
                                @else
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium" style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium" style="color: var(--text-primary);">{{ $log->penggunaSistem->nama_lengkap ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-1">
                                    <a href="{{ route('log-penyimpanan.show', $log) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--accent-primary); background-color: var(--accent-bg);" onmouseover="this.style.backgroundColor='var(--accent-primary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--accent-bg)'; this.style.color='var(--accent-primary)';" title="Lihat Detail">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('log-penyimpanan.edit', $log) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--accent-secondary); background-color: var(--accent-bg-secondary);" onmouseover="this.style.backgroundColor='var(--accent-secondary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--accent-bg-secondary)'; this.style.color='var(--accent-secondary)';" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <form action="{{ route('log-penyimpanan.destroy', $log) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg transition-colors" style="color: var(--danger-primary); background-color: var(--danger-bg);" onmouseover="this.style.backgroundColor='var(--danger-primary)'; this.style.color='white';" onmouseout="this.style.backgroundColor='var(--danger-bg)'; this.style.color='var(--danger-primary)';" title="Hapus" onclick="return confirm('Anda yakin ingin menghapus log ini?')">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-clipboard-list mb-4 text-6xl" style="color: var(--text-tertiary);"></i>
                                    <h3 class="mb-2 text-lg font-medium" style="color: var(--text-primary);">Belum ada data</h3>
                                    <p style="color: var(--text-secondary);">Tidak ada data log penyimpanan limbah yang tersedia saat ini.</p>
                                </div>
                            </td>
                        </tr>
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
