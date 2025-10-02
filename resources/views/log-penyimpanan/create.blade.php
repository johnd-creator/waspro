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
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="mb-6 rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="flex items-center justify-between border-b px-8 py-6" style="border-color: var(--border-primary);">
            <div>
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Tambah Log Penyimpanan Limbah</h1>
                <p style="color: var(--text-secondary);">Tambahkan data penyimpanan limbah baru ke sistem</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center rounded-xl bg-slate-600 px-6 py-3 font-medium text-white shadow-lg transition-all duration-200 hover:bg-slate-700 hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
        <div class="px-8 py-6">
                    <form action="{{ route('log-penyimpanan.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Row 1: Tanggal dan Jenis Limbah -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="tanggal_limbah_masuk" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Tanggal Limbah Masuk <span class="text-red-500">*</span></label>
                                    <input type="date" class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tanggal_limbah_masuk') border-red-500 @enderror" 
                                           style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" 
                                           id="tanggal_limbah_masuk" name="tanggal_limbah_masuk" 
                                           value="{{ old('tanggal_limbah_masuk', date('Y-m-d')) }}" required>
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
                                           value="{{ old('kode_limbah') }}" 
                                           placeholder="Pilih jenis limbah"
                                           list="jenis_limbah_list" 
                                           required>
                                    
                                    <!-- Datalist untuk autocomplete dengan nilai dari database -->
                                    <datalist id="jenis_limbah_list">
                                        @foreach($jenisLimbah as $jenis)
                                            <option value="{{ $jenis->kode_limbah }}">
                                                {{ $jenis->kode_limbah }} - {{ $jenis->nama_limbah }}
                                            </option>
                                        @endforeach
                                    </datalist>
                                    
                                    @error('kode_limbah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Row 2: Jumlah Limbah -->
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="jumlah_limbah_masuk" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Jumlah Limbah Masuk (Kg) <span class="text-red-500">*</span></label>
                                    <input type="number" class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jumlah_limbah_masuk') border-red-500 @enderror" 
                                           style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" 
                                           id="jumlah_limbah_masuk" name="jumlah_limbah_masuk" 
                                           value="{{ old('jumlah_limbah_masuk') }}" step="0.01" min="0.01" required>
                                    @error('jumlah_limbah_masuk')
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
                                           value="{{ old('perusahaan_nama') }}" 
                                           placeholder="Masukkan nama perusahaan penghasil limbah"
                                           list="perusahaan_list">
                                    
                                    <!-- Datalist untuk autocomplete dengan nilai dari database -->
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
                        </div>

                            <!-- Row 4: Detail Sumber Limbah -->
                            <div class="mt-6">
                                <label for="detail_sumber_limbah" class="mb-2 block text-sm font-medium" style="color: var(--text-primary);">Sumber Kegiatan Limbah <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       class="w-full rounded-lg border px-3 py-2 shadow-sm transition-all duration-200 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('detail_sumber_limbah') border-red-500 @enderror" 
                                       style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" 
                                       id="detail_sumber_limbah" 
                                       name="detail_sumber_limbah" 
                                       value="{{ old('detail_sumber_limbah') }}" 
                                       placeholder="Masukkan sumber kegiatan limbah"
                                       list="sumber_limbah_list" 
                                       required>
                                
                                <!-- Datalist untuk autocomplete dengan nilai dari database -->
                                <datalist id="sumber_limbah_list">
                                    @foreach($kategoriKegiatanSumber as $kategori)
                                        <option value="{{ $kategori->nama_kategori }}">
                                    @endforeach
                                </datalist>
                                
                                @error('detail_sumber_limbah')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end gap-3 border-t pt-4" style="border-color: var(--border-primary);">
                                <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center rounded-xl px-6 py-3 font-medium text-white transition-all duration-200" style="background-color: var(--text-secondary);">Batal</a>
                                <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 px-6 py-3 font-medium text-white shadow-lg transition-all duration-200 hover:bg-blue-700 hover:shadow-xl">
                                    <i class="fas fa-save mr-2"></i> Simpan
                                </button>
                            </div>
                    </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validasi untuk field kode_limbah
    const kodeLimbahInput = document.getElementById('kode_limbah');
    const jenisLimbahDatalist = document.getElementById('jenis_limbah_list');
    
    if (kodeLimbahInput && jenisLimbahDatalist) {
        kodeLimbahInput.addEventListener('input', function() {
            const value = this.value;
            const options = Array.from(jenisLimbahDatalist.options).map(option => option.value);
            
            // Reset custom validity
            this.setCustomValidity('');
            
            if (value && !options.includes(value)) {
                this.setCustomValidity('Pilih jenis limbah yang tersedia dari daftar');
            }
        });
    }
    
    // Validasi untuk field perusahaan_nama
    const perusahaanInput = document.getElementById('perusahaan_nama');
    const perusahaanDatalist = document.getElementById('perusahaan_list');
    
    if (perusahaanInput && perusahaanDatalist) {
        perusahaanInput.addEventListener('input', function() {
            const value = this.value;
            const options = Array.from(perusahaanDatalist.options).map(option => option.value);
            
            // Reset custom validity
            this.setCustomValidity('');
            
            // Allow new company names (don't validate against existing list)
        });
    }
    
    // Validasi untuk field detail_sumber_limbah
    const sumberLimbahInput = document.getElementById('detail_sumber_limbah');
    const sumberLimbahDatalist = document.getElementById('sumber_limbah_list');
    
    if (sumberLimbahInput && sumberLimbahDatalist) {
        sumberLimbahInput.addEventListener('input', function() {
            const value = this.value;
            const options = Array.from(sumberLimbahDatalist.options).map(option => option.value);
            
            // Reset custom validity
            this.setCustomValidity('');
            
            if (value && !options.includes(value)) {
                this.setCustomValidity('Pilih sumber kegiatan limbah yang tersedia dari daftar');
            }
        });
    }
});
</script>

@endsection