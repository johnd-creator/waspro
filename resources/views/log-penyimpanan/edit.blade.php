@extends('layouts.app')

@push('styles')
<style>
/* Safari Select Compatibility Fixes */
select, .form-select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 40px !important;
    min-height: 42px;
}

/* Ensure consistent height with other inputs */
select, .form-select, .form-control, input[type="text"], input[type="number"], input[type="date"], textarea {
    min-height: 42px;
}

/* Safari specific fixes */
@supports (-webkit-appearance: none) {
    select, .form-select {
        background-color: white;
        border: 1px solid #ced4da;
    }
    
    select:focus, .form-select:focus {
        outline: none;
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
}
</style>
@endpush

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-2">Edit Log Penyimpanan Limbah</h1>
                <p class="text-slate-600">Ubah data penyimpanan limbah yang sudah ada</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('log-penyimpanan.show', $logPenyimpanan) }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-eye mr-2"></i> Lihat
                </a>
                <a href="{{ route('log-penyimpanan.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
        <div class="px-8 py-6">
                    <form action="{{ route('log-penyimpanan.update', $logPenyimpanan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_limbah_masuk" class="form-label">Tanggal Limbah Masuk <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_limbah_masuk') is-invalid @enderror" 
                                           id="tanggal_limbah_masuk" name="tanggal_limbah_masuk" 
                                           value="{{ old('tanggal_limbah_masuk', $logPenyimpanan->tanggal_limbah_masuk) }}" required>
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
                                                    {{ old('kode_limbah', $logPenyimpanan->kode_limbah) == $jenis->kode_limbah ? 'selected' : '' }}>
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
                                           value="{{ old('jumlah_limbah_masuk', $logPenyimpanan->jumlah_limbah_masuk) }}" step="0.01" min="0.01" required>
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
                                                    {{ old('perusahaan_id', $logPenyimpanan->perusahaan_id) == $perusahaan->perusahaan_id ? 'selected' : '' }}>
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
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status_log" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status_log') is-invalid @enderror" 
                                            id="status_log" name="status_log" required>
                                        <option value="Tersimpan" {{ old('status_log', $logPenyimpanan->status_log) == 'Tersimpan' ? 'selected' : '' }}>Tersimpan</option>
                                        <option value="Diangkut" {{ old('status_log', $logPenyimpanan->status_log) == 'Diangkut' ? 'selected' : '' }}>Diangkut</option>
                                        <option value="Kadaluarsa" {{ old('status_log', $logPenyimpanan->status_log) == 'Kadaluarsa' ? 'selected' : '' }}>Kadaluarsa</option>
                                    </select>
                                    @error('status_log')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_pengangkutan" class="form-label">Tanggal Pengangkutan</label>
                                    <input type="date" class="form-control @error('tanggal_pengangkutan') is-invalid @enderror"
                                           id="tanggal_pengangkutan" name="tanggal_pengangkutan" 
                                           value="{{ old('tanggal_pengangkutan', $logPenyimpanan->tanggal_pengangkutan ? $logPenyimpanan->tanggal_pengangkutan->format('Y-m-d') : '') }}">
                                    @error('tanggal_pengangkutan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Kosongkan jika limbah belum diangkut</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah_diangkut" class="form-label">Jumlah Diangkut (Kg)</label>
                                    <input type="number" step="0.01" min="0" class="form-control @error('jumlah_diangkut') is-invalid @enderror"
                                           id="jumlah_diangkut" name="jumlah_diangkut" 
                                           value="{{ old('jumlah_diangkut', $logPenyimpanan->jumlah_diangkut) }}">
                                    @error('jumlah_diangkut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Masukkan 0 jika limbah belum diangkut</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="detail_sumber_limbah" class="form-label">Sumber Limbah <span class="text-danger">*</span></label>
                            <select class="form-select @error('detail_sumber_limbah') is-invalid @enderror" 
                                    id="detail_sumber_limbah" name="detail_sumber_limbah" required>
                                <option value="">Pilih Sumber Limbah</option>
                                @foreach($kategoriKegiatanSumber as $kategori)
                                    <option value="{{ $kategori->nama_kategori }}" 
                                            {{ old('detail_sumber_limbah', $logPenyimpanan->detail_sumber_limbah) == $kategori->nama_kategori ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('detail_sumber_limbah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Transport Information (only show if status is Diangkut) -->
                        <div id="transport-fields" class="{{ old('status_log', $logPenyimpanan->status_log) == 'Diangkut' ? '' : 'd-none' }}">
                            <hr>
                            <h5>Informasi Pengangkutan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_pengangkutan" class="form-label">Tanggal Pengangkutan</label>
                                        <input type="date" class="form-control @error('tanggal_pengangkutan') is-invalid @enderror" 
                                               id="tanggal_pengangkutan" name="tanggal_pengangkutan" 
                                               value="{{ old('tanggal_pengangkutan', $logPenyimpanan->tanggal_pengangkutan) }}">
                                        @error('tanggal_pengangkutan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jumlah_diangkut" class="form-label">Jumlah Diangkut (Kg)</label>
                                        <input type="number" class="form-control @error('jumlah_diangkut') is-invalid @enderror" 
                                               id="jumlah_diangkut" name="jumlah_diangkut" 
                                               value="{{ old('jumlah_diangkut', $logPenyimpanan->jumlah_diangkut) }}" step="0.01" min="0">
                                        @error('jumlah_diangkut')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('log-penyimpanan.show', $logPenyimpanan) }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200">Batal</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-save mr-2"></i> Update
                            </button>
                        </div>
                    </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status_log');
    const transportFields = document.getElementById('transport-fields');
    
    statusSelect.addEventListener('change', function() {
        if (this.value === 'Diangkut') {
            transportFields.classList.remove('d-none');
        } else {
            transportFields.classList.add('d-none');
        }
    });
});
</script>
@endpush