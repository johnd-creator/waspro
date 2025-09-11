@extends('layouts.app')

@section('title', 'Edit Pengguna Sistem')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Pengguna Sistem</h1>
            <p class="text-muted mb-0">Perbarui informasi pengguna: {{ $penggunaSistem->nama_lengkap }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pengguna-sistem.show', $penggunaSistem) }}" class="btn btn-info">
                <i class="fas fa-eye me-2"></i>Lihat Detail
            </a>
            <a href="{{ route('pengguna-sistem.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit me-2"></i>Form Edit Pengguna
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('pengguna-sistem.update', $penggunaSistem) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user me-2"></i>Informasi Dasar
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                       id="nama_lengkap" name="nama_lengkap" 
                                       value="{{ old('nama_lengkap', $penggunaSistem->nama_lengkap) }}" 
                                       placeholder="Masukkan nama lengkap" required>
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email_address" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email_address') is-invalid @enderror" 
                                       id="email_address" name="email_address" 
                                       value="{{ old('email_address', $penggunaSistem->email_address) }}" 
                                       placeholder="contoh@email.com" required>
                                @error('email_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Keamanan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-lock me-2"></i>Keamanan
                                    <small class="text-muted">(Kosongkan jika tidak ingin mengubah kata sandi)</small>
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="kata_sandi" class="form-label">Kata Sandi Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('kata_sandi') is-invalid @enderror" 
                                           id="kata_sandi" name="kata_sandi" placeholder="Minimal 8 karakter">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('kata_sandi')">
                                        <i class="fas fa-eye" id="kata_sandi-icon"></i>
                                    </button>
                                    @error('kata_sandi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah kata sandi</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="kata_sandi_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('kata_sandi_confirmation') is-invalid @enderror" 
                                           id="kata_sandi_confirmation" name="kata_sandi_confirmation" 
                                           placeholder="Ulangi kata sandi baru">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('kata_sandi_confirmation')">
                                        <i class="fas fa-eye" id="kata_sandi_confirmation-icon"></i>
                                    </button>
                                    @error('kata_sandi_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Organisasi -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-building me-2"></i>Organisasi
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="unit_id" class="form-label">Unit Pembangkit <span class="text-danger">*</span></label>
                            <select class="form-select @error('unit_id') is-invalid @enderror"
                                    id="unit_id" name="unit_id" required>
                                <option value="">Pilih Unit Pembangkit</option>
                                @foreach($unitList as $unit)
                                    <option value="{{ $unit->unit_id }}"
                                            {{ old('unit_id', $penggunaSistem->unit_id) == $unit->unit_id ? 'selected' : '' }}>
                                        {{ $unit->nama_unit }} - {{ $unit->kota }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Peran Pengguna <span class="text-danger">*</span></label>
                                <div class="border rounded p-3 @error('peran_ids') border-danger @enderror">
                                    @foreach($peranList as $peran)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="peran_{{ $peran->peran_id }}" 
                                                   name="peran_ids[]" value="{{ $peran->peran_id }}"
                                                   {{ in_array($peran->peran_id, old('peran_ids', $userPeranIds)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="peran_{{ $peran->peran_id }}">
                                                <strong>{{ $peran->nama_peran }}</strong>
                                                @if($peran->deskripsi_peran)
                                                    <br><small class="text-muted">{{ $peran->deskripsi_peran }}</small>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('peran_ids')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Pilih minimal satu peran</small>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-toggle-on me-2"></i>Status
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="aktif" 
                                           name="aktif" value="1" 
                                           {{ old('aktif', $penggunaSistem->aktif) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aktif">
                                        <strong>Status Aktif</strong>
                                        <br><small class="text-muted">Pengguna dapat login dan mengakses sistem</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dibuat Pada</label>
                                <input type="text" class="form-control" 
                                       value="{{ $penggunaSistem->created_at ? $penggunaSistem->created_at->format('d/m/Y H:i') : 'N/A' }}" 
                                       readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Terakhir Diperbarui</label>
                                <input type="text" class="form-control" 
                                       value="{{ $penggunaSistem->updated_at ? $penggunaSistem->updated_at->format('d/m/Y H:i') : 'N/A' }}" 
                                       readonly>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('pengguna-sistem.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Perbarui Pengguna
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border: none;
}

.form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

.border-bottom {
    border-bottom: 2px solid #e3e6f0 !important;
}

.input-group .btn {
    border-left: none;
}

.form-check {
    padding-left: 1.5em;
}

.form-check-input {
    margin-left: -1.5em;
}

.form-control[readonly] {
    background-color: #f8f9fc;
    opacity: 1;
}
</style>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Validasi form sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
    const peranCheckboxes = document.querySelectorAll('input[name="peran_ids[]"]');
    const isAnyChecked = Array.from(peranCheckboxes).some(checkbox => checkbox.checked);
    
    if (!isAnyChecked) {
        e.preventDefault();
        alert('Silakan pilih minimal satu peran untuk pengguna.');
        return false;
    }
    
    // Validasi password confirmation jika password diisi
    const password = document.getElementById('kata_sandi').value;
    const passwordConfirmation = document.getElementById('kata_sandi_confirmation').value;
    
    if (password && password !== passwordConfirmation) {
        e.preventDefault();
        alert('Konfirmasi kata sandi tidak cocok.');
        return false;
    }
});
</script>
@endsection