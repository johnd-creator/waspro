@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Tambah Perusahaan Penghasil Limbah</h1>
                    <p class="text-slate-600">Masukkan informasi perusahaan penghasil limbah baru</p>
                </div>
                <div>
                    <a href="{{ route('perusahaan-penghasil.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
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
                    <i class="fas fa-building mr-2"></i>Informasi Perusahaan
                </h6>
            </div>
            <div class="px-8 py-6">
                <form action="{{ route('perusahaan-penghasil.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_perusahaan" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror" 
                                           id="nama_perusahaan" name="nama_perusahaan" 
                                           value="{{ old('nama_perusahaan') }}" 
                                           placeholder="Contoh: PT. Rumah Sakit Sehat" maxlength="100" required>
                                    @error('nama_perusahaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_perusahaan" class="form-label">Jenis Perusahaan</label>
                                    <select class="form-select @error('jenis_perusahaan') is-invalid @enderror" 
                                            id="jenis_perusahaan" name="jenis_perusahaan">
                                        <option value="">Pilih Jenis Perusahaan</option>
                                        <option value="Rumah Sakit" {{ old('jenis_perusahaan') == 'Rumah Sakit' ? 'selected' : '' }}>Rumah Sakit</option>
                                        <option value="Klinik" {{ old('jenis_perusahaan') == 'Klinik' ? 'selected' : '' }}>Klinik</option>
                                        <option value="Laboratorium" {{ old('jenis_perusahaan') == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                                        <option value="Industri" {{ old('jenis_perusahaan') == 'Industri' ? 'selected' : '' }}>Industri</option>
                                        <option value="Perkantoran" {{ old('jenis_perusahaan') == 'Perkantoran' ? 'selected' : '' }}>Perkantoran</option>
                                        <option value="Lainnya" {{ old('jenis_perusahaan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('jenis_perusahaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telepon" class="form-label">Telepon</label>
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror" 
                                           id="telepon" name="telepon" 
                                           value="{{ old('telepon') }}" 
                                           placeholder="Contoh: 021-1234567" maxlength="15">
                                    @error('telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Contoh: info@perusahaan.com" maxlength="100">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kota" class="form-label">Kota</label>
                                    <input type="text" class="form-control @error('kota') is-invalid @enderror" 
                                           id="kota" name="kota" 
                                           value="{{ old('kota') }}" 
                                           placeholder="Contoh: Jakarta" maxlength="50">
                                    @error('kota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat_perusahaan') is-invalid @enderror" 
                                      id="alamat_perusahaan" name="alamat_perusahaan" 
                                      rows="3" maxlength="255" required
                                      placeholder="Alamat lengkap perusahaan...">{{ old('alamat_perusahaan') }}</textarea>
                            <div class="form-text">Maksimal 255 karakter</div>
                            @error('alamat_perusahaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="person_in_charge" class="form-label">Penanggung Jawab</label>
                                    <input type="text" class="form-control @error('person_in_charge') is-invalid @enderror" 
                                           id="person_in_charge" name="person_in_charge" 
                                           value="{{ old('person_in_charge') }}" 
                                           placeholder="Nama penanggung jawab" maxlength="100">
                                    @error('person_in_charge')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status_aktif" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status_aktif') is-invalid @enderror" 
                                            id="status_aktif" name="status_aktif" required>
                                        <option value="1" {{ old('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status_aktif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                      id="keterangan" name="keterangan" 
                                      rows="3" maxlength="500" 
                                      placeholder="Keterangan tambahan tentang perusahaan...">{{ old('keterangan') }}</textarea>
                            <div class="form-text">Maksimal 500 karakter</div>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('perusahaan-penghasil.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection