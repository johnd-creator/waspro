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

<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-6 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Tambah Unit Pembangkit</h1>
                <p class="text-slate-600">Masukkan informasi unit pembangkit listrik baru</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('unit-pembangkit.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Form Section -->
        <div class="px-6 py-6">
            <form action="{{ route('unit-pembangkit.store') }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Row: Nama Unit, Kode Pos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_unit" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                Nama Unit <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('nama_unit') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="nama_unit" name="nama_unit"
                                   value="{{ old('nama_unit') }}"
                                   placeholder="Contoh: Unit Pembangkit Jakarta Pusat"
                                   maxlength="100" required>
                            @error('nama_unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kode_pos" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                Kode Pos <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('kode_pos') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="kode_pos" name="kode_pos"
                                   value="{{ old('kode_pos') }}"
                                   placeholder="Contoh: 12345"
                                   maxlength="10" required>
                            @error('kode_pos')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row: Kota -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kota" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                Kota <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('kota') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="kota" name="kota"
                                   value="{{ old('kota') }}"
                                   placeholder="Contoh: Jakarta"
                                   maxlength="50" required>
                            @error('kota')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row: Alamat -->
                    <div>
                        <label for="alamat_unit" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                            Alamat Unit <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('alamat_unit') border-red-500 @enderror"
                            style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                            id="alamat_unit" name="alamat_unit" rows="3" maxlength="500" required>{{ old('alamat_unit') }}</textarea>
                        @error('alamat_unit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-slate-200">
                    <a href="{{ route('unit-pembangkit.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200">
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
