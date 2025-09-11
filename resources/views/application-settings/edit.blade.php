@extends('layouts.app')

@section('title', 'Edit System Setting')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit System Setting</h1>
            <p class="text-muted mb-0">Edit pengaturan sistem: <code>{{ $setting->key }}</code></p>
        </div>
        <a href="{{ route('application-settings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Form Edit Setting
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('application-settings.update', $setting) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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
                                       id="key" name="key" value="{{ old('key', $setting->key) }}" 
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
                                    <option value="string" {{ old('type', $setting->type) == 'string' ? 'selected' : '' }}>String</option>
                                    <option value="integer" {{ old('type', $setting->type) == 'integer' ? 'selected' : '' }}>Integer</option>
                                    <option value="boolean" {{ old('type', $setting->type) == 'boolean' ? 'selected' : '' }}>Boolean</option>
                                    <option value="json" {{ old('type', $setting->type) == 'json' ? 'selected' : '' }}>JSON</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                       id="category" name="category" value="{{ old('category', $setting->category) }}" 
                                       placeholder="e.g., general, mail, database" required>
                                <div class="form-text">Kategori untuk mengelompokkan settings</div>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                           name="is_active" value="1" {{ old('is_active', $setting->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Setting Aktif
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Deskripsi penggunaan setting ini...">{{ old('description', $setting->description) }}</textarea>
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
                                       id="value_string" name="value_string" 
                                       value="{{ old('value', $setting->type !== 'boolean' && $setting->type !== 'json' ? $setting->value : '') }}" 
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
                                    <option value="1" {{ old('value', $setting->type === 'boolean' ? $setting->value : '1') == '1' ? 'selected' : '' }}>True</option>
                                    <option value="0" {{ old('value', $setting->type === 'boolean' ? $setting->value : '1') == '0' ? 'selected' : '' }}>False</option>
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
                                          placeholder='{"key": "value"}'>{{ old('value', $setting->type === 'json' ? $setting->value : '') }}</textarea>
                                <div class="form-text">Masukkan JSON yang valid</div>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('application-settings.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Setting
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Current Value Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>Informasi Saat Ini
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Key:</strong> <code>{{ $setting->key }}</code><br>
                            <strong>Tipe:</strong> <span class="badge bg-info text-dark">{{ $setting->type }}</span><br>
                            <strong>Kategori:</strong> {{ $setting->category }}<br>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong> 
                            @if($setting->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-danger">Nonaktif</span>
                            @endif<br>
                            <strong>Dibuat:</strong> {{ $setting->created_at->format('d/m/Y H:i') }}<br>
                            <strong>Diupdate:</strong> {{ $setting->updated_at->format('d/m/Y H:i') }}<br>
                        </div>
                    </div>
                    <hr>
                    <strong>Nilai Saat Ini:</strong><br>
                    @if($setting->type === 'boolean')
                        <span class="badge {{ $setting->value ? 'bg-success' : 'bg-danger' }}">
                            {{ $setting->value ? 'True' : 'False' }}
                        </span>
                    @elseif($setting->type === 'json')
                        <pre class="bg-light p-2 rounded"><code>{{ json_encode(json_decode($setting->value), JSON_PRETTY_PRINT) }}</code></pre>
                    @else
                        <code>{{ $setting->value }}</code>
                    @endif
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

.form-label {
    font-weight: 600;
    color: #5a5c69;
}

.border-bottom {
    border-color: #e3e6f0 !important;
}

.form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}

pre {
    max-height: 200px;
    overflow-y: auto;
}
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