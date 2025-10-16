@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div style="background-color: var(--success-bg); border: 1px solid var(--success-border); color: var(--success-text);" class="px-4 py-3 rounded-xl mb-6 flex items-center" role="alert" data-auto-dismiss="2500">
            <i style="color: var(--success-primary);" class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
            <button type="button" style="color: var(--success-primary); transition: opacity 0.2s;" class="ml-auto hover:opacity-75" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="rounded-2xl shadow-sm border mb-6" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6 flex justify-between items-center" style="border-color: var(--border-primary);">
            <div>
                <h1 class="text-2xl font-bold mb-2" style="color: var(--text-primary);">Detail Unit Pembangkit</h1>
                <p style="color: var(--text-secondary);">Informasi lengkap mengenai unit pembangkit</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('unit-pembangkit.index') }}" class="inline-flex items-center px-6 py-3 font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--card-secondary-bg); color: var(--text-primary); border: 1px solid var(--border-primary);"
                   onmouseover="this.style.backgroundColor='var(--hover-bg)'; this.style.boxShadow='var(--shadow-xl)';"
                   onmouseout="this.style.backgroundColor='var(--card-secondary-bg)'; this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('unit-pembangkit.edit', $unitPembangkit) }}" class="inline-flex items-center px-6 py-3 text-white font-medium rounded-xl transition-all duration-200 shadow-lg"
                   style="background-color: var(--accent-primary);"
                   onmouseover="this.style.boxShadow='var(--shadow-xl)';"
                   onmouseout="this.style.boxShadow='var(--shadow-lg)';">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="rounded-2xl shadow-sm border" style="background-color: var(--card-bg); border-color: var(--border-primary);">
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left: Informasi Unit -->
                <div>
                    <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-4">Informasi Unit</h5>
                    <div class="space-y-4">
                        <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                            <span style="color: var(--text-secondary);" class="font-medium">Nama Unit:</span>
                            <span style="color: var(--text-primary);">{{ $unitPembangkit->nama_unit }}</span>
                        </div>
                        <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                            <span style="color: var(--text-secondary);" class="font-medium">Alamat:</span>
                            <span style="color: var(--text-primary);" class="text-right">{{ $unitPembangkit->alamat_unit ?? '-' }}</span>
                        </div>
                        <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                            <span style="color: var(--text-secondary);" class="font-medium">Kota:</span>
                            <span style="color: var(--text-primary);">{{ $unitPembangkit->kota ?? '-' }}</span>
                        </div>
                        <div style="border-bottom: 1px solid var(--border-secondary);" class="flex justify-between py-2">
                            <span style="color: var(--text-secondary);" class="font-medium">Kode Pos:</span>
                            <span style="color: var(--text-primary);">{{ $unitPembangkit->kode_pos ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Card Visual -->
                <div>
                    <div style="background-color: var(--secondary-bg-light); border: 1px solid var(--border-secondary);" class="rounded-xl p-6 text-center h-full flex flex-col items-center justify-center">
                        <i style="color: var(--accent-primary);" class="fas fa-building text-5xl mb-4"></i>
                        <h5 style="color: var(--text-primary);" class="text-lg font-semibold">Unit Pembangkit</h5>
                        <p style="color: var(--text-tertiary);">{{ $unitPembangkit->nama_unit }}</p>
                    </div>
                </div>
            </div>

            @if(!empty($unitPembangkit->alamat_unit))
                <div class="mt-8">
                    <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-3">Alamat Unit Pembangkit</h5>
                    <div style="background-color: var(--secondary-bg-light); border: 1px solid var(--border-secondary);" class="rounded-lg">
                        <div class="p-4">
                            <p style="color: var(--text-primary);">{{ $unitPembangkit->alamat_unit }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(!empty($unitPembangkit->keterangan))
                <div class="mt-8">
                    <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-3">Keterangan</h5>
                    <div style="background-color: var(--secondary-bg-light); border: 1px solid var(--border-secondary);" class="rounded-lg">
                        <div class="p-4">
                            <p style="color: var(--text-primary);">{{ $unitPembangkit->keterangan }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(method_exists($unitPembangkit, 'logPenyimpananLimbah') && $unitPembangkit->logPenyimpananLimbah && $unitPembangkit->logPenyimpananLimbah->count() > 0)
                <div class="mt-8">
                    <h5 style="color: var(--text-primary);" class="text-lg font-semibold mb-4">Log Penyimpanan Limbah dari Unit Ini</h5>
                    <div style="background-color: var(--card-bg); border: 1px solid var(--border-primary);" class="rounded-xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[700px]">
                                <thead style="background: linear-gradient(to right, var(--table-header-start), var(--table-header-end)); color: white;">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Tanggal Masuk</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Jenis Limbah</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Jumlah (Kg)</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody style="border-color: var(--border-secondary);" class="divide-y">
                                    @foreach($unitPembangkit->logPenyimpananLimbah->take(5) as $log)
                                        <tr style="transition: all 0.2s;" class="hover:bg-opacity-50" onmouseover="this.style.backgroundColor='var(--hover-bg)'" onmouseout="this.style.backgroundColor='transparent'">
                                            <td style="color: var(--text-secondary);" class="px-4 py-3 text-sm">{{ $log->tanggal_limbah_masuk ?? '-' }}</td>
                                            <td style="color: var(--text-secondary);" class="px-4 py-3 text-sm">{{ $log->jenisLimbah->nama_limbah ?? '-' }}</td>
                                            <td style="color: var(--text-primary);" class="px-4 py-3 text-sm">
                                                <span style="background-color: var(--accent-bg); color: var(--accent-primary);" class="px-2 py-1 rounded-md">
                                                    {{ isset($log->jumlah_limbah_masuk) ? number_format($log->jumlah_limbah_masuk, 2) : '-' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @php $status = $log->status_log ?? null; @endphp
                                                @if($status === 'Tersimpan')
                                                    <span style="background-color: var(--accent-bg); color: var(--accent-primary);" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium">{{ $status }}</span>
                                                @elseif($status === 'Diangkut')
                                                    <span style="background-color: var(--success-bg); color: var(--success-primary);" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium">{{ $status }}</span>
                                                @else
                                                    <span style="background-color: var(--secondary-bg-light); color: var(--text-secondary);" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium">{{ $status ?? '-' }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="{{ route('log-penyimpanan.show', $log) }}" style="background-color: var(--accent-bg); color: var(--accent-primary); transition: all 0.2s;" class="inline-flex items-center justify-center w-8 h-8 rounded-lg hover:opacity-80" title="Lihat Log">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($unitPembangkit->logPenyimpananLimbah->count() > 5)
                            <div style="border-top: 1px solid var(--border-secondary); color: var(--text-tertiary);" class="text-center px-4 py-3 text-sm">
                                Menampilkan 5 dari {{ $unitPembangkit->logPenyimpananLimbah->count() }} log penyimpanan
                            </div>
                        @endif
                    </div>
                </div>
            @endif


        </div>
    </div>
</div>
@endsection
