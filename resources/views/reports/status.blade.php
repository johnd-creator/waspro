@extends('layouts.app')

@section('title', 'Laporan Status Limbah')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Status Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('reports.status') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="TERSIMPAN" {{ request('status') === 'TERSIMPAN' ? 'selected' : '' }}>Tersimpan</option>
                                    <option value="DIANGKUT" {{ request('status') === 'DIANGKUT' ? 'selected' : '' }}>Diangkut</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">Dari Tanggal</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" 
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">Sampai Tanggal</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" 
                                       value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if(isset($data))
                    <!-- Export Buttons -->
                    <div class="mb-3">
                        <div class="btn-group" role="group">
                            <a href="{{ route('reports.status.export', ['format' => 'pdf']) }}?{{ http_build_query(request()->all()) }}" 
                               class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <a href="{{ route('reports.status.export', ['format' => 'excel']) }}?{{ http_build_query(request()->all()) }}" 
                               class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </div>

                    <!-- Summary Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-warehouse"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tersimpan</span>
                                    <span class="info-box-number">{{ $data['summary']['stored'] }}</span>
                                    <span class="progress-description">{{ number_format($data['summary']['stored_weight'], 2) }} Ton</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-truck"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Diangkut</span>
                                    <span class="info-box-number">{{ $data['summary']['transported'] }}</span>
                                    <span class="progress-description">{{ number_format($data['summary']['transported_weight'], 2) }} Ton</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Kadaluarsa</span>
                                    <span class="info-box-number">{{ $data['summary']['expired'] }}</span>
                                    <span class="progress-description">Melewati batas penyimpanan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Jenis Limbah</th>
                                    <th>Perusahaan</th>
                                    <th>Unit</th>
                                    <th>Jumlah (Ton)</th>
                                    <th>Status</th>
                                    <th>Tanggal Pengangkutan</th>
                                    <th>Maksimal Penyimpanan</th>
                                    <th>Hari Tersisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['logs'] as $index => $log)
                                @php
                                    $daysRemaining = null;
                                    $isExpired = false;
                                    if ($log->status_log === 'TERSIMPAN' && $log->maksimal_penyimpanan_tanggal) {
                                        $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($log->maksimal_penyimpanan_tanggal), false);
                                        $isExpired = $daysRemaining < 0;
                                    }
                                @endphp
                                <tr class="{{ $isExpired ? 'table-danger' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $log->tanggal_limbah_masuk }}</td>
                                    <td>{{ $log->jenisLimbah->nama_limbah ?? 'Unknown' }}</td>
                                    <td>{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal' }}</td>
                                    <td>{{ $log->unitPembangkit->nama_unit ?? 'Unknown' }}</td>
                                    <td>{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $log->status_log === 'DIANGKUT' ? 'success' : 'warning' }}">
                                            {{ $log->status_log }}
                                        </span>
                                    </td>
                                    <td>{{ $log->tanggal_pengangkutan ?: '-' }}</td>
                                    <td>{{ $log->maksimal_penyimpanan_tanggal ?: '-' }}</td>
                                    <td>
                                        @if($daysRemaining !== null)
                                            @if($isExpired)
                                                <span class="badge badge-danger">Kadaluarsa</span>
                                            @else
                                                <span class="badge badge-{{ $daysRemaining <= 7 ? 'warning' : 'info' }}">
                                                    {{ $daysRemaining }} hari
                                                </span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data untuk filter yang dipilih</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($data['logs'], 'links'))
                        <div class="d-flex justify-content-center">
                            {{ $data['logs']->appends(request()->query())->links() }}
                        </div>
                    @endif
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Silakan pilih filter untuk menampilkan laporan.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection