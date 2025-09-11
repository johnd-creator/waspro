@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Perusahaan Penghasil Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('perusahaan-penghasil.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('perusahaan-penghasil.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
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

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('perusahaan-penghasil.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection