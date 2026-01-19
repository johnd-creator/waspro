<?php

namespace App\Jobs;

use App\Models\LogPenyimpananLimbah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $logs;
    protected $reportType;
    protected $filename;
    protected $user;
    protected $filters;

    public function __construct(string $reportType, array $filters, string $filename, ?\App\Models\PenggunaSistem $user = null)
    {
        $this->reportType = $reportType;
        $this->filters = $filters;
        $this->filename = $filename;
        $this->user = $user;
    }

    public function handle()
    {
        try {
            Log::info('Generating report', [
                'report_type' => $this->reportType,
                'filename' => $this->filename,
                'user_id' => $this->user ? $this->user->user_id : null,
            ]);

            $logs = $this->fetchLogs();
            $pdf = Pdf::loadView($this->getViewName(), [
                'logs' => $logs,
                'generatedAt' => now(),
                'user' => $this->user,
            ]);

            $filePath = 'reports/' . $this->filename;
            $pdf->save(storage_path('app/' . $filePath));

            Log::info('Report generated successfully', [
                'report_type' => $this->reportType,
                'file_path' => $filePath,
            ]);

            return $filePath;
        } catch (\Exception $e) {
            Log::error('Report generation failed', [
                'report_type' => $this->reportType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    protected function fetchLogs()
    {
        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unit', 'penggunaSistem']);

        if (isset($this->filters['start_date'])) {
            $query->whereDate('tanggal_limbah_masuk', '>=', $this->filters['start_date']);
        }

        if (isset($this->filters['end_date'])) {
            $query->whereDate('tanggal_limbah_masuk', '<=', $this->filters['end_date']);
        }

        if (isset($this->filters['jenis_limbah'])) {
            $query->where('kode_limbah', $this->filters['jenis_limbah']);
        }

        if (isset($this->filters['unit_id'])) {
            $query->where('unit_id', $this->filters['unit_id']);
        }

        return $query->orderBy('tanggal_limbah_masuk', 'desc')->get();
    }

    protected function getViewName(): string
    {
        $views = [
            'daily' => 'reports.daily-report',
            'monthly' => 'reports.monthly-report',
            'by_type' => 'reports.by-type-report',
            'by_company' => 'reports.by-company-report',
        ];

        return $views[$this->reportType] ?? 'reports.daily-report';
    }

    public function failed(\Throwable $exception)
    {
        Log::error('GenerateReportJob failed', [
            'report_type' => $this->reportType,
            'error' => $exception->getMessage(),
        ]);
    }
}
