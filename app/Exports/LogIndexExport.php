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

class LogIndexExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
            'Kode Identitas',
            'Tanggal Masuk',
            'Jenis Limbah',
            'Perusahaan Penghasil',
            'Unit Pembangkit',
            'Jumlah (Kg)',
            'Status',
            'Hari Tersisa',
            'Penginput Data',
            'Sumber Limbah',
        ];
    }

    public function map($log): array
    {
        static $no = 1;

        $daysRemaining = '-';
        $days = method_exists($log, 'getDaysUntilExpiry') ? $log->getDaysUntilExpiry() : null;
        if ($log->status_log === 'Tersimpan' && $days !== null) {
            $daysRemaining = $days >= 0 ? $days : 'Kadaluarsa';
        }

        return [
            $no++,
            $log->kode_identitas ?? '-',
            $log->tanggal_limbah_masuk,
            $log->jenisLimbah->nama_limbah ?? 'Unknown',
            $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal',
            $log->unitPembangkit->nama_unit ?? 'Unknown',
            number_format($log->jumlah_limbah_masuk, 2),
            $log->status_log,
            $daysRemaining,
            $log->penggunaSistem->nama_lengkap ?? '-',
            $log->detail_sumber_limbah,
        ];
    }

    public function title(): string
    {
        return 'Laporan Log Penyimpanan';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            'A1:K1' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF2563EB'],
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
