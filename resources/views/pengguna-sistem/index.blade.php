@extends('layouts.app')

@section('title', 'Pengelolaan Pengguna Sistem')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Data Pengguna Sistem</h1>
                    <p class="text-slate-600">Kelola pengguna sistem berdasarkan unit pembangkit</p>
                </div>
                <a href="{{ route('pengguna-sistem.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-plus mr-2"></i> Tambah Pengguna
                </a>
            </div>
        </div>
    </div>



    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
            <form method="GET" action="{{ route('pengguna-sistem.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nama, email, atau unit...">
                </div>
                <div class="col-md-3">
                    <label for="unit_id" class="form-label">Unit Pembangkit</label>
                    <select class="form-select" id="unit_id" name="unit_id">
                        <option value="">Semua Unit</option>
                        @foreach($unitList as $unit)
                            <option value="{{ $unit->unit_id }}"
                                    {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>
                                {{ $unit->nama_unit }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('pengguna-sistem.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-8 py-6 border-b border-slate-200">
            <h6 class="text-lg font-semibold text-slate-900 flex items-center">
                <i class="fas fa-users mr-2"></i>Daftar Pengguna Sistem
                <span class="ml-2 px-3 py-1 bg-slate-100 text-slate-700 text-sm rounded-full">{{ $users->total() }} pengguna</span>
            </h6>
        </div>
        <div class="px-8 py-6">
            @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>Unit Pembangkit</th>
                                <th>Peran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <div class="avatar-title bg-primary text-white rounded-circle">
                                                    {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->nama_lengkap }}</h6>
                                                <small class="text-muted">ID: {{ $user->user_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email_address }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $user->unitPembangkit->nama_unit ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        @foreach($user->peranPengguna as $peran)
                                            <span class="badge bg-secondary me-1">{{ $peran->nama_peran }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if($user->aktif)
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
                                            <a href="{{ route('pengguna-sistem.show', $user) }}" 
                                               class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('pengguna-sistem.edit', $user) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('pengguna-sistem.toggle-status', $user) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-sm {{ $user->aktif ? 'btn-secondary' : 'btn-success' }}" 
                                                        title="{{ $user->aktif ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas {{ $user->aktif ? 'fa-ban' : 'fa-check' }}"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete('{{ $user->user_id }}', '{{ $user->nama_lengkap }}')"
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

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} 
                        dari {{ $users->total() }} pengguna
                    </div>
                    {{ $users->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada pengguna ditemukan</h5>
                    <p class="text-muted">Silakan tambah pengguna baru atau ubah filter pencarian.</p>
                    <a href="{{ route('pengguna-sistem.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Pengguna Pertama
                    </a>
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
                <p>Apakah Anda yakin ingin menghapus pengguna <strong id="userName"></strong>?</p>
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
.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

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
</style>

<script>
function confirmDelete(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = `/pengguna-sistem/${userId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
</script>
@endsection