<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Perusahaan Penghasil Limbah</title>
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
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .summary h3 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .summary-grid {
            display: flex;
            justify-content: space-between;
        }

        .summary-item {
            text-align: center;
            flex: 1;
        }

        .summary-item p {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            color: #007bff;
        }

        .summary-item small {
            color: #666;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
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

        .company-highlight {
            background: #e3f2fd;
            font-weight: bold;
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
        <h1>Laporan Perusahaan Penghasil Limbah</h1>
        @if($perusahaanId && isset($companyStats))
            @php
                $selectedCompany = $companyStats->where('perusahaan_id', $perusahaanId)->first();
            @endphp
            @if($selectedCompany)
                <p>Perusahaan: {{ $selectedCompany['nama_perusahaan'] }}</p>
            @endif
        @else
            <p>Perusahaan: Semua Perusahaan</p>
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
        <h3>Ringkasan Statistik</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <p>{{ number_format($logs->count()) }}</p>
                <small>Total Log</small>
            </div>
            <div class="summary-item">
                <p>{{ number_format($logs->sum('jumlah_limbah_masuk'), 2) }}</p>
                <small>Total Limbah (Kg)</small>
            </div>
            <div class="summary-item">
                <p>{{ number_format($logs->whereNotNull('perusahaan_id')->groupBy('perusahaan_id')->count()) }}</p>
                <small>Jumlah Perusahaan</small>
            </div>
            <div class="summary-item">
                <p>{{ number_format($logs->where('status_log', 'Diangkut')->count()) }}</p>
                <small>Diangkut</small>
            </div>
        </div>
    </div>

    <div class="section-title">Statistik Per Perusahaan</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Perusahaan</th>
                <th>Total Log</th>
                <th>Total Kuantitas (Kg)</th>
                <th>Rata-rata per Log (Kg)</th>
                <th>Efisiensi Pengangkutan (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companyStats as $index => $company)
                <tr @if($perusahaanId == $company['perusahaan_id']) class="company-highlight" @endif>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $company['nama_perusahaan'] }}</td>
                    <td>{{ number_format($company['total_logs']) }}</td>
                    <td>{{ number_format($company['total_quantity'], 2) }}</td>
                    <td>{{ number_format(($company['total_logs'] ?? 0) ? (($company['total_quantity'] ?? 0) / $company['total_logs']) : 0, 2) }}
                    </td>
                    <td>{{ number_format($company['efficiency_rate'], 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Detail Log Penyimpanan</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Masuk</th>
                <th>Perusahaan</th>
                <th>Jenis Limbah</th>
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
                    <td>{{ $log->perusahaanPenghasil?->nama_perusahaan ?? 'N/A' }}</td>
                    <td>{{ $log->jenisLimbah?->nama_limbah ?? 'N/A' }}</td>
                    <td>{{ $log->unitPembangkit?->nama_unit ?? 'N/A' }}</td>
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