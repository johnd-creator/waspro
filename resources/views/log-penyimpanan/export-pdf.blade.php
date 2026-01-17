<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Log Penyimpanan Limbah</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 8px; }
        .header h1 { margin: 0; color: #333; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; color: #333; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 20px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 8px; }
        .badge { padding: 2px 6px; border-radius: 3px; }
        .badge-stored { color: #0c4a6e; background: #e0f2fe; }
        .badge-transport { color: #064e3b; background: #d1fae5; }
        .badge-expired { color: #7f1d1d; background: #fee2e2; }
    </style>
    </head>
<body>
    <div class="header">
        <h1>Export Log Penyimpanan Limbah</h1>
        <p>Dicetak pada: {{ $generatedAt->format('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Identitas</th>
                <th>Tanggal Masuk</th>
                <th>Jenis Limbah</th>
                <th>Uraian Pekerjaan</th>
                <th>Perusahaan</th>
                <th>Unit</th>
                <th>Jumlah (Kg)</th>
                <th>Status</th>
                <th>Hari Tersisa</th>
                <th>Penginput</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $index => $log)
                @php($daysLeft = method_exists($log, 'getDaysUntilExpiry') ? $log->getDaysUntilExpiry() : null)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log->kode_identitas ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->tanggal_limbah_masuk)->format('d/m/Y') }}</td>
                    <td>{{ $log->jenisLimbah->nama_limbah ?? 'N/A' }}</td>
                    <td>{{ Str::limit($log->uraian_pekerjaan ?? '-', 100) }}</td>
                    <td>{{ $log->perusahaanPenghasil->nama_perusahaan ?? 'N/A' }}</td>
                    <td>{{ $log->unitPembangkit->nama_unit ?? 'N/A' }}</td>
                    <td>{{ number_format($log->jumlah_limbah_masuk, 2) }}</td>
                    <td>
                        @php($status = strtolower($log->status_log))
                        <span class="badge {{ $status === 'tersimpan' ? 'badge-stored' : ($status === 'diangkut' ? 'badge-transport' : 'badge-expired') }}">{{ $log->status_log }}</span>
                    </td>
                    <td>
                        @if($log->status_log === 'Tersimpan' && $daysLeft !== null)
                            {{ $daysLeft >= 0 ? $daysLeft : 'Kadaluarsa' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $log->penggunaSistem->nama_lengkap ?? '-' }}</td>
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