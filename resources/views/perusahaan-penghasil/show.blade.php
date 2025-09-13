@extends('layouts.app')

@section('content')
<div class="px-2 py-4">
    <!-- Header Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-6">
        <div class="px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 mb-2">Detail Perusahaan Penghasil Limbah</h1>
                    <p class="text-slate-600">Informasi lengkap perusahaan penghasil limbah</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('perusahaan-penghasil.edit', $perusahaanPenghasil) }}" class="inline-flex items-center px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('perusahaan-penghasil.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl">
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
                <i class="fas fa-building mr-2"></i>Informasi Detail
            </h6>
        </div>
        <div class="px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nama Perusahaan:</th>
                                    <td><strong>{{ $perusahaanPenghasil->nama_perusahaan }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Jenis Perusahaan:</th>
                                    <td>
                                        @if($perusahaanPenghasil->jenis_perusahaan)
                                            <span class="badge bg-info">{{ $perusahaanPenghasil->jenis_perusahaan }}</span>
                                        @else
                                            <span class="text-muted">Tidak ditentukan</span>
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <th>Telepon:</th>
                                    <td>
                                        @if($perusahaanPenghasil->telepon)
                                            <i class="fas fa-phone"></i> {{ $perusahaanPenghasil->telepon }}
                                        @else
                                            <span class="text-muted">Tidak ada</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>
                                        @if($perusahaanPenghasil->email)
                                            <i class="fas fa-envelope"></i> 
                                            <a href="mailto:{{ $perusahaanPenghasil->email }}">{{ $perusahaanPenghasil->email }}</a>
                                        @else
                                            <span class="text-muted">Tidak ada</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Kota:</th>
                                    <td>
                                        @if($perusahaanPenghasil->kota)
                                            <i class="fas fa-map-marker-alt"></i> {{ $perusahaanPenghasil->kota }}
                                        @else
                                            <span class="text-muted">Tidak ditentukan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Penanggung Jawab:</th>
                                    <td>
                                        @if($perusahaanPenghasil->person_in_charge)
                                            <i class="fas fa-user"></i> {{ $perusahaanPenghasil->person_in_charge }}
                                        @else
                                            <span class="text-muted">Tidak ditentukan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($perusahaanPenghasil->status_aktif)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dibuat:</th>
                                    <td>
                                        <small class="text-muted">
                                            {{ $perusahaanPenghasil->created_at ? $perusahaanPenghasil->created_at->format('d/m/Y H:i') : '-' }}
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Diperbarui:</th>
                                    <td>
                                        <small class="text-muted">
                                            {{ $perusahaanPenghasil->updated_at ? $perusahaanPenghasil->updated_at->format('d/m/Y H:i') : '-' }}
                                        </small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Alamat Perusahaan</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0"><i class="fas fa-map-marker-alt"></i> {{ $perusahaanPenghasil->alamat_perusahaan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($perusahaanPenghasil->keterangan)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Keterangan</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $perusahaanPenghasil->keterangan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Unit Pembangkit -->
                    @if($perusahaanPenghasil->unitPembangkit && $perusahaanPenghasil->unitPembangkit->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Unit Pembangkit ({{ $perusahaanPenghasil->unitPembangkit->count() }})</h5>
                                    <a href="{{ route('unit-pembangkit.create', ['perusahaan_id' => $perusahaanPenghasil->perusahaan_id]) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Unit Pembangkit
                                        </a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Nama Unit</th>
                                                <th>Alamat</th>
                                                <th>Telepon</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($perusahaanPenghasil->unitPembangkit as $unit)
                                                <tr>
                                                    <td><strong>{{ $unit->nama_unit }}</strong></td>
                                                    <td><small>{{ Str::limit($unit->alamat_unit, 50) }}</small></td>
                                                    <td>
                                                        @if($unit->telepon_unit)
                                                            <small>{{ $unit->telepon_unit }}</small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($unit->status_aktif)
                                                            <span class="badge bg-success">Aktif</span>
                                                        @else
                                                            <span class="badge bg-danger">Tidak Aktif</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('unit-pembangkit.show', $unit) }}" 
                                                           class="btn btn-info btn-sm" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-building fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-2">Belum ada unit pembangkit</p>
                                        <a href="{{ route('unit-pembangkit.create', ['perusahaan_id' => $perusahaanPenghasil->perusahaan_id]) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Tambah Unit Pembangkit Pertama
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Log Penyimpanan Terkait -->
                    @if($perusahaanPenghasil->logPenyimpananLimbah && $perusahaanPenghasil->logPenyimpananLimbah->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Log Penyimpanan Limbah Terkait</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jenis Limbah</th>
                                                <th>Jumlah (Kg)</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($perusahaanPenghasil->logPenyimpananLimbah->take(5) as $log)
                                                <tr>
                                                    <td>{{ $log->tanggal_limbah_masuk ? \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d/m/Y') : '-' }}</td>
                                                    <td>
                                                        @if($log->jenisLimbah)
                                                            <span class="badge bg-info">{{ $log->jenisLimbah->kode_limbah }}</span>
                                                            <br><small>{{ $log->jenisLimbah->nama_limbah }}</small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                                                    <td>
                                                        @if($log->status_log == 'Tersimpan')
                                                            <span class="badge bg-primary">{{ $log->status_log }}</span>
                                                        @elseif($log->status_log == 'Diangkut')
                                                            <span class="badge bg-success">{{ $log->status_log }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ $log->status_log }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('log-penyimpanan.show', $log) }}" 
                                                           class="btn btn-info btn-sm" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($perusahaanPenghasil->logPenyimpananLimbah->count() > 5)
                                    <p class="text-muted"><small>Menampilkan 5 dari {{ $perusahaanPenghasil->logPenyimpananLimbah->count() }} log penyimpanan</small></p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <form action="{{ route('perusahaan-penghasil.destroy', $perusahaanPenghasil) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                         onclick="return handleDeleteConfirm(event, 'Apakah Anda yakin ingin menghapus perusahaan ini? Semua data terkait akan ikut terhapus.')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                        <div>
                            <a href="{{ route('perusahaan-penghasil.edit', $perusahaanPenghasil) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('perusahaan-penghasil.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> Daftar Perusahaan
                            </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection