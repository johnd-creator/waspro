<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Jenis Limbah</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        .summary h3 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN JENIS LIMBAH</h1>
        <p>Periode: {{ $dateFrom ? date('d/m/Y', strtotime($dateFrom)) : 'Semua' }} -
            {{ $dateTo ? date('d/m/Y', strtotime($dateTo)) : 'Semua' }}</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="info">
        <p><strong>Filter:</strong></p>
        <p>Jenis Limbah:
            {{ $jenisLimbahId ? ($logs->first()?->jenisLimbah?->nama_limbah ?? 'Unknown') : 'Semua Jenis Limbah' }}</p>
        <p>Periode: {{ $dateFrom ? date('d/m/Y', strtotime($dateFrom)) : 'Tidak dibatasi' }} s/d
            {{ $dateTo ? date('d/m/Y', strtotime($dateTo)) : 'Tidak dibatasi' }}</p>
    </div>

    @if(count($logs) > 0)
        <table>
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
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $index => $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $log->jenisLimbah?->nama_limbah ?? 'Unknown' }}</td>
                        <td>{{ $log->kode_limbah }}</td>
                        <td>{{ date('d/m/Y', strtotime($log->tanggal_limbah_masuk)) }}</td>
                        <td>{{ $log->perusahaanPenghasil?->nama_perusahaan ?? 'Unknown' }}</td>
                        <td>{{ $log->unitPembangkit?->nama_unit ?? 'Unknown' }}</td>
                        <td>{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                        <td>{{ $log->status_log }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <h3>Ringkasan Distribusi Jenis Limbah</h3>
            @if(count($wasteTypeDistribution) > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Jenis Limbah</th>
                            <th>Total Kuantitas (Kg)</th>
                            <th>Jumlah Log</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wasteTypeDistribution as $waste)
                            <tr>
                                <td>{{ $waste['nama_limbah'] }}</td>
                                <td>{{ number_format($waste['total_quantity'], 2) }}</td>
                                <td>{{ $waste['total_logs'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Tidak ada data distribusi jenis limbah.</p>
            @endif
        </div>
    @else
        <p style="text-align: center; margin-top: 50px; font-style: italic;">Tidak ada data yang sesuai dengan filter yang
            dipilih.</p>
    @endif
</body>

</html>