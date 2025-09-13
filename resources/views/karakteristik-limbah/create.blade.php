@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Tambah Karakteristik Limbah</h1>
                    <p class="text-slate-600">Tambahkan karakteristik limbah baru ke dalam sistem</p>
                </div>
                <a href="{{ route('karakteristik-limbah.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
    
    <!-- Form Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="p-8">
                    <form action="{{ route('karakteristik-limbah.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status_aktif" class="block text-sm font-medium text-slate-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status_aktif') border-red-500 @enderror" 
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
                            <label for="nama_karakteristik" class="block text-sm font-medium text-slate-700 mb-2">Nama Karakteristik <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_karakteristik') border-red-500 @enderror" 
                                   id="nama_karakteristik" name="nama_karakteristik" 
                                   value="{{ old('nama_karakteristik') }}" 
                                   placeholder="Contoh: Mudah Terbakar" maxlength="100" required>
                            @error('nama_karakteristik')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('karakteristik-limbah.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200">Batal</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i> Simpan
                            </button>
                        </div>
                    </form>
        </div>
    </div>
</div>
@endsection