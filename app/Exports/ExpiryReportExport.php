<?php

namespace App\Exports;

use App\Models\LogPenyimpananLimbah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ExpiryReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $logs;

    public function __construct($logs)
    {
        $this->logs = $logs;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->logs;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Masuk',
            'Kode Limbah',
            'Jenis Limbah',
            'Jumlah (Kg)',
            'Perusahaan',
            'Unit Pembangkit',
            'Tanggal Kadaluarsa',
            'Status Kadaluarsa',
            'Hari Tersisa',
            'Status Log',
            'Tanggal Input'
        ];
    }

    public function map($log): array
    {
        static $counter = 0;
        $counter++;

        $daysUntilExpiry = $log->getDaysUntilExpiry();
        $daysText = '';
        if ($daysUntilExpiry !== null) {
            if ($daysUntilExpiry > 0) {
                $daysText = $daysUntilExpiry . ' hari lagi';
            } elseif ($daysUntilExpiry == 0) {
                $daysText = 'Kadaluarsa hari ini';
            } else {
                $daysText = 'Sudah kadaluarsa ' . abs($daysUntilExpiry) . ' hari';
            }
        }

        return [
            $counter,
            $log->tanggal_limbah_masuk,
            $log->kode_limbah,
            $log->jenisLimbah->nama_limbah ?? 'N/A',
            number_format($log->jumlah_limbah_masuk, 2),
            $log->perusahaanPenghasil->nama_perusahaan ?? 'N/A',
            $log->unitPembangkit->nama_unit ?? 'N/A',
            $log->tanggal_kadaluarsa ? Carbon::parse($log->tanggal_kadaluarsa)->format('d/m/Y') : 'N/A',
            $log->getExpiryStatusText(),
            $daysText,
            $log->status_log,
            $log->created_at->format('d/m/Y H:i:s')
        ];
    }
}
