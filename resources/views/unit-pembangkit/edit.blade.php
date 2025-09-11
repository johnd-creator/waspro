@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Unit Pembangkit</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('unit-pembangkit.update', $unitPembangkit) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_unit" class="form-label">Nama Unit <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama_unit') is-invalid @enderror" 
                                           id="nama_unit" name="nama_unit" 
                                           value="{{ old('nama_unit', $unitPembangkit->nama_unit) }}" required>
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
                                           value="{{ old('kode_pos', $unitPembangkit->kode_pos) }}" required>
                                    @error('kode_pos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alamat_unit" class="form-label">Alamat Unit <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('alamat_unit') is-invalid @enderror" 
                                          id="alamat_unit" name="alamat_unit" 
                                          rows="3" required>{{ old('alamat_unit', $unitPembangkit->alamat_unit) }}</textarea>
                                @error('alamat_unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kota" class="form-label">Kota <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('kota') is-invalid @enderror" 
                                           id="kota" name="kota" 
                                           value="{{ old('kota', $unitPembangkit->kota) }}" required>
                                    @error('kota')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>




                        <div class="d-flex justify-content-between">
                            <a href="{{ route('unit-pembangkit.show', $unitPembangkit) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Unit Pembangkit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection