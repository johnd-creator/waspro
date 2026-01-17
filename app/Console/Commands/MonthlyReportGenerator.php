<?php

namespace App\Console\Commands;

use App\Models\ApplicationSetting;
use App\Models\LogPenyimpananLimbah;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LogIndexExport;

class MonthlyReportGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:generate-monthly {--force : Force generation regardless of settings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly waste report for the previous month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Check Settings
        $autoGenerate = ApplicationSetting::getValue('report.auto_generate_monthly', true);
        $generationDay = (int) ApplicationSetting::getValue('report.monthly_generation_day', 1);
        $defaultFormat = ApplicationSetting::getValue('report.default_format', 'pdf');

        if (!$autoGenerate && !$this->option('force')) {
            $this->info('Auto-generation is disabled. Use --force to override.');
            return;
        }

        // Check if today is the generation day (unless forced)
        if (now()->day !== $generationDay && !$this->option('force')) {
            $this->info("Today is not the generation day (Set to: {$generationDay}). Skipping.");
            return;
        }

        $this->info('Starting monthly report generation...');

        // 2. Define Date Range (Previous Month)
        $startOfMonth = now()->subMonth()->startOfMonth();
        $endOfMonth = now()->subMonth()->endOfMonth();

        $this->info("Period: {$startOfMonth->toDateString()} - {$endOfMonth->toDateString()}");

        // 3. Fetch Data
        $logs = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unit', 'penggunaSistem'])
            ->whereDate('tanggal_limbah_masuk', '>=', $startOfMonth)
            ->whereDate('tanggal_limbah_masuk', '<=', $endOfMonth)
            ->orderBy('tanggal_limbah_masuk', 'asc')
            ->get();

        if ($logs->isEmpty()) {
            $this->info('No logs found for the previous month.');
            return;
        }

        // 4. Generate File
        $filename = 'laporan-bulanan-' . $startOfMonth->format('F-Y') . '-' . now()->timestamp;

        try {
            if ($defaultFormat === 'excel') {
                $filename .= '.xlsx';
                $path = 'reports/' . $filename;
                Excel::store(new LogIndexExport(['logs' => $logs]), $path, 'local');
            } else {
                $filename .= '.pdf';
                $path = 'reports/' . $filename;

                $pdf = Pdf::loadView('log-penyimpanan.export-pdf', [
                    'logs' => $logs,
                    'generatedAt' => now(),
                    'title' => 'Laporan Bulanan: ' . $startOfMonth->format('F Y'),
                ]);

                Storage::disk('local')->put($path, $pdf->output());
            }

            $this->info("Report generated successfully: storage/app/{$path}");

            // Note: In Phase 7 (Notifications), we would email this file here.

        } catch (\Exception $e) {
            $this->error('Failed to generate report: ' . $e->getMessage());
        }
    }
}
