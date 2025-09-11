@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="dashboard-title">Dashboard</h1>
                <p class="dashboard-subtitle">Sistem Manajemen Limbah Terintegrasi</p>
            </div>
            <div class="header-right">
                <div class="date-widget">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ now()->format('l, d F Y') }}</span>
                </div>
                <div class="time-widget" id="currentTime"></div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-card-primary" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card-content">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ number_format($totalLogs) }}</h3>
                    <p class="stat-label">Total Log Penyimpanan</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+12% dari bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-success" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card-content">
                <div class="stat-icon">
                    <i class="fas fa-recycle"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ number_format($totalWasteTypes) }}</h3>
                    <p class="stat-label">Jenis Limbah</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+5% dari bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-info" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card-content">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ number_format($totalCompanies) }}</h3>
                    <p class="stat-label">Perusahaan Penghasil</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+8% dari bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="stat-card stat-card-warning" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card-content">
                <div class="stat-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="stat-info">
                    <h3 class="stat-number">{{ number_format($totalBranches) }}</h3>
                    <p class="stat-label">Unit Pembangkit</p>
                    <div class="stat-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>+3% dari bulan lalu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="charts-section">
        <div class="section-header">
            <h2 class="section-title">Analisis Data</h2>
            <p class="section-subtitle">Visualisasi data limbah dan tren penyimpanan</p>
        </div>
        
        <div class="charts-grid">
            <!-- Monthly Waste Chart -->
            <div class="chart-card chart-card-large" data-aos="fade-up" data-aos-delay="500">
                <div class="chart-header">
                    <div class="chart-title-group">
                        <h3 class="chart-title">Penyimpanan Limbah Bulanan</h3>
                        <p class="chart-subtitle">Tren penyimpanan limbah sepanjang tahun</p>
                    </div>
                    <div class="chart-actions">
                        <button class="btn-chart-action" data-period="month">Bulan</button>
                        <button class="btn-chart-action active" data-period="year">Tahun</button>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="chart-area">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Status Distribution Chart -->
            <div class="chart-card chart-card-small" data-aos="fade-up" data-aos-delay="600">
                <div class="chart-header">
                    <div class="chart-title-group">
                        <h3 class="chart-title">Status Limbah</h3>
                        <p class="chart-subtitle">Distribusi status limbah saat ini</p>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="chart-pie">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-color" style="background: #4e73df;"></span>
                            <span class="legend-label">Tersimpan</span>
                            <span class="legend-value">65%</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #1cc88a;"></span>
                            <span class="legend-label">Diangkut</span>
                            <span class="legend-value">25%</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #e74a3b;"></span>
                            <span class="legend-label">Kadaluarsa</span>
                            <span class="legend-value">10%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Section -->
    <div class="data-section">
        <div class="section-header">
            <h2 class="section-title">Data Teratas</h2>
            <p class="section-subtitle">Peringkat limbah dan perusahaan berdasarkan volume</p>
        </div>
        
        <div class="data-grid">
            <!-- Top Waste Types -->
            <div class="data-card" data-aos="fade-up" data-aos-delay="700">
                <div class="data-header">
                    <div class="data-title-group">
                        <h3 class="data-title">Top 10 Jenis Limbah</h3>
                        <p class="data-subtitle">Berdasarkan total volume penyimpanan</p>
                    </div>
                    <div class="data-actions">
                        <button class="btn-data-action">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn-data-action">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
                <div class="data-body">
                    <div class="modern-table">
                        <div class="table-header">
                            <div class="table-cell">Nama Limbah</div>
                            <div class="table-cell">Total (Ton)</div>
                            <div class="table-cell">Jumlah Log</div>
                        </div>
                        <div class="table-body">
                            @foreach($topWasteTypes as $index => $waste)
                            <div class="table-row">
                                <div class="table-cell">
                                    <div class="cell-content">
                                        <span class="rank-badge">{{ $index + 1 }}</span>
                                        <span class="cell-text">{{ $waste->nama_limbah }}</span>
                                    </div>
                                </div>
                                <div class="table-cell">
                                    <span class="cell-number">{{ number_format($waste->total_quantity, 2) }}</span>
                                </div>
                                <div class="table-cell">
                                    <span class="cell-badge">{{ number_format($waste->total_logs) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Companies -->
            <div class="data-card" data-aos="fade-up" data-aos-delay="800">
                <div class="data-header">
                    <div class="data-title-group">
                        <h3 class="data-title">Top 10 Perusahaan Penghasil</h3>
                        <p class="data-subtitle">Berdasarkan total volume limbah yang dihasilkan</p>
                    </div>
                    <div class="data-actions">
                        <button class="btn-data-action">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn-data-action">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
                <div class="data-body">
                    <div class="modern-table">
                        <div class="table-header">
                            <div class="table-cell">Nama Perusahaan</div>
                            <div class="table-cell">Total (Ton)</div>
                            <div class="table-cell">Jumlah Log</div>
                        </div>
                        <div class="table-body">
                            @foreach($topCompanies as $index => $company)
                            <div class="table-row">
                                <div class="table-cell">
                                    <div class="cell-content">
                                        <span class="rank-badge">{{ $index + 1 }}</span>
                                        <span class="cell-text">{{ $company->nama_perusahaan }}</span>
                                    </div>
                                </div>
                                <div class="table-cell">
                                    <span class="cell-number">{{ number_format($company->total_quantity, 2) }}</span>
                                </div>
                                <div class="table-cell">
                                    <span class="cell-badge">{{ number_format($company->total_logs) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Near Expiry Waste Alert -->
    @if($nearExpiryWaste->count() > 0)
    <div class="alert-section">
        <div class="alert-card alert-danger" data-aos="fade-up" data-aos-delay="900">
            <div class="alert-header">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-content">
                    <h3 class="alert-title">Peringatan Limbah Kritis</h3>
                    <p class="alert-subtitle">{{ $nearExpiryWaste->count() }} limbah mendekati atau melewati batas penyimpanan</p>
                </div>
                <div class="alert-actions">
                    <button class="btn-alert-action">
                        <i class="fas fa-bell"></i>
                        Notifikasi
                    </button>
                </div>
            </div>
            <div class="alert-body">
                <div class="alert-table">
                    @foreach($nearExpiryWaste as $waste)
                    <div class="alert-row {{ \Carbon\Carbon::parse($waste->maksimal_penyimpanan_tanggal)->isPast() ? 'expired' : 'warning' }}">
                        <div class="alert-item-info">
                            <div class="alert-item-main">
                                <span class="item-name">{{ $waste->jenisLimbah->nama_limbah }}</span>
                                <span class="item-company">{{ $waste->perusahaanPenghasil->nama_perusahaan ?? '-' }}</span>
                            </div>
                            <div class="alert-item-details">
                                <span class="item-branch">{{ $waste->unitPembangkit->nama_unit }}</span>
                                <span class="item-amount">{{ number_format($waste->jumlah_limbah_masuk, 2) }} Ton</span>
                            </div>
                        </div>
                        <div class="alert-item-status">
                            <div class="status-date">{{ \Carbon\Carbon::parse($waste->maksimal_penyimpanan_tanggal)->format('d/m/Y') }}</div>
                            <div class="status-badge {{ \Carbon\Carbon::parse($waste->maksimal_penyimpanan_tanggal)->isPast() ? 'expired' : 'warning' }}">
                                @if(\Carbon\Carbon::parse($waste->maksimal_penyimpanan_tanggal)->isPast())
                                    <i class="fas fa-times-circle"></i> Kadaluarsa
                                @else
                                    <i class="fas fa-exclamation-circle"></i> Mendekati Batas
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Activities -->
    <div class="activities-section">
        <div class="section-header">
            <h2 class="section-title">Aktivitas Terbaru</h2>
            <p class="section-subtitle">Log aktivitas sistem dalam 24 jam terakhir</p>
        </div>
        
        <div class="activities-card" data-aos="fade-up" data-aos-delay="1000">
            <div class="activities-header">
                <div class="activities-title-group">
                    <h3 class="activities-title">Log Aktivitas Sistem</h3>
                    <span class="activities-count">{{ $recentActivities->count() }} aktivitas terbaru</span>
                </div>
                <div class="activities-actions">
                    <button class="btn-activities-action">
                        <i class="fas fa-sync-alt"></i>
                        Refresh
                    </button>
                    <button class="btn-activities-action">
                        <i class="fas fa-history"></i>
                        Lihat Semua
                    </button>
                </div>
            </div>
            <div class="activities-body">
                <div class="activities-timeline">
                    @foreach($recentActivities as $activity)
                    <div class="timeline-item">
                        <div class="timeline-marker">
                            <div class="timeline-icon status-{{ strtolower(str_replace(' ', '-', $activity->status_log)) }}">
                                @if($activity->status_log == 'Tersimpan')
                                    <i class="fas fa-archive"></i>
                                @elseif($activity->status_log == 'Diangkut')
                                    <i class="fas fa-truck"></i>
                                @else
                                    <i class="fas fa-exclamation"></i>
                                @endif
                            </div>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <h4 class="timeline-title">{{ $activity->jenisLimbah->nama_limbah }}</h4>
                                <span class="timeline-time">{{ \Carbon\Carbon::parse($activity->timestamp_input)->diffForHumans() }}</span>
                            </div>
                            <div class="timeline-details">
                                <div class="detail-row">
                                    <span class="detail-label">Perusahaan:</span>
                                    <span class="detail-value">{{ $activity->perusahaanPenghasil->nama_perusahaan ?? '-' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Jumlah:</span>
                                    <span class="detail-value">{{ number_format($activity->jumlah_limbah_masuk, 2) }} Ton</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Pengguna:</span>
                                    <span class="detail-value">{{ $activity->penggunaSistem->nama_lengkap }}</span>
                                </div>
                            </div>
                            <div class="timeline-status">
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $activity->status_log)) }}">
                                    {{ $activity->status_log }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });

    // Update time widget
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const timeWidget = document.querySelector('.time-widget');
        if (timeWidget) {
            timeWidget.textContent = timeString;
        }
    }

    // Update time every second
    setInterval(updateTime, 1000);
    updateTime();

    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyChart = new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Total Limbah (Ton)',
                data: [12, 19, 3, 5, 2, 3, 15, 8, 12, 7, 9, 11],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tersimpan', 'Diangkut', 'Kadaluarsa'],
            datasets: [{
                data: [65, 25, 10],
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#e74a3b'
                ],
                hoverBackgroundColor: [
                    '#2e59d9',
                    '#17a673',
                    '#e02d1b'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        },
    });
});
</script>
@endpush

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
/* Dashboard Container */
.dashboard-container {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem;
}

