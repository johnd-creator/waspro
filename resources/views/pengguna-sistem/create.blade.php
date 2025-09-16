@extends('layouts.app')

@section('title', 'Tambah Pengguna Sistem')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="rounded-2xl shadow-sm border mb-6" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-8 py-6 border-b" style="border-color: var(--border-primary);">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">Tambah Pengguna Sistem</h1>
                    <p style="color: var(--text-secondary);">Buat pengguna sistem baru untuk unit pembangkit</p>
                </div>
                <a href="{{ route('pengguna-sistem.index') }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200"
                   style="background-color: var(--secondary-bg);"
                   onmouseover="this.style.backgroundColor='var(--secondary-bg-hover)'; this.style.boxShadow='var(--shadow-xl)';" 
                   onmouseout="this.style.backgroundColor='var(--secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="max-w-4xl mx-auto">
        <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
            <div class="px-8 py-6">
            <div class="card" style="background-color: var(--card-bg); border-color: var(--border-primary);">
                <div class="card-header" style="background-color: var(--card-bg); border-color: var(--border-primary);">
                    <h6 class="m-0 font-weight-bold" style="color: var(--accent-primary);">
                        <i class="fas fa-user-plus me-2"></i>Form Tambah Pengguna
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('pengguna-sistem.store') }}" method="POST">
                        @csrf
                        
                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3" style="color: var(--accent-primary); border-color: var(--border-primary);">
                                    <i class="fas fa-user me-2"></i>Informasi Dasar
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nama_lengkap" class="form-label" style="color: var(--text-primary);">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                       style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                       id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" 
                                       placeholder="Masukkan nama lengkap" required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email_address" class="form-label" style="color: var(--text-primary);">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email_address') is-invalid @enderror" 
                                       style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                       id="email_address" name="email_address" value="{{ old('email_address') }}" 
                                       placeholder="contoh@email.com" required>
                                @error('email_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Keamanan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3" style="color: var(--accent-primary); border-color: var(--border-primary);">
                                    <i class="fas fa-lock me-2"></i>Keamanan
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="kata_sandi" class="form-label" style="color: var(--text-primary);">Kata Sandi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('kata_sandi') is-invalid @enderror" 
                                           style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                           id="kata_sandi" name="kata_sandi" placeholder="Minimal 8 karakter" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('kata_sandi')"
                                            style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--text-primary);">
                                        <i class="fas fa-eye" id="kata_sandi-icon"></i>
                                    </button>
                                    @error('kata_sandi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small style="color: var(--text-secondary);">Kata sandi harus minimal 8 karakter</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="kata_sandi_confirmation" class="form-label" style="color: var(--text-primary);">Konfirmasi Kata Sandi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('kata_sandi_confirmation') is-invalid @enderror" 
                                           style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                           id="kata_sandi_confirmation" name="kata_sandi_confirmation" 
                                           placeholder="Ulangi kata sandi" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('kata_sandi_confirmation')"
                                            style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--text-primary);">
                                        <i class="fas fa-eye" id="kata_sandi_confirmation-icon"></i>
                                    </button>
                                    @error('kata_sandi_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Unit & Peran -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3" style="color: var(--accent-primary); border-color: var(--border-primary);">
                                    <i class="fas fa-building me-2"></i>Unit & Peran
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="unit_id" class="form-label" style="color: var(--text-primary);">Unit Pembangkit <span class="text-danger">*</span></label>
                                <select class="form-select @error('unit_id') is-invalid @enderror" 
                                        style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);"
                                        id="unit_id" name="unit_id" required>
                                    <option value="">Pilih Unit Pembangkit</option>
                                    @foreach($unitList as $unit)
                                    <option value="{{ $unit->unit_id }}" 
                                            {{ old('unit_id') == $unit->unit_id ? 'selected' : '' }}>
                                        {{ $unit->nama_unit }} - {{ $unit->kota }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: var(--text-primary);">Peran Pengguna <span class="text-danger">*</span></label>
                                <div class="border rounded p-3 @error('peran_ids') border-danger @enderror"
                                     style="background-color: var(--input-bg); border-color: var(--border-primary);">
                                    @foreach($peranList as $peran)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="peran_{{ $peran->peran_id }}" 
                                                   name="peran_ids[]" value="{{ $peran->peran_id }}"
                                                   {{ in_array($peran->peran_id, old('peran_ids', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="peran_{{ $peran->peran_id }}"
                                                   style="color: var(--text-primary);">
                                                {{ $peran->nama_peran }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('peran_ids')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3" style="color: var(--accent-primary); border-color: var(--border-primary);">
                                    <i class="fas fa-toggle-on me-2"></i>Status
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: var(--text-primary);">Status Akun</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status_aktif" name="status_aktif" value="1" 
                                           {{ old('status_aktif', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif" style="color: var(--text-primary);">Aktif</label>
                                </div>
                                <small style="color: var(--text-secondary);">Pengguna tidak aktif tidak dapat login ke sistem</small>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('pengguna-sistem.index') }}" class="btn"
                               style="background-color: var(--secondary-bg); color: white;"
                               onmouseover="this.style.backgroundColor='var(--secondary-bg-hover)';" 
                               onmouseout="this.style.backgroundColor='var(--secondary-bg)';">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn"
                                    style="background-color: var(--primary-bg); color: white;"
                                    onmouseover="this.style.backgroundColor='var(--primary-bg-hover)';" 
                                    onmouseout="this.style.backgroundColor='var(--primary-bg)';">
                                <i class="fas fa-save me-1"></i> Simpan Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '-icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush