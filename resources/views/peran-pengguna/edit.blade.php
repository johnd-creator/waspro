@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary); margin-bottom: 1.5rem;">
        <div style="padding: 2rem;">
            <div class="flex justify-between items-center">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Edit Peran Pengguna</h1>
                    <p style="color: var(--text-secondary);">Ubah informasi peran: {{ $peranPengguna->nama_peran }}</p>
                </div>
                <div>
                    <a href="{{ route('peran-pengguna.index') }}" style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: var(--secondary-bg); color: white; font-weight: 500; border-radius: 0.75rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='var(--secondary-hover)'" onmouseout="this.style.background='var(--secondary-bg)'">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="max-w-4xl mx-auto">
        <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary);">
            <div style="padding: 2rem; border-bottom: 1px solid var(--border-primary);">
                <h6 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); display: flex; align-items: center;">
                    <i class="fas fa-user-edit mr-2"></i>Form Edit Peran
                </h6>
            </div>
            <div style="padding: 2rem;">
                <x-session-messages />
                
                <form action="{{ route('peran-pengguna.update', $peranPengguna->peran_id) }}" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <x-form-input 
                                    name="nama_peran" 
                                    label="Nama Peran" 
                                    placeholder="Masukkan nama peran" 
                                    :value="old('nama_peran', $peranPengguna->nama_peran)" 
                                    :required="true" 
                                    maxlength="255" 
                                />
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active" style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem; display: block;">Status</label>
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               style="background: var(--input-bg); border: 1px solid var(--border-primary); color: var(--accent-primary);" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1" 
                                               {{ old('is_active', $peranPengguna->is_active) ? 'checked' : '' }}>
                                        <label style="color: var(--text-secondary); margin-left: 0.5rem;" for="is_active">
                                            Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <x-form-input 
                                    type="textarea" 
                                    name="deskripsi" 
                                    label="Deskripsi" 
                                    placeholder="Masukkan deskripsi peran (opsional)" 
                                    :value="old('deskripsi', $peranPengguna->deskripsi)" 
                                    rows="4" 
                                    maxlength="1000" 
                                />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('peran-pengguna.index') }}" style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: var(--secondary-bg); color: white; font-weight: 500; border-radius: 0.75rem; text-decoration: none; transition: all 0.2s; margin-right: 0.75rem;" onmouseover="this.style.background='var(--secondary-hover)'" onmouseout="this.style.background='var(--secondary-bg)'">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: var(--accent-primary); color: white; font-weight: 500; border-radius: 0.75rem; border: none; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);" onmouseover="this.style.background='var(--accent-hover)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)'" onmouseout="this.style.background='var(--accent-primary)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)'">
                                <i class="fas fa-save mr-2"></i>Update
                            </button>
                        </div>
                    @csrf
                    @method('PUT')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection