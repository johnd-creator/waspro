@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Unit Pembangkit</h3>
                    <div class="card-tools">
                        <a href="{{ route('unit-pembangkit.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('unit-pembangkit.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_unit" class="form-label">Nama Unit <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_unit') is-invalid @enderror" 
                                           id="nama_unit" name="nama_unit" 
                                           value="{{ old('nama_unit') }}" 
                                           placeholder="Contoh: Unit Pembangkit Jakarta Pusat" maxlength="100" required>
                                    @error('nama_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode_pos" class="form-label">Kode Pos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" 
                                           id="kode_pos" name="kode_pos" 
                                           value="{{ old('kode_pos') }}" 
                                           placeholder="Contoh: 12345" maxlength="10" required>
                                    @error('kode_pos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kota" class="form-label">Kota <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kota') is-invalid @enderror" 
                                           id="kota" name="kota" 
                                           value="{{ old('kota') }}" 
                                           placeholder="Contoh: Jakarta" maxlength="50" required>
                                    @error('kota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat_unit" class="form-label">Alamat Unit <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat_unit') is-invalid @enderror" 
                                              id="alamat_unit" name="alamat_unit" 
                                              rows="3" maxlength="500" required>{{ old('alamat_unit') }}</textarea>
                                    @error('alamat_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                        </div>





                        <div class="d-flex justify-content-end">
                            <a href="{{ route('unit-pembangkit.index') }}" class="btn btn-secondary me-2">Batal</a>
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