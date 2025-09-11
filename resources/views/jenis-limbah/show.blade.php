@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">


            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Jenis Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('jenis-limbah.edit', $jenisLimbah) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('jenis-limbah.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Kode Limbah:</th>
                                    <td>
                                        <span class="badge bg-info fs-6">{{ $jenisLimbah->kode_limbah }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Nama Limbah:</th>
                                    <td><strong>{{ $jenisLimbah->nama_limbah }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Karakteristik:</th>
                                    <td>
                                        @if($jenisLimbah->karakteristikLimbah)
                                            <span class="badge bg-secondary">{{ $jenisLimbah->karakteristikLimbah->nama_karakteristik }}</span>
                                        @else
                                            <span class="text-muted">Tidak ada karakteristik</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kategori Sumber:</th>
                                    <td>
                                        @if($jenisLimbah->kategoriKegiatanSumber)
                                            <span class="badge bg-warning">{{ $jenisLimbah->kategoriKegiatanSumber->kode_kategori }}</span>
                                            <br><small class="text-muted">{{ $jenisLimbah->kategoriKegiatanSumber->nama_kategori }}</small>
                                        @else
                                            <span class="text-muted">Tidak ada kategori</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Batas Penyimpanan:</th>
                                    <td>
                                        @if($jenisLimbah->batas_penyimpanan_hari)
                                            <span class="badge bg-info">{{ $jenisLimbah->batas_penyimpanan_hari }} hari</span>
                                        @else
                                            <span class="text-muted">Tidak ditentukan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($jenisLimbah->status_aktif)
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
                                            {{ $jenisLimbah->created_at ? $jenisLimbah->created_at->format('d/m/Y H:i') : '-' }}
                                        </small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Diperbarui:</th>
                                    <td>
                                        <small class="text-muted">
                                            {{ $jenisLimbah->updated_at ? $jenisLimbah->updated_at->format('d/m/Y H:i') : '-' }}
                                        </small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($jenisLimbah->deskripsi_limbah)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Deskripsi Limbah</h5>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0">{{ $jenisLimbah->deskripsi_limbah }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Related Log Penyimpanan -->
                    @if($jenisLimbah->logPenyimpananLimbah && $jenisLimbah->logPenyimpananLimbah->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Log Penyimpanan Terkait</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tanggal Masuk</th>
                                                <th>Jumlah (Kg)</th>
                                                <th>Status</th>
                                                <th>Perusahaan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jenisLimbah->logPenyimpananLimbah->take(5) as $log)
                                                <tr>
                                                    <td>{{ $log->tanggal_limbah_masuk ? \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d/m/Y') : '-' }}</td>
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
                                                        @if($log->perusahaanPenghasil)
                                                            {{ $log->perusahaanPenghasil->nama_perusahaan }}
                                                        @else
                                                            <span class="text-muted">-</span>
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
                                @if($jenisLimbah->logPenyimpananLimbah->count() > 5)
                                    <p class="text-muted"><small>Menampilkan 5 dari {{ $jenisLimbah->logPenyimpananLimbah->count() }} log penyimpanan</small></p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <form action="{{ route('jenis-limbah.destroy', $jenisLimbah) }}" method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis limbah ini? Semua data terkait akan ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                        <div>
                            <a href="{{ route('jenis-limbah.edit', $jenisLimbah) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('jenis-limbah.index') }}" class="btn btn-secondary">
                                <i class="fas fa-list"></i> Daftar Jenis Limbah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection