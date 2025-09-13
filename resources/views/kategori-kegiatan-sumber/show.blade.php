@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6 border-b border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Detail Kategori Kegiatan Sumber</h1>
                    <p class="text-slate-600">Informasi lengkap kategori kegiatan sumber</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('kategori-kegiatan-sumber.edit', $kategoriKegiatanSumber) }}" class="inline-flex items-center px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-xl transition-all duration-200">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('kategori-kegiatan-sumber.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white font-medium rounded-xl transition-all duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-8 py-6">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="25%"><strong>Nama Kategori</strong></td>
                                    <td width="5%">:</td>
                                    <td>{{ $kategoriKegiatanSumber->nama_kategori }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat</strong></td>
                                    <td>:</td>
                                    <td>{{ $kategoriKegiatanSumber->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diperbarui</strong></td>
                                    <td>:</td>
                                    <td>{{ $kategoriKegiatanSumber->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-list-alt fa-3x text-primary mb-3"></i>
                                    <h5>Kategori Kegiatan</h5>
                                    <p class="text-muted">{{ $kategoriKegiatanSumber->nama_kategori }}</p>
                                </div>
                            </div>
                        </div>
                    </div>





                    <!-- Delete Form -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="card-title mb-0">Zona Berbahaya</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Menghapus kategori kegiatan sumber ini akan mempengaruhi semua log penyimpanan limbah yang terkait. Pastikan Anda yakin sebelum melanjutkan.</p>
                                    <form action="{{ route('kategori-kegiatan-sumber.destroy', $kategoriKegiatanSumber) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus kategori kegiatan sumber ini? Tindakan ini tidak dapat dibatalkan!')">
                                            <i class="fas fa-trash"></i> Hapus Kategori Kegiatan Sumber
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    </div>
</div>
@endsection