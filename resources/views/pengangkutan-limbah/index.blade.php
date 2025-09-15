@extends('layouts.app')

@section('title', 'Pengangkutan Limbah')

@section('content')
<div class="px-2 py-4">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-600"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Header & Quick Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Pengangkutan Limbah</h1>
                <p class="text-slate-600">Kelola proses pengangkutan limbah dengan mudah</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('pengangkutan-limbah.diangkut') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-truck mr-2"></i> Limbah Diangkut
                </a>
            </div>
        </div>
        <!-- Filter -->
        <div class="px-6 py-6">
            <form method="GET" action="{{ route('pengangkutan-limbah.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                        <input type="text" name="kode_identitas" value="{{ request('kode_identitas') }}" placeholder="Cari Kode Identitas..." class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                    </div>
                </div>
                <div>
                    <select name="status_diangkut" class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="0" {{ request('status_diangkut') == '0' ? 'selected' : '' }}>Belum Diangkut</option>
                        <option value="1" {{ request('status_diangkut') == '1' ? 'selected' : '' }}>Sudah Diangkut</option>
                    </select>
                </div>
                <div class="flex items-stretch gap-3">
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    @if(request()->hasAny(['kode_identitas','status_diangkut']))
                        <a href="{{ route('pengangkutan-limbah.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form id="bulk-angkut-form" method="POST" action="{{ route('pengangkutan-limbah.bulk-approve') }}">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px]">
                    <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                        <tr>
                            <th class="px-4 py-4 text-left text-sm font-semibold w-12">
                                <input type="checkbox" id="select-all" class="w-4 h-4">
                            </th>
                            <th class="px-4 py-4 text-left text-sm font-semibold w-16">No</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Kode Identitas</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Perusahaan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Jenis Limbah</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal Masuk</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Jumlah (Kg)</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($logPenyimpanan as $key => $log)
                            <tr class="hover:bg-slate-50/50 transition-all duration-200">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="selected_logs[]" value="{{ $log->log_id ?? $log->id }}" class="log-checkbox w-4 h-4">
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-700 text-center">{{ $logPenyimpanan->firstItem() + $key }}</td>
                                <td class="px-6 py-3 text-sm text-slate-700">{{ $log->kode_identitas }}</td>
                                <td class="px-6 py-3 text-sm text-slate-700">{{ $log->perusahaanPenghasil->nama_perusahaan ?? $log->perusahaan->nama_perusahaan }}</td>
                                <td class="px-6 py-3 text-sm text-slate-700">{{ $log->jenisLimbah->nama_limbah }}</td>
                                <td class="px-6 py-3 text-sm text-slate-700">{{ $log->tanggal_limbah_masuk->format('d/m/Y') }}</td>
                                <td class="px-6 py-3 text-sm font-bold text-slate-900 text-right">
                                    <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg">{{ number_format($log->jumlah_limbah_masuk, 2) }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    @if(strtolower($log->status_log) === 'diangkut')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Sudah Diangkut</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Belum Diangkut</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    @if(strtolower($log->status_log) !== 'diangkut')
                                        <button type="button" class="inline-flex items-center px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium angkut-btn" data-id="{{ $log->log_id ?? $log->id }}">
                                            <i class="fas fa-truck mr-2"></i> Angkut
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between gap-3 p-4">
                <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium" id="bulk-angkut-btn">
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
    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.querySelectorAll('.log-checkbox');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }

    document.querySelectorAll('.angkut-btn').forEach(button => {
        button.addEventListener('click', function() {
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