@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div style="background-color: var(--success-bg); border: 1px solid var(--success-border); color: var(--success-text);" class="px-4 py-3 rounded-xl mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3" style="color: var(--success-primary);"></i>
            <span>{{ session('success') }}</span>
            <button type="button" style="color: var(--success-primary); transition: color 0.2s;" class="ml-auto hover:opacity-80" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

<div class="px-2 py-4">
    <!-- Header Section -->
    <div style="background-color: var(--card-bg); border: 1px solid var(--border-primary);" class="rounded-2xl shadow-sm mb-6">
        <div style="border-bottom: 1px solid var(--border-primary);" class="px-6 py-6 flex justify-between items-center">
            <div>
                <h1 style="color: var(--text-primary);" class="text-2xl font-bold mb-2">Edit Unit Pembangkit</h1>
                <p style="color: var(--text-secondary);">Perbarui informasi unit pembangkit listrik</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('unit-pembangkit.show', $unitPembangkit) }}" style="background-color: var(--secondary-bg); color: var(--text-white); transition: all 0.2s;" class="inline-flex items-center px-6 py-3 font-medium rounded-xl shadow-lg hover:shadow-xl hover:opacity-90">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Form Section -->
        <div class="px-6 py-6">
            <form method="POST" action="{{ route('unit-pembangkit.update', $unitPembangkit) }}">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Row: Nama Unit, Kode Pos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_unit" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                Nama Unit <span style="color: var(--danger-primary);">*</span>
                            </label>
                            <input type="text"
                                   class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('nama_unit') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-secondary); color: var(--text-primary);"
                                   id="nama_unit" name="nama_unit"
                                   value="{{ old('nama_unit', $unitPembangkit->nama_unit) }}"
                                   placeholder="Contoh: Unit Pembangkit Jakarta Pusat"
                                   maxlength="100" required>
                            @error('nama_unit')
                                <p class="mt-1 text-sm" style="color: var(--danger-primary);">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kode_pos" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                Kode Pos <span style="color: var(--danger-primary);">*</span>
                            </label>
                            <input type="text"
                                   class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('kode_pos') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-secondary); color: var(--text-primary);"
                                   id="kode_pos" name="kode_pos"
                                   value="{{ old('kode_pos', $unitPembangkit->kode_pos) }}"
                                   placeholder="Contoh: 12345"
                                   maxlength="10" required>
                            @error('kode_pos')
                                <p class="mt-1 text-sm" style="color: var(--danger-primary);">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row: Kota -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kota" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                Kota <span style="color: var(--danger-primary);">*</span>
                            </label>
                            <input type="text"
                                   class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('kota') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-secondary); color: var(--text-primary);"
                                   id="kota" name="kota"
                                   value="{{ old('kota', $unitPembangkit->kota) }}"
                                   placeholder="Contoh: Jakarta"
                                   maxlength="50" required>
                            @error('kota')
                                <p class="mt-1 text-sm" style="color: var(--danger-primary);">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Row: Alamat -->
                    <div>
                        <label for="alamat_unit" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                            Alamat Unit <span style="color: var(--danger-primary);">*</span>
                        </label>
                        <textarea
                            class="w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('alamat_unit') border-red-500 @enderror"
                            style="background-color: var(--input-bg); border-color: var(--border-secondary); color: var(--text-primary);"
                            id="alamat_unit" name="alamat_unit" rows="3" maxlength="500" required>{{ old('alamat_unit', $unitPembangkit->alamat_unit) }}</textarea>
                        @error('alamat_unit')
                            <p class="mt-1 text-sm" style="color: var(--danger-primary);">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="border-top: 1px solid var(--border-primary);" class="flex justify-end gap-3 pt-6 mt-6">
                    <a href="{{ route('unit-pembangkit.show', $unitPembangkit) }}" style="background-color: var(--secondary-bg); color: var(--text-white); transition: all 0.2s;" class="inline-flex items-center px-6 py-3 font-medium rounded-xl hover:opacity-90">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" style="background-color: var(--accent-primary); color: var(--text-white); transition: all 0.2s;" class="inline-flex items-center px-6 py-3 font-medium rounded-xl shadow-lg hover:shadow-xl hover:opacity-90">
                        <i class="fas fa-save mr-2"></i> Update Unit Pembangkit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
