<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MonthlyReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
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
            'Kode Limbah',
            'Perusahaan Penghasil',
            'Unit Pembangkit',
            'Jumlah (Ton)',
            'Status',
            'Tanggal Pengangkutan',
            'Jumlah Diangkut (Ton)',
            'Maksimal Penyimpanan',
            'Sumber Limbah'
        ];
    }

    public function map($log): array
    {
        static $no = 1;
        
        return [
            $no++,
            $log->tanggal_limbah_masuk,
            $log->jenisLimbah->nama_limbah ?? 'Unknown',
            $log->kode_limbah,
            $log->perusahaanPenghasil->nama_perusahaan ?? 'Internal',
            $log->unitPembangkit->nama_unit ?? 'Unknown',
            number_format($log->jumlah_limbah_masuk, 2),
            $log->status_log,
            $log->tanggal_pengangkutan ?: '-',
            $log->jumlah_diangkut ? number_format($log->jumlah_diangkut, 2) : '-',
            $log->maksimal_penyimpanan_tanggal,
            $log->detail_sumber_limbah
        ];
    }

    public function title(): string
    {
        $period = $this->data['year'];
        if ($this->data['month']) {
            $period .= ' - ' . $this->data['monthName'];
        }
        return "Laporan Bulanan {$period}";
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
                    'startColor' => ['argb' => 'FF4472C4']
                ],
                'font' => [
                    'color' => ['argb' => 'FFFFFFFF'],
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000']
                    ]
                ]
            ]
        ];
    }
}