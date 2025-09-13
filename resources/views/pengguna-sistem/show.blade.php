@extends('layouts.app')

@section('title', 'Detail Pengguna Sistem')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Detail Pengguna Sistem</h1>
                    <p class="text-slate-600">Informasi lengkap pengguna: {{ $penggunaSistem->nama_lengkap }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pengguna-sistem.edit', $penggunaSistem) }}" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-xl transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('pengguna-sistem.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
                <div class="px-8 py-6 text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-primary text-white rounded-circle">
                            {{ strtoupper(substr($penggunaSistem->nama_lengkap, 0, 2)) }}
                        </div>
                    </div>
                    <h5 class="card-title mb-1">{{ $penggunaSistem->nama_lengkap }}</h5>
                    <p class="text-muted mb-3">{{ $penggunaSistem->email_address }}</p>
                    
                    <div class="mb-3">
                        @if($penggunaSistem->aktif)
                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-times-circle me-1"></i>Nonaktif
                            </span>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-center gap-2">
                        <form action="{{ route('pengguna-sistem.toggle-status', $penggunaSistem) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            @php
                                $confirmMessage = $penggunaSistem->aktif ? 'menonaktifkan' : 'mengaktifkan';
                            @endphp
                            <button type="submit" class="btn btn-sm {{ $penggunaSistem->aktif ? 'btn-outline-secondary' : 'btn-outline-success' }}"
                                     onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin {{ $confirmMessage }} pengguna ini?')">
                                <i class="fas {{ $penggunaSistem->aktif ? 'fa-ban' : 'fa-check' }} me-1"></i>
                                {{ $penggunaSistem->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
                <div class="px-8 py-6 border-b border-slate-200">
                    <h6 class="text-lg font-semibold text-slate-900 flex items-center">
                        <i class="fas fa-info-circle me-2"></i>Informasi Detail
                    </h6>
                </div>
                <div class="px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">ID Pengguna</label>
                            <p class="fw-bold">{{ $penggunaSistem->user_id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Nama Lengkap</label>
                            <p class="fw-bold">{{ $penggunaSistem->nama_lengkap }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email Address</label>
                            <p class="fw-bold">{{ $penggunaSistem->email_address }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Unit Pembangkit</label>
                            <p class="fw-bold">
                                <span class="badge bg-info text-dark fs-6">
                                    {{ $penggunaSistem->unitPembangkit->nama_unit ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Alamat Unit</label>
                            <p class="fw-bold">
                                {{ $penggunaSistem->unitPembangkit->alamat_unit ?? 'N/A' }}<br>
                                <small class="text-muted">
                                    {{ $penggunaSistem->unitPembangkit->kota ?? '' }}
                        {{ $penggunaSistem->unitPembangkit->kode_pos ? ', ' . $penggunaSistem->unitPembangkit->kode_pos : '' }}
                                </small>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Peran Pengguna</label>
                            <p class="fw-bold">
                                @forelse($penggunaSistem->peranPengguna as $peran)
                                    <span class="badge bg-secondary me-1 fs-6">{{ $peran->nama_peran }}</span>
                                @empty
                                    <span class="text-muted">Tidak ada peran</span>
                                @endforelse
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Dibuat Pada</label>
                            <p class="fw-bold">{{ $penggunaSistem->created_at ? $penggunaSistem->created_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Terakhir Diperbarui</label>
                            <p class="fw-bold">{{ $penggunaSistem->updated_at ? $penggunaSistem->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Log Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history me-2"></i>Riwayat Aktivitas Log Penyimpanan
                        <span class="badge bg-secondary ms-2">{{ $penggunaSistem->logPenyimpananLimbah->count() }} log</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if($penggunaSistem->logPenyimpananLimbah->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Jenis Limbah</th>
                                        <th>Jumlah</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penggunaSistem->logPenyimpananLimbah->take(10) as $index => $log)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $log->tanggal_penyimpanan ? $log->tanggal_penyimpanan->format('d/m/Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $log->jenisLimbah->nama_jenis ?? 'N/A' }}</span>
                                            </td>
                                            <td>{{ $log->jumlah_limbah ?? 0 }} {{ $log->satuan ?? '' }}</td>
                                            <td>{{ $log->lokasi_penyimpanan ?? 'N/A' }}</td>
                                            <td>
                                                @if($log->tanggal_kadaluarsa && $log->tanggal_kadaluarsa->isPast())
                                                    <span class="badge bg-danger">Kadaluarsa</span>
                                                @elseif($log->tanggal_kadaluarsa && $log->tanggal_kadaluarsa->diffInDays(now()) <= 30)
                                                    <span class="badge bg-warning text-dark">Akan Kadaluarsa</span>
                                                @elseif($log->tanggal_kadaluarsa)
                                                    <span class="badge bg-success">Normal</span>
                                                @else
                                                    <span class="badge bg-secondary">Tidak Ada Tanggal</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($penggunaSistem->logPenyimpananLimbah->count() > 10)
                            <div class="text-center mt-3">
                                <p class="text-muted">Menampilkan 10 dari {{ $penggunaSistem->logPenyimpananLimbah->count() }} log aktivitas</p>
                                <a href="{{ route('log-penyimpanan') }}?user_id={{ $penggunaSistem->user_id }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Lihat Semua Log
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Belum Ada Aktivitas</h6>
                            <p class="text-muted">Pengguna ini belum melakukan input log penyimpanan limbah.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.5rem;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border: none;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
}

.badge {
    font-size: 0.75em;
}

.fs-6 {
    font-size: 0.875rem !important;
}

.form-label {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.fw-bold {
    font-weight: 600 !important;
}
</style>
@endsection