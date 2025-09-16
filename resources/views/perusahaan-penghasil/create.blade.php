@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="rounded-2xl shadow-sm border mb-6" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6 border-b flex justify-between items-center" style="border-color: var(--border-primary);">
            <div>
                <h1 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">Tambah Perusahaan Penghasil Limbah</h1>
                <p style="color: var(--text-secondary);">Masukkan informasi perusahaan penghasil limbah baru</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('perusahaan-penghasil.index') }}" class="inline-flex items-center px-6 py-3 font-medium text-white rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl" style="background-color: var(--text-secondary); hover:background-color: var(--text-primary);">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>    
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="max-w-4xl mx-auto">
        <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
            <div class="px-6 py-6 border-b" style="border-color: var(--border-primary);">
                <h6 class="text-lg font-semibold flex items-center" style="color: var(--text-primary);">
                    <i class="fas fa-building mr-2"></i>Informasi Perusahaan
                </h6>
            </div>
            <div class="px-6 py-6">
                <form action="{{ route('perusahaan-penghasil.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_perusahaan" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Nama Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_perusahaan') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="nama_perusahaan" name="nama_perusahaan"
                                   value="{{ old('nama_perusahaan') }}"
                                   placeholder="Contoh: PT. Rumah Sakit Sehat" maxlength="100" required>
                            @error('nama_perusahaan')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_perusahaan" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Jenis Perusahaan</label>
                            <select class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jenis_perusahaan') border-red-500 @enderror"
                                    style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                    id="jenis_perusahaan" name="jenis_perusahaan">
                                <option value="">Pilih Jenis Perusahaan</option>
                                <option value="Rumah Sakit" {{ old('jenis_perusahaan') == 'Rumah Sakit' ? 'selected' : '' }}>Rumah Sakit</option>
                                <option value="Klinik" {{ old('jenis_perusahaan') == 'Klinik' ? 'selected' : '' }}>Klinik</option>
                                <option value="Laboratorium" {{ old('jenis_perusahaan') == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                                <option value="Industri" {{ old('jenis_perusahaan') == 'Industri' ? 'selected' : '' }}>Industri</option>
                                <option value="Perkantoran" {{ old('jenis_perusahaan') == 'Perkantoran' ? 'selected' : '' }}>Perkantoran</option>
                                <option value="Lainnya" {{ old('jenis_perusahaan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('jenis_perusahaan')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="telepon" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Telepon</label>
                            <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telepon') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="telepon" name="telepon"
                                   value="{{ old('telepon') }}"
                                   placeholder="Contoh: 021-1234567" maxlength="15">
                            @error('telepon')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Email</label>
                            <input type="email" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="email" name="email"
                                   value="{{ old('email') }}"
                                   placeholder="Contoh: info@perusahaan.com" maxlength="100">
                            @error('email')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="kota" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Kota</label>
                            <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kota') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="kota" name="kota"
                                   value="{{ old('kota') }}"
                                   placeholder="Contoh: Jakarta" maxlength="50">
                            @error('kota')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="person_in_charge" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Penanggung Jawab</label>
                            <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('person_in_charge') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="person_in_charge" name="person_in_charge"
                                   value="{{ old('person_in_charge') }}"
                                   placeholder="Nama penanggung jawab" maxlength="100">
                            @error('person_in_charge')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="alamat_perusahaan" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Alamat Perusahaan <span class="text-red-500">*</span></label>
                        <textarea class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat_perusahaan') border-red-500 @enderror"
                                  style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                  id="alamat_perusahaan" name="alamat_perusahaan"
                                  rows="3" maxlength="255" required
                                  placeholder="Alamat lengkap perusahaan...">{{ old('alamat_perusahaan') }}</textarea>
                        <div class="text-sm mt-1" style="color: var(--text-secondary);">Maksimal 255 karakter</div>
                        @error('alamat_perusahaan')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="status_aktif" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Status <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status_aktif') border-red-500 @enderror"
                                    style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                    id="status_aktif" name="status_aktif" required>
                                <option value="1" {{ old('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status_aktif')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div></div>
                    </div>

                    <div class="mt-6">
                        <label for="keterangan" class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Keterangan</label>
                        <textarea class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror"
                                  style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                  id="keterangan" name="keterangan"
                                  rows="3" maxlength="500"
                                  placeholder="Keterangan tambahan tentang perusahaan...">{{ old('keterangan') }}</textarea>
                        <div class="text-sm mt-1" style="color: var(--text-secondary);">Maksimal 500 karakter</div>
                        @error('keterangan')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 mt-8">
                        <a href="{{ route('perusahaan-penghasil.index') }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl" style="background-color: var(--text-secondary);">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-save mr-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
