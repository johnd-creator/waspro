@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div style="background-color: var(--card-bg); border: 1px solid var(--border-primary);" class="rounded-2xl shadow-sm mb-6">
        <div style="border-bottom: 1px var(--border-primary);" class="px-6 py-6 flex justify-between items-center">
            <div>
                <h1 style="color: var(--text-primary);" class="text-2xl font-bold mb-2">Edit Perusahaan Penghasil Limbah</h1>
                <p style="color: var(--text-secondary);">Perbarui informasi perusahaan penghasil limbah</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('perusahaan-penghasil.show', $perusahaanPenghasil) }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--accent-secondary); color: white;"
                   onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                   onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-eye mr-2"></i>Lihat
                </a>
                <a href="{{ route('perusahaan-penghasil.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);"
                   onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';"
                   onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6 border-b" style="border-color: var(--border-primary);">
            <h6 class="text-lg font-semibold flex items-center" style="color: var(--text-primary);">
                <i class="fas fa-building mr-2"></i>Informasi Perusahaan
            </h6>
        </div>
            <div class="px-6 py-6">
                <form action="{{ route('perusahaan-penghasil.update', $perusahaanPenghasil) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_perusahaan" style="color: var(--text-secondary);" class="block text-sm font-medium mb-1">Nama Perusahaan <span style="color: var(--danger-primary);">*</span></label>
                            <input type="text" style="background-color: var(--input-bg); border: 1px solid var(--border-secondary); color: var(--text-primary);" class="w-full px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_perusahaan') border-red-500 @enderror"
                                   id="nama_perusahaan" name="nama_perusahaan"
                                   value="{{ old('nama_perusahaan', $perusahaanPenghasil->nama_perusahaan) }}"
                                   placeholder="Contoh: PT. Rumah Sakit Sehat" maxlength="100" required>
                            @error('nama_perusahaan')
                                <div style="color: var(--danger-primary);" class="text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="jenis_perusahaan" style="color: var(--text-secondary);" class="block text-sm font-medium mb-1">Jenis Perusahaan</label>
                            <select style="background-color: var(--input-bg); border: 1px solid var(--border-secondary); color: var(--text-primary);" class="w-full px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jenis_perusahaan') border-red-500 @enderror"
                                    id="jenis_perusahaan" name="jenis_perusahaan">
                                <option value="">Pilih Jenis Perusahaan</option>
                                <option value="Rumah Sakit" {{ old('jenis_perusahaan', $perusahaanPenghasil->jenis_perusahaan) == 'Rumah Sakit' ? 'selected' : '' }}>Rumah Sakit</option>
                                <option value="Klinik" {{ old('jenis_perusahaan', $perusahaanPenghasil->jenis_perusahaan) == 'Klinik' ? 'selected' : '' }}>Klinik</option>
                                <option value="Laboratorium" {{ old('jenis_perusahaan', $perusahaanPenghasil->jenis_perusahaan) == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                                <option value="Industri" {{ old('jenis_perusahaan', $perusahaanPenghasil->jenis_perusahaan) == 'Industri' ? 'selected' : '' }}>Industri</option>
                                <option value="Perkantoran" {{ old('jenis_perusahaan', $perusahaanPenghasil->jenis_perusahaan) == 'Perkantoran' ? 'selected' : '' }}>Perkantoran</option>
                                <option value="Lainnya" {{ old('jenis_perusahaan', $perusahaanPenghasil->jenis_perusahaan) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('jenis_perusahaan')
                                <div style="color: var(--danger-primary);" class="text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="telepon" style="color: var(--text-secondary);" class="block text-sm font-medium mb-1">Telepon</label>
                            <input type="text" style="background-color: var(--input-bg); border: 1px solid var(--border-secondary); color: var(--text-primary);" class="w-full px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telepon') border-red-500 @enderror"
                                   id="telepon" name="telepon"
                                   value="{{ old('telepon', $perusahaanPenghasil->telepon) }}"
                                   placeholder="Contoh: 021-1234567" maxlength="15">
                            @error('telepon')
                                <div style="color: var(--danger-primary);" class="text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="email" style="color: var(--text-secondary);" class="block text-sm font-medium mb-1">Email</label>
                            <input type="email" style="background-color: var(--input-bg); border: 1px solid var(--border-secondary); color: var(--text-primary);" class="w-full px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                   id="email" name="email"
                                   value="{{ old('email', $perusahaanPenghasil->email) }}"
                                   placeholder="Contoh: info@perusahaan.com" maxlength="100">
                            @error('email')
                                <div style="color: var(--danger-primary);" class="text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="kota" style="color: var(--text-secondary);" class="block text-sm font-medium mb-1">Kota</label>
                            <input type="text" style="background-color: var(--input-bg); border: 1px solid var(--border-secondary); color: var(--text-primary);" class="w-full px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kota') border-red-500 @enderror"
                                   id="kota" name="kota"
                                   value="{{ old('kota', $perusahaanPenghasil->kota) }}"
                                   placeholder="Contoh: Jakarta" maxlength="50">
                            @error('kota')
                                <div style="color: var(--danger-primary);" class="text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="person_in_charge" style="color: var(--text-secondary);" class="block text-sm font-medium mb-1">Penanggung Jawab</label>
                            <input type="text" style="background-color: var(--input-bg); border: 1px solid var(--border-secondary); color: var(--text-primary);" class="w-full px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('person_in_charge') border-red-500 @enderror"
                                   id="person_in_charge" name="person_in_charge"
                                   value="{{ old('person_in_charge', $perusahaanPenghasil->person_in_charge) }}"
                                   placeholder="Nama penanggung jawab" maxlength="100">
                            @error('person_in_charge')
                                <div style="color: var(--danger-primary);" class="text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="alamat_perusahaan" style="color: var(--text-secondary);" class="block text-sm font-medium mb-1">Alamat Perusahaan <span style="color: var(--danger-primary);">*</span></label>
                        <textarea style="background-color: var(--input-bg); border: 1px solid var(--border-secondary); color: var(--text-primary);" class="w-full px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat_perusahaan') border-red-500 @enderror"
                                  id="alamat_perusahaan" name="alamat_perusahaan"
                                  rows="3" maxlength="255" required
                                  placeholder="Alamat lengkap perusahaan...">{{ old('alamat_perusahaan', $perusahaanPenghasil->alamat_perusahaan) }}</textarea>
                        <div style="color: var(--text-tertiary);" class="text-sm mt-1">Maksimal 255 karakter</div>
                        @error('alamat_perusahaan')
                            <div style="color: var(--danger-primary);" class="text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="status_aktif" style="color: var(--text-secondary);" class="block text-sm font-medium mb-1">Status <span style="color: var(--danger-primary);">*</span></label>
                            <select style="background-color: var(--input-bg); border: 1px solid var(--border-secondary); color: var(--text-primary);" class="w-full px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status_aktif') border-red-500 @enderror"
                                    id="status_aktif" name="status_aktif" required>
                                <option value="1" {{ old('status_aktif', $perusahaanPenghasil->status_aktif) == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status_aktif', $perusahaanPenghasil->status_aktif) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status_aktif')
                                <div style="color: var(--danger-primary);" class="text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div></div>
                    </div>

                    <div class="mt-6">
                        <label for="keterangan" style="color: var(--text-secondary);" class="block text-sm font-medium mb-1">Keterangan</label>
                        <textarea style="background-color: var(--input-bg); border: 1px solid var(--border-secondary); color: var(--text-primary);" class="w-full px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror"
                                  id="keterangan" name="keterangan"
                                  rows="3" maxlength="500"
                                  placeholder="Keterangan tambahan tentang perusahaan...">{{ old('keterangan', $perusahaanPenghasil->keterangan) }}</textarea>
                        <div style="color: var(--text-tertiary);" class="text-sm mt-1">Maksimal 500 karakter</div>
                        @error('keterangan')
                            <div style="color: var(--danger-primary);" class="text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 mt-8">
                        <a href="{{ route('perusahaan-penghasil.show', $perusahaanPenghasil) }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                           style="background-color: var(--danger-primary); color: white;"
                           onmouseover="this.style.backgroundColor='var(--danger-hover)'; this.style.boxShadow='var(--shadow-xl)';"
                           onmouseout="this.style.backgroundColor='var(--danger-primary)'; this.style.boxShadow='var(--shadow-lg)';">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg"
                                style="background-color: var(--accent-primary);"
                                onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                                onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                            <i class="fas fa-save mr-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
