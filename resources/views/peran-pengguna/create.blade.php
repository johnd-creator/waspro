@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Tambah Peran Pengguna</h1>
                    <p class="text-slate-600">Buat peran baru untuk pengguna sistem</p>
                </div>
                <div>
                    <a href="{{ route('peran-pengguna.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
            <div class="px-8 py-6 border-b border-slate-200">
                <h6 class="text-lg font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-user-plus mr-2"></i>Form Tambah Peran
                </h6>
            </div>
            <div class="px-8 py-6">
                <x-session-messages />
                
                <form action="{{ route('peran-pengguna.store') }}" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <x-form-input 
                                    name="nama_peran" 
                                    label="Nama Peran" 
                                    placeholder="Masukkan nama peran" 
                                    :required="true" 
                                    maxlength="255" 
                                />
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">Status</label>
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
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
                                rows="4" 
                                maxlength="1000" 
                            />
                        </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('peran-pengguna.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i>Simpan
                            </button>
                        </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection