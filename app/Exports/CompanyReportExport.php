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

class CompanyReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
            'Perusahaan Penghasil',
            'Tanggal Masuk',
            'Jenis Limbah',
            'Kode Limbah',
            'Unit Pembangkit',
            'Jumlah (Kg)',
            'Status',
            'Tanggal Pengangkutan',
            'Jumlah Diangkut (Kg)',
            'Maksimal Penyimpanan',
            'Sumber Limbah',
        ];
    }

    public function map($log): array
    {
        static $no = 1;

        return [
            $no++,
            $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal',
            $log->tanggal_limbah_masuk,
            $log->jenisLimbah->nama_limbah ?? 'Unknown',
            $log->kode_limbah,
            $log->unitPembangkit->nama_unit ?? 'Unknown',
            number_format($log->jumlah_limbah_masuk, 2),
            $log->status_log,
            $log->tanggal_pengangkutan ?: '-',
            $log->jumlah_diangkut ? number_format($log->jumlah_diangkut, 2) : '-',
            $log->maksimal_penyimpanan_tanggal ?: '-',
            $log->detail_sumber_limbah,
        ];
    }

    public function title(): string
    {
        $company = $this->data['company'] ? $this->data['company']->nama_perusahaan : 'Semua Perusahaan';

        return "Laporan Perusahaan - {$company}";
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
                    'startColor' => ['argb' => 'FFdc3545'],
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
