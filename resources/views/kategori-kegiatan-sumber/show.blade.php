@extends('layouts.app')

@section('content')
<div class="px-2 py-4 dark:bg-gray-900">
    <!-- Header Section -->
    <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary); margin-bottom: 1.5rem;" class="dark:bg-gray-800 dark:border-gray-700">
        <div style="padding: 2rem; border-bottom: 1px solid var(--border-primary);" class="dark:border-gray-700">
            <div class="flex justify-between items-start">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;" class="dark:text-white">Detail Kategori Kegiatan Sumber</h1>
                    <p style="color: var(--text-secondary);" class="dark:text-gray-300">Informasi lengkap kategori kegiatan sumber</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('kategori-kegiatan-sumber.edit', $kategoriKegiatanSumber) }}" style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: var(--warning-bg); color: var(--warning-primary); font-weight: 500; border-radius: 0.75rem; text-decoration: none; transition: all 0.2s;" class="dark:bg-yellow-700 dark:text-yellow-100 dark:hover:bg-yellow-600 dark:hover:text-white" onmouseover="this.style.background='var(--warning-primary)'; this.style.color='white'" onmouseout="this.style.background='var(--warning-bg)'; this.style.color='var(--warning-primary)'">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('kategori-kegiatan-sumber.index') }}" style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: var(--secondary-bg); color: white; font-weight: 500; border-radius: 0.75rem; text-decoration: none; transition: all 0.2s;" class="dark:bg-gray-700 dark:hover:bg-gray-600" onmouseover="this.style.background='var(--secondary-hover)'" onmouseout="this.style.background='var(--secondary-bg)'">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Section -->
    <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary);" class="dark:bg-gray-800 dark:border-gray-700">
        <div style="padding: 2rem;">
            <div class="row">
                <div class="col-md-8">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td width="25%" style="padding: 0.5rem 0; color: var(--text-primary); font-weight: 600;" class="dark:text-white">Nama Kategori</td>
                            <td width="5%" style="padding: 0.5rem 0; color: var(--text-primary);" class="dark:text-gray-400">:</td>
                            <td style="padding: 0.5rem 0; color: var(--text-secondary);" class="dark:text-gray-300">{{ $kategoriKegiatanSumber->nama_kategori }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.5rem 0; color: var(--text-primary); font-weight: 600;" class="dark:text-white">Dibuat</td>
                            <td style="padding: 0.5rem 0; color: var(--text-primary);" class="dark:text-gray-400">:</td>
                            <td style="padding: 0.5rem 0; color: var(--text-secondary);" class="dark:text-gray-300">{{ $kategoriKegiatanSumber->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 0.5rem 0; color: var(--text-primary); font-weight: 600;" class="dark:text-white">Diperbarui</td>
                            <td style="padding: 0.5rem 0; color: var(--text-primary);" class="dark:text-gray-400">:</td>
                            <td style="padding: 0.5rem 0; color: var(--text-secondary);" class="dark:text-gray-300">{{ $kategoriKegiatanSumber->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <div style="background: var(--secondary-bg-light); border-radius: 0.5rem; border: 1px solid var(--border-primary);" class="dark:bg-gray-700 dark:border-gray-600">
                        <div style="padding: 1.5rem; text-align: center;">
                            <i class="fas fa-list-alt fa-3x mb-3" style="color: var(--accent-primary);" class="dark:text-blue-300"></i>
                            <h5 style="color: var(--text-primary); margin-bottom: 0.5rem;" class="dark:text-white">Kategori Kegiatan</h5>
                            <p style="color: var(--text-secondary); margin: 0;" class="dark:text-gray-300">{{ $kategoriKegiatanSumber->nama_kategori }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Form -->
            <div class="row mt-4">
                <div class="col-12">
                    <div style="border: 1px solid var(--danger-primary); border-radius: 0.5rem;" class="dark:border-red-700">
                        <div style="background: var(--danger-bg); color: var(--danger-primary); padding: 1rem; border-radius: 0.5rem 0.5rem 0 0;" class="dark:bg-red-900 dark:text-red-200">
                            <h6 style="margin: 0; font-weight: 600;">Zona Berbahaya</h6>
                        </div>
                        <div style="padding: 1.5rem; background: var(--card-bg);" class="dark:bg-gray-800">
                            <p style="margin-bottom: 1rem; color: var(--text-secondary);" class="dark:text-gray-300">Menghapus kategori kegiatan sumber ini akan mempengaruhi semua log penyimpanan limbah yang terkait. Pastikan Anda yakin sebelum melanjutkan.</p>
                            <form action="{{ route('kategori-kegiatan-sumber.destroy', $kategoriKegiatanSumber) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: var(--danger-bg); color: var(--danger-primary); font-weight: 500; border-radius: 0.5rem; border: none; cursor: pointer; transition: all 0.2s;" 
                                        class="dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-700 dark:hover:text-white"
                                        onmouseover="this.style.background='var(--danger-primary)'; this.style.color='white'" 
                                        onmouseout="this.style.background='var(--danger-bg)'; this.style.color='var(--danger-primary)'"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus kategori kegiatan sumber ini? Tindakan ini tidak dapat dibatalkan!')">
                                    <i class="fas fa-trash mr-2"></i> Hapus
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