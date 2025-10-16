@extends('layouts.app')

@push('styles')
<style>
/* Safari Select Compatibility Fixes */
select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 40px !important;
    min-height: 42px;
}

/* Ensure consistent height with other inputs */
select, input[type="text"], input[type="number"], input[type="date"], textarea {
    min-height: 42px;
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
@endpush

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="mb-6 rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="flex items-center justify-between border-b px-8 py-6" style="border-color: var(--border-primary);">
            <div>
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Edit Log Penyimpanan Limbah</h1>
                <p style="color: var(--text-secondary);">Ubah data penyimpanan limbah yang sudah ada</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center rounded-xl bg-slate-600 px-6 py-3 font-medium text-white shadow-lg transition-all duration-200 hover:bg-slate-700 hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
        <div class="px-8 py-6">
            <form action="{{ route('log-penyimpanan.update', $logPenyimpanan->log_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <!-- Row 1: Tanggal dan Jenis Limbah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal_limbah_masuk" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Tanggal Limbah Masuk <span class="text-red-500">*</span></label>
                            <input type="date" class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_limbah_masuk') border-red-500 @enderror" 
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" 
                                   id="tanggal_limbah_masuk" name="tanggal_limbah_masuk" 
                                   value="{{ old('tanggal_limbah_masuk', $logPenyimpanan->tanggal_limbah_masuk) }}" required>
                            @error('tanggal_limbah_masuk')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="kode_limbah" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Jenis Limbah <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('kode_limbah') border-red-500 @enderror" 
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" 
                                   id="kode_limbah" 
                                   name="kode_limbah" 
                                   value="{{ old('kode_limbah', $logPenyimpanan->kode_limbah) }}" 
                                   placeholder="Pilih jenis limbah"
                                   list="jenis_limbah_list" 
                                   required>
                            
                            <datalist id="jenis_limbah_list">
                                @foreach($jenisLimbah as $jenis)
                                    <option value="{{ $jenis->kode_limbah }}">{{ $jenis->kode_limbah }} - {{ $jenis->nama_limbah }}</option>
                                @endforeach
                            </datalist>
                            
                            @error('kode_limbah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 2: Jumlah Limbah & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="jumlah_limbah_masuk" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Jumlah Limbah Masuk (Kg) <span class="text-red-500">*</span></label>
                            <input type="number" class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jumlah_limbah_masuk') border-red-500 @enderror" 
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" 
                                   id="jumlah_limbah_masuk" name="jumlah_limbah_masuk" 
                                   value="{{ old('jumlah_limbah_masuk', $logPenyimpanan->jumlah_limbah_masuk) }}" step="0.01" min="0.01" required>
                            @error('jumlah_limbah_masuk')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="status_log" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Status <span class="text-red-500">*</span></label>
                            <select class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status_log') border-red-500 @enderror" 
                                    style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" 
                                    id="status_log" name="status_log" required>
                                <option value="Tersimpan" {{ old('status_log', $logPenyimpanan->status_log) == 'Tersimpan' ? 'selected' : '' }}>Tersimpan</option>
                                <option value="Diangkut" {{ old('status_log', $logPenyimpanan->status_log) == 'Diangkut' ? 'selected' : '' }}>Diangkut</option>
                                <option value="Kadaluarsa" {{ old('status_log', $logPenyimpanan->status_log) == 'Kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                            </select>
                            @error('status_log')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 3: Perusahaan Penghasil -->
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="perusahaan_nama" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Perusahaan/Vendor Penghasil Limbah</label>
                            <input type="text" 
                                   class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('perusahaan_nama') border-red-500 @enderror" 
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" 
                                   id="perusahaan_nama" 
                                   name="perusahaan_nama" 
                                   value="{{ old('perusahaan_nama', $logPenyimpanan->perusahaanPenghasil->nama_perusahaan ?? '') }}" 
                                   placeholder="Masukkan nama perusahaan penghasil limbah"
                                   list="perusahaan_list">
                            
                            <datalist id="perusahaan_list">
                                @foreach($perusahaanPenghasil as $perusahaan)
                                    <option value="{{ $perusahaan->nama_perusahaan }}">
                                @endforeach
                            </datalist>
                            
                            @error('perusahaan_nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 4: Detail Sumber Limbah -->
                    <div class="mt-6">
                        <label for="detail_sumber_limbah" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Sumber Kegiatan Limbah <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('detail_sumber_limbah') border-red-500 @enderror" 
                               style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" 
                               id="detail_sumber_limbah" 
                               name="detail_sumber_limbah" 
                               value="{{ old('detail_sumber_limbah', $logPenyimpanan->detail_sumber_limbah) }}" 
                               placeholder="Masukkan sumber kegiatan limbah"
                               list="sumber_limbah_list" 
                               required>
                        
                        <datalist id="sumber_limbah_list">
                            @foreach($kategoriKegiatanSumber as $kategori)
                                <option value="{{ $kategori->nama_kategori }}">
                            @endforeach
                        </datalist>
                        
                        @error('detail_sumber_limbah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Transport Information -->
                    <div id="transport-fields" class="{{ old('status_log', $logPenyimpanan->status_log) == 'Diangkut' ? '' : 'hidden' }}">
                        <div class="my-6 border-t" style="border-color: var(--border-primary);"></div>
                        <h3 class="mb-4 text-lg font-semibold" style="color: var(--text-primary);">Informasi Pengangkutan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tanggal_pengangkutan" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Tanggal Pengangkutan</label>
                                <input type="date" class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_pengangkutan') border-red-500 @enderror" 
                                       style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" 
                                       id="tanggal_pengangkutan" name="tanggal_pengangkutan" 
                                       value="{{ old('tanggal_pengangkutan', optional($logPenyimpanan->tanggal_pengangkutan)->format('Y-m-d')) }}">
                                @error('tanggal_pengangkutan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="jumlah_diangkut" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Jumlah Diangkut (Kg)</label>
                                <input type="number" step="0.01" min="0" class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jumlah_diangkut') border-red-500 @enderror" 
                                       style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" 
                                       id="jumlah_diangkut" name="jumlah_diangkut" 
                                       value="{{ old('jumlah_diangkut', $logPenyimpanan->jumlah_diangkut) }}">
                                @error('jumlah_diangkut')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Pendukung -->
                    <div class="mt-6 space-y-3">
                        <label for="dokumen_limbah" class="block text-sm font-medium" style="color: var(--text-primary);">Dokumen Pendukung</label>
                        @if($logPenyimpanan->dokumen_path)
                            <div class="rounded-lg border px-4 py-3 text-sm" style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--text-secondary);">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium" style="color: var(--text-primary);">{{ $logPenyimpanan->dokumen_original_name ?? basename($logPenyimpanan->dokumen_path) }}</p>
                                        <p class="text-xs">Ukuran: {{ number_format(($logPenyimpanan->dokumen_size ?? 0) / 1024, 2) }} KB · Diunggah {{ optional($logPenyimpanan->dokumen_uploaded_at)->diffForHumans() }}</p>
                                    </div>
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($logPenyimpanan->dokumen_path) }}" target="_blank" class="inline-flex items-center rounded-lg border px-3 py-1 text-xs font-medium transition-all duration-200" style="border-color: var(--border-primary); color: var(--text-primary);">
                                        <i class="fas fa-download mr-1"></i> Unduh
                                    </a>
                                </div>
                                <p class="mt-2 text-xs">Mengunggah file baru akan menggantikan dokumen saat ini.</p>
                            </div>
                        @endif
                        <input type="file"
                               id="dokumen_limbah"
                               name="dokumen_limbah"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg"
                               class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('dokumen_limbah') border-red-500 @enderror"
                               style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                        <p class="text-sm text-slate-500">Format diperbolehkan: PDF, Word, Excel, atau gambar (maksimal {{ number_format(config('app.max_upload_size', 10240) / 1024, 1) }} MB).</p>
                        @error('dokumen_limbah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 border-t pt-4 mt-6" style="border-color: var(--border-primary);">
                        <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center rounded-xl px-6 py-3 font-medium transition-all duration-200" style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">Batal</a>
                        <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-6 py-3 font-medium text-white shadow-lg transition-all duration-200 hover:bg-blue-700 hover:shadow-xl">
                            <i class="fas fa-save mr-2"></i> Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status_log');
    const transportFields = document.getElementById('transport-fields');
    
    statusSelect.addEventListener('change', function() {
        if (this.value === 'Diangkut') {
            transportFields.classList.remove('hidden');
        } else {
            transportFields.classList.add('hidden');
        }
    });
});
</script>
@endpush
