@extends('layouts.app')

@section('title', 'Limbah Diangkut')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Limbah Diangkut</h3>
                    <div class="card-tools">
                        <a href="{{ route('pengangkutan-limbah.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali ke Pengangkutan
                        </a>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card-body">
                    <form method="GET" action="{{ route('pengangkutan-limbah.diangkut') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="jenis_limbah" class="form-control form-control-sm">
                                    <option value="">Semua Jenis Limbah</option>
                                    @foreach($jenisLimbah as $jenis)
                                        <option value="{{ $jenis->kode_limbah }}" 
                                            {{ request('jenis_limbah') == $jenis->kode_limbah ? 'selected' : '' }}>
                                            {{ $jenis->nama_limbah }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="perusahaan" class="form-control form-control-sm">
                                    <option value="">Semua Perusahaan</option>
                                    @foreach($perusahaan as $p)
                                        <option value="{{ $p->perusahaan_id }}" 
                                            {{ request('perusahaan') == $p->perusahaan_id ? 'selected' : '' }}>
                                            {{ $p->nama_perusahaan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="tanggal_mulai" class="form-control form-control-sm" 
                                    value="{{ request('tanggal_mulai') }}" placeholder="Tanggal Pengangkutan Mulai">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="tanggal_akhir" class="form-control form-control-sm" 
                                    value="{{ request('tanggal_akhir') }}" placeholder="Tanggal Pengangkutan Akhir">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="kode_identitas" class="form-control form-control-sm" 
                                    value="{{ request('kode_identitas') }}" placeholder="Cari Kode Identitas...">
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('pengangkutan-limbah.diangkut') }}" class="btn btn-secondary btn-sm ml-1">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Kode Identitas</th>
                                    <th>Jenis Limbah</th>
                                    <th>Perusahaan</th>
                                    <th>Unit</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Diangkut</th>
                                    <th>Jumlah Masuk (Kg)</th>
                                    <th>Jumlah Diangkut (Kg)</th>
                                    <th>Status</th>
                                    <th>Operator Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logPenyimpanan as $log)
                                <tr>
                                    <td>{{ $log->kode_identitas }}</td>
                                    <td>{{ $log->jenisLimbah->nama_limbah ?? '-' }}</td>
                                    <td>{{ $log->perusahaanPenghasil->nama_perusahaan ?? '-' }}</td>
                                    <td>{{ $log->unitPembangkit->nama_unit ?? '-' }}</td>
                                    <td>{{ $log->tanggal_limbah_masuk->format('d/m/Y') }}</td>
                                    <td>
                                        @if($log->tanggal_pengangkutan)
                                            {{ $log->tanggal_pengangkutan->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                                    <td>{{ number_format($log->jumlah_diangkut, 2) }}</td>
                                    <td>
                                        <span class="badge badge-success">
                                            <i class="fas fa-truck"></i> Diangkut
                                        </span>
                                    </td>
                                    <td>{{ $log->penggunaSistem->nama_lengkap ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        Tidak ada data limbah yang sudah diangkut.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $logPenyimpanan->firstItem() ?? 0 }} sampai {{ $logPenyimpanan->lastItem() ?? 0 }} 
                            dari {{ $logPenyimpanan->total() }} data
                        </div>
                        <div>
                            {{ $logPenyimpanan->appends(request()->query())->links() }}
                        </div>
                    </div>

                    <!-- Summary Statistics -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h5 class="card-title">Ringkasan Pengangkutan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="info-box bg-success">
                                                <span class="info-box-icon"><i class="fas fa-truck"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Total Diangkut</span>
                                                    <span class="info-box-number">{{ $logPenyimpanan->count() }} Item</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-box bg-info">
                                                <span class="info-box-icon"><i class="fas fa-weight"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Total Berat</span>
                                                    <span class="info-box-number">{{ number_format($logPenyimpanan->sum('jumlah_diangkut'), 2) }} Kg</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-box bg-warning">
                                                <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Periode</span>
                                                    <span class="info-box-number">
                                                        @if(request('tanggal_mulai') && request('tanggal_akhir'))
                                                            {{ date('d/m/Y', strtotime(request('tanggal_mulai'))) }} - {{ date('d/m/Y', strtotime(request('tanggal_akhir'))) }}
                                                        @else
                                                            Semua Periode
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-box bg-secondary">
                                                <span class="info-box-icon"><i class="fas fa-building"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Jenis Limbah</span>
                                                    <span class="info-box-number">{{ $logPenyimpanan->groupBy('kode_limbah')->count() }} Jenis</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

@push('styles')
<style>
.info-box {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.info-box-number {
    font-size: 14px !important;
    font-weight: bold;
}

.info-box-text {
    font-size: 12px;
    text-transform: uppercase;
}
</style>
@endpush