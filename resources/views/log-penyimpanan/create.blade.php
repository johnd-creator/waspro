@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Log Penyimpanan Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('log-penyimpanan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('log-penyimpanan.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_limbah_masuk" class="form-label">Tanggal Limbah Masuk <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_limbah_masuk') is-invalid @enderror" 
                                           id="tanggal_limbah_masuk" name="tanggal_limbah_masuk" 
                                           value="{{ old('tanggal_limbah_masuk', date('Y-m-d')) }}" required>
                                    @error('tanggal_limbah_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode_limbah" class="form-label">Jenis Limbah <span class="text-danger">*</span></label>
                                    <select class="form-select @error('kode_limbah') is-invalid @enderror" 
                                            id="kode_limbah" name="kode_limbah" required>
                                        <option value="">Pilih Jenis Limbah</option>
                                        @foreach($jenisLimbah as $jenis)
                                            <option value="{{ $jenis->kode_limbah }}" 
                                                    {{ old('kode_limbah') == $jenis->kode_limbah ? 'selected' : '' }}>
                                                {{ $jenis->kode_limbah }} - {{ $jenis->nama_limbah }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kode_limbah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah_limbah_masuk" class="form-label">Jumlah Limbah Masuk (Kg) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('jumlah_limbah_masuk') is-invalid @enderror" 
                                           id="jumlah_limbah_masuk" name="jumlah_limbah_masuk" 
                                           value="{{ old('jumlah_limbah_masuk') }}" step="0.01" min="0.01" required>
                                    @error('jumlah_limbah_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="perusahaan_id" class="form-label">Perusahaan Penghasil</label>
                                    <select class="form-select @error('perusahaan_id') is-invalid @enderror" 
                                            id="perusahaan_id" name="perusahaan_id">
                                        <option value="">Pilih Perusahaan (Opsional)</option>
                                        @foreach($perusahaanPenghasil as $perusahaan)
                                            <option value="{{ $perusahaan->perusahaan_id }}" 
                                                    {{ old('perusahaan_id') == $perusahaan->perusahaan_id ? 'selected' : '' }}>
                                                {{ $perusahaan->nama_perusahaan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('perusahaan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>



                        <div class="mb-3">
                            <label for="detail_sumber_limbah" class="form-label">Detail Sumber Limbah <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('detail_sumber_limbah') is-invalid @enderror" 
                                      id="detail_sumber_limbah" name="detail_sumber_limbah" 
                                      rows="4" maxlength="1000" required>{{ old('detail_sumber_limbah') }}</textarea>
                            <div class="form-text">Maksimal 1000 karakter</div>
                            @error('detail_sumber_limbah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('log-penyimpanan.index') }}" class="btn btn-secondary me-2">Batal</a>
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