@extends('layouts.app')

@section('title', 'Dashboard Laporan Kadaluarsa')

@section('content')
<div class="px-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dashboard Laporan Kadaluarsa</h3>
                    <div class="card-tools">
                        <a href="{{ route('expiry-reports.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-list"></i> Lihat Detail
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Summary Statistics -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <x-statistics-card 
                            title="Kadaluarsa" 
                            :value="$statistics['expired'] ?? 0" 
                            icon="fas fa-times-circle" 
                            type="expired" 
                        />
                        
                        <x-statistics-card 
                            title="Kritis" 
                            :value="$statistics['critical'] ?? 0" 
                            icon="fas fa-exclamation-triangle" 
                            type="critical" 
                            subtitle="< 30 hari" 
                        />
                        
                        <x-statistics-card 
                            title="Peringatan" 
                            :value="$statistics['warning'] ?? 0" 
                            icon="fas fa-exclamation-circle" 
                            type="warning" 
                            subtitle="< 90 hari" 
                        />
                        
                        <x-statistics-card 
                            title="Aman" 
                            :value="$statistics['safe'] ?? 0" 
                            icon="fas fa-check-circle" 
                            type="safe" 
                            subtitle="> 90 hari" 
                        />
                    </div>

                    <!-- Charts -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Distribusi Status Limbah</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Trend Bulanan</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Expired Items -->
                    @if(isset($recentExpired) && count($recentExpired) > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Limbah yang Baru Kadaluarsa</h3>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Kode Identitas</th>
                                                <th>Jenis Limbah</th>
                                                <th>Jumlah</th>
                                                <th>Tanggal Kadaluarsa</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentExpired as $item)
                                            <tr>
                                                <td>{{ $item->kode_identitas }}</td>
                                                <td>{{ $item->jenisLimbah->nama ?? '-' }}</td>
                                                <td>{{ number_format($item->jumlah, 2) }} {{ $item->satuan }}</td>
                                                <td>{{ $item->tanggal_kadaluarsa ? \Carbon\Carbon::parse($item->tanggal_kadaluarsa)->format('d/m/Y') : '-' }}</td>
                                                <td>
                                                    @php
                                                        $status = $item->getExpiryStatus();
                                                        $badgeClass = match($status) {
                                                            'expired' => 'badge-danger',
                                                            'critical' => 'badge-warning',
                                                            'warning' => 'badge-info',
                                                            default => 'badge-success'
                                                        };
                                                        $statusText = match($status) {
                                                            'expired' => 'Kadaluarsa',
                                                            'critical' => 'Kritis',
                                                            'warning' => 'Peringatan',
                                                            default => 'Aman'
                                                        };
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Pie Chart
    var pieChartCanvas = document.getElementById('pieChart').getContext('2d');
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
    var barChartCanvas = document.getElementById('barChart').getContext('2d');
    var barData = {
        labels: {!! json_encode($chartData['labels'] ?? []) !!},
        datasets: [
            {
                label: 'Kadaluarsa',
                backgroundColor: '#dc3545',
                data: {!! json_encode($chartData['expired'] ?? []) !!}
            },
            {
                label: 'Kritis',
                backgroundColor: '#ffc107',
                data: {!! json_encode($chartData['critical'] ?? []) !!}
            },
            {
                label: 'Peringatan',
                backgroundColor: '#17a2b8',
                data: {!! json_encode($chartData['warning'] ?? []) !!}
            },
            {
                label: 'Aman',
                backgroundColor: '#28a745',
                data: {!! json_encode($chartData['safe'] ?? []) !!}
            }
        ]
    };
    
    var barOptions = {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
            }
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