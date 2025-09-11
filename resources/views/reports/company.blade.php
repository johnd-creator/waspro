@extends('layouts.app')

@section('title', 'Laporan Perusahaan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Perusahaan Penghasil Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('reports.company') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="perusahaan_id" class="form-label">Perusahaan</label>
                                <select name="perusahaan_id" id="perusahaan_id" class="form-control">
                                    <option value="">Semua Perusahaan</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->perusahaan_id }}" 
                                                {{ request('perusahaan_id') == $company->perusahaan_id ? 'selected' : '' }}>
                                            {{ $company->nama_perusahaan }}
                                        </option>
                                    @endforeach
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
                            <div class="col-md-2">
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
                            <a href="{{ route('reports.company.export', ['format' => 'pdf']) }}?{{ http_build_query(request()->all()) }}" 
                               class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <a href="{{ route('reports.company.export', ['format' => 'excel']) }}?{{ http_build_query(request()->all()) }}" 
                               class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </div>

                    <!-- Summary by Company -->
                    @if(isset($data['companySummary']) && count($data['companySummary']) > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Ringkasan per Perusahaan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Perusahaan</th>
                                                    <th>Total Log</th>
                                                    <th>Total Berat (Ton)</th>
                                                    <th>Tersimpan</th>
                                                    <th>Diangkut</th>
                                                    <th>Jenis Limbah Terbanyak</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data['companySummary'] as $summary)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $summary->nama_perusahaan }}</strong>
                                                    </td>
                                                    <td>{{ $summary->total_logs }}</td>
                                                    <td>{{ number_format($summary->total_weight, 2) }}</td>
                                                    <td>{{ $summary->stored_count }}</td>
                                                    <td>{{ $summary->transported_count }}</td>
                                                    <td>
                                                        <small class="text-muted">{{ $summary->top_waste_type ?? '-' }}</small>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Perusahaan</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Jenis Limbah</th>
                                    <th>Kode Limbah</th>
                                    <th>Unit</th>
                                    <th>Jumlah (Ton)</th>
                                    <th>Status</th>
                                    <th>Tanggal Pengangkutan</th>
                                    <th>Sumber Limbah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['logs'] as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong class="text-primary">{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal' }}</strong>
                                    </td>
                                    <td>{{ $log->tanggal_limbah_masuk }}</td>
                                    <td>{{ $log->jenisLimbah->nama_limbah ?? 'Unknown' }}</td>
                                    <td>{{ $log->kode_limbah }}</td>
                                    <td>{{ $log->unitPembangkit->nama_unit ?? 'Unknown' }}</td>
                                    <td>{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $log->status_log === 'DIANGKUT' ? 'success' : 'warning' }}">
                                            {{ $log->status_log }}
                                        </span>
                                    </td>
                                    <td>{{ $log->tanggal_pengangkutan ?: '-' }}</td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($log->detail_sumber_limbah, 50) }}</small>
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