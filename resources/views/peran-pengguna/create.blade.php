@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="rounded-2xl shadow-sm border mb-6" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">Tambah Peran Pengguna</h1>
                    <p style="color: var(--text-secondary);">Buat peran baru untuk pengguna sistem</p>
                </div>
                <div>
                    <a href="{{ route('peran-pengguna.index') }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200"
                       style="background-color: var(--secondary-bg);"
                       onmouseover="this.style.backgroundColor='var(--secondary-bg-hover)'; this.style.boxShadow='var(--shadow-xl)';" 
                       onmouseout="this.style.backgroundColor='var(--secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="max-w-4xl mx-auto">
        <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
            <div class="px-8 py-6 border-b" style="border-color: var(--border-primary);">
                <h6 class="text-lg font-semibold flex items-center" style="color: var(--text-primary);">
                    <i class="fas fa-user-plus mr-2"></i>Form Tambah Peran
                </h6>
            </div>
            <div class="px-8 py-6">
                <x-session-messages />
                
                <form action="{{ route('peran-pengguna.store') }}" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <x-form-input 
                                    name="nama_peran" 
                                    label="Nama Peran" 
                                    placeholder="Masukkan nama peran" 
                                    :required="true" 
                                    maxlength="255" 
                                />
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active" style="color: var(--text-primary);">Status</label>
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active" style="color: var(--text-primary);">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                            <x-form-input 
                                type="textarea" 
                                name="deskripsi" 
                                label="Deskripsi" 
                                placeholder="Masukkan deskripsi peran (opsional)" 
                                rows="4" 
                                maxlength="1000" 
                            />
                        </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('peran-pengguna.index') }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200"
                               style="background-color: var(--secondary-bg);"
                               onmouseover="this.style.backgroundColor='var(--secondary-bg-hover)'; this.style.boxShadow='var(--shadow-xl)';" 
                               onmouseout="this.style.backgroundColor='var(--secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg"
                                    style="background-color: var(--primary-bg);"
                                    onmouseover="this.style.backgroundColor='var(--primary-bg-hover)'; this.style.boxShadow='var(--shadow-xl)';" 
                                    onmouseout="this.style.backgroundColor='var(--primary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-save mr-2"></i>Simpan
                            </button>
                        </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection