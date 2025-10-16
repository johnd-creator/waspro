<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kadaluwarsa Limbah</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 4px 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background-color: #28a745; color: #fff; font-weight: bold; text-align: center; }
        tr:nth-child(even) { background-color: #f7f7f7; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
    </head>
<body>
    <div class="header">
        <h1>Laporan Kadaluwarsa Limbah</h1>
        @if($expiryStatus)
            <p>Status Kadaluarsa: {{ $expiryStatus }}</p>
        @else
            <p>Status Kadaluarsa: Semua</p>
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

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Masuk</th>
                <th>Kode Limbah</th>
                <th>Jenis Limbah</th>
                <th>Jumlah (Kg)</th>
                <th>Perusahaan</th>
                <th>Unit Pembangkit</th>
                <th>Tanggal Kadaluarsa</th>
                <th>Status Kadaluarsa</th>
                <th>Hari Tersisa</th>
                <th>Status Log</th>
                <th>Tanggal Input</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($logs as $log)
                @php
                    $daysRemaining = $log->tanggal_kadaluarsa
                        ? \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($log->tanggal_kadaluarsa), false)
                        : null;
                @endphp
                <tr>
                    <td style="text-align:center;">{{ $no++ }}</td>
                    <td>{{ $log->tanggal_limbah_masuk }}</td>
                    <td>{{ $log->kode_limbah }}</td>
                    <td>{{ $log->jenisLimbah->nama_limbah ?? '-' }}</td>
                    <td style="text-align:right;">{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                    <td>{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal' }}</td>
                    <td>{{ $log->unitPembangkit->nama_unit ?? '-' }}</td>
                    <td>{{ $log->tanggal_kadaluarsa ?? '-' }}</td>
                    <td>{{ $log->expiry_status ?? '-' }}</td>
                    <td style="text-align:center;">
                        @if(is_null($daysRemaining))
                            -
                        @else
                            {{ $daysRemaining >= 0 ? $daysRemaining : 'Kadaluarsa' }}
                        @endif
                    </td>
                    <td>{{ $log->status_log }}</td>
                    <td>{{ $log->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat otomatis oleh Sistem Manajemen Limbah K3</p>
        <p>© {{ date('Y') }} - Semua hak dilindungi</p>
    </div>
</body>
</html>