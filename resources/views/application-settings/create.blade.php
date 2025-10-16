@extends('layouts.app')

@section('title', 'Tambah System Setting')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="rounded-2xl shadow-sm border mb-6" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6 flex justify-between items-center" style="border-color: var(--border-primary);">
            <div>
                <h1 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">Tambah System Setting</h1>
                <p style="color: var(--text-secondary);">Buat pengaturan sistem baru</p>
            </div>
            <a href="{{ route('application-settings.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
               style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);"
               onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';"
               onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6">
            <h6 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">
                <i class="fas fa-plus mr-2"></i>Form Tambah Setting
            </h6>
            <div>
                <form action="{{ route('application-settings.store') }}" method="POST">
                    @csrf
                        
                        <!-- Informasi Dasar -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-cog me-2"></i>Informasi Setting
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="key" class="form-label">Key <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('key') is-invalid @enderror" 
                                       id="key" name="key" value="{{ old('key') }}" 
                                       placeholder="e.g., app.name, mail.driver" required>
                                <div class="form-text">Gunakan format dot notation (e.g., app.name)</div>
                                @error('key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Tipe Data <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" name="type" required onchange="toggleValueInput()">
                                    <option value="">Pilih Tipe Data</option>
                                    <option value="string" {{ old('type') == 'string' ? 'selected' : '' }}>String</option>
                                    <option value="integer" {{ old('type') == 'integer' ? 'selected' : '' }}>Integer</option>
                                    <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Boolean</option>
                                    <option value="json" {{ old('type') == 'json' ? 'selected' : '' }}>JSON</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                       id="category" name="category" value="{{ old('category') }}" 
                                       placeholder="e.g., general, mail, database" required>
                                <div class="form-text">Kategori untuk mengelompokkan settings</div>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                           name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Setting Aktif
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Deskripsi penggunaan setting ini...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Value Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-edit me-2"></i>Nilai Setting
                                </h6>
                            </div>
                            
                            <!-- String/Integer Input -->
                            <div class="col-12 mb-3" id="string-input" style="display: none;">
                                <label for="value_string" class="form-label">Value <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('value') is-invalid @enderror" 
                                       id="value_string" name="value_string" value="{{ old('value') }}" 
                                       placeholder="Masukkan nilai setting">
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Boolean Input -->
                            <div class="col-12 mb-3" id="boolean-input" style="display: none;">
                                <label for="value_boolean" class="form-label">Value <span class="text-danger">*</span></label>
                                <select class="form-select @error('value') is-invalid @enderror" 
                                        id="value_boolean" name="value_boolean">
                                    <option value="1" {{ old('value') == '1' ? 'selected' : '' }}>True</option>
                                    <option value="0" {{ old('value') == '0' ? 'selected' : '' }}>False</option>
                                </select>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- JSON Input -->
                            <div class="col-12 mb-3" id="json-input" style="display: none;">
                                <label for="value_json" class="form-label">Value (JSON) <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('value') is-invalid @enderror" 
                                          id="value_json" name="value_json" rows="5" 
                                          placeholder='{"key": "value"}'>{{ old('value') }}</textarea>
                                <div class="form-text">Masukkan JSON yang valid</div>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('application-settings.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                               style="background-color: var(--danger-primary); color: white;"
                               onmouseover="this.style.backgroundColor='var(--danger-hover)'; this.style.boxShadow='var(--shadow-xl)';"
                               onmouseout="this.style.backgroundColor='var(--danger-primary)'; this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg"
                                    style="background-color: var(--accent-primary);"
                                    onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                                    onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-save mr-2"></i>Simpan Setting
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.form-label { font-weight: 600; color: var(--text-primary); }
.border-bottom { border-color: var(--border-primary) !important; }
.form-check-input:checked { background-color: var(--accent-primary); border-color: var(--accent-primary); }
</style>

<script>
function toggleValueInput() {
    const type = document.getElementById('type').value;
    const stringInput = document.getElementById('string-input');
    const booleanInput = document.getElementById('boolean-input');
    const jsonInput = document.getElementById('json-input');
    
    // Hide all inputs
    stringInput.style.display = 'none';
    booleanInput.style.display = 'none';
    jsonInput.style.display = 'none';
    
    // Clear all inputs
    document.getElementById('value_string').value = '';
    document.getElementById('value_boolean').value = '1';
    document.getElementById('value_json').value = '';
    
    // Show appropriate input
    if (type === 'string' || type === 'integer') {
        stringInput.style.display = 'block';
        if (type === 'integer') {
            document.getElementById('value_string').type = 'number';
            document.getElementById('value_string').placeholder = 'Masukkan angka';
        } else {
            document.getElementById('value_string').type = 'text';
            document.getElementById('value_string').placeholder = 'Masukkan nilai setting';
        }
    } else if (type === 'boolean') {
        booleanInput.style.display = 'block';
    } else if (type === 'json') {
        jsonInput.style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleValueInput();
});
</script>
@endsection
