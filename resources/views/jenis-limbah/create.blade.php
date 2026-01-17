@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="rounded-2xl shadow-sm border mb-6" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6 flex justify-between items-center" style="border-color: var(--border-primary);">
            <div>
                <h1 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">Tambah Jenis Limbah</h1>
                <p style="color: var(--text-secondary);">Tambahkan data jenis limbah baru ke sistem</p>
            </div>
            <div>
                <a href="{{ route('jenis-limbah.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);"
                   onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';"
                   onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6">
            <h6 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                <i class="fas fa-user-plus mr-2"></i>Form Tambah Jenis Limbah
            </h6>

            <form action="{{ route('jenis-limbah.store') }}" method="POST">
                @csrf

                <!-- Data Dasar Section -->
                <div class="mb-8">
                    <h6 class="text-md font-semibold mb-3" style="color: var(--text-primary);">Data Dasar</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kode_limbah" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kode Limbah <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kode_limbah') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="kode_limbah" name="kode_limbah"
                                   value="{{ old('kode_limbah') }}"
                                   placeholder="Contoh: A101" maxlength="10" required>
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">Kode unik untuk jenis limbah (maksimal 10 karakter)</p>
                            @error('kode_limbah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nama_limbah" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Nama Limbah <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_limbah') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="nama_limbah" name="nama_limbah"
                                   value="{{ old('nama_limbah') }}"
                                   placeholder="Contoh: Limbah Medis Infeksius" maxlength="255" required>
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">Nama lengkap jenis limbah</p>
                            @error('nama_limbah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kemasan" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kemasan <span class="text-red-500">*</span></label>
                            <input type="text" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kemasan') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="kemasan" name="kemasan"
                                   value="{{ old('kemasan') }}"
                                   placeholder="Contoh: Kantong Plastik Kuning" required>
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">Jenis kemasan limbah</p>
                            @error('kemasan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="karakteristik_id" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Karakteristik Limbah <span class="text-red-500">*</span></label>
                            <select class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('karakteristik_id') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="karakteristik_id" name="karakteristik_id" required>
                                <option value="">Pilih Karakteristik</option>
                                @foreach($karakteristikLimbah as $karakteristik)
                                    <option value="{{ $karakteristik->karakteristik_id }}"
                                            {{ old('karakteristik_id') == $karakteristik->karakteristik_id ? 'selected' : '' }}>
                                        {{ $karakteristik->kode_karakteristik }} - {{ $karakteristik->nama_karakteristik }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">Klasifikasi karakteristik limbah</p>
                            @error('karakteristik_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Kategori & Deskripsi Section -->
                <div class="mb-8">
                    <h6 class="text-md font-semibold mb-3" style="color: var(--text-primary);">Kategori & Deskripsi</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kategori_id" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Kategori Kegiatan Sumber <span class="text-red-500">*</span></label>
                            <select id="kategori_id" name="kategori_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kategori_id') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);" required>
                                <option value="">Pilih Kategori</option>
                                @foreach(App\Models\KategoriKegiatanSumber::orderBy('nama_kategori')->get() as $kategori)
                                    <option value="{{ $kategori->kategori_id }}" {{ old('kategori_id') == $kategori->kategori_id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">Kategori kegiatan sumber limbah</p>
                            @error('kategori_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="deskripsi_limbah" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Deskripsi Limbah</label>
                            <textarea class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('deskripsi_limbah') border-red-500 @enderror"
                                      style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                      id="deskripsi_limbah" name="deskripsi_limbah"
                                      rows="4" maxlength="500"
                                      placeholder="Deskripsi detail tentang jenis limbah ini...">{{ old('deskripsi_limbah') }}</textarea>
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">Maksimal 500 karakter</p>
                            @error('deskripsi_limbah')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Waktu Penyimpanan Section -->
                <div class="mb-8">
                    <h6 class="text-md font-semibold mb-3" style="color: var(--text-primary);">Waktu Penyimpanan</h6>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="waktu_penyimpanan_hari" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Waktu Penyimpanan (Hari) <span class="text-red-500">*</span></label>
                            <input type="number" id="waktu_penyimpanan_hari" name="waktu_penyimpanan_hari" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('waktu_penyimpanan_hari') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   value="{{ old('waktu_penyimpanan_hari') }}"
                                   min="1" max="365" placeholder="Contoh: 90" required>
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">Maksimal penyimpanan dalam hari (1-365 hari)</p>
                            @error('waktu_penyimpanan_hari')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="batas_penyimpanan_hari" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Batas Penyimpanan (Hari)</label>
                            <input type="number" id="batas_penyimpanan_hari" name="batas_penyimpanan_hari" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('batas_penyimpanan_hari') border-red-500 @enderror"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   value="{{ old('batas_penyimpanan_hari') }}"
                                   min="1" max="365" placeholder="Contoh: 365 (maksimal 1 tahun)">
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">Batas waktu penyimpanan sebelum harus dibuang (dalam hari)</p>
                            @error('batas_penyimpanan_hari')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status Section -->
                <div class="mb-8">
                    <label for="status_aktif" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">Status <span class="text-red-500">*</span></label>
                    <select id="status_aktif" name="status_aktif" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status_aktif') border-red-500 @enderror"
                           style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
                                <option value="1" {{ old('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    <p class="mt-1 text-sm" style="color: var(--text-tertiary);">Apakah jenis limbah ini aktif</p>
                    @error('status_aktif')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Biaya Pengangkutan Section -->
                <div class="rounded-xl p-6 border mt-6" style="background-color: var(--card-secondary-bg); border-color: var(--border-primary);">
                    <h6 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                        <i class="fas fa-truck mr-2"></i>Biaya Pengangkutan
                    </h6>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="biaya_pengangkutan_per_kg" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                Biaya Pengangkutan per Kg (Rp)
                            </label>
                            <input type="number" step="0.01" min="0"
                                   class="w-full px-3 py-2 border rounded-lg"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="biaya_pengangkutan_per_kg" name="biaya_pengangkutan_per_kg"
                                   value="{{ old('biaya_pengangkutan_per_kg') }}"
                                   placeholder="Contoh: 15000">
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">
                                Biaya pengangkutan per kilogram dalam Rupiah
                            </p>
                        </div>

                        <div>
                            <label for="mulai_berlaku" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                Tanggal Mulai Berlaku
                            </label>
                            <input type="date"
                                   class="w-full px-3 py-2 border rounded-lg"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="mulai_berlaku" name="mulai_berlaku"
                                   value="{{ old('mulai_berlaku', now()->format('Y-m-d')) }}"
                                   min="{{ now()->format('Y-m-d') }}">
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">
                                Tanggal mulai berlaku untuk biaya ini
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="akhir_berlaku" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                Tanggal Akhir Berlaku (Opsional)
                            </label>
                            <input type="date"
                                   class="w-full px-3 py-2 border rounded-lg"
                                   style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                   id="akhir_berlaku" name="akhir_berlaku"
                                   value="{{ old('akhir_berlaku') }}"
                                   min="{{ old('mulai_berlaku', now()->format('Y-m-d')) }}">
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">
                                Kosongkan jika biaya masih berlaku
                            </p>
                        </div>

                        <div>
                            <label for="keterangan_biaya" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                Keterangan Biaya (Opsional)
                            </label>
                            <textarea class="w-full px-3 py-2 border rounded-lg"
                                      style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                      id="keterangan_biaya" name="keterangan_biaya"
                                      rows="3"
                                      placeholder="Keterangan tambahan mengenai biaya...">{{ old('keterangan_biaya') }}</textarea>
                            <p class="mt-1 text-sm" style="color: var(--text-tertiary);">
                                Keterangan tambahan mengenai biaya pengangkutan
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 mt-8">
                    <a href="{{ route('jenis-limbah.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                       style="background-color: var(--danger-primary); color: white;"
                       onmouseover="this.style.backgroundColor='var(--danger-hover)'; this.style.boxShadow='var(--shadow-xl)';"
                       onmouseout="this.style.backgroundColor='var(--danger-primary)'; this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg"
                           style="background-color: var(--accent-primary);"
                           onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                           onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-save mr-2"></i>Simpan Jenis Limbah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
