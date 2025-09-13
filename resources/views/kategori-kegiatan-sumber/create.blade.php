@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Tambah Kategori Kegiatan Sumber</h1>
                    <p class="text-slate-600">Tambahkan kategori kegiatan sumber baru ke dalam sistem</p>
                </div>
                <a href="{{ route('kategori-kegiatan-sumber.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    
    <!-- Form Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-8 py-6">
            <form action="{{ route('kategori-kegiatan-sumber.store') }}" method="POST">
                @csrf
                        
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" 
                                   id="nama_kategori" name="nama_kategori" 
                                   value="{{ old('nama_kategori') }}" 
                                   placeholder="Contoh: Kegiatan Medis" maxlength="100" required>
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('kategori-kegiatan-sumber.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i> Simpan
                            </button>
                        </div>
                    </form>
        </div>
    </div>
</div>
@endsection