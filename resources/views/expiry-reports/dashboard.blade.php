@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i> Dashboard Status Kadaluarsa Limbah
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('expiry-reports.index') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> Lihat Detail
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Summary Statistics -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>{{ $statistics['expired'] }}</h3>
                                    <p>Limbah Kadaluarsa</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <a href="{{ route('expiry-reports.index', ['expiry_status' => 'Expired']) }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $statistics['critical'] }}</h3>
                                    <p>Status Kritis</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <a href="{{ route('expiry-reports.index', ['expiry_status' => 'Critical']) }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $statistics['warning'] }}</h3>
                                    <p>Status Peringatan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <a href="{{ route('expiry-reports.index', ['expiry_status' => 'Warning']) }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $statistics['safe'] }}</h3>
                                    <p>Status Aman</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <a href="{{ route('expiry-reports.index', ['expiry_status' => 'Safe']) }}" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Charts Row -->
                    <div class="row">
                        <!-- Pie Chart -->
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie"></i> Distribusi Status Kadaluarsa
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bar Chart -->
                        <div class="col-md-6">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-bar"></i> Status per Jenis Limbah
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Expired Items -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-exclamation-circle"></i> Limbah yang Baru Kadaluarsa (7 Hari Terakhir)
                                    </h3>
                                </div>
                                <div class="card-body">
                                    @if($recentExpired->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Kode Limbah</th>
                                                        <th>Jenis Limbah</th>
                                                        <th>Jumlah (Kg)</th>
                                                        <th>Perusahaan</th>
                                                        <th>Tanggal Kadaluarsa</th>
                                                        <th>Hari Terlambat</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentExpired as $item)
                                                        <tr>
                                                            <td>{{ $item->kode_limbah }}</td>
                                                            <td>{{ $item->jenisLimbah->nama_limbah ?? 'N/A' }}</td>
                                                            <td>{{ number_format($item->jumlah_limbah_masuk, 2) }}</td>
                                                            <td>{{ $item->perusahaanPenghasil->nama_perusahaan ?? 'N/A' }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d/m/Y') }}</td>
                                                            <td>
                                                                <span class="badge bg-danger">
                                                                    {{ abs($item->getDaysUntilExpiry()) }} hari
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                            <h5 class="text-muted">Tidak ada limbah yang baru kadaluarsa</h5>
                                            <p class="text-muted">Semua limbah masih dalam kondisi baik dalam 7 hari terakhir</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Critical Items Alert -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-exclamation-triangle"></i> Limbah dengan Status Kritis (≤ 7 Hari)
                                    </h3>
                                </div>
                                <div class="card-body">
                                    @if($criticalItems->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Kode Limbah</th>
                                                        <th>Jenis Limbah</th>
                                                        <th>Jumlah (Kg)</th>
                                                        <th>Perusahaan</th>
                                                        <th>Tanggal Kadaluarsa</th>
                                                        <th>Hari Tersisa</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($criticalItems as $item)
                                                        <tr>
                                                            <td>{{ $item->kode_limbah }}</td>
                                                            <td>{{ $item->jenisLimbah->nama_limbah ?? 'N/A' }}</td>
                                                            <td>{{ number_format($item->jumlah_limbah_masuk, 2) }}</td>
                                                            <td>{{ $item->perusahaanPenghasil->nama_perusahaan ?? 'N/A' }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d/m/Y') }}</td>
                                                            <td>
                                                                @php $days = $item->getDaysUntilExpiry(); @endphp
                                                                @if($days > 0)
                                                                    <span class="badge bg-warning">{{ $days }} hari</span>
                                                                @elseif($days == 0)
                                                                    <span class="badge bg-danger">Hari ini</span>
                                                                @else
                                                                    <span class="badge bg-danger">Terlambat {{ abs($days) }} hari</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                                            <h5 class="text-muted">Tidak ada limbah dengan status kritis</h5>
                                            <p class="text-muted">Semua limbah masih dalam batas waktu yang aman</p>
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
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Pie Chart
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
    var pieData = {
        labels: ['Kadaluarsa', 'Kritis', 'Peringatan', 'Aman'],
        datasets: [{
            data: [{{ $statistics['expired'] ?? 0 }}, {{ $statistics['critical'] ?? 0 }}, {{ $statistics['warning'] ?? 0 }}, {{ $statistics['safe'] ?? 0 }}],
            backgroundColor: ['#dc3545', '#ffc107', '#17a2b8', '#28a745']
        }]
    };
    
    var pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    };
    
    new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions
    });
    
    // Bar Chart
    var barChartCanvas = $('#barChart').get(0).getContext('2d');
    var barData = {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [
            {
                label: 'Kadaluarsa',
                backgroundColor: '#dc3545',
                data: {!! json_encode($chartData['expired']) !!}
            },
            {
                label: 'Kritis',
                backgroundColor: '#ffc107',
                data: {!! json_encode($chartData['critical']) !!}
            },
            {
                label: 'Peringatan',
                backgroundColor: '#17a2b8',
                data: {!! json_encode($chartData['warning']) !!}
            },
            {
                label: 'Aman',
                backgroundColor: '#28a745',
                data: {!! json_encode($chartData['safe']) !!}
            }
        ]
    };
    
    var barOptions = {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    stepSize: 1
                }
            }]
        },
        legend: {
            display: true,
            position: 'bottom'
        }
    };
    
    new Chart(barChartCanvas, {
        type: 'bar',
        data: barData,
        options: barOptions
    });
});
</script>
@endpush
@endsection