@extends('layouts.app')

@section('content')
<style>
    .section-title {
        color: var(--text-primary);
        font-size: 1.125rem;
        font-weight: 600;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--accent-primary);
        display: inline-block;
        margin-bottom: 1.5rem;
    }
    .info-card {
        background-color: var(--card-bg-secondary);
        border-radius: 0.75rem;
        padding: 1.25rem;
        transition: all 0.2s ease;
        height: 100%;
        border: 1px solid var(--border-primary);
    }
    .info-card:hover {
        box-shadow: 0 4px 6px -1px rgba(var(--shadow-rgb), 0.1);
        transform: translateY(-2px);
    }
    .info-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        background-color: var(--accent-bg);
        color: var(--accent-primary);
    }
    .info-card-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }
    .info-card-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
    }
    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .status-badge.active {
        background-color: var(--accent-bg-secondary);
        color: var(--accent-secondary);
    }
    .status-badge.inactive {
        background-color: var(--danger-bg);
        color: var(--danger-primary);
    }
    /* Refined detail list */
    .detail-grid { display: grid; grid-template-columns: 1fr; gap: 0.5rem 1rem; }
    .detail-row { display: grid; grid-template-columns: 220px 1fr; align-items: start; padding: 0.75rem 0; border-bottom: 1px solid var(--border-primary); }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: var(--text-secondary); font-weight: 500; }
    .detail-value { color: var(--text-primary); font-weight: 600; }
    .detail-subtext { display: block; color: var(--text-secondary); font-weight: 400; margin-top: 0.25rem; }
</style>
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="mb-6 rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6 flex justify-between items-center">
            <div>
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Detail Peran Pengguna</h1>
                <p style="color: var(--text-secondary);">Informasi lengkap peran: {{ $peranPengguna->nama_peran }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('peran-pengguna.edit', $peranPengguna->peran_id) }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--accent-primary);" onmouseover="this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('peran-pengguna.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg" style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);" onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';" onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-8 py-6 border-b" style="border-color: var(--border-primary);">
            <h6 class="section-title flex items-center">
                <i class="fas fa-info-circle mr-2"></i>Informasi Detail
            </h6>
        </div>
        <div class="px-8 py-6">
            <div class="detail-grid">
                <div class="detail-row">
                    <div class="detail-label">ID Peran</div>
                    <div class="detail-value">{{ $peranPengguna->peran_id }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Nama Peran</div>
                    <div class="detail-value">{{ $peranPengguna->nama_peran }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        @if($peranPengguna->is_active)
                            <span class="status-badge active">Aktif</span>
                        @else
                            <span class="status-badge inactive">Tidak Aktif</span>
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Deskripsi</div>
                    <div class="detail-value">{{ $peranPengguna->deskripsi ?? '-' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Dibuat Pada</div>
                    <div class="detail-value">{{ $peranPengguna->created_at ? $peranPengguna->created_at->format('d M Y, H:i') : '-' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Diperbarui Pada</div>
                    <div class="detail-value">{{ $peranPengguna->updated_at ? $peranPengguna->updated_at->format('d M Y, H:i') : '-' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Dibuat Oleh</div>
                    <div class="detail-value">{{ $peranPengguna->created_by ?? '-' }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Diperbarui Oleh</div>
                    <div class="detail-value">{{ $peranPengguna->updated_by ?? '-' }}</div>
                </div>
            </div>

                    <!-- Users with this role -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 style="color: var(--text-primary); font-weight: 600; margin-bottom: 1rem;">Pengguna dengan Peran Ini</h5>
                            @if($peranPengguna->penggunaSistem->count() > 0)
                                <div class="table-responsive">
                                    <table style="width: 100%; border-collapse: collapse; border: 1px solid var(--border-primary); border-radius: 0.5rem; overflow: hidden;">
                                        <thead style="background: linear-gradient(135deg, var(--table-header-start) 0%, var(--table-header-end) 100%);">
                                            <tr>
                                                <th style="padding: 0.75rem; color: var(--text-primary); font-weight: 600; text-align: left; border-bottom: 1px solid var(--border-primary);">No</th>
                                                <th style="padding: 0.75rem; color: var(--text-primary); font-weight: 600; text-align: left; border-bottom: 1px solid var(--border-primary);">Nama Pengguna</th>
                                                <th style="padding: 0.75rem; color: var(--text-primary); font-weight: 600; text-align: left; border-bottom: 1px solid var(--border-primary);">Email</th>
                                                <th style="padding: 0.75rem; color: var(--text-primary); font-weight: 600; text-align: left; border-bottom: 1px solid var(--border-primary);">Ditambahkan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($peranPengguna->penggunaSistem as $index => $user)
                                                <tr style="transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                                                    <td style="padding: 0.75rem; color: var(--text-secondary); border-bottom: 1px solid var(--border-secondary);">{{ $index + 1 }}</td>
                                                    <td style="padding: 0.75rem; color: var(--text-secondary); border-bottom: 1px solid var(--border-secondary);">{{ $user->nama_lengkap }}</td>
                                                    <td style="padding: 0.75rem; color: var(--text-secondary); border-bottom: 1px solid var(--border-secondary);">{{ $user->email_address }}</td>
                                                    <td style="padding: 0.75rem; color: var(--text-secondary); border-bottom: 1px solid var(--border-secondary);">{{ $user->pivot->created_at ? $user->pivot->created_at->format('d/m/Y H:i:s') : 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div style="background: var(--accent-bg); border: 1px solid var(--accent-secondary); color: var(--accent-primary); padding: 1rem; border-radius: 0.5rem; display: flex; align-items: center;">
                                    <i class="fas fa-info-circle mr-2"></i> Belum ada pengguna yang memiliki peran ini.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="background: var(--secondary-bg-light); border-top: 1px solid var(--border-primary); padding: 1.5rem; border-radius: 0 0 1rem 1rem;">
                    <form action="{{ route('peran-pengguna.toggle-status', $peranPengguna->peran_id) }}"
                          method="POST" style="display: inline; margin-left: 0.5rem;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                           style="background-color: var(--danger-primary); color: white;"
                           onmouseover="this.style.backgroundColor='var(--danger-hover)'; this.style.boxShadow='var(--shadow-xl)';"
                           onmouseout="this.style.backgroundColor='var(--danger-primary)'; this.style.boxShadow='var(--shadow-lg)';"
                                class="btn {{ $peranPengguna->is_active ? 'btn-secondary' : 'btn-success' }}"
                                onclick="return confirm('Apakah Anda yakin ingin @if($peranPengguna->is_active) menonaktifkan @else mengaktifkan @endif peran ini?')">
                            <i class="fas fa-{{ $peranPengguna->is_active ? 'times' : 'check' }} mr-1"></i>
                            {{ $peranPengguna->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>

                    @if($peranPengguna->penggunaSistem->count() == 0)
                        <form action="{{ route('peran-pengguna.destroy', $peranPengguna->peran_id) }}"
                              method="POST" style="display: inline; margin-left: 0.5rem;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: var(--danger-bg); color: var(--danger-primary); border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s;"
                                    onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus peran ini?')"
                                    onmouseover="this.style.background='var(--danger-primary)'; this.style.color='white'" onmouseout="this.style.background='var(--danger-bg)'; this.style.color='var(--danger-primary)'">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </form>
                    @else
                        <button type="button" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: var(--secondary-bg-light); color: var(--text-tertiary); border: none; border-radius: 0.5rem; font-weight: 500; cursor: not-allowed; margin-left: 0.5rem;" disabled title="Tidak dapat dihapus karena masih digunakan">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    @endif
        </div>
    </div>
</div>
@endsection
