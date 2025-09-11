@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Jenis Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('jenis-limbah.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('jenis-limbah.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode_limbah" class="form-label">Kode Limbah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode_limbah') is-invalid @enderror" 
                                           id="kode_limbah" name="kode_limbah" 
                                           value="{{ old('kode_limbah') }}" 
                                           placeholder="Contoh: A101" maxlength="10" required>
                                    <div class="form-text">Kode unik untuk jenis limbah (maksimal 10 karakter)</div>
                                    @error('kode_limbah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_limbah" class="form-label">Nama Limbah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_limbah') is-invalid @enderror" 
                                           id="nama_limbah" name="nama_limbah" 
                                           value="{{ old('nama_limbah') }}" 
                                           placeholder="Contoh: Limbah Medis Infeksius" maxlength="100" required>
                                    @error('nama_limbah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="karakteristik_id" class="form-label">Karakteristik Limbah</label>
                                    <select class="form-select @error('karakteristik_id') is-invalid @enderror" 
                                            id="karakteristik_id" name="karakteristik_id">
                                        <option value="">Pilih Karakteristik (Opsional)</option>
                                        @foreach($karakteristikLimbah as $karakteristik)
                                            <option value="{{ $karakteristik->karakteristik_id }}" 
                                                    {{ old('karakteristik_id') == $karakteristik->karakteristik_id ? 'selected' : '' }}>
                                                {{ $karakteristik->kode_karakteristik }} - {{ $karakteristik->nama_karakteristik }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('karakteristik_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kategori_id" class="form-label">Kategori Kegiatan Sumber</label>
                                    <select class="form-select @error('kategori_id') is-invalid @enderror" 
                                            id="kategori_id" name="kategori_id">
                                        <option value="">Pilih Kategori (Opsional)</option>
                                        @foreach($kategoriKegiatanSumber as $kategori)
                                            <option value="{{ $kategori->kategori_id }}" 
                                                    {{ old('kategori_id') == $kategori->kategori_id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi_limbah" class="form-label">Deskripsi Limbah</label>
                            <textarea class="form-control @error('deskripsi_limbah') is-invalid @enderror" 
                                      id="deskripsi_limbah" name="deskripsi_limbah" 
                                      rows="4" maxlength="500" 
                                      placeholder="Deskripsi detail tentang jenis limbah ini...">{{ old('deskripsi_limbah') }}</textarea>
                            <div class="form-text">Maksimal 500 karakter</div>
                            @error('deskripsi_limbah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="batas_penyimpanan_hari" class="form-label">Batas Penyimpanan (Hari)</label>
                                    <input type="number" class="form-control @error('batas_penyimpanan_hari') is-invalid @enderror" 
                                           id="batas_penyimpanan_hari" name="batas_penyimpanan_hari" 
                                           value="{{ old('batas_penyimpanan_hari') }}" 
                                           min="1" max="365" placeholder="Contoh: 90">
                                    <div class="form-text">Maksimal penyimpanan dalam hari (1-365 hari)</div>
                                    @error('batas_penyimpanan_hari')
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

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('jenis-limbah.index') }}" class="btn btn-secondary me-2">Batal</a>
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