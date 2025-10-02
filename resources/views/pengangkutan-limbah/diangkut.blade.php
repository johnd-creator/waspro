@extends('layouts.app')

@section('title', 'Limbah Diangkut')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    @if(session('success'))
        <div class="mb-6 flex items-center rounded-xl border p-4" style="background-color: var(--accent-bg-secondary); border-color: var(--border-secondary); color: var(--accent-secondary);">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto transition-opacity hover:opacity-75" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Header & Filters -->
    <div class="mb-6 rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="flex items-center justify-between border-b px-6 py-6" style="border-color: var(--border-primary);">
            <div>
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Limbah Diangkut</h1>
                <p style="color: var(--text-secondary);">Daftar limbah yang sudah diangkut dari penyimpanan</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('pengangkutan-limbah.index') }}" class="inline-flex items-center rounded-xl px-6 py-3 font-medium transition-all duration-200" style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
        <!-- Filter -->
        <div class="px-6 py-6">
            <form method="GET" action="{{ route('pengangkutan-limbah.diangkut') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-2">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <i class="fas fa-search" style="color: var(--text-tertiary);"></i>
                        </div>
                        <input type="text" name="kode_identitas" value="{{ request('kode_identitas') }}" placeholder="Cari Kode Identitas..." class="w-full rounded-xl border py-3 pl-12 pr-4 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" />
                    </div>
                </div>
                <div>
                    <select name="jenis_limbah" class="w-full rounded-xl border px-3 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                        <option value="">Semua Jenis Limbah</option>
                        @foreach($jenisLimbah as $jenis)
                            <option value="{{ $jenis->kode_limbah }}" {{ request('jenis_limbah') == $jenis->kode_limbah ? 'selected' : '' }}>{{ $jenis->nama_limbah }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="perusahaan" class="w-full rounded-xl border px-3 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                        <option value="">Semua Perusahaan</option>
                        @foreach($perusahaan as $p)
                            <option value="{{ $p->perusahaan_id }}" {{ request('perusahaan') == $p->perusahaan_id ? 'selected' : '' }}>{{ $p->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full rounded-xl border px-3 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" />
                </div>
                <div>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="w-full rounded-xl border px-3 py-3 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" />
                </div>
                <div class="col-span-1 flex items-stretch gap-3 md:col-span-2 lg:col-span-2">
                    <button type="submit" class="w-full rounded-xl bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-700">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    @if(request()->hasAny(['kode_identitas','jenis_limbah','perusahaan','tanggal_mulai','tanggal_akhir']))
                        <a href="{{ route('pengangkutan-limbah.diangkut') }}" class="w-full rounded-xl px-6 py-3 text-center font-medium transition-colors" style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="overflow-hidden rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="overflow-x-auto">
            <table class="min-w-full w-full">
                <thead style="background-color: var(--border-primary);">
                    <tr>
                        <th class="w-16 px-4 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Kode Identitas</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Perusahaan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Jenis Limbah</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Tanggal Diangkut</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold" style="color: var(--text-secondary);">Jumlah (Kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logPenyimpanan as $key => $limbah)
                        <tr class="border-b" style="border-color: var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                            <td class="px-4 py-3 text-center text-sm font-medium" style="color: var(--text-secondary);">{{ $logPenyimpanan->firstItem() + $key }}</td>
                            <td class="px-6 py-3 text-sm" style="color: var(--text-primary);">{{ $limbah->kode_identitas }}</td>
                            <td class="px-6 py-3 text-sm" style="color: var(--text-secondary);">{{ optional($limbah->perusahaanPenghasil)->nama_perusahaan ?? optional($limbah->perusahaan)->nama_perusahaan ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm" style="color: var(--text-secondary);">{{ optional($limbah->jenisLimbah)->nama_limbah ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm" style="color: var(--text-secondary);">{{ $limbah->tanggal_pengangkutan ? \Carbon\Carbon::parse($limbah->tanggal_pengangkutan)->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-3 text-right text-sm font-bold" style="color: var(--text-primary);"><span class="rounded-lg px-3 py-1" style="background-color: var(--accent-bg-secondary); color: var(--accent-secondary);">{{ number_format($limbah->jumlah_diangkut ?? 0, 2) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center" style="color: var(--text-secondary);">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-inbox mb-4 text-6xl" style="color: var(--text-tertiary);"></i>
                                    <h3 class="mb-2 text-lg font-medium" style="color: var(--text-primary);">Tidak ada data</h3>
                                    <p>Tidak ada data yang cocok dengan filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between gap-3 p-4" style="background-color: var(--card-bg); border-top: 1px solid var(--border-primary);">
            <div class="text-sm" style="color: var(--text-secondary);">Menampilkan {{ $logPenyimpanan->firstItem() }} - {{ $logPenyimpanan->lastItem() }} dari {{ $logPenyimpanan->total() }} data</div>
            <div>
                {{ $logPenyimpanan->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
