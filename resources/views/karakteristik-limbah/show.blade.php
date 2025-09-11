@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">


            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Karakteristik Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('karakteristik-limbah.edit', $karakteristikLimbah) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('karakteristik-limbah.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="25%"><strong>Kode Karakteristik</strong></td>
                                    <td width="5%">:</td>
                                    <td><span class="badge bg-info fs-6">{{ $karakteristikLimbah->kode_karakteristik }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Karakteristik</strong></td>
                                    <td>:</td>
                                    <td>{{ $karakteristikLimbah->nama_karakteristik }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>:</td>
                                    <td>
                                        @if($karakteristikLimbah->status_aktif)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat</strong></td>
                                    <td>:</td>
                                    <td>{{ $karakteristikLimbah->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diperbarui</strong></td>
                                    <td>:</td>
                                    <td>{{ $karakteristikLimbah->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="fas fa-flask fa-3x text-primary mb-3"></i>
                                    <h5>Karakteristik Limbah</h5>
                                    <p class="text-muted">{{ $karakteristikLimbah->nama_karakteristik }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Jenis Limbah -->
                    @if($karakteristikLimbah->jenisLimbah && $karakteristikLimbah->jenisLimbah->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Jenis Limbah Terkait</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Kode</th>
                                                        <th>Nama Limbah</th>
                                                        <th>Kategori</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($karakteristikLimbah->jenisLimbah->take(5) as $jenisLimbah)
                                                        <tr>
                                                            <td><span class="badge bg-secondary">{{ $jenisLimbah->kode_limbah }}</span></td>
                                                            <td>{{ $jenisLimbah->nama_limbah }}</td>
                                                            <td>{{ $jenisLimbah->kategori ?? '-' }}</td>
                                                            <td>
                                                                @if($jenisLimbah->status_aktif)
                                                                    <span class="badge bg-success">Aktif</span>
                                                                @else
                                                                    <span class="badge bg-danger">Tidak Aktif</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('jenis-limbah.show', $jenisLimbah) }}" 
                                                                   class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($karakteristikLimbah->jenisLimbah->count() > 5)
                                            <div class="text-center mt-2">
                                                <small class="text-muted">Menampilkan 5 dari {{ $karakteristikLimbah->jenisLimbah->count() }} jenis limbah</small>
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
                                    <p class="mb-3">Menghapus karakteristik limbah ini akan mempengaruhi semua jenis limbah yang terkait. Pastikan Anda yakin sebelum melanjutkan.</p>
                                    <form action="{{ route('karakteristik-limbah.destroy', $karakteristikLimbah) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus karakteristik limbah ini? Tindakan ini tidak dapat dibatalkan!')">
                                            <i class="fas fa-trash"></i> Hapus Karakteristik Limbah
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