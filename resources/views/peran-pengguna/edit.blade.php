@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="mb-6 rounded-2xl border shadow-sm" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6 flex justify-between items-center">
            <div>
                <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">Edit Peran Pengguna</h1>
                <p style="color: var(--text-secondary);">Ubah informasi peran: {{ $peranPengguna->nama_peran }}</p>
            </div>
            <div>
                <a href="{{ route('peran-pengguna.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);"
                   onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';"
                   onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-8 py-6 border-b" style="border-color: var(--border-primary);">
            <h6 class="section-title flex items-center">
                <i class="fas fa-user-edit mr-2"></i>Form Edit Peran
            </h6>
        </div>
        <div class="px-8 py-6">
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
                            <a href="{{ route('peran-pengguna.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                               style="background-color: var(--danger-primary); color: white;"
                               onmouseover="this.style.backgroundColor='var(--danger-hover)'; this.style.boxShadow='var(--shadow-xl)';"
                               onmouseout="this.style.backgroundColor='var(--danger-primary)'; this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg"
                                    style="background-color: var(--accent-primary);"
                                    onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                                    onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                                <i class="fas fa-save mr-2"></i>Perbarui Peran
                            </button>
                        </div>
                    @csrf
                    @method('PUT')
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
