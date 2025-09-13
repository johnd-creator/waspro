@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog"></i> Pengaturan Status Kadaluarsa Limbah
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('expiry-reports.dashboard') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> Lihat Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    <!-- Information Card -->
                    <div class="card card-info mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle"></i> Informasi Pengaturan</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <x-status-indicator status="critical" size="sm" />
                                        <h5 class="text-lg font-semibold mb-0">Status Kritis</h5>
                                    </div>
                                    <p class="text-gray-600">Limbah akan ditandai sebagai <strong>Kritis</strong> jika tanggal kadaluarsa kurang dari atau sama dengan jumlah hari yang ditentukan.</p>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <x-status-indicator status="warning" size="sm" />
                                        <h5 class="text-lg font-semibold mb-0">Status Peringatan</h5>
                                    </div>
                                    <p class="text-gray-600">Limbah akan ditandai sebagai <strong>Peringatan</strong> jika tanggal kadaluarsa kurang dari atau sama dengan jumlah hari yang ditentukan, tetapi lebih dari hari kritis.</p>
                                </div>
                            </div>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Catatan:</strong> Hari peringatan harus lebih besar dari hari kritis. Sistem akan otomatis menghitung ulang status semua limbah setelah pengaturan diubah.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Settings Form -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-edit"></i> Pengaturan Hari Kadaluarsa</h3>
                        </div>
                        <form action="{{ route('expiry-settings.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="critical_days" class="form-label">
                                                <i class="fas fa-exclamation-triangle text-warning"></i> Hari Kritis
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('critical_days') is-invalid @enderror" 
                                                   id="critical_days" 
                                                   name="critical_days" 
                                                   value="{{ old('critical_days', $settings['critical_days']) }}"
                                                   min="1" 
                                                   max="365" 
                                                   required>
                                            <small class="form-text text-muted">
                                                Limbah akan berstatus <strong>Kritis</strong> jika kadaluarsa dalam ≤ <span id="critical_display">{{ $settings['critical_days'] }}</span> hari
                                            </small>
                                            @error('critical_days')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="warning_days" class="form-label">
                                                <i class="fas fa-clock text-info"></i> Hari Peringatan
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('warning_days') is-invalid @enderror" 
                                                   id="warning_days" 
                                                   name="warning_days" 
                                                   value="{{ old('warning_days', $settings['warning_days']) }}"
                                                   min="1" 
                                                   max="365" 
                                                   required>
                                            <small class="form-text text-muted">
                                                Limbah akan berstatus <strong>Peringatan</strong> jika kadaluarsa dalam ≤ <span id="warning_display">{{ $settings['warning_days'] }}</span> hari
                                            </small>
                                            @error('warning_days')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Preview Section -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5><i class="fas fa-eye"></i> Preview Status</h5>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="info-box bg-danger">
                                                    <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Kadaluarsa</span>
                                                        <span class="info-box-number">≤ 0 hari</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="info-box bg-warning">
                                                    <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Kritis</span>
                                                        <span class="info-box-number" id="critical_range">1 - {{ $settings['critical_days'] }} hari</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="info-box bg-info">
                                                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Peringatan</span>
                                                        <span class="info-box-number" id="warning_range">{{ $settings['critical_days'] + 1 }} - {{ $settings['warning_days'] }} hari</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="info-box bg-success">
                                                    <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Aman</span>
                                                        <span class="info-box-number" id="safe_range">> {{ $settings['warning_days'] }} hari</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Simpan Pengaturan
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                            <i class="fas fa-undo"></i> Reset Form
                                        </button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" class="btn btn-warning" onclick="resetToDefault()">
                                            <i class="fas fa-refresh"></i> Reset ke Default
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Current Settings Display -->
                    <div class="card card-secondary mt-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-list"></i> Pengaturan Saat Ini</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Hari Kritis</span>
                                            <span class="info-box-number">{{ $settings['critical_days'] }} hari</span>
                                            <div class="progress">
                                                <div class="progress-bar bg-warning" style="width: {{ $criticalPercentage }}%"></div>
                                            </div>
                                            <span class="progress-description">dari maksimal 365 hari</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-clock"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Hari Peringatan</span>
                                            <span class="info-box-number">{{ $settings['warning_days'] }} hari</span>
                                            <div class="progress">
                                                <div class="progress-bar bg-info" style="width: {{ $warningPercentage }}%"></div>
                                            </div>
                                            <span class="progress-description">dari maksimal 365 hari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update preview when input changes
document.getElementById('critical_days').addEventListener('input', updatePreview);
document.getElementById('warning_days').addEventListener('input', updatePreview);

function updatePreview() {
    const criticalDays = parseInt(document.getElementById('critical_days').value) || 0;
    const warningDays = parseInt(document.getElementById('warning_days').value) || 0;
    
    // Update display text
    document.getElementById('critical_display').textContent = criticalDays;
    document.getElementById('warning_display').textContent = warningDays;
    
    // Update preview ranges
    if (criticalDays > 0 && warningDays > criticalDays) {
        document.getElementById('critical_range').textContent = `1 - ${criticalDays} hari`;
        document.getElementById('warning_range').textContent = `${criticalDays + 1} - ${warningDays} hari`;
        document.getElementById('safe_range').textContent = `> ${warningDays} hari`;
    }
}

function resetForm() {
    document.getElementById('critical_days').value = {{ $settings['critical_days'] ?? 30 }};
    document.getElementById('warning_days').value = {{ $settings['warning_days'] ?? 7 }};
    updatePreview();
}

function resetToDefault() {
    if (confirm('Apakah Anda yakin ingin mereset pengaturan ke default (Kritis: 7 hari, Peringatan: 30 hari)?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("expiry-settings.reset") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize preview on page load
updatePreview();
</script>
@endsection