@extends('layouts.app')

@section('title', 'Detail Pengguna Sistem')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div style="background-color: var(--card-bg); border: 1px solid var(--border-primary);" class="rounded-2xl shadow-sm mb-6">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 style="color: var(--text-primary);" class="text-2xl font-bold mb-2">Detail Pengguna Sistem</h1>
                    <p style="color: var(--text-secondary);">Informasi lengkap pengguna: {{ $penggunaSistem->nama_lengkap }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pengguna-sistem.edit', $penggunaSistem) }}" style="background-color: var(--warning-primary); color: var(--text-white); transition: all 0.2s;" class="inline-flex items-center px-6 py-3 font-medium rounded-xl hover:opacity-90">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('pengguna-sistem.index') }}" style="background-color: var(--secondary-bg); color: var(--text-white); transition: all 0.2s;" class="inline-flex items-center px-6 py-3 font-medium rounded-xl hover:opacity-90">
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
            <div style="background-color: var(--card-bg); border: 1px solid var(--border-primary);" class="rounded-2xl shadow-sm">
                <div class="px-8 py-6 text-center">
                    <div class="avatar-lg mx-auto mb-3">
                        <div style="background-color: var(--accent-primary); color: var(--text-white);" class="avatar-title rounded-circle">
                            {{ strtoupper(substr($penggunaSistem->nama_lengkap, 0, 2)) }}
                        </div>
                    </div>
                    <h5 style="color: var(--text-primary);" class="card-title mb-1">{{ $penggunaSistem->nama_lengkap }}</h5>
                    <p style="color: var(--text-secondary);" class="mb-3">{{ $penggunaSistem->email_address }}</p>
                    
                    <div class="mb-3">
                        @if($penggunaSistem->aktif)
                            <span style="background-color: var(--success-bg); color: var(--success-primary);" class="badge fs-6">
                                <i class="fas fa-check-circle me-1"></i>Aktif
                            </span>
                        @else
                            <span style="background-color: var(--danger-bg); color: var(--danger-primary);" class="badge fs-6">
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
                            <button type="submit" style="border: 1px solid var(--border-secondary); color: var(--text-primary); background-color: var(--card-bg); transition: all 0.2s;" class="btn btn-sm hover:opacity-80"
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
            <div style="background-color: var(--card-bg); border: 1px solid var(--border-primary);" class="rounded-2xl shadow-sm">
                <div style="border-bottom: 1px solid var(--border-primary);" class="px-8 py-6">
                    <h6 style="color: var(--text-primary);" class="text-lg font-semibold flex items-center">
                        <i class="fas fa-info-circle me-2"></i>Informasi Detail
                    </h6>
                </div>
                <div class="px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-md-6 mb-3">
                            <label style="color: var(--text-secondary);" class="form-label">ID Pengguna</label>
                            <p style="color: var(--text-primary);" class="fw-bold">{{ $penggunaSistem->user_id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="color: var(--text-secondary);" class="form-label">Nama Lengkap</label>
                            <p style="color: var(--text-primary);" class="fw-bold">{{ $penggunaSistem->nama_lengkap }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="color: var(--text-secondary);" class="form-label">Email Address</label>
                            <p style="color: var(--text-primary);" class="fw-bold">{{ $penggunaSistem->email_address }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="color: var(--text-secondary);" class="form-label">Unit Pembangkit</label>
                            <p class="fw-bold">
                                <span style="background-color: var(--accent-bg); color: var(--accent-primary);" class="badge fs-6">
                                    {{ $penggunaSistem->unitPembangkit->nama_unit ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="color: var(--text-secondary);" class="form-label">Alamat Unit</label>
                            <p style="color: var(--text-primary);" class="fw-bold">
                                {{ $penggunaSistem->unitPembangkit->alamat_unit ?? 'N/A' }}<br>
                                <small style="color: var(--text-secondary);">
                                    {{ $penggunaSistem->unitPembangkit->kota ?? '' }}
                        {{ $penggunaSistem->unitPembangkit->kode_pos ? ', ' . $penggunaSistem->unitPembangkit->kode_pos : '' }}
                                </small>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="color: var(--text-secondary);" class="form-label">Peran Pengguna</label>
                            <p class="fw-bold">
                                @forelse($penggunaSistem->peranPengguna as $peran)
                                    <span style="background-color: var(--secondary-bg-light); color: var(--text-primary);" class="badge me-1 fs-6">{{ $peran->nama_peran }}</span>
                                @empty
                                    <span style="color: var(--text-secondary);">Tidak ada peran</span>
                                @endforelse
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="color: var(--text-secondary);" class="form-label">Dibuat Pada</label>
                            <p style="color: var(--text-primary);" class="fw-bold">{{ $penggunaSistem->created_at ? $penggunaSistem->created_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label style="color: var(--text-secondary);" class="form-label">Terakhir Diperbarui</label>
                            <p style="color: var(--text-primary);" class="fw-bold">{{ $penggunaSistem->updated_at ? $penggunaSistem->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Log Card -->
    <div class="row">
        <div class="col-12">
            <div style="background-color: var(--card-bg); border: 1px solid var(--border-primary); box-shadow: 0 0.15rem 1.75rem 0 rgba(0, 0, 0, 0.1);" class="card">
                <div style="background-color: var(--table-header-start); border-bottom: 1px solid var(--border-primary);" class="card-header">
                    <h6 style="color: var(--text-primary);" class="m-0 font-weight-bold">
                        <i class="fas fa-history me-2"></i>Riwayat Aktivitas Log Penyimpanan
                        <span style="background-color: var(--secondary-bg-light); color: var(--text-primary);" class="badge ms-2">{{ $penggunaSistem->logPenyimpananLimbah->count() }} log</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if($penggunaSistem->logPenyimpananLimbah->count() > 0)
                        <div class="table-responsive">
                            <table style="background-color: var(--card-bg);" class="table table-hover">
                                <thead style="background-color: var(--table-header-start);">
                                    <tr>
                                        <th style="color: var(--text-primary); border-top: none;">No</th>
                                        <th style="color: var(--text-primary); border-top: none;">Tanggal</th>
                                        <th style="color: var(--text-primary); border-top: none;">Jenis Limbah</th>
                                        <th style="color: var(--text-primary); border-top: none;">Jumlah</th>
                                        <th style="color: var(--text-primary); border-top: none;">Lokasi</th>
                                        <th style="color: var(--text-primary); border-top: none;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($penggunaSistem->logPenyimpananLimbah->take(10) as $index => $log)
                                        <tr style="border-bottom: 1px solid var(--border-secondary);" class="hover:bg-opacity-50" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                                            <td style="color: var(--text-primary);">{{ $index + 1 }}</td>
                                            <td style="color: var(--text-primary);">{{ $log->tanggal_penyimpanan ? $log->tanggal_penyimpanan->format('d/m/Y') : 'N/A' }}</td>
                                            <td>
                                                <span style="background-color: var(--accent-primary); color: var(--text-white);" class="badge">{{ $log->jenisLimbah->nama_jenis ?? 'N/A' }}</span>
                                            </td>
                                            <td style="color: var(--text-primary);">{{ $log->jumlah_limbah ?? 0 }} {{ $log->satuan ?? '' }}</td>
                                            <td style="color: var(--text-primary);">{{ $log->lokasi_penyimpanan ?? 'N/A' }}</td>
                                            <td>
                                                @if($log->tanggal_kadaluarsa && $log->tanggal_kadaluarsa->isPast())
                                                    <span style="background-color: var(--danger-bg); color: var(--danger-primary);" class="badge">Kadaluarsa</span>
                                                @elseif($log->tanggal_kadaluarsa && $log->tanggal_kadaluarsa->diffInDays(now()) <= 30)
                                                    <span style="background-color: var(--warning-bg); color: var(--warning-primary);" class="badge">Akan Kadaluarsa</span>
                                                @elseif($log->tanggal_kadaluarsa)
                                                    <span style="background-color: var(--success-bg); color: var(--success-primary);" class="badge">Normal</span>
                                                @else
                                                    <span style="background-color: var(--secondary-bg-light); color: var(--text-primary);" class="badge">Tidak Ada Tanggal</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($penggunaSistem->logPenyimpananLimbah->count() > 10)
                            <div class="text-center mt-3">
                                <p style="color: var(--text-secondary);">Menampilkan 10 dari {{ $penggunaSistem->logPenyimpananLimbah->count() }} log aktivitas</p>
                                <a href="{{ route('log-penyimpanan') }}?user_id={{ $penggunaSistem->user_id }}" style="border: 1px solid var(--accent-primary); color: var(--accent-primary); background-color: transparent; transition: all 0.2s;" class="btn hover:bg-opacity-10">
                                    <i class="fas fa-eye me-2"></i>Lihat Semua Log
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i style="color: var(--text-secondary);" class="fas fa-clipboard-list fa-3x mb-3"></i>
                            <h6 style="color: var(--text-secondary);">Belum Ada Aktivitas</h6>
                            <p style="color: var(--text-secondary);">Pengguna ini belum melakukan input log penyimpanan limbah.</p>
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
    border: none;
}

.table th {
    font-weight: 600;
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