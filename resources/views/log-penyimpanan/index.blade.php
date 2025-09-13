@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-600"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-600 hover:text-green-800" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Log Penyimpanan Limbah</h1>
                <p class="text-slate-600">Kelola dan pantau data penyimpanan limbah dengan mudah</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('log-penyimpanan.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i> Tambah Log Baru
                </a>
            </div>
        </div>
        <!-- Search Section -->
        <div class="px-6 py-6">
            <!-- Quick Search Bar -->
            <div class="mb-6">
                <form method="GET" action="{{ route('log-penyimpanan.index') }}" class="flex gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-slate-400"></i>
                            </div>
                            <input type="text" class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   name="search_kode_identitas" 
                                   value="{{ request('search_kode_identitas') }}" 
                                   placeholder="Cari berdasarkan Kode Identitas Limbah (contoh: LMB-UNIT-202501-001)...">
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                    @if(request()->hasAny(['search_kode_identitas', 'search_jenis', 'search_perusahaan', 'search_status', 'search_tanggal']))
                        <a href="{{ route('log-penyimpanan.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                            <i class="fas fa-times mr-2"></i> Reset
                        </a>
                    @endif
                </form>
            </div>
                    
            <!-- Advanced Search Form -->
            <div class="mb-4">
                <div class="text-center mb-3">
                    <button class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors" type="button" onclick="toggleAdvancedSearch()" id="advancedSearchToggle">
                        <i class="fas fa-filter mr-2"></i> Pencarian Lanjutan
                    </button>
                </div>
                <div class="hidden" id="advancedSearch">
                    <div class="bg-slate-50 rounded-xl border border-slate-200 p-4">
                        <form method="GET" action="{{ route('log-penyimpanan.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                            <input type="hidden" name="search_kode_identitas" value="{{ request('search_kode_identitas') }}">
                            <div>
                                <label for="search_jenis" class="block text-sm font-medium text-slate-700 mb-1">Jenis Limbah</label>
                                <input type="text" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       id="search_jenis" name="search_jenis" 
                                       value="{{ request('search_jenis') }}" placeholder="Cari jenis limbah...">
                            </div>
                            <div>
                                <label for="search_perusahaan" class="block text-sm font-medium text-slate-700 mb-1">Perusahaan</label>
                                <input type="text" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       id="search_perusahaan" name="search_perusahaan" 
                                       value="{{ request('search_perusahaan') }}" placeholder="Cari perusahaan...">
                            </div>
                            <div>
                                <label for="search_status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                                <select class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="search_status" name="search_status">
                                    <option value="">Semua Status</option>
                                    <option value="Tersimpan" {{ request('search_status') == 'Tersimpan' ? 'selected' : '' }}>Tersimpan</option>
                                    <option value="Diangkut" {{ request('search_status') == 'Diangkut' ? 'selected' : '' }}>Diangkut</option>
                                </select>
                            </div>
                            <div>
                                <label for="search_tanggal" class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                                <input type="date" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                       id="search_tanggal" name="search_tanggal" 
                                       value="{{ request('search_tanggal') }}">
                            </div>
                            <div class="flex items-end gap-2">
                                <button type="submit" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="fas fa-search mr-1"></i> Cari
                                </button>
                                <a href="{{ route('log-penyimpanan.index') }}" class="px-3 py-2 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                                    <i class="fas fa-times mr-1"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
                    
                    @if(request()->hasAny(['search_jenis', 'search_perusahaan', 'search_status', 'search_tanggal']))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Menampilkan hasil pencarian untuk: 
                            @if(request('search_jenis'))
                                <strong>Jenis:</strong> {{ request('search_jenis') }}
                            @endif
                            @if(request('search_perusahaan'))
                                <strong>Perusahaan:</strong> {{ request('search_perusahaan') }}
                            @endif
                            @if(request('search_status'))
                                <strong>Status:</strong> {{ request('search_status') }}
                            @endif
                            @if(request('search_tanggal'))
                                <strong>Tanggal:</strong> {{ request('search_tanggal') }}
                            @endif
                        </div>
                    @endif
            <!-- Tabel Log Penyimpanan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1200px]">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                            <tr>
                                <th class="px-4 py-4 text-left text-sm font-semibold w-16">No</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold min-w-[140px]">Kode Identitas</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold min-w-[120px]">Tanggal Masuk</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold min-w-[150px]">Jenis Limbah</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold min-w-[200px]">Sumber Limbah</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold min-w-[120px]">Jumlah (Kg)</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold min-w-[150px]">Perusahaan</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold min-w-[100px]">Status</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold min-w-[140px]">Status Kadaluarsa</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold min-w-[150px]">Penginput Data</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold min-w-[180px]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($logs as $index => $log)
                                <tr class="hover:bg-slate-50/50 transition-all duration-200 border-b border-slate-100 last:border-b-0">
                                    <td class="px-4 py-4 text-sm font-medium text-slate-700 text-center">{{ $logs->firstItem() + $index }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-blue-600 text-sm">{{ $log->kode_identitas ?? 'Belum Ada' }}</div>
                                        @if($log->kode_identitas)
                                            <div class="text-xs text-slate-500 mt-1 flex items-center">
                                                <i class="fas fa-qrcode mr-1"></i> ID Limbah
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700 font-medium">{{ $log->tanggal_limbah_masuk }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900 text-sm">{{ $log->jenisLimbah->nama_limbah ?? 'N/A' }}</div>
                                        @if($log->kode_limbah)
                                            <div class="text-xs text-slate-500 mt-1 bg-slate-100 px-2 py-1 rounded-md inline-block">{{ $log->kode_limbah }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 leading-relaxed">
                                        <div class="max-w-xs">
                                            {{ Str::limit($log->detail_sumber_limbah, 60) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-900 text-right">
                                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg">
                                            {{ number_format($log->jumlah_limbah_masuk, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <div class="font-medium truncate max-w-[140px]" title="{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'N/A' }}">
                                            {{ $log->perusahaanPenghasil->nama_perusahaan ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($log->status_log == 'Tersimpan')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $log->status_log }}</span>
                                        @elseif($log->status_log == 'Diangkut')
                                             <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">{{ $log->status_log }}</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $log->status_log }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($log->status_log == 'Tersimpan' && $log->expiry_status)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $log->getExpiryStatusBadgeClass() }}">
                                                {{ $log->getExpiryStatusText() }}
                                            </span>
                                            @if($log->expiry_status == 'Critical' || $log->expiry_status == 'Warning')
                                                <div class="text-xs text-slate-500 mt-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    @if($log->tanggal_kadaluarsa)
                                                        Kadaluarsa: {{ \Carbon\Carbon::parse($log->tanggal_kadaluarsa)->format('d/m/Y') }}
                                                    @endif
                                                </div>
                                            @endif
                                        @elseif($log->status_log == 'Diangkut')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">-</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Belum Dihitung</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900 text-sm">
                                            {{ $log->penggunaSistem->nama_lengkap ?? 'N/A' }}
                                        </div>
                                        @if($log->penggunaSistem)
                                            <div class="text-xs text-slate-500 mt-1">
                                                <i class="fas fa-user mr-1"></i> {{ $log->penggunaSistem->username }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col space-y-2">
                                            @if($log->status_log == 'Tersimpan')
                                                <!-- Quick Action Button untuk Tandai Diangkut -->
                                                <button type="button" class="inline-flex items-center justify-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors" 
                                                        onclick="openModal('transportModal{{ $log->log_id }}')" 
                                                        title="Quick Action: Tandai Diangkut ({{ $log->kode_identitas }})">
                                                    <i class="fas fa-truck mr-1"></i> Angkut
                                                </button>
                                            @endif
                                            <div class="flex items-center space-x-1">
                                                <a href="{{ route('log-penyimpanan.show', $log) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-600 transition-colors" 
                                                   title="Lihat Detail ({{ $log->kode_identitas }})" 
                                                   target="_self">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                                <a href="{{ route('log-penyimpanan.edit', $log) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-600 transition-colors" 
                                                   title="Edit ({{ $log->kode_identitas }})">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                                <form action="{{ route('log-penyimpanan.destroy', $log) }}" 
                                                      method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 hover:bg-red-200 text-red-600 transition-colors" 
                                                            title="Hapus ({{ $log->kode_identitas }})"
                                                            onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus log {{ $log->kode_identitas }}?')">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                @if($log->status_log == 'Tersimpan')
                                <!-- Transport Modal - Tailwind CSS -->
                                <div id="transportModal{{ $log->log_id }}" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 hidden">
                                    <div class="p-5 border w-96 shadow-lg rounded-md bg-white">
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between mb-4">
                                                <h3 class="text-lg font-medium text-gray-900">Tandai Sebagai Diangkut</h3>
                                                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeModal('transportModal{{ $log->log_id }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('log-penyimpanan.transport', $log) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengangkutan</label>
                                                    <input type="date" name="tanggal_pengangkutan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                                </div>
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Diangkut (Kg)</label>
                                                    <input type="number" name="jumlah_diangkut" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" step="0.01" max="{{ $log->jumlah_limbah_masuk }}" required>
                                                    <small class="text-gray-500 text-sm">Maksimal: {{ $log->jumlah_limbah_masuk }} kg</small>
                                                </div>
                                                <div class="flex justify-end space-x-3">
                                                    <button type="button" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors" onclick="closeModal('transportModal{{ $log->log_id }}')">Batal</button>
                                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="11" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-clipboard-list text-6xl text-slate-300 mb-4"></i>
                                            <h3 class="text-lg font-medium text-slate-900 mb-2">Belum ada data log penyimpanan limbah</h3>
                                            <p class="text-slate-500 mb-4">Mulai dengan menambahkan log penyimpanan limbah pertama Anda</p>
                                            <a href="{{ route('log-penyimpanan.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors">
                                                <i class="fas fa-plus mr-2"></i> Tambah Log Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($logs->hasPages())
                <div class="flex justify-center mt-8">
                    <div class="bg-white rounded-xl border border-slate-200 px-6 py-4">
                        {{ $logs->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
function toggleAdvancedSearch() {
    const advancedSearch = document.getElementById('advancedSearch');
    const toggleButton = document.getElementById('advancedSearchToggle');
    
    if (advancedSearch.classList.contains('hidden')) {
        advancedSearch.classList.remove('hidden');
        toggleButton.innerHTML = '<i class="fas fa-filter mr-2"></i> Tutup Pencarian Lanjutan';
    } else {
        advancedSearch.classList.add('hidden');
        toggleButton.innerHTML = '<i class="fas fa-filter mr-2"></i> Pencarian Lanjutan';
    }
}

// Auto-show advanced search if there are search parameters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const hasSearchParams = urlParams.has('search_jenis') || urlParams.has('search_perusahaan') || 
                           urlParams.has('search_status') || urlParams.has('search_tanggal');
    
    if (hasSearchParams) {
        toggleAdvancedSearch();
    }
});
</script>
@endpush

@push('styles')
<style>
/* Safari compatibility fixes for select elements */
select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.7rem center;
    background-size: 1.2em;
    padding-right: 2.5rem;
}

/* Ensure consistent height across browsers */
select,
input[type="text"],
input[type="date"] {
    min-height: 2.5rem;
    line-height: 1.5;
}

/* Safari specific fixes */
@supports (-webkit-appearance: none) {
    select {
        background-color: white;
        border: 1px solid #cbd5e1;
    }
    
    select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
}
</style>

<script>
// Modal functions for Tailwind CSS modals
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('bg-gray-600') && event.target.classList.contains('bg-opacity-50')) {
        event.target.classList.add('hidden');
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[id^="transportModal"]');
        modals.forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
            }
        });
    }
});
</script>
@endpush