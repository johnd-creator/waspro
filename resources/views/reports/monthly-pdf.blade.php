<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bulanan - {{ $monthName ? $monthName . ' ' : '' }}{{ $year }}</title>
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
            padding: 10px;
            background: #f8f9fa;
            margin: 0 5px;
            border-radius: 5px;
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
            color: #007bff;
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
        <h1>Laporan Bulanan Penyimpanan Limbah</h1>
        <p>Periode: {{ $monthName ? $monthName . ' ' : 'Seluruh Bulan ' }}{{ $year }}</p>
        @if($unitId && isset($units))
            <p>Unit: {{ $units->where('unit_id', $unitId)->first()->nama_unit ?? 'Semua Unit' }}</p>
        @endif
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <h3>Total Log</h3>
            <p>{{ number_format($totalLogs) }}</p>
        </div>
        <div class="summary-item">
            <h3>Total Limbah (Kg)</h3>
            <p>{{ number_format($totalWaste, 2) }}</p>
        </div>
        <div class="summary-item">
            <h3>Diangkut</h3>
            <p>{{ number_format($totalTransported) }}</p>
        </div>
        <div class="summary-item">
            <h3>Tersimpan</h3>
            <p>{{ number_format($wasteStored) }}</p>
        </div>
        <div class="summary-item">
            <h3>Kadaluarsa (Kg)</h3>
            <p>{{ number_format($wasteExpired, 2) }}</p>
        </div>
    </div>

    @if($month)
    <div class="section-title">Detail Bulan {{ $monthName }} {{ $year }}</div>
    @else
    <div class="section-title">Breakdown Bulanan {{ $year }}</div>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Log</th>
                <th>Total Limbah (Kg)</th>
                <th>Diangkut</th>
                <th>Tersimpan</th>
                <th>Kadaluarsa (Kg)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyBreakdown as $monthNum => $data)
            <tr>
                <td>{{ DateTime::createFromFormat('m', $monthNum)->format('F') }}</td>
                <td>{{ number_format($data['total_logs']) }}</td>
                <td>{{ number_format($data['total_waste'], 2) }}</td>
                <td>{{ number_format($data['transported']) }}</td>
                <td>{{ number_format($data['stored']) }}</td>
                <td>{{ number_format($data['expired'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="section-title">Top 10 Jenis Limbah</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Limbah</th>
                <th>Total Kuantitas (Kg)</th>
                <th>Total Log</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topWasteTypes as $index => $waste)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $waste['nama_limbah'] }}</td>
                <td>{{ number_format($waste['total_quantity'], 2) }}</td>
                <td>{{ number_format($waste['total_logs']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Top 10 Perusahaan Penghasil</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Perusahaan</th>
                <th>Total Kuantitas (Kg)</th>
                <th>Total Log</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topCompanies as $index => $company)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $company['nama_perusahaan'] }}</td>
                <td>{{ number_format($company['total_quantity'], 2) }}</td>
                <td>{{ number_format($company['total_logs']) }}</td>
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