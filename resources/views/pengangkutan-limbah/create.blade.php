@extends('layouts.app')

@section('title', 'Tambah Data Pengangkutan Limbah')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="card shadow-sm border-0 flex-grow-1 me-3">
                    <div class="card-header bg-gradient-primary text-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-truck-moving me-3 fs-4"></i>
                            <div>
                                <h4 class="mb-0 fw-bold">Tambah Data Pengangkutan Limbah</h4>
                                <small class="opacity-75">Tambahkan data limbah yang langsung diangkut</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('pengangkutan-limbah.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card shadow-sm rounded-2xl">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('pengangkutan-limbah.store') }}">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            <!-- Jenis Limbah -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kode_limbah" class="form-label fw-medium">Jenis Limbah <span class="text-danger">*</span></label>
                                    <select name="kode_limbah" id="kode_limbah" class="form-select @error('kode_limbah') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis Limbah</option>
                                        @foreach($jenisLimbah as $jenis)
                                            <option value="{{ $jenis->kode_limbah }}" {{ old('kode_limbah') == $jenis->kode_limbah ? 'selected' : '' }}>
                                                {{ $jenis->kode_limbah }} - {{ $jenis->nama_limbah }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kode_limbah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Perusahaan Penghasil -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="perusahaan_id" class="form-label fw-medium">Perusahaan Penghasil <span class="text-danger">*</span></label>
                                    <select name="perusahaan_id" id="perusahaan_id" class="form-select @error('perusahaan_id') is-invalid @enderror" required>
                                        <option value="">Pilih Perusahaan</option>
                                        @foreach($perusahaan as $p)
                                            <option value="{{ $p->perusahaan_id }}" {{ old('perusahaan_id') == $p->perusahaan_id ? 'selected' : '' }}>
                                                {{ $p->nama_perusahaan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('perusahaan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Jumlah Limbah -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah_limbah_masuk" class="form-label fw-medium">Jumlah Limbah (Kg) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="jumlah_limbah_masuk" id="jumlah_limbah_masuk" 
                                        class="form-control @error('jumlah_limbah_masuk') is-invalid @enderror" 
                                        value="{{ old('jumlah_limbah_masuk') }}" required>
                                    @error('jumlah_limbah_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tanggal Limbah Masuk -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_limbah_masuk" class="form-label fw-medium">Tanggal Limbah Masuk <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_limbah_masuk" id="tanggal_limbah_masuk" 
                                        class="form-control @error('tanggal_limbah_masuk') is-invalid @enderror" 
                                        value="{{ old('tanggal_limbah_masuk', date('Y-m-d')) }}" required>
                                    @error('tanggal_limbah_masuk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label fw-medium">Keterangan</label>
                                    <textarea name="keterangan" id="keterangan" rows="3" 
                                        class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <a href="{{ route('pengangkutan-limbah.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize select2 if available
        if ($.fn.select2) {
            $('#kode_limbah, #perusahaan_id').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        }
    });
</script>
@endpush