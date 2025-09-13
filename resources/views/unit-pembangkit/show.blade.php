@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Detail Unit Pembangkit</h1>
                    <p class="text-slate-600">Informasi lengkap unit pembangkit listrik</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('unit-pembangkit.edit', $unitPembangkit) }}" class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('unit-pembangkit.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="px-8 py-6 border-b border-slate-200">
            <h6 class="text-lg font-semibold text-slate-900 flex items-center">
                <i class="fas fa-bolt mr-2"></i>Informasi Detail
            </h6>
        </div>
        <div class="px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="25%"><strong>Nama Unit</strong></td>
                                    <td width="5%">:</td>
                                    <td>{{ $unitPembangkit->nama_unit }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat</strong></td>
                                    <td>:</td>
                                    <td>{{ $unitPembangkit->alamat_unit ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kota</strong></td>
                                    <td>:</td>
                                    <td>{{ $unitPembangkit->kota ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kode Pos</strong></td>
                                    <td>:</td>
                                    <td>{{ $unitPembangkit->kode_pos ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-building fa-3x text-primary mb-3"></i>
                                    <h5>Unit Pembangkit</h5>
                                    <p class="text-muted">{{ $unitPembangkit->nama_unit }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($unitPembangkit->alamat_unit)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Alamat Unit Pembangkit</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $unitPembangkit->alamat_unit }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($unitPembangkit->keterangan)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Keterangan</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $unitPembangkit->keterangan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Related Log Penyimpanan -->
                    @if($unitPembangkit->logPenyimpananLimbah && $unitPembangkit->logPenyimpananLimbah->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Log Penyimpanan Limbah dari Unit Ini</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Jenis Limbah</th>
                                                        <th>Jumlah</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($unitPembangkit->logPenyimpananLimbah->take(5) as $log)
                                                        <tr>
                                                            <td>{{ $log->tanggal_penyimpanan->format('d/m/Y') }}</td>
                                                            <td>{{ $log->jenisLimbah->nama_limbah ?? '-' }}</td>
                                                            <td>{{ $log->jumlah }} {{ $log->satuan }}</td>
                                                            <td>
                                                                @if($log->status_transportasi)
                                                                    <span class="badge bg-success">Sudah Diangkut</span>
                                                                @else
                                                                    <span class="badge bg-warning">Belum Diangkut</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('log-penyimpanan.show', $log) }}" 
                                                                   class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($unitPembangkit->logPenyimpananLimbah->count() > 5)
                                            <div class="text-center mt-2">
                                                <small class="text-muted">Menampilkan 5 dari {{ $unitPembangkit->logPenyimpananLimbah->count() }} log penyimpanan</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Delete Form -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="card-title mb-0">Zona Berbahaya</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Menghapus unit pembangkit ini akan mempengaruhi semua log penyimpanan limbah yang terkait. Pastikan Anda yakin sebelum melanjutkan.</p>
                                    <form action="{{ route('unit-pembangkit.destroy', $unitPembangkit) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus unit pembangkit ini? Tindakan ini tidak dapat dibatalkan!')">
                                            <i class="fas fa-trash"></i> Hapus Unit Pembangkit
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection