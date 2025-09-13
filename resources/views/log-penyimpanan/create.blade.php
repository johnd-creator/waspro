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
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Tambah Log Penyimpanan Limbah</h1>
                <p class="text-slate-600">Tambahkan data penyimpanan limbah baru ke sistem</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
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
                                    <label for="tanggal_limbah_masuk" class="block text-sm font-medium text-slate-700 mb-2">Tanggal Limbah Masuk <span class="text-red-500">*</span></label>
                                    <input type="date" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_limbah_masuk') border-red-500 @enderror" 
                                           id="tanggal_limbah_masuk" name="tanggal_limbah_masuk" 
                                           value="{{ old('tanggal_limbah_masuk', date('Y-m-d')) }}" required>
                                    @error('tanggal_limbah_masuk')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="kode_limbah" class="block text-sm font-medium text-slate-700 mb-2">Jenis Limbah <span class="text-red-500">*</span></label>
                                    <select class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kode_limbah') border-red-500 @enderror" 
                                            id="kode_limbah" name="kode_limbah" required>
                                        <option value="">Pilih Jenis Limbah</option>
                                        @foreach($jenisLimbah as $jenis)
                                            <option value="{{ $jenis->kode_limbah }}" 
                                                    {{ old('kode_limbah') == $jenis->kode_limbah ? 'selected' : '' }}>
                                                {{ $jenis->kode_limbah }} - {{ $jenis->nama_limbah }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kode_limbah')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Row 2: Jumlah Limbah -->
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="jumlah_limbah_masuk" class="block text-sm font-medium text-slate-700 mb-2">Jumlah Limbah Masuk (Kg) <span class="text-red-500">*</span></label>
                                    <input type="number" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jumlah_limbah_masuk') border-red-500 @enderror" 
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
                                    <label for="perusahaan_id" class="block text-sm font-medium text-slate-700 mb-2">Perusahaan/Vendor Penghasil Limbah</label>
                                    <select class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('perusahaan_id') border-red-500 @enderror" 
                                            id="perusahaan_id" name="perusahaan_id">
                                        <option value="">Pilih Perusahaan (Opsional)</option>
                                        @foreach($perusahaanPenghasil as $perusahaan)
                                            <option value="{{ $perusahaan->perusahaan_id }}" 
                                                    {{ old('perusahaan_id') == $perusahaan->perusahaan_id ? 'selected' : '' }}>
                                                {{ $perusahaan->nama_perusahaan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('perusahaan_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                            <!-- Row 4: Detail Sumber Limbah -->
                            <div class="mt-6">
                                <label for="detail_sumber_limbah" class="block text-sm font-medium text-slate-700 mb-2">Sumber Kegiatan Limbah <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('detail_sumber_limbah') border-red-500 @enderror" 
                                        id="detail_sumber_limbah" name="detail_sumber_limbah" required>
                                    <option value="">Pilih Sumber Limbah</option>
                                    @foreach($kategoriKegiatanSumber as $kategori)
                                        <option value="{{ $kategori->nama_kategori }}" 
                                                {{ old('detail_sumber_limbah') == $kategori->nama_kategori ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('detail_sumber_limbah')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                                <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200">Batal</a>
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                    <i class="fas fa-save mr-2"></i> Simpan
                                </button>
                            </div>
                    </form>
        </div>
    </div>
</div>
@endsection