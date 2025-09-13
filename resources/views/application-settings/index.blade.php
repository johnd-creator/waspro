@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">System Settings</h1>
            <p class="text-muted mb-0">Kelola pengaturan sistem aplikasi</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('application-settings.clear-cache') }}" class="btn btn-outline-warning"
               onclick="return confirm('Apakah Anda yakin ingin menghapus cache settings?')">
                <i class="fas fa-sync-alt me-2"></i>Clear Cache
            </a>
            <a href="{{ route('application-settings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Setting
            </a>
        </div>
    </div>



    <!-- Settings Tabs -->
    @if($settingsByCategory->count() > 0)
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                    @foreach($settingsByCategory as $category => $settings)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                    id="{{ $category }}-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#{{ $category }}-pane" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="{{ $category }}-pane" 
                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <i class="fas fa-cog me-2"></i>{{ ucfirst($category) }}
                                <span class="badge bg-secondary ms-2">{{ $settings->count() }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="settingsTabContent">
                    @foreach($settingsByCategory as $category => $settings)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                             id="{{ $category }}-pane" 
                             role="tabpanel" 
                             aria-labelledby="{{ $category }}-tab">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Key</th>
                                            <th>Value</th>
                                            <th>Type</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($settings as $setting)
                                            <tr>
                                                <td>
                                                    <code class="text-primary">{{ $setting->key }}</code>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $setting->value }}">
                                                        @if($setting->type === 'boolean')
                                                            <span class="badge {{ $setting->value ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $setting->value ? 'True' : 'False' }}
                                                            </span>
                                                        @elseif($setting->type === 'json')
                                                            <small class="text-muted">JSON Object</small>
                                                        @else
                                                            {{ $setting->value }}
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info text-dark">{{ $setting->type }}</span>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 250px;" title="{{ $setting->description }}">
                                                        {{ $setting->description ?: '-' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($setting->is_active)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Aktif
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle me-1"></i>Nonaktif
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('application-settings.show', $setting) }}" 
                                                           class="btn btn-sm btn-info" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('application-settings.edit', $setting) }}" 
                                                           class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                onclick="confirmDelete('{{ $setting->id }}', '{{ $setting->key }}')"
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="text-center py-5">
                    <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada settings ditemukan</h5>
                    <p class="text-muted">Silakan tambah setting baru atau ubah filter pencarian.</p>
                    <a href="{{ route('application-settings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Setting Pertama
                    </a>
                </div>
            </div>
        </div>
    @endif
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
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
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
.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border: none;
}

.badge {
    font-size: 0.75em;
}

code {
    font-size: 0.875em;
}
</style>

<script>
function confirmDelete(settingId, settingKey) {
    document.getElementById('settingKey').textContent = settingKey;
    document.getElementById('deleteForm').action = `/application-settings/${settingId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
</script>
@endsection