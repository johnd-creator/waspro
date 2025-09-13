@extends('layouts.app')

@section('title', 'Dashboard Report - Sistem Manajemen Limbah')

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 1px, transparent 1px),
                radial-gradient(circle at 80% 50%, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 30px 30px;
    opacity: 0.3;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    transition: all 0.3s ease;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.report-card {
    border: none;
    border-radius: 15px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.report-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

.report-card .card-header {
    border: none;
    padding: 1.5rem;
    position: relative;
}

.report-card.border-primary .card-header {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.report-card.border-success .card-header {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.report-card.border-warning .card-header {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.report-card.border-danger .card-header {
    background: linear-gradient(135deg, #ff6b6b 0%, #ffa726 100%);
}

.report-card.border-info .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.report-card.border-secondary .card-header {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.progress-modern {
    height: 8px;
    border-radius: 10px;
    background: rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.progress-modern .progress-bar {
    border-radius: 10px;
    transition: width 1s ease-in-out;
}

.quick-action-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    color: white;
    padding: 12px 24px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.quick-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.dashboard-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeInUp 0.6s ease-out;
}

.animate-delay-1 { animation-delay: 0.1s; }
.animate-delay-2 { animation-delay: 0.2s; }
.animate-delay-3 { animation-delay: 0.3s; }
.animate-delay-4 { animation-delay: 0.4s; }
.animate-delay-5 { animation-delay: 0.5s; }
.animate-delay-6 { animation-delay: 0.6s; }

/* Responsive Grid Layout */
@media (max-width: 768px) {
    .hero-section {
        padding: 2rem 1rem !important;
    }
    .dashboard-title {
        font-size: 2rem;
    }
    .stat-card {
        margin-top: 2rem;
    }
    .quick-action-btn {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }
}

@media (max-width: 576px) {
    .dashboard-title {
        font-size: 1.75rem;
    }
    .col-lg-4 {
        margin-bottom: 1rem;
    }
}

/* Loading States */
.loading-skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.card-loading {
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Enhanced Animations */
.report-card {
    animation: slideInUp 0.6s ease-out;
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@section('content')
<div class="px-4">
    <!-- Hero Section -->
    <div class="hero-section p-4 mb-4 animate-fade-in">
        <div class="row align-items-center position-relative">
            <div class="col-lg-8">
                <h1 class="dashboard-title">Dashboard Report</h1>
                <p class="dashboard-subtitle">Sistem Manajemen Limbah Terintegrasi</p>
                <p class="mb-4">Pantau dan kelola data limbah dengan mudah melalui dashboard komprehensif ini</p>
                <div class="d-flex gap-3 flex-wrap">
                    <button type="button" class="quick-action-btn" onclick="window.location.href='{{ route('log-penyimpanan.index') }}'">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Log Limbah
                    </button>
                    <button type="button" class="quick-action-btn" onclick="clearReportCache()">
                        <i class="fas fa-sync-alt me-2"></i>Refresh Data
                    </button>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="stat-card p-4">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="text-primary mb-1">{{ $totalLogs ?? 0 }}</h3>
                            <small class="text-muted">Total Log</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-success mb-1">{{ $totalTransported ?? 0 }}</h3>
                            <small class="text-muted">Diangkut</small>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3 class="text-warning mb-1">{{ $totalStored ?? 0 }}</h3>
                            <small class="text-muted">Tersimpan</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-info mb-1">{{ number_format($totalWaste ?? 0, 2) }}</h3>
                            <small class="text-muted">Total (Ton)</small>
                        </div>
                    </div>
                    @if(($totalLogs ?? 0) > 0)
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Progress Pengangkutan</small>
                            <small>{{ round((($totalTransported ?? 0) / ($totalLogs ?? 1)) * 100) }}%</small>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-bar bg-success" style="width: {{ round((($totalTransported ?? 0) / ($totalLogs ?? 1)) * 100) }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards Section -->
    <div class="row g-4 mb-5">
                        <!-- Monthly/Yearly Report -->
                        <div class="col-md-6 col-lg-4 animate-fade-in animate-delay-1">
                            <div class="card report-card h-100 border-primary">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-calendar-alt me-2"></i> Laporan Bulanan/Tahunan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted mb-4">Laporan berdasarkan periode waktu tertentu dengan ringkasan statistik komprehensif.</p>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('reports.monthly') }}" class="btn btn-primary btn-lg">
                                            <i class="fas fa-eye me-2"></i> Lihat Laporan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Report -->
                        <div class="col-md-6 col-lg-4 animate-fade-in animate-delay-2">
                            <div class="card report-card h-100 border-success">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-tasks me-2"></i> Laporan Status
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted mb-4">Laporan berdasarkan status limbah (Tersimpan, Diangkut, dll) dengan analisis mendalam.</p>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('reports.status') }}" class="btn btn-success btn-lg">
                                            <i class="fas fa-eye me-2"></i> Lihat Laporan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Waste Type Report -->
                        <div class="col-md-6 col-lg-4 animate-fade-in animate-delay-3">
                            <div class="card report-card h-100 border-warning">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-recycle me-2"></i> Laporan Jenis Limbah
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted mb-4">Laporan berdasarkan jenis limbah yang disimpan dengan kategorisasi detail.</p>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('reports.waste-type') }}" class="btn btn-warning btn-lg">
                                            <i class="fas fa-eye me-2"></i> Lihat Laporan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Report -->
                        <div class="col-md-6 col-lg-4 animate-fade-in animate-delay-4">
                            <div class="card report-card h-100 border-danger">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-building me-2"></i> Laporan Perusahaan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted mb-4">Laporan berdasarkan perusahaan penghasil limbah dengan analisis performa.</p>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('reports.company') }}" class="btn btn-danger btn-lg">
                                            <i class="fas fa-eye me-2"></i> Lihat Laporan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unit Report -->
                        <div class="col-md-6 col-lg-4 animate-fade-in animate-delay-5">
                            <div class="card report-card h-100 border-info">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-industry me-2"></i> Laporan Unit
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted mb-4">Laporan berdasarkan unit pembangkit dengan breakdown detail operasional.</p>
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('reports.unit') }}" class="btn btn-info btn-lg">
                                            <i class="fas fa-eye me-2"></i> Lihat Laporan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="col-md-6 col-lg-4 animate-fade-in animate-delay-6">
                            <div class="card report-card h-100 border-secondary">
                                <div class="card-header text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-bar me-2"></i> Statistik Cepat
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="text-center p-3 bg-light rounded">
                                                <h4 class="text-primary mb-1">{{ $totalLogs ?? 0 }}</h4>
                                                <small class="text-muted">Total Log</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-3 bg-light rounded">
                                                <h4 class="text-success mb-1">{{ $totalTransported ?? 0 }}</h4>
                                                <small class="text-muted">Diangkut</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-3 bg-light rounded">
                                                <h4 class="text-warning mb-1">{{ $totalStored ?? 0 }}</h4>
                                                <small class="text-muted">Tersimpan</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center p-3 bg-light rounded">
                                                <h4 class="text-info mb-1">{{ number_format($totalWaste ?? 0, 2) }}</h4>
                                                <small class="text-muted">Total (Ton)</small>
                                            </div>
                                        </div>
                                    </div>
                                    @if(($totalLogs ?? 0) > 0)
                                    <div class="mt-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <small class="fw-bold">Progress Pengangkutan</small>
                                            <small class="text-success fw-bold">{{ round((($totalTransported ?? 0) / ($totalLogs ?? 1)) * 100) }}%</small>
                                        </div>
                                        <div class="progress-modern">
                                            @php $progressWidth = round((($totalTransported ?? 0) / ($totalLogs ?? 1)) * 100); @endphp
                                            <div class="progress-bar bg-gradient" style="width: {{ $progressWidth }}%; background: linear-gradient(90deg, #28a745, #20c997)"></div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function clearReportCache() {
    if (confirm('Apakah Anda yakin ingin menghapus cache laporan?')) {
        $.ajax({
            url: '{{ route("reports.clear-cache") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Cache laporan berhasil dihapus');
                } else {
                    toastr.error('Gagal menghapus cache laporan');
                }
            },
            error: function() {
                toastr.error('Terjadi kesalahan saat menghapus cache');
            }
        });
    }
}
</script>
@endpush
@endsection