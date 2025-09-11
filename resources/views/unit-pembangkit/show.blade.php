@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">


            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Unit Pembangkit</h3>
                    <div class="card-tools">
                        <a href="{{ route('unit-pembangkit.edit', $unitPembangkit) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('unit-pembangkit.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
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
</div>
@endsection