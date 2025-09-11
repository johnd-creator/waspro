@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle"></i> Laporan Status Kadaluarsa Limbah
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('expiry-reports.dashboard') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar"></i> Dashboard
                        </a>
                        <button type="button" class="btn btn-success" onclick="exportData()">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Summary Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Kadaluarsa</span>
                                    <span class="info-box-number">{{ $summary['expired'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Kritis</span>
                                    <span class="info-box-number">{{ $summary['critical'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Peringatan</span>
                                    <span class="info-box-number">{{ $summary['warning'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Aman</span>
                                    <span class="info-box-number">{{ $summary['safe'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Form -->
                    <div class="card card-outline card-primary mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-filter"></i> Filter Data</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('expiry-reports.index') }}" id="filterForm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Status Kadaluarsa</label>
                                        <select name="expiry_status" class="form-control">
                                            <option value="">Semua Status</option>
                                            <option value="Expired" {{ request('expiry_status') == 'Expired' ? 'selected' : '' }}>Kadaluarsa</option>
                                            <option value="Critical" {{ request('expiry_status') == 'Critical' ? 'selected' : '' }}>Kritis</option>
                                            <option value="Warning" {{ request('expiry_status') == 'Warning' ? 'selected' : '' }}>Peringatan</option>
                                            <option value="Safe" {{ request('expiry_status') == 'Safe' ? 'selected' : '' }}>Aman</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Jenis Limbah</label>
                                        <select name="jenis_limbah_id" class="form-control">
                                            <option value="">Semua Jenis</option>
                                            @foreach($jenisLimbah as $jenis)
                                                <option value="{{ $jenis->id }}" {{ request('jenis_limbah_id') == $jenis->id ? 'selected' : '' }}>
                                                    {{ $jenis->nama_limbah }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Perusahaan</label>
                                        <select name="perusahaan_id" class="form-control">
                                            <option value="">Semua Perusahaan</option>
                                            @foreach($perusahaan as $company)
                                                <option value="{{ $company->id }}" {{ request('perusahaan_id') == $company->id ? 'selected' : '' }}>
                                                    {{ $company->nama_perusahaan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Kadaluarsa</label>
                                        <div class="input-group">
                                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Dari">
                                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Sampai">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('expiry-reports.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="4%">No</th>
                                    <th width="10%">Tanggal Masuk</th>
                                    <th width="8%">Kode</th>
                                    <th width="15%">Jenis Limbah</th>
                                    <th width="8%">Jumlah (Kg)</th>
                                    <th width="15%">Perusahaan</th>
                                    <th width="10%">Tanggal Kadaluarsa</th>
                                    <th width="12%">Status Kadaluarsa</th>
                                    <th width="8%">Hari Tersisa</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $index => $log)
                                    <tr>
                                        <td>{{ $logs->firstItem() + $index }}</td>
                                        <td>{{ $log->tanggal_limbah_masuk }}</td>
                                        <td>{{ $log->kode_limbah }}</td>
                                        <td>{{ $log->jenisLimbah->nama_limbah ?? 'N/A' }}</td>
                                        <td>{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                                        <td>{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'N/A' }}</td>
                                        <td>
                                            @if($log->tanggal_kadaluarsa)
                                                {{ \Carbon\Carbon::parse($log->tanggal_kadaluarsa)->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->expiry_status)
                                                <span class="badge {{ $log->getExpiryStatusBadgeClass() }}">
                                                    {{ $log->getExpiryStatusText() }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Belum Dihitung</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $daysUntilExpiry = $log->getDaysUntilExpiry();
                                            @endphp
                                            @if($daysUntilExpiry !== null)
                                                @if($daysUntilExpiry > 0)
                                                    <span class="text-success">{{ $daysUntilExpiry }} hari</span>
                                                @elseif($daysUntilExpiry == 0)
                                                    <span class="text-warning">Hari ini</span>
                                                @else
                                                    <span class="text-danger">-{{ abs($daysUntilExpiry) }} hari</span>
                                                @endif
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('log-penyimpanan.show', $log) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Tidak ada data yang ditemukan</h5>
                                                <p class="text-muted">Silakan ubah filter atau tambah data baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($logs->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportData() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    
    window.location.href = '{{ route("expiry-reports.export") }}?' + params.toString();
}
</script>
@endsection