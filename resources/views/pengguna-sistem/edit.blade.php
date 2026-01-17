@extends('layouts.app')

@section('title', 'Edit Pengguna Sistem')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="rounded-2xl shadow-sm border mb-6" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6" style="border-color: var(--border-primary);">
            <div class="flex justify-between items-center">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Edit Pengguna Sistem</h1>
                    <p style="color: var(--text-secondary);">Perbarui informasi pengguna: {{ $penggunaSistem->nama_lengkap }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pengguna-sistem.show', $penggunaSistem) }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--accent-secondary); color: white;"
                   onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                   onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-eye mr-2"></i>Lihat Detail
                    </a>
                    <a href="{{ route('pengguna-sistem.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                       style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);"
                       onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';"
                       onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6">
            <h6 class="text-lg font-semibold mb-4" style="color: var(--text-primary); display: flex; align-items: center;">
                <i class="fas fa-user-edit mr-2"></i>Form Edit Pengguna
            </h6>
            <div class="">
                    <form action="{{ route('pengguna-sistem.update', $penggunaSistem) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 style="color: var(--accent-primary); border-bottom: 2px solid var(--border-secondary); padding-bottom: 0.5rem; margin-bottom: 0.75rem;">
                                    <i class="fas fa-user me-2"></i>Informasi Dasar
                                </h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nama_lengkap" style="color: var(--text-primary); font-weight: 500; margin-bottom: 0.5rem; display: block;">Nama Lengkap <span style="color: var(--danger-primary);">*</span></label>
                                <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                       id="nama_lengkap" name="nama_lengkap"
                                       value="{{ old('nama_lengkap', $penggunaSistem->nama_lengkap) }}"
                                       placeholder="Masukkan nama lengkap" required
                                       style="background: var(--input-bg); border: 1px solid var(--border-primary); color: var(--input-text); border-radius: 0.5rem; padding: 0.75rem;">
                                @error('nama_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email_address" style="color: var(--text-primary); font-weight: 500; margin-bottom: 0.5rem; display: block;">Email Address <span style="color: var(--danger-primary);">*</span></label>
                                <input type="email" class="form-control @error('email_address') is-invalid @enderror"
                                       id="email_address" name="email_address"
                                       value="{{ old('email_address', $penggunaSistem->email_address) }}"
                                       placeholder="contoh@email.com" required
                                       style="background: var(--input-bg); border: 1px solid var(--border-primary); color: var(--input-text); border-radius: 0.5rem; padding: 0.75rem;">
                                @error('email_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Keamanan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 style="color: var(--accent-primary); border-bottom: 2px solid var(--border-secondary); padding-bottom: 0.5rem; margin-bottom: 0.75rem;">
                                    <i class="fas fa-lock me-2"></i>Keamanan
                                    <small style="color: var(--text-secondary);">(Kosongkan jika tidak ingin mengubah kata sandi)</small>
                                </h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="kata_sandi" style="color: var(--text-primary); font-weight: 500; margin-bottom: 0.5rem; display: block;">Kata Sandi Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('kata_sandi') is-invalid @enderror"
                                           id="kata_sandi" name="kata_sandi" placeholder="Minimal 8 karakter"
                                           style="background: var(--input-bg); border: 1px solid var(--border-primary); color: var(--input-text); border-radius: 0.5rem 0 0 0.5rem; padding: 0.75rem;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('kata_sandi')"
                                            style="background: var(--secondary-bg-light); border: 1px solid var(--border-primary); color: var(--text-secondary); border-radius: 0 0.5rem 0.5rem 0;">
                                        <i class="fas fa-eye" id="kata_sandi-icon"></i>
                                    </button>
                                    @error('kata_sandi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small style="color: var(--text-secondary);">Kosongkan jika tidak ingin mengubah kata sandi</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="kata_sandi_confirmation" style="color: var(--text-primary); font-weight: 500; margin-bottom: 0.5rem; display: block;">Konfirmasi Kata Sandi Baru</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('kata_sandi_confirmation') is-invalid @enderror"
                                           id="kata_sandi_confirmation" name="kata_sandi_confirmation"
                                           placeholder="Ulangi kata sandi baru"
                                           style="background: var(--input-bg); border: 1px solid var(--border-primary); color: var(--input-text); border-radius: 0.5rem 0 0 0.5rem; padding: 0.75rem;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('kata_sandi_confirmation')"
                                            style="background: var(--secondary-bg-light); border: 1px solid var(--border-primary); color: var(--text-secondary); border-radius: 0 0.5rem 0.5rem 0;">
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
                                <h6 style="color: var(--accent-primary); border-bottom: 2px solid var(--border-secondary); padding-bottom: 0.5rem; margin-bottom: 0.75rem;">
                                    <i class="fas fa-building me-2"></i>Organisasi
                                </h6>
                            </div>

                            <div class="col-md-6 mb-3" id="unit-field">
                                <label for="unit_id" style="color: var(--text-primary); font-weight: 500; margin-bottom: 0.5rem; display: block;">Unit Pembangkit <span id="unit-required" style="color: var(--danger-primary);">*</span></label>
                                <select class="form-select @error('unit_id') is-invalid @enderror"
                                        id="unit_id" name="unit_id" required
                                        style="background: var(--input-bg); border: 1px solid var(--border-primary); color: var(--input-text); border-radius: 0.5rem; padding: 0.75rem;">
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
                                <label style="color: var(--text-primary); font-weight: 500; margin-bottom: 0.5rem; display: block;">Peran Pengguna <span style="color: var(--danger-primary);">*</span></label>
                                <div style="border: 1px solid var(--border-primary); border-radius: 0.5rem; padding: 0.75rem; background: var(--input-bg);" class="@error('peran_ids') border-danger @enderror">
                                    @foreach($peranList as $peran)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input peran-checkbox" type="checkbox"
                                                   id="peran_{{ $peran->peran_id }}"
                                                   name="peran_ids[]" value="{{ $peran->peran_id }}"
                                                   data-peran="{{ $peran->nama_peran }}"
                                                   {{ in_array($peran->peran_id, old('peran_ids', $userPeranIds)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="peran_{{ $peran->peran_id }}" style="color: var(--text-primary);">
                                                <strong>{{ $peran->nama_peran }}</strong>
                                                @if($peran->deskripsi_peran)
                                                    <br><small style="color: var(--text-secondary);">{{ $peran->deskripsi_peran }}</small>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('peran_ids')
                                    <div style="color: var(--danger-primary); font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                                @enderror
                                <small style="color: var(--text-secondary);">Pilih minimal satu peran</small>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 style="color: var(--accent-primary); border-bottom: 2px solid var(--border-secondary); padding-bottom: 0.5rem; margin-bottom: 0.75rem;">
                                    <i class="fas fa-toggle-on me-2"></i>Status
                                </h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="aktif"
                                           name="aktif" value="1"
                                           {{ old('aktif', $penggunaSistem->aktif) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="aktif" style="color: var(--text-primary);">
                                        <strong>Status Aktif</strong>
                                        <br><small style="color: var(--text-secondary);">Pengguna dapat login dan mengakses sistem</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tambahan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 style="color: var(--accent-primary); border-bottom: 2px solid var(--border-secondary); padding-bottom: 0.5rem; margin-bottom: 0.75rem;">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                                </h6>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label style="color: var(--text-primary); font-weight: 500; margin-bottom: 0.5rem; display: block;">Dibuat Pada</label>
                                <input type="text" class="form-control"
                                       value="{{ $penggunaSistem->created_at ? $penggunaSistem->created_at->format('d/m/Y H:i') : 'N/A' }}"
                                       readonly
                                       style="background: var(--secondary-bg-light); border: 1px solid var(--border-primary); color: var(--text-secondary); border-radius: 0.5rem; padding: 0.75rem;">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label style="color: var(--text-primary); font-weight: 500; margin-bottom: 0.5rem; display: block;">Terakhir Diperbarui</label>
                                <input type="text" class="form-control"
                                       value="{{ $penggunaSistem->updated_at ? $penggunaSistem->updated_at->format('d/m/Y H:i') : 'N/A' }}"
                                       readonly
                                       style="background: var(--secondary-bg-light); border: 1px solid var(--border-primary); color: var(--text-secondary); border-radius: 0.5rem; padding: 0.75rem;">
                            </div>
                        </div>

                        <!-- Action Buttons -->
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
                                <i class="fas fa-save mr-2"></i>Perbarui Pengguna
                            </button>
                        </div>
                    </form>
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
    background-color: var(--accent-primary);
    border-color: var(--accent-primary);
}

.border-bottom {
    border-bottom: 2px solid var(--border-secondary) !important;
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
    background-color: var(--secondary-bg-light);
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

// Handle Super Admin role on page load and change
document.addEventListener('DOMContentLoaded', function() {
    handleSuperAdminRole();
});

document.querySelectorAll('.peran-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', handleSuperAdminRole);
});

// Validasi form sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
    const peranCheckboxes = document.querySelectorAll('input[name="peran_ids[]"]');
    const isAnyChecked = Array.from(peranCheckboxes).some(checkbox => checkbox.checked);

    if (!isAnyChecked) {
        e.preventDefault();
        showNotification('Silakan pilih minimal satu peran untuk pengguna.', 'warning');
        return false;
    }

    // Validasi password confirmation jika password diisi
    const password = document.getElementById('kata_sandi').value;
    const passwordConfirmation = document.getElementById('kata_sandi_confirmation').value;

    if (password && password !== passwordConfirmation) {
        e.preventDefault();
        showNotification('Konfirmasi kata sandi tidak cocok.', 'error');
        return false;
    }
});

// Simple notification function
function showNotification(message, type = 'info') {
    // Create notification container if it doesn't exist
    let container = document.getElementById('notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notification-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
    }

    const notification = document.createElement('div');
    const typeClasses = {
        success: 'bg-green-500 border-green-600',
        error: 'bg-red-500 border-red-600',
        warning: 'bg-yellow-500 border-yellow-600',
        info: 'bg-blue-500 border-blue-600'
    };

    notification.className = `
        ${typeClasses[type] || typeClasses.info}
        text-white px-6 py-4 rounded-lg shadow-lg border-l-4
        transform transition-all duration-300 ease-in-out
        max-w-sm
    `;

    const icon = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };

    notification.innerHTML = `
        <div class="flex items-center">
            <i class="${icon[type] || icon.info} mr-3"></i>
            <span class="flex-1">${message}</span>
            <button class="ml-4 text-white hover:text-gray-200 focus:outline-none" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    container.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}
</script>
@endsection
