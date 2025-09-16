@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary); margin-bottom: 1.5rem;">
        <div style="padding: 2rem;">
            <div class="flex justify-between items-center">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Detail Peran Pengguna</h1>
                    <p style="color: var(--text-secondary);">Informasi lengkap peran: {{ $peranPengguna->nama_peran }}</p>
                </div>
                <div>
                    <a href="{{ route('peran-pengguna.index') }}" style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: var(--secondary-bg); color: white; font-weight: 500; border-radius: 0.75rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--secondary-hover)'" onmouseout="this.style.background='var(--secondary-bg)'">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary);">
        <div style="padding: 2rem; border-bottom: 1px solid var(--border-primary);">
            <h6 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); display: flex; align-items: center;">
                <i class="fas fa-info-circle mr-2"></i>Informasi Detail
            </h6>
        </div>
        <div style="padding: 2rem;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-md-6">
                            <table style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <th style="width: 30%; color: var(--text-primary); font-weight: 600; padding: 0.5rem 0; text-align: left;">ID Peran:</th>
                                    <td style="color: var(--text-secondary); padding: 0.5rem 0;">{{ $peranPengguna->peran_id }}</td>
                                </tr>
                                <tr>
                                    <th style="color: var(--text-primary); font-weight: 600; padding: 0.5rem 0; text-align: left;">Nama Peran:</th>
                                    <td style="color: var(--text-secondary); padding: 0.5rem 0;">{{ $peranPengguna->nama_peran }}</td>
                                </tr>
                                <tr>
                                    <th style="color: var(--text-primary); font-weight: 600; padding: 0.5rem 0; text-align: left;">Status:</th>
                                    <td style="padding: 0.5rem 0;">
                                        @if($peranPengguna->is_active)
                                            <span style="background: var(--success-bg); color: var(--success-primary); padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500;">Aktif</span>
                                        @else
                                            <span style="background: var(--danger-bg); color: var(--danger-primary); padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500;">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="color: var(--text-primary); font-weight: 600; padding: 0.5rem 0; text-align: left;">Dibuat:</th>
                                    <td style="color: var(--text-secondary); padding: 0.5rem 0;">{{ $peranPengguna->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th style="color: var(--text-primary); font-weight: 600; padding: 0.5rem 0; text-align: left;">Diperbarui:</th>
                                    <td style="color: var(--text-secondary); padding: 0.5rem 0;">{{ $peranPengguna->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem; display: block;"><strong>Deskripsi:</strong></label>
                                <div style="border: 1px solid var(--border-primary); padding: 0.75rem; background: var(--secondary-bg-light); border-radius: 0.5rem; color: var(--text-secondary);">
                                    {{ $peranPengguna->deskripsi ?? 'Tidak ada deskripsi' }}
                                </div>
                            </div>
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
                    <a href="{{ route('peran-pengguna.edit', $peranPengguna->peran_id) }}" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: var(--warning-bg); color: var(--warning-primary); border-radius: 0.5rem; text-decoration: none; font-weight: 500; margin-right: 0.5rem; transition: all 0.2s;" onmouseover="this.style.background='var(--warning-primary)'; this.style.color='white'" onmouseout="this.style.background='var(--warning-bg)'; this.style.color='var(--warning-primary)'">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    
                    <form action="{{ route('peran-pengguna.toggle-status', $peranPengguna->peran_id) }}" 
                          method="POST" style="display: inline; margin-left: 0.5rem;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: {{ $peranPengguna->is_active ? 'var(--secondary-bg)' : 'var(--success-bg)' }}; color: white; border: none; border-radius: 0.5rem; font-weight: 500; cursor: pointer; transition: all 0.2s;" 
                                onclick="return confirm('Apakah Anda yakin ingin @if($peranPengguna->is_active) menonaktifkan @else mengaktifkan @endif peran ini?')"
                                onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
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