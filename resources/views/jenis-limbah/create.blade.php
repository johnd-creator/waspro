@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Tambah Jenis Limbah</h1>
                <p class="text-slate-600">Tambahkan data jenis limbah baru ke sistem</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('jenis-limbah.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
        <div class="px-8 py-6">
                    <form action="{{ route('jenis-limbah.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="kode_limbah" class="block text-sm font-medium text-gray-700 mb-2">Kode Limbah <span class="text-red-500">*</span></label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kode_limbah') border-red-500 @enderror" 
                                       id="kode_limbah" name="kode_limbah" 
                                       value="{{ old('kode_limbah') }}" 
                                       placeholder="Contoh: A101" maxlength="10" required>
                                <p class="mt-1 text-sm text-gray-500">Kode unik untuk jenis limbah (maksimal 10 karakter)</p>
                                @error('kode_limbah')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="nama_limbah" class="block text-sm font-medium text-gray-700 mb-2">Nama Limbah <span class="text-red-500">*</span></label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_limbah') border-red-500 @enderror" 
                                       id="nama_limbah" name="nama_limbah" 
                                       value="{{ old('nama_limbah') }}" 
                                       placeholder="Contoh: Limbah Medis Infeksius" maxlength="100" required>
                                @error('nama_limbah')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="karakteristik_id" class="block text-sm font-medium text-gray-700 mb-2">Karakteristik Limbah</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('karakteristik_id') border-red-500 @enderror" 
                                        id="karakteristik_id" name="karakteristik_id">
                                    <option value="">Pilih Karakteristik (Opsional)</option>
                                    @foreach($karakteristikLimbah as $karakteristik)
                                        <option value="{{ $karakteristik->karakteristik_id }}" 
                                                {{ old('karakteristik_id') == $karakteristik->karakteristik_id ? 'selected' : '' }}>
                                            {{ $karakteristik->kode_karakteristik }} - {{ $karakteristik->nama_karakteristik }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('karakteristik_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            

                        </div>

                        <div class="mt-6">
                            <label for="deskripsi_limbah" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Limbah</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi_limbah') border-red-500 @enderror" 
                                      id="deskripsi_limbah" name="deskripsi_limbah" 
                                      rows="4" maxlength="500" 
                                      placeholder="Deskripsi detail tentang jenis limbah ini...">{{ old('deskripsi_limbah') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maksimal 500 karakter</p>
                            @error('deskripsi_limbah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="waktu_penyimpanan_hari" class="block text-sm font-medium text-gray-700 mb-2">Waktu Penyimpanan (Hari) <span class="text-red-500">*</span></label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('waktu_penyimpanan_hari') border-red-500 @enderror" 
                                       id="waktu_penyimpanan_hari" name="waktu_penyimpanan_hari" 
                                       value="{{ old('waktu_penyimpanan_hari') }}" 
                                       min="1" max="365" placeholder="Contoh: 90" required>
                                <p class="mt-1 text-sm text-gray-500">Maksimal penyimpanan dalam hari (1-365 hari)</p>
                                @error('waktu_penyimpanan_hari')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="status_aktif" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status_aktif') border-red-500 @enderror" 
                                        id="status_aktif" name="status_aktif" required>
                                    <option value="1" {{ old('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                @error('status_aktif')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('jenis-limbah.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200">Batal</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i> Simpan
                            </button>
                        </div>
                    </form>
        </div>
    </div>
</div>
@endsection