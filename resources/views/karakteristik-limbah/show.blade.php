@extends('layouts.app')

@section('content')
<div class="px-2 py-4 dark:bg-gray-900">
    <!-- Header Section -->
    <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary); margin-bottom: 1.5rem;">
        <div style="padding: 2rem; border-bottom: 1px solid var(--border-primary);">
            <div class="flex justify-between items-start">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;" class="dark:text-white">Detail Karakteristik Limbah</h1>
                    <p style="color: var(--text-secondary);" class="dark:text-gray-300">Informasi lengkap tentang karakteristik limbah</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('karakteristik-limbah.edit', $karakteristikLimbah) }}" style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: var(--warning-bg); color: var(--warning-primary); font-weight: 500; border-radius: 0.75rem; text-decoration: none; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);" class="dark:bg-yellow-700 dark:text-yellow-100 dark:hover:bg-yellow-600 dark:hover:text-white" onmouseover="this.style.background='var(--warning-primary)'; this.style.color='white'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)'" onmouseout="this.style.background='var(--warning-bg)'; this.style.color='var(--warning-primary)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)'">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <a href="{{ route('karakteristik-limbah.index') }}" style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: var(--secondary-bg); color: white; font-weight: 500; border-radius: 0.75rem; text-decoration: none; transition: all 0.2s;" class="dark:bg-gray-700 dark:hover:bg-gray-600" onmouseover="this.style.background='var(--secondary-hover)'" onmouseout="this.style.background='var(--secondary-bg)'">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Section -->
    <div style="background: var(--card-bg); border-radius: 1rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid var(--border-primary);" class="dark:bg-gray-800 dark:border-gray-700">
        <div style="padding: 2rem;">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-1/3">
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--text-primary);" class="dark:text-white">Kode Karakteristik</span>
                            </div>
                            <div class="w-8 text-center">
                                <span style="color: var(--text-secondary);" class="dark:text-gray-400">:</span>
                            </div>
                            <div class="flex-1">
                                <span style="display: inline-flex; padding: 0.25rem 0.75rem; font-size: 0.875rem; font-weight: 600; border-radius: 9999px; background: var(--accent-bg); color: var(--accent-primary);" class="dark:bg-blue-900 dark:text-blue-200">{{ $karakteristikLimbah->kode_karakteristik }}</span>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-1/3">
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--text-primary);" class="dark:text-white">Nama Karakteristik</span>
                            </div>
                            <div class="w-8 text-center">
                                <span style="color: var(--text-secondary);" class="dark:text-gray-400">:</span>
                            </div>
                            <div class="flex-1">
                                <span style="color: var(--text-primary);" class="dark:text-white">{{ $karakteristikLimbah->nama_karakteristik }}</span>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-1/3">
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--text-primary);" class="dark:text-white">Status</span>
                            </div>
                            <div class="w-8 text-center">
                                <span style="color: var(--text-secondary);" class="dark:text-gray-400">:</span>
                            </div>
                            <div class="flex-1">
                                @if($karakteristikLimbah->status_aktif)
                                    <span style="display: inline-flex; padding: 0.25rem 0.75rem; font-size: 0.875rem; font-weight: 600; border-radius: 9999px; background: var(--success-bg); color: var(--success-primary);" class="dark:bg-green-900 dark:text-green-200">Aktif</span>
                                @else
                                    <span style="display: inline-flex; padding: 0.25rem 0.75rem; font-size: 0.875rem; font-weight: 600; border-radius: 9999px; background: var(--danger-bg); color: var(--danger-primary);" class="dark:bg-red-900 dark:text-red-200">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-1/3">
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--text-primary);" class="dark:text-white">Dibuat</span>
                            </div>
                            <div class="w-8 text-center">
                                <span style="color: var(--text-secondary);" class="dark:text-gray-400">:</span>
                            </div>
                            <div class="flex-1">
                                <span style="color: var(--text-tertiary);" class="dark:text-gray-400">{{ $karakteristikLimbah->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-1/3">
                                <span style="font-size: 0.875rem; font-weight: 500; color: var(--text-primary);" class="dark:text-white">Diperbarui</span>
                            </div>
                            <div class="w-8 text-center">
                                <span style="color: var(--text-secondary);" class="dark:text-gray-400">:</span>
                            </div>
                            <div class="flex-1">
                                <span style="color: var(--text-tertiary);" class="dark:text-gray-400">{{ $karakteristikLimbah->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1">
                    <div style="background: var(--secondary-bg-light); border-radius: 0.5rem; border: 1px solid var(--border-primary); padding: 1.5rem; text-align: center;" class="dark:bg-gray-700 dark:border-gray-600">
                        <i class="fas fa-flask text-4xl" style="color: var(--accent-primary); margin-bottom: 1rem;" class="dark:text-blue-300"></i>
                        <h5 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;" class="dark:text-white">Karakteristik Limbah</h5>
                        <p style="color: var(--text-secondary);" class="dark:text-gray-300">{{ $karakteristikLimbah->nama_karakteristik }}</p>
                    </div>
                </div>
            </div>

            <!-- Related Jenis Limbah -->
            @if($karakteristikLimbah->jenisLimbah && $karakteristikLimbah->jenisLimbah->count() > 0)
                <div style="margin-top: 2rem;">
                    <h5 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1rem;" class="dark:text-white">Jenis Limbah Terkait</h5>
                    <div class="overflow-x-auto">
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid var(--border-primary); border-radius: 0.5rem; overflow: hidden;" class="dark:border-gray-700">
                            <thead style="background: var(--table-header-bg);" class="dark:bg-gray-700">
                                <tr>
                                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.05em;" class="dark:text-gray-200">Kode</th>
                                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.05em;" class="dark:text-gray-200">Nama Limbah</th>
                                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.05em;" class="dark:text-gray-200">Status</th>
                                    <th style="padding: 0.75rem 1.5rem; text-align: left; font-size: 0.75rem; font-weight: 500; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.05em;" class="dark:text-gray-200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody style="background: var(--card-bg);" class="dark:bg-gray-800">
                                @foreach($karakteristikLimbah->jenisLimbah as $jenisLimbah)
                                    <tr style="border-bottom: 1px solid var(--border-secondary); transition: background-color 0.2s;" class="dark:border-gray-700 dark:hover:bg-gray-700" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                                        <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: var(--text-primary);" class="dark:text-gray-300">{{ $jenisLimbah->kode_limbah }}</td>
                                        <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: var(--text-primary);" class="dark:text-gray-300">{{ $jenisLimbah->nama_limbah }}</td>
                                        <td style="padding: 1rem 1.5rem; white-space: nowrap;">
                                            @if($jenisLimbah->status_aktif)
                                                <span style="display: inline-flex; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 9999px; background: var(--success-bg); color: var(--success-primary);" class="dark:bg-green-900 dark:text-green-200">Aktif</span>
                                            @else
                                                <span style="display: inline-flex; padding: 0.25rem 0.5rem; font-size: 0.75rem; font-weight: 600; border-radius: 9999px; background: var(--danger-bg); color: var(--danger-primary);" class="dark:bg-red-900 dark:text-red-200">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; font-weight: 500;">
                                            <a href="{{ route('jenis-limbah.show', $jenisLimbah) }}" 
                                               style="display: inline-flex; align-items: center; padding: 0.25rem 0.5rem; background: var(--accent-primary); color: white; font-size: 0.75rem; font-weight: 500; border-radius: 0.25rem; text-decoration: none; transition: all 0.2s;" class="dark:bg-blue-700 dark:hover:bg-blue-600" title="Lihat Detail" onmouseover="this.style.background='var(--accent-hover)'" onmouseout="this.style.background='var(--accent-primary)'">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-primary);" class="dark:border-gray-700">
                <div>
                    <form action="{{ route('karakteristik-limbah.destroy', $karakteristikLimbah) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus karakteristik limbah ini? Semua data terkait akan ikut terhapus.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="display: inline-flex; align-items: center; padding: 0.5rem 1rem; background: var(--danger-bg); color: var(--danger-primary); font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; border: none; cursor: pointer; transition: all 0.2s;" class="dark:bg-red-900 dark:text-red-200 dark:hover:bg-red-700 dark:hover:text-white" onmouseover="this.style.background='var(--danger-primary)'; this.style.color='white'" onmouseout="this.style.background='var(--danger-bg)'; this.style.color='var(--danger-primary)'">
                            <i class="fas fa-trash mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection