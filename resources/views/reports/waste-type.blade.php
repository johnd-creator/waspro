@extends('layouts.app')

@section('title', 'Laporan Jenis Limbah')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Jenis Limbah</h3>
                    <div class="card-tools">
                        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('reports.waste-type') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="jenis_limbah_id" class="form-label">Jenis Limbah</label>
                                <select name="jenis_limbah_id" id="jenis_limbah_id" class="form-control">
                                    <option value="">Semua Jenis Limbah</option>
                                    @foreach($wasteTypes as $wasteType)
                                        <option value="{{ $wasteType->jenis_limbah_id }}" 
                                                {{ request('jenis_limbah_id') == $wasteType->jenis_limbah_id ? 'selected' : '' }}>
                                            {{ $wasteType->nama_limbah }}
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
                            <a href="{{ route('reports.waste-type.export', ['format' => 'pdf']) }}?{{ http_build_query(request()->all()) }}" 
                               class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <a href="{{ route('reports.waste-type.export', ['format' => 'excel']) }}?{{ http_build_query(request()->all()) }}" 
                               class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </div>

                    <!-- Summary by Waste Type -->
                    @if(isset($data['wasteTypeSummary']) && count($data['wasteTypeSummary']) > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Ringkasan per Jenis Limbah</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Jenis Limbah</th>
                                                    <th>Total Log</th>
                                                    <th>Total Berat (Kg)</th>
                                                    <th>Tersimpan</th>
                                                    <th>Diangkut</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($data['wasteTypeSummary'] as $summary)
                                                <tr>
                                                    <td>{{ $summary->nama_limbah }}</td>
                                                    <td>{{ $summary->total_logs }}</td>
                                                    <td>{{ number_format($summary->total_weight, 2) }}</td>
                                                    <td>{{ $summary->stored_count }}</td>
                                                    <td>{{ $summary->transported_count }}</td>
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
                                    <th>Jenis Limbah</th>
                                    <th>Kode Limbah</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Perusahaan</th>
                                    <th>Unit</th>
                                    <th>Jumlah (Kg)</th>
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
                                        <span class="badge badge-info">{{ $log->jenisLimbah->nama_limbah ?? 'Unknown' }}</span>
                                    </td>
                                    <td>{{ $log->kode_limbah }}</td>
                                    <td>{{ $log->tanggal_limbah_masuk }}</td>
                                    <td>{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal' }}</td>
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
                        <div class="flex justify-center mt-6">
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