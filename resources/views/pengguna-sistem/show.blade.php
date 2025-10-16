@extends('layouts.app')

@section('title', 'Detail Pengguna Sistem')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div style="background-color: var(--card-bg); border: 1px solid var(--border-primary);" class="rounded-2xl shadow-sm border mb-6">
        <div class="px-6 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 style="color: var(--text-primary);" class="text-2xl font-bold mb-2">Detail Pengguna Sistem</h1>
                    <p style="color: var(--text-secondary);">Informasi lengkap pengguna: {{ $penggunaSistem->nama_lengkap }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('pengguna-sistem.edit', $penggunaSistem) }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--accent-primary);" onmouseover="this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('pengguna-sistem.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section: Single Card -->
    <div style="background-color: var(--card-bg); border: 1px solid var(--border-primary);" class="rounded-2xl shadow-sm border">
        <div class="px-6 py-6">
            <!-- Profile Header -->
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6 mb-8">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <div class="avatar-profile">
                        <div style="background-color: var(--accent-primary); color: var(--text-white);" class="avatar-title">
                            {{ strtoupper(substr($penggunaSistem->nama_lengkap, 0, 2)) }}
                        </div>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="flex-grow text-center md:text-left">
                    <h3 style="color: var(--text-primary);" class="text-xl font-bold mb-1">{{ $penggunaSistem->nama_lengkap }}</h3>
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 mb-3">
                        <p style="color: var(--text-secondary);" class="flex items-center justify-center md:justify-start">
                            <i class="fas fa-envelope text-sm mr-2"></i>{{ $penggunaSistem->email_address }}
                        </p>
                        <p style="color: var(--text-secondary);" class="flex items-center justify-center md:justify-start">
                            <i class="fas fa-building text-sm mr-2"></i>{{ $penggunaSistem->unitPembangkit->nama_unit ?? 'N/A' }}
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                        @if($penggunaSistem->aktif)
                            <span style="background-color: var(--success-bg); color: var(--success-primary);" class="status-badge">
                                <i class="fas fa-check-circle mr-1"></i>Aktif
                            </span>
                        @else
                            <span style="background-color: var(--danger-bg); color: var(--danger-primary);" class="status-badge">
                                <i class="fas fa-times-circle mr-1"></i>Nonaktif
                            </span>
                        @endif
                        
                        <form action="{{ route('pengguna-sistem.toggle-status', $penggunaSistem) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PATCH')
                            @php
                                $confirmMessage = $penggunaSistem->aktif ? 'menonaktifkan' : 'mengaktifkan';
                            @endphp
                            <button type="submit" class="action-button" onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin {{ $confirmMessage }} pengguna ini?')">
                                <i class="fas {{ $penggunaSistem->aktif ? 'fa-ban' : 'fa-check' }} mr-1"></i>
                                {{ $penggunaSistem->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Divider -->
            <!-- Detail Section (refined, single list) -->
            <div class="w-full" style="margin-top: 0.5rem;">
                <div class="section-title flex items-center" style="color: var(--text-primary);">
                    <i class="fas fa-info-circle mr-2"></i>Informasi Detail
                </div>
                <div class="detail-grid">
                        <div class="detail-row">
                            <div class="detail-label">ID Pengguna</div>
                            <div class="detail-value">{{ $penggunaSistem->user_id }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Nama Lengkap</div>
                            <div class="detail-value">{{ $penggunaSistem->nama_lengkap }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $penggunaSistem->email_address }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Unit Pembangkit</div>
                            <div class="detail-value">{{ $penggunaSistem->unitPembangkit->nama_unit ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Alamat Unit</div>
                            <div class="detail-value">
                                {{ $penggunaSistem->unitPembangkit->alamat_unit ?? 'N/A' }}
                                <span class="detail-subtext">
                                    {{ $penggunaSistem->unitPembangkit->kota ?? '' }}
                                    {{ $penggunaSistem->unitPembangkit->kode_pos ? ', ' . $penggunaSistem->unitPembangkit->kode_pos : '' }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Peran Pengguna</div>
                            <div class="detail-value">
                                @forelse($penggunaSistem->peranPengguna as $peran)
                                    <span class="role-badge">{{ $peran->nama_peran }}</span>
                                @empty
                                    <span class="detail-subtext">Tidak ada peran</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Dibuat Pada</div>
                            <div class="detail-value">{{ $penggunaSistem->created_at ? $penggunaSistem->created_at->format('d/m/Y H:i:s') : 'N/A' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Terakhir Diperbarui</div>
                            <div class="detail-value">{{ $penggunaSistem->updated_at ? $penggunaSistem->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</div>
                        </div>
                </div>
            </div>
        </div>

</div>

<style>
/* Avatar Styling */
.avatar-profile {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border: 3px solid var(--accent-primary);
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 2rem;
    border-radius: 50%;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 0.8rem;
    border-radius: 2rem;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Action Button */
.action-button {
    display: inline-flex;
    align-items: center;
    padding: 0.4rem 0.8rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 0.875rem;
    border: 1px solid var(--border-secondary);
    color: var(--text-primary);
    background-color: var(--card-bg);
    transition: all 0.2s;
}

.action-button:hover {
    background-color: var(--hover-bg);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Divider */
.divider {
    height: 1px;
    background-color: var(--border-primary);
    margin: 1.5rem 0;
}

/* Section Title */
.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--accent-primary);
    margin-bottom: 1.5rem;
    width: fit-content;
}

/* Info Cards */
.info-card {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    border-radius: 0.75rem;
    background-color: var(--card-secondary-bg);
    border: 1px solid var(--border-primary);
    transition: all 0.2s;
}

.info-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.info-card-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 0.5rem;
    background-color: var(--accent-bg-light);
    color: var(--text-primary);
    margin-right: 1rem;
    flex-shrink: 0;
}

.info-card-content {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.info-card-label {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
}

.info-card-value {
    font-weight: 600;
    color: var(--text-primary);
    word-break: break-word;
}

.info-card-subtext {
    display: block;
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-top: 0.25rem;
}

.highlight-accent {
    color: var(--accent-primary);
    font-weight: 700;
}

/* Role Badge */
.role-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background-color: var(--secondary-bg-light);
    color: var(--text-primary);
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 500;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}

/* Refined detail list */
.detail-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.5rem 1rem;
}

.detail-row {
    display: grid;
    grid-template-columns: 220px 1fr;
    align-items: start;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-primary);
}

.detail-row:last-child { border-bottom: none; }

.detail-label { color: var(--text-secondary); font-weight: 500; }
.detail-value { color: var(--text-primary); font-weight: 600; }
.detail-subtext { display: block; color: var(--text-secondary); font-weight: 400; margin-top: 0.25rem; }

/* Responsive Adjustments */
@media (max-width: 768px) {
    .avatar-profile {
        width: 80px;
        height: 80px;
    }
    
    .avatar-title {
        font-size: 1.5rem;
    }
    .detail-row { grid-template-columns: 1fr; }
    .detail-label { margin-bottom: 0.25rem; }
}
</style>
@endsection
