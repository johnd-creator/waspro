@extends('layouts.app')

@section('title', 'Tambah Pengguna Sistem')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="rounded-2xl shadow-sm border mb-6" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6 flex justify-between items-center" style="border-color: var(--border-primary);">
            <div>
                <div>
                    <h1 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">Tambah Pengguna Sistem</h1>
                    <p style="color: var(--text-secondary);">Buat pengguna sistem baru untuk unit pembangkit</p>
                </div>
                </div>
                <a href="{{ route('pengguna-sistem.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);"
                   onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';" 
                   onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
        </div>
    </div>

    <!-- Form Section -->
    <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6">
            <h6 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                <i class="fas fa-user-plus me-2"></i>Form Tambah Pengguna
            </h6>
                    <form action="{{ route('pengguna-sistem.store') }}" method="POST">
                        @csrf
                        
                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="section-title">
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
                                <h6 class="section-title">
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
                                <small class="help-text">Kata sandi harus minimal 8 karakter</small>
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
                                <h6 class="section-title">
                                    <i class="fas fa-building me-2"></i>Unit & Peran
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3" id="unit-field">
                                <label for="unit_id" class="form-label" style="color: var(--text-primary);">Unit Pembangkit <span id="unit-required" class="text-danger">*</span></label>
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
                                            <input class="form-check-input peran-checkbox" type="checkbox" 
                                                   id="peran_{{ $peran->peran_id }}" 
                                                   name="peran_ids[]" value="{{ $peran->peran_id }}"
                                                   data-peran="{{ $peran->nama_peran }}"
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
                                <h6 class="section-title">
                                    <i class="fas fa-toggle-on me-2"></i>Status
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label" style="color: var(--text-primary);">Status Akun</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="aktif" name="aktif" value="1" 
                                           {{ old('aktif', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aktif" style="color: var(--text-primary);">Aktif</label>
                                </div>
                                <small style="color: var(--text-secondary);">Pengguna tidak aktif tidak dapat login ke sistem</small>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('pengguna-sistem.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                               style="background-color: var(--danger-primary); color: white;"
                               onmouseover="this.style.backgroundColor='var(--danger-hover)'; this.style.boxShadow='var(--shadow-xl)';" 
                               onmouseout="this.style.backgroundColor='var(--danger-primary)'; this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg"
                                    style="background-color: var(--accent-primary);"
                                    onmouseover="this.style.boxShadow='var(--shadow-xl)';" 
                                    onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-save mr-2"></i>Simpan Pengguna
                            </button>
                        </div>
                    </form>
    </div>
</div>
@endsection

<style>
/* Input & Select enhanced styles */
.form-control, .form-select {
    background-color: var(--input-bg);
    border: 1px solid var(--border-primary);
    color: var(--input-text);
    border-radius: 0.75rem;
    padding: 0.75rem 1rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.form-control::placeholder { color: var(--text-secondary); }
.form-control:focus, .form-select:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px var(--accent-bg);
}

/* Input group for password toggle */
.input-group .form-control { border-right: 0; border-radius: 0.75rem 0 0 0.75rem; }
.input-group .btn {
    background-color: var(--secondary-bg-light);
    border: 1px solid var(--border-primary);
    color: var(--text-secondary);
    border-left: 0;
    border-radius: 0 0.75rem 0.75rem 0;
    transition: background-color 0.2s ease, box-shadow 0.2s ease;
}
.input-group .btn:hover { background-color: var(--hover-bg); }

/* Labels & feedback */
.form-label { color: var(--text-primary); font-weight: 500; }
.invalid-feedback { color: var(--danger-primary); }
.help-text { color: var(--text-secondary); }

/* Checkboxes & switches */
.form-check-input { background-color: var(--input-bg); border-color: var(--border-primary); }
.form-check-input:checked { background-color: var(--accent-primary); border-color: var(--accent-primary); }
.form-check-label { color: var(--text-primary); }

/* Section title */
.section-title { color: var(--accent-primary); border-bottom: 2px solid var(--border-secondary); padding-bottom: 0.5rem; margin-bottom: 0.75rem; }
</style>

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

    function handleSuperAdminRole() {
        const checkboxes = document.querySelectorAll('.peran-checkbox');
        const unitField = document.getElementById('unit-field');
        const unitRequired = document.getElementById('unit-required');
        const unitInput = document.getElementById('unit_id');
        let isSuperAdmin = false;

        checkboxes.forEach(checkbox => {
            if (checkbox.checked && checkbox.dataset.peran === 'Super Admin') {
                isSuperAdmin = true;
            }
        });

        if (isSuperAdmin) {
            unitField.style.display = 'none';
            unitInput.removeAttribute('required');
            unitRequired.classList.add('d-none');
            unitInput.value = '';
        } else {
            unitField.style.display = 'block';
            unitInput.setAttribute('required', 'required');
            unitRequired.classList.remove('d-none');
        }
    }

    document.querySelectorAll('.peran-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', handleSuperAdminRole);
    });
</script>
@endpush
