<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProcessBulkExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $exportClass;
    protected $filename;
    protected $user;

    public function __construct($exportClass, array $data, string $filename, ?\App\Models\PenggunaSistem $user = null)
    {
        $this->exportClass = $exportClass;
        $this->data = $data;
        $this->filename = $filename;
        $this->user = $user;
    }

    public function handle()
    {
        try {
            Log::info('Processing bulk export', [
                'filename' => $this->filename,
                'user_id' => $this->user ? $this->user->user_id : null,
                'data_count' => count($this->data['logs'] ?? []),
            ]);

            $filePath = Excel::store(
                new $this->exportClass($this->data),
                $this->filename,
                'exports',
                null,
                ['visibility' => 'private']
            );

            Log::info('Bulk export completed', [
                'filename' => $this->filename,
                'file_path' => $filePath,
            ]);

            return $filePath;
        } catch (\Exception $e) {
            Log::error('Bulk export failed', [
                'filename' => $this->filename,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('ProcessBulkExportJob failed', [
            'filename' => $this->filename,
            'error' => $exception->getMessage(),
        ]);
    }
}
