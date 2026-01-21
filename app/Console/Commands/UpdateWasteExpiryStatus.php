<?php

namespace App\Console\Commands;

use App\Models\LogPenyimpananLimbah;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateWasteExpiryStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'waste:update-expiry-status {--force : Force update all records}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expiry status for all waste storage logs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting waste expiry status update...');

        try {
            // Get all waste logs that are still stored (not transported yet)
            $query = LogPenyimpananLimbah::with('jenisLimbah')->whereIn('status_log', ['Tersimpan']);

            if (!$this->option('force')) {
                // Only update records that don't have expiry status set or need recalculation
                $query->where(function ($q) {
                    $q->whereNull('expiry_status')
                        ->orWhereNull('tanggal_kadaluarsa')
                        ->orWhere('expiry_status', '!=', 'Expired');
                });
            }

            $wasteLogs = $query->get();
            $totalRecords = $wasteLogs->count();

            if ($totalRecords === 0) {
                $this->info('No records to update.');

                return Command::SUCCESS;
            }

            $this->info("Found {$totalRecords} records to update.");
            $progressBar = $this->output->createProgressBar($totalRecords);
            $progressBar->start();

            $updatedCount = 0;
            $errorCount = 0;

            foreach ($wasteLogs as $wasteLog) {
                try {
                    $wasteLog->updateExpiryStatus();
                    $updatedCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    Log::error('Failed to update expiry status for waste log ID: ' . $wasteLog->log_id, [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);

            $this->info('Update completed!');
            $this->info("Successfully updated: {$updatedCount} records");

            if ($errorCount > 0) {
                $this->warn("Errors encountered: {$errorCount} records");
                $this->warn('Check the logs for more details.');
            }

            // Log the operation
            Log::info('Waste expiry status update completed', [
                'total_records' => $totalRecords,
                'updated_count' => $updatedCount,
                'error_count' => $errorCount,
                'forced' => $this->option('force'),
            ]);

            // Update last run timestamp for scheduler frequency control
            \Illuminate\Support\Facades\Cache::put('expiry_check_last_run', now());

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to update waste expiry status: ' . $e->getMessage());
            Log::error('Waste expiry status update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
