@extends('layouts.app')

@section('title', 'Detail System Setting')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Detail System Setting</h1>
            <p class="text-muted mb-0">Informasi lengkap setting: <code>{{ $setting->key }}</code></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('application-settings.edit', $setting) }}" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('application-settings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Informasi Setting
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Key</label>
                            <div class="p-2 bg-light rounded">
                                <code class="text-primary">{{ $setting->key }}</code>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Tipe Data</label>
                            <div class="p-2 bg-light rounded">
                                <span class="badge bg-info text-dark">{{ $setting->type }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Kategori</label>
                            <div class="p-2 bg-light rounded">
                                {{ $setting->category }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Status</label>
                            <div class="p-2 bg-light rounded">
                                @if($setting->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i>Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted">Deskripsi</label>
                            <div class="p-2 bg-light rounded">
                                {{ $setting->description ?: 'Tidak ada deskripsi' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Value Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>Nilai Setting
                    </h6>
                </div>
                <div class="card-body">
                    @if($setting->type === 'boolean')
                        <div class="text-center py-3">
                            <span class="badge {{ $setting->value ? 'bg-success' : 'bg-danger' }} fs-6 p-3">
                                <i class="fas {{ $setting->value ? 'fa-check' : 'fa-times' }} me-2"></i>
                                {{ $setting->value ? 'True' : 'False' }}
                            </span>
                        </div>
                    @elseif($setting->type === 'json')
                        <div class="position-relative">
                            <button class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" 
                                    onclick="copyToClipboard('json-value')" title="Copy JSON">
                                <i class="fas fa-copy"></i>
                            </button>
                            <pre class="bg-light p-3 rounded" id="json-value"><code>{{ json_encode(json_decode($setting->value), JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                    @else
                        <div class="position-relative">
                            <button class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" 
                                    onclick="copyToClipboard('text-value')" title="Copy Value">
                                <i class="fas fa-copy"></i>
                            </button>
                            <div class="p-3 bg-light rounded" id="text-value">
                                <code class="fs-5">{{ $setting->value }}</code>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usage Example -->
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-code me-2"></i>Contoh Penggunaan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="position-relative">
                        <button class="btn btn-sm btn-outline-secondary position-absolute top-0 end-0 m-2" 
                                onclick="copyToClipboard('usage-example')" title="Copy Code">
                            <i class="fas fa-copy"></i>
                        </button>
                        <pre class="bg-dark text-light p-3 rounded" id="usage-example"><code>// Menggunakan helper method
$value = ApplicationSetting::get('{{ $setting->key }}');

// Atau menggunakan config helper (jika sudah di-cache)
config('{{ $setting->key }}');

// Set nilai baru
ApplicationSetting::set('{{ $setting->key }}', $newValue);</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Metadata -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-clock me-2"></i>Metadata
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">ID</small><br>
                        <strong>{{ $setting->id }}</strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Dibuat</small><br>
                        <strong>{{ $setting->created_at->format('d/m/Y H:i:s') }}</strong><br>
                        <small class="text-muted">{{ $setting->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Terakhir Diupdate</small><br>
                        <strong>{{ $setting->updated_at->format('d/m/Y H:i:s') }}</strong><br>
                        <small class="text-muted">{{ $setting->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-tools me-2"></i>Aksi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('application-settings.edit', $setting) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Setting
                        </a>
                        <button type="button" class="btn btn-danger" 
                                onclick="confirmDelete('{{ $setting->id }}', '{{ $setting->key }}')">
                            <i class="fas fa-trash me-2"></i>Hapus Setting
                        </button>
                        <a href="{{ route('application-settings.clear-cache') }}" class="btn btn-outline-info"
                           onclick="return confirm('Clear cache untuk semua settings?')">
                            <i class="fas fa-sync-alt me-2"></i>Clear Cache
                        </a>
                    </div>
                </div>
            </div>

            <!-- Related Settings -->
            @if($relatedSettings->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-link me-2"></i>Settings Terkait
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($relatedSettings as $related)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <a href="{{ route('application-settings.show', $related) }}" 
                                   class="text-decoration-none">
                                    <code class="text-primary">{{ $related->key }}</code>
                                </a><br>
                                <small class="text-muted">{{ Str::limit($related->description, 30) }}</small>
                            </div>
                            <span class="badge bg-info text-dark">{{ $related->type }}</span>
                        </div>
                        @if(!$loop->last)<hr class="my-2">@endif
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus setting <strong id="settingKey"></strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan dan dapat mempengaruhi fungsi aplikasi.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
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

.form-label {
    font-weight: 600;
    font-size: 0.875rem;
}

pre {
    max-height: 300px;
    overflow-y: auto;
    font-size: 0.875rem;
}

code {
    font-size: 0.875rem;
}

.position-relative pre,
.position-relative div {
    margin-bottom: 0;
}
</style>

<script>
function confirmDelete(settingId, settingKey) {
    document.getElementById('settingKey').textContent = settingKey;
    document.getElementById('deleteForm').action = `/application-settings/${settingId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent || element.innerText;
    
    navigator.clipboard.writeText(text).then(function() {
        // Show success feedback
        const button = element.parentElement.querySelector('button');
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check text-success"></i>';
        
        setTimeout(() => {
            button.innerHTML = originalIcon;
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Gagal menyalin ke clipboard');
    });
}
</script>
@endsection