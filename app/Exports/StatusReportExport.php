<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StatusReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data['logs'];
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Masuk',
            'Jenis Limbah',
            'Perusahaan Penghasil',
            'Unit Pembangkit',
            'Jumlah (Kg)',
            'Status',
            'Tanggal Pengangkutan',
            'Jumlah Diangkut (Kg)',
            'Maksimal Penyimpanan',
            'Hari Tersisa',
            'Sumber Limbah',
        ];
    }

    public function map($log): array
    {
        static $no = 1;

        $daysRemaining = '-';
        if ($log->status_log === 'Tersimpan' && $log->maksimal_penyimpanan_tanggal) {
            $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($log->maksimal_penyimpanan_tanggal), false);
            $daysRemaining = $daysRemaining >= 0 ? $daysRemaining : 'Kadaluarsa';
        }

        return [
            $no++,
            $log->tanggal_limbah_masuk,
            $log->jenisLimbah->nama_limbah ?? 'Unknown',
            $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal',
            $log->unitPembangkit->nama_unit ?? 'Unknown',
            number_format($log->jumlah_limbah_masuk, 2),
            $log->status_log,
            $log->tanggal_pengangkutan ?: '-',
            $log->jumlah_diangkut ? number_format($log->jumlah_diangkut, 2) : '-',
            $log->maksimal_penyimpanan_tanggal ?: '-',
            $daysRemaining,
            $log->detail_sumber_limbah,
        ];
    }

    public function title(): string
    {
        $status = $this->data['status'] ? ucfirst(strtolower($this->data['status'])) : 'Semua Status';

        return "Laporan Status - {$status}";
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true, 'size' => 12]],

            // Style the header row
            'A1:L1' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF28a745'],
                ],
                'font' => [
                    'color' => ['argb' => 'FFFFFFFF'],
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
        ];
    }
}
