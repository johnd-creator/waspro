@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="rounded-2xl shadow-sm border mb-6" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6 border-b flex justify-between items-center" style="border-color: var(--border-primary);">
            <div>
                <h1 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">Tambah Karakteristik Limbah</h1>
                <p style="color: var(--text-secondary);">Tambahkan karakteristik limbah baru ke dalam sistem</p>
            </div>
            <a href="{{ route('karakteristik-limbah.index') }}" class="inline-flex items-center px-6 py-3 font-medium text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl" style="background-color: var(--text-secondary); hover:background-color: var(--text-primary);">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
    
    <!-- Form Section -->
    <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="p-6">
            <form action="{{ route('karakteristik-limbah.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status_aktif" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Status <span class="text-red-500">*</span></label>
                        <select class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status_aktif') border-red-500 @enderror" 
                                style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                id="status_aktif" name="status_aktif" required>
                            <option value="1" {{ old('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status_aktif')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="nama_karakteristik" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Karakteristik <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_karakteristik') border-red-500 @enderror" 
                           style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                           id="nama_karakteristik" name="nama_karakteristik" 
                           value="{{ old('nama_karakteristik') }}" 
                           placeholder="Contoh: Mudah Terbakar" maxlength="100" required>
                    @error('nama_karakteristik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="px-6 py-3 rounded-xl text-white font-medium shadow-lg hover:shadow-xl transition-all duration-200" style="background-color: var(--accent-primary);">
                        <i class="fas fa-save mr-2"></i> Simpan Karakteristik
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection