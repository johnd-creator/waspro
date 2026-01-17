@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary); margin-bottom: 1.5rem;">
        <div style="padding: 2rem; border-bottom: 1px solid var(--border-primary);">
            <div class="flex justify-between items-start">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Edit Jenis Limbah</h1>
                    <p style="color: var(--text-secondary);">Perbarui informasi jenis limbah yang sudah ada</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('jenis-limbah.show', $jenisLimbah) }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--accent-primary);" onmouseover="this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-eye mr-2"></i>Lihat
                    </a>
                    <a href="{{ route('jenis-limbah.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form Section -->
    <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary);">
        <div style="padding: 2rem;">
                    <form action="{{ route('jenis-limbah.update', $jenisLimbah) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-6">
                                    <label for="kode_limbah" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">Kode Limbah <span style="color: var(--danger-primary);">*</span></label>
                                    <input type="text" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); background: var(--input-bg); color: var(--text-primary); transition: all 0.2s; @error('kode_limbah') border-color: var(--danger-primary); @enderror" 
                                           id="kode_limbah" name="kode_limbah" 
                                           value="{{ old('kode_limbah', $jenisLimbah->kode_limbah) }}" 
                                           placeholder="Contoh: A101" maxlength="10" required
                                           onfocus="this.style.outline='none'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 2px var(--accent-bg)'"
                                           onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">
                                    <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--text-tertiary);">Kode unik untuk jenis limbah (maksimal 10 karakter)</p>
                                    @error('kode_limbah')
                                        <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--danger-primary);">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-6">
                                    <label for="nama_limbah" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">Nama Limbah <span style="color: var(--danger-primary);">*</span></label>
                                    <input type="text" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); background: var(--input-bg); color: var(--text-primary); transition: all 0.2s; @error('nama_limbah') border-color: var(--danger-primary); @enderror" 
                                           id="nama_limbah" name="nama_limbah" 
                                           value="{{ old('nama_limbah', $jenisLimbah->nama_limbah) }}" 
                                           placeholder="Contoh: Limbah Medis Infeksius" maxlength="100" required
                                           onfocus="this.style.outline='none'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 2px var(--accent-bg)'"
                                           onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">
                                    @error('nama_limbah')
                                        <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--danger-primary);">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-6">
                                    <label for="karakteristik_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">Karakteristik Limbah <span style="color: var(--danger-primary);">*</span></label>
                                    <select style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); background: var(--input-bg); color: var(--text-primary); transition: all 0.2s; @error('karakteristik_id') border-color: var(--danger-primary); @enderror" 
                                            id="karakteristik_id" name="karakteristik_id" required
                                            onfocus="this.style.outline='none'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 2px var(--accent-bg)'"
                                            onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">
                                        <option value="">Pilih Karakteristik</option>
                                        @foreach($karakteristikLimbah as $karakteristik)
                                            <option value="{{ $karakteristik->karakteristik_id }}" 
                                                    {{ old('karakteristik_id', $jenisLimbah->karakteristik_id) == $karakteristik->karakteristik_id ? 'selected' : '' }}>
                                                {{ $karakteristik->kode_karakteristik }} - {{ $karakteristik->nama_karakteristik }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('karakteristik_id')
                                        <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--danger-primary);">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-6">
                                    <label for="kategori_id" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">Kategori Kegiatan Sumber <span style="color: var(--danger-primary);">*</span></label>
                                     <select style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); background: var(--input-bg); color: var(--text-primary); transition: all 0.2s; @error('kategori_id') border-color: var(--danger-primary); @enderror"
                                             id="kategori_id" name="kategori_id" required
                                             onfocus="this.style.outline='none'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 2px var(--accent-bg)'"
                                             onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">
                                         <option value="">Pilih Kategori</option>
                                         @foreach($kategoriKegiatanSumber as $kategori)
                                             <option value="{{ $kategori->kategori_id }}"
                                                     {{ old('kategori_id', $jenisLimbah->kategori_id) == $kategori->kategori_id ? 'selected' : '' }}>
                                                 {{ $kategori->nama_kategori }}
                                             </option>
                                         @endforeach
                                     </select>
                                    <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--text-tertiary);">Kategori kegiatan sumber limbah</p>
                                    @error('kategori_id')
                                        <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--danger-primary);">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="deskripsi_limbah" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">Deskripsi Limbah</label>
                            <textarea style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); background: var(--input-bg); color: var(--text-primary); transition: all 0.2s; @error('deskripsi_limbah') border-color: var(--danger-primary); @enderror" 
                                      id="deskripsi_limbah" name="deskripsi_limbah" 
                                      rows="4" maxlength="500" 
                                      placeholder="Deskripsi detail tentang jenis limbah ini..."
                                      onfocus="this.style.outline='none'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 2px var(--accent-bg)'"
                                      onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">{{ old('deskripsi_limbah', $jenisLimbah->deskripsi_limbah) }}</textarea>
                            <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--text-tertiary);">Maksimal 500 karakter</p>
                            @error('deskripsi_limbah')
                                <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--danger-primary);">{{ $message }}</p>
                                     @enderror
                                 </div>
                             </div>

                             <div>
                                 <div class="mb-6">
                                     <label for="kemasan" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">Kemasan <span style="color: var(--danger-primary);">*</span></label>
                                     <input type="text" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); background: var(--input-bg); color: var(--text-primary); transition: all 0.2s; @error('kemasan') border-color: var(--danger-primary); @enderror" 
                                            id="kemasan" name="kemasan" 
                                            value="{{ old('kemasan', $jenisLimbah->kemasan) }}" 
                                            placeholder="Contoh: Kantong Plastik Kuning" maxlength="255" required
                                            onfocus="this.style.outline='none'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 2px var(--accent-bg)'"
                                            onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">
                                     <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--text-tertiary);">Jenis kemasan limbah</p>
                                     @error('kemasan')
                                         <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--danger-primary);">{{ $message }}</p>
                                     @enderror
                                 </div>
                             </div>
                             
                             <div>
                                 <div class="mb-6">
                                    <label for="waktu_penyimpanan_hari" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">Waktu Penyimpanan (Hari) <span style="color: var(--danger-primary);">*</span></label>
                                    <input type="number" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); background: var(--input-bg); color: var(--text-primary); transition: all 0.2s; @error('waktu_penyimpanan_hari') border-color: var(--danger-primary); @enderror" 
                                           id="waktu_penyimpanan_hari" name="waktu_penyimpanan_hari" 
                                           value="{{ old('waktu_penyimpanan_hari', $jenisLimbah->waktu_penyimpanan_hari) }}" 
                                           min="1" max="365" placeholder="Contoh: 90" required
                                           onfocus="this.style.outline='none'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 2px var(--accent-bg)'"
                                           onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">
                                    <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--text-tertiary);">Maksimal penyimpanan dalam hari (1-365 hari)</p>
                                    @error('waktu_penyimpanan_hari')
                                        <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--danger-primary);">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <div class="mb-6">
                                    <label for="status_aktif" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">Status <span style="color: var(--danger-primary);">*</span></label>
                                    <select style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); background: var(--input-bg); color: var(--text-primary); transition: all 0.2s; @error('status_aktif') border-color: var(--danger-primary); @enderror" 
                                            id="status_aktif" name="status_aktif" required
                                            onfocus="this.style.outline='none'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 2px var(--accent-bg)'"
                                            onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">
                                        <option value="1" {{ old('status_aktif', $jenisLimbah->status_aktif) == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('status_aktif', $jenisLimbah->status_aktif) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status_aktif')
                                        <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--danger-primary);">{{ $message }}</p>
                                    @enderror
                                </div>
                             </div>
                         </div>

                         <div class="rounded-xl p-6 border mt-6" style="background-color: var(--card-secondary-bg); border-color: var(--border-primary);">
                            <h6 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-primary);">
                                <i class="fas fa-truck mr-2"></i>Biaya Pengangkutan
                            </h6>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="biaya_pengangkutan_per_kg" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">
                                        Biaya Pengangkutan per Kg (Rp)
                                    </label>
                                    <input type="number" step="0.01" min="0"
                                           style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); background: var(--input-bg); color: var(--text-primary); transition: all 0.2s; @error('biaya_pengangkutan_per_kg') border-color: var(--danger-primary); @enderror" 
                                           id="biaya_pengangkutan_per_kg" name="biaya_pengangkutan_per_kg" 
                                           value="{{ old('biaya_pengangkutan_per_kg', $jenisLimbah->biaya_pengangkutan_per_kg) }}" 
                                           placeholder="Contoh: 15000"
                                           onfocus="this.style.outline='none'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 2px var(--accent-bg)'"
                                           onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">
                                    <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--text-tertiary);">
                                        Biaya pengangkutan per kilogram dalam Rupiah
                                    </p>
                                </div>

                                <div>
                                    <label for="mulai_berlaku" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">
                                        Tanggal Mulai Berlaku
                                    </label>
                                    <input type="date"
                                           style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); background: var(--input-bg); color: var(--text-primary); transition: all 0.2s; @error('mulai_berlaku') border-color: var(--danger-primary); @enderror" 
                                           id="mulai_berlaku" name="mulai_berlaku" 
                                           value="{{ old('mulai_berlaku', optional($jenisLimbah->mulai_berlaku)->format('Y-m-d')) }}" 
                                           min="{{ now()->format('Y-m-d') }}"
                                           onfocus="this.style.outline='none'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 2px var(--accent-bg)'"
                                           onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">
                                    <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--text-tertiary);">
                                        Tanggal mulai berlaku untuk biaya ini
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <div>
                                    <label for="akhir_berlaku" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">
                                        Tanggal Akhir Berlaku (Opsional)
                                    </label>
                                    <input type="date"
                                           style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.375rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); background: var(--input-bg); color: var(--text-primary); transition: all 0.2s; @error('akhir_berlaku') border-color: var(--danger-primary); @enderror" 
                                           id="akhir_berlaku" name="akhir_berlaku" 
                                           value="{{ old('akhir_berlaku', optional($jenisLimbah->akhir_berlaku)->format('Y-m-d')) }}" 
                                           min="{{ old('mulai_berlaku', optional($jenisLimbah->mulai_berlaku)->format('Y-m-d')) }}"
                                           onfocus="this.style.outline='none'; this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 2px var(--accent-bg)'"
                                           onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">
                                    <p style="margin-top: 0.25rem; font-size: 0.875rem; color: var(--text-tertiary);">
                                        Kosongkan jika biaya masih berlaku
                                    </p>
                                </div>

                                <div>
                                    <label for="keterangan_biaya" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--text-primary); margin-bottom: 0.5rem;">
                                        Keterangan Biaya (Opsional)
                                    </label>
                                    <textarea class="w-full px-3 py-2 border rounded-lg"
                                              style="background-color: var(--input-bg); border-color: var(--border-primary);"
                                              id="keterangan_biaya" name="keterangan_biaya" 
                                              rows="3"
                                              placeholder="Keterangan tambahan mengenai biaya...">{{ old('keterangan_biaya', is_array($jenisLimbah->keterangan_biaya) ? json_encode($jenisLimbah->keterangan_biaya) : $jenisLimbah->keterangan_biaya) }}</textarea>
                                </div>
                            </div>
                        </div>

                         <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('jenis-limbah.show', $jenisLimbah) }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--danger-primary); color: white;" onmouseover="this.style.backgroundColor='var(--danger-hover)'; this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.backgroundColor='var(--danger-primary)'; this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--accent-primary);" onmouseover="this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-save mr-2"></i>Update
                            </button>
                        </div>
                    </form>
        </div>
    </div>
</div>
@endsection
