@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary); margin-bottom: 1.5rem;">
        <div style="padding: 2rem; border-bottom: 1px solid var(--border-primary);">
            <div class="flex justify-between items-start">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Edit Kategori Kegiatan Sumber</h1>
                    <p style="color: var(--text-secondary);">Ubah data kategori kegiatan sumber dalam sistem</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('kategori-kegiatan-sumber.show', $kategoriKegiatanSumber) }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--accent-primary);" onmouseover="this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-eye mr-2"></i>Lihat
                    </a>
                    <a href="{{ route('kategori-kegiatan-sumber.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form Section -->
    <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary);">
        <div style="padding: 2rem;">
            <form action="{{ route('kategori-kegiatan-sumber.update', $kategoriKegiatanSumber) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nama_kategori" style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 500;">Nama Kategori <span style="color: var(--danger-primary);">*</span></label>
                            <input type="text" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-primary); border-radius: 0.5rem; background: var(--input-bg); color: var(--input-text); transition: all 0.2s; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);" 
                                   class="@error('nama_kategori') is-invalid @enderror" 
                                   id="nama_kategori" name="nama_kategori" 
                                   value="{{ old('nama_kategori', $kategoriKegiatanSumber->nama_kategori) }}" 
                                   placeholder="Contoh: Kegiatan Medis" maxlength="100" required
                                   onfocus="this.style.borderColor='var(--accent-primary)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'"
                                   onblur="this.style.borderColor='var(--border-primary)'; this.style.boxShadow='0 1px 2px 0 rgba(0, 0, 0, 0.05)'">
                            @error('nama_kategori')
                                <div style="color: var(--danger-primary); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('kategori-kegiatan-sumber.show', $kategoriKegiatanSumber) }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--danger-primary); color: white;" onmouseover="this.style.backgroundColor='var(--danger-hover)'; this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.backgroundColor='var(--danger-primary)'; this.style.boxShadow='var(--shadow-lg)';">
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