/* Header Styles */
.dashboard-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-title {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 0;
}

.dashboard-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
    margin: 0.5rem 0 0 0;
}

.header-right {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.date-widget, .time-widget {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 500;
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
}

.stat-card-primary { --gradient-start: #4e73df; --gradient-end: #224abe; }
.stat-card-success { --gradient-start: #1cc88a; --gradient-end: #13855c; }
.stat-card-info { --gradient-start: #36b9cc; --gradient-end: #258391; }
.stat-card-warning { --gradient-start: #f6c23e; --gradient-end: #dda20a; }

.stat-card-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    color: white;
    font-size: 1.8rem;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.stat-label {
    color: #6c757d;
    font-weight: 500;
    margin: 0.5rem 0;
}

.stat-trend {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #1cc88a;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Section Headers */
.section-header {
    text-align: center;
    margin-bottom: 2rem;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: white;
    margin: 0;
}

.section-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
    margin: 0.5rem 0 0 0;
}

/* Charts Section */
.charts-section {
    margin-bottom: 3rem;
}

.charts-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}

.chart-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.chart-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.chart-subtitle {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0.25rem 0 0 0;
}

.chart-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-chart-action {
    padding: 0.5rem 1rem;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 8px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-chart-action.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.chart-area {
    position: relative;
    height: 300px;
    width: 100%;
}

.chart-pie {
    position: relative;
    height: 250px;
    width: 100%;
}

.chart-legend {
    margin-top: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 0.75rem;
}

.legend-label {
    flex: 1;
    font-weight: 500;
}

.legend-value {
    font-weight: 600;
    color: #2d3748;
}

/* Data Section */
.data-section {
    margin-bottom: 3rem;
}

.data-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 1.5rem;
}

.data-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.data-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.data-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.data-subtitle {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0.25rem 0 0 0;
}

.data-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-data-action {
    width: 40px;
    height: 40px;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-data-action:hover {
    background: #f8fafc;
    border-color: #cbd5e0;
}

/* Modern Table */
.modern-table {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.table-header {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    background: #f8fafc;
    font-weight: 600;
    color: #4a5568;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.table-header .table-cell {
    padding: 1rem;
    border-right: 1px solid #e2e8f0;
}

.table-body .table-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 0.2s;
}

.table-body .table-row:hover {
    background: #f8fafc;
}

.table-body .table-cell {
    padding: 1rem;
    border-right: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
}

.cell-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.rank-badge {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
}

.cell-number {
    font-weight: 600;
    color: #2d3748;
}

.cell-badge {
    background: #e2e8f0;
    color: #4a5568;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Alert Section */
.alert-section {
    margin-bottom: 3rem;
}

.alert-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border-left: 6px solid #e74a3b;
}

.alert-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.alert-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #e74a3b, #c0392b);
    color: white;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.alert-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.alert-subtitle {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0.25rem 0 0 0;
}

.btn-alert-action {
    padding: 0.5rem 1rem;
    background: #e74a3b;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 0.85rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-left: auto;
}

.alert-row {
    padding: 1rem;
    border-radius: 12px;
    margin-bottom: 0.75rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s;
}

.alert-row.expired {
    background: #fee;
    border-left: 4px solid #e74a3b;
}

.alert-row.warning {
    background: #fff8e1;
    border-left: 4px solid #f6c23e;
}

.alert-item-main {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.item-name {
    font-weight: 600;
    color: #2d3748;
}

.item-company {
    color: #6c757d;
    font-size: 0.9rem;
}

.alert-item-details {
    display: flex;
    gap: 1rem;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: #6c757d;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-badge.expired {
    background: #e74a3b;
    color: white;
}

.status-badge.warning {
    background: #f6c23e;
    color: white;
}

/* Activities Section */
.activities-section {
    margin-bottom: 2rem;
}

.activities-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.activities-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.activities-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.activities-count {
    color: #6c757d;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

.activities-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-activities-action {
    padding: 0.5rem 1rem;
    border: 1px solid #e2e8f0;
    background: white;
    border-radius: 8px;
    font-size: 0.85rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.btn-activities-action:hover {
    background: #f8fafc;
}

/* Timeline */
.activities-timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    position: relative;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 25px;
    top: 50px;
    bottom: -24px;
    width: 2px;
    background: #e2e8f0;
}

.timeline-marker {
    flex-shrink: 0;
}

.timeline-icon {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
}

.timeline-icon.status-tersimpan {
    background: linear-gradient(135deg, #36b9cc, #258391);
}

.timeline-icon.status-diangkut {
    background: linear-gradient(135deg, #1cc88a, #13855c);
}

.timeline-content {
    flex: 1;
    background: #f8fafc;
    border-radius: 12px;
    padding: 1.5rem;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.timeline-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
}

.timeline-time {
    color: #6c757d;
    font-size: 0.85rem;
}

.timeline-details {
    margin-bottom: 1rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.detail-label {
    color: #6c757d;
    font-weight: 500;
}

.detail-value {
    color: #2d3748;
    font-weight: 500;
}

.timeline-status .status-badge {
    font-size: 0.8rem;
    padding: 0.25rem 0.75rem;
}

.status-badge.status-tersimpan {
    background: #36b9cc;
    color: white;
}

.status-badge.status-diangkut {
    background: #1cc88a;
    color: white;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .data-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-title {
        font-size: 2rem;
    }
    
    .table-header,
    .table-body .table-row {
        grid-template-columns: 1fr;
    }
    
    .table-cell {
        border-right: none !important;
        border-bottom: 1px solid #f1f5f9;
    }
}
</style>
@endpush