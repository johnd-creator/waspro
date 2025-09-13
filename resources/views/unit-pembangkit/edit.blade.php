@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Edit Unit Pembangkit</h1>
                    <p class="text-slate-600">Perbarui informasi unit pembangkit listrik</p>
                </div>
                <div>
                    <a href="{{ route('unit-pembangkit.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
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
                    <i class="fas fa-bolt mr-2"></i>Informasi Unit Pembangkit
                </h6>
            </div>
            <div class="px-8 py-6">
                <form method="POST" action="{{ route('unit-pembangkit.update', $unitPembangkit) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 mt-8">
                            <a href="{{ route('unit-pembangkit.show', $unitPembangkit) }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i>Update Unit Pembangkit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection