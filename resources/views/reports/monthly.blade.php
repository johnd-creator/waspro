@extends('layouts.app')

@section('title', 'Laporan Bulanan/Tahunan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Bulanan/Tahunan</h3>
                    <div class="card-tools">
                        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('reports.monthly') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="year" class="form-label">Tahun</label>
                                <select name="year" id="year" class="form-control">
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="month" class="form-label">Bulan (Opsional)</label>
                                <select name="month" id="month" class="form-control">
                                    <option value="">Semua Bulan</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="unit_id" class="form-label">Unit Pembangkit</label>
                                <select name="unit_id" id="unit_id" class="form-control">
                                    <option value="">Semua Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->unit_id }}" {{ (request('unit_id') ?? $unitId) == $unit->unit_id ? 'selected' : '' }}>
                                            {{ $unit->nama_unit }}
                                        </option>
                                    @endforeach
                                </select>
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
                            <a href="{{ route('reports.monthly.export', ['format' => 'pdf']) }}?{{ http_build_query(request()->all()) }}" 
                               class="btn btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <a href="{{ route('reports.monthly.export', ['format' => 'excel']) }}?{{ http_build_query(request()->all()) }}" 
                               class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </div>

                    <!-- Summary Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-list"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Log</span>
                                    <span class="info-box-number">{{ $data['summary']['total_logs'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-truck"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Diangkut</span>
                                    <span class="info-box-number">{{ $data['summary']['transported'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-warehouse"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tersimpan</span>
                                    <span class="info-box-number">{{ $data['summary']['stored'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-weight"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total (Ton)</span>
                                    <span class="info-box-number">{{ number_format($data['summary']['total_weight'], 2) }}</span>
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
                                    <th>Kode Limbah</th>
                                    <th>Perusahaan</th>
                                    <th>Unit</th>
                                    <th>Jumlah (Kg)</th>
                                    <th>Status</th>
                                    <th>Tanggal Pengangkutan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['logs'] as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $log->tanggal_limbah_masuk }}</td>
                                    <td>{{ $log->jenisLimbah->nama_limbah ?? 'Unknown' }}</td>
                                    <td>{{ $log->kode_limbah }}</td>
                                    <td>{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal' }}</td>
                                    <td>{{ $log->unitPembangkit->nama_unit ?? 'Unknown' }}</td>
                                    <td>{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $log->status_log === 'DIANGKUT' ? 'success' : 'warning' }}">
                                            {{ $log->status_log }}
                                        </span>
                                    </td>
                                    <td>{{ $log->tanggal_pengangkutan ?: '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data untuk periode yang dipilih</td>
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