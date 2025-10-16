<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Status Limbah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .summary-item {
            text-align: center;
            flex: 1;
            padding: 15px;
            margin: 0 5px;
            border-radius: 5px;
        }
        .summary-item.tersimpan {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
        }
        .summary-item.diangkut {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
        }
        .summary-item.kadaluarsa {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        .summary-item h3 {
            margin: 0;
            color: #333;
            font-size: 14px;
        }
        .summary-item p {
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: bold;
        }
        .summary-item .count {
            color: #007bff;
        }
        .summary-item .quantity {
            color: #28a745;
            font-size: 12px;
        }
        .summary-item .percentage {
            color: #6c757d;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .status-tersimpan {
            color: #856404;
            background: #fff3cd;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .status-diangkut {
            color: #0c5460;
            background: #d1ecf1;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .status-kadaluarsa {
            color: #721c24;
            background: #f8d7da;
            padding: 2px 6px;
            border-radius: 3px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Status Penyimpanan Limbah</h1>
        @if($status)
            <p>Status: {{ $status }}</p>
        @else
            <p>Status: Semua Status</p>
        @endif
        @if($dateFrom || $dateTo)
            <p>Periode: 
                @if($dateFrom) {{ date('d/m/Y', strtotime($dateFrom)) }} @endif
                @if($dateFrom && $dateTo) - @endif
                @if($dateTo) {{ date('d/m/Y', strtotime($dateTo)) }} @endif
            </p>
        @endif
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item tersimpan">
            <h3>Tersimpan</h3>
            <p class="count">{{ number_format($statusDistribution['Tersimpan']['count'] ?? 0) }} Log</p>
            <p class="quantity">{{ number_format($statusDistribution['Tersimpan']['total_quantity'] ?? 0, 2) }} Kg</p>
            <p class="percentage">{{ $statusDistribution['Tersimpan']['percentage'] ?? 0 }}%</p>
        </div>
        <div class="summary-item diangkut">
            <h3>Diangkut</h3>
            <p class="count">{{ number_format($statusDistribution['Diangkut']['count'] ?? 0) }} Log</p>
            <p class="quantity">{{ number_format($statusDistribution['Diangkut']['total_quantity'] ?? 0, 2) }} Kg</p>
            <p class="percentage">{{ $statusDistribution['Diangkut']['percentage'] ?? 0 }}%</p>
        </div>
        <div class="summary-item kadaluarsa">
            <h3>Kadaluarsa</h3>
            <p class="count">{{ number_format($statusDistribution['Kadaluarsa']['count'] ?? 0) }} Log</p>
            <p class="quantity">{{ number_format($statusDistribution['Kadaluarsa']['total_quantity'] ?? 0, 2) }} Kg</p>
            <p class="percentage">{{ $statusDistribution['Kadaluarsa']['percentage'] ?? 0 }}%</p>
        </div>
    </div>

    <div class="section-title">Detail Log Penyimpanan</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Masuk</th>
                <th>Jenis Limbah</th>
                <th>Perusahaan</th>
                <th>Unit</th>
                <th>Jumlah (Kg)</th>
                <th>Status</th>
                <th>Tanggal Pengangkutan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $index => $log)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date('d/m/Y', strtotime($log->tanggal_limbah_masuk)) }}</td>
                <td>{{ $log->jenisLimbah->nama_limbah ?? 'N/A' }}</td>
                <td>{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'N/A' }}</td>
                <td>{{ $log->unitPembangkit->nama_unit ?? 'N/A' }}</td>
                <td>{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                <td>
                    <span class="status-{{ strtolower($log->status_log) }}">
                        {{ $log->status_log }}
                    </span>
                </td>
                <td>
                    @if($log->tanggal_pengangkutan)
                        {{ date('d/m/Y', strtotime($log->tanggal_pengangkutan)) }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Manajemen Limbah K3</p>
        <p>© {{ date('Y') }} - Semua hak dilindungi</p>
    </div>
</body>
</html>