<?php

namespace App\Jobs;

use App\Models\Notifikasi;
use App\Events\WasteDocumentUploaded;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWasteDocumentUploadedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $log;
    protected $documentPath;
    protected $originalFilename;
    protected $fileSize;

    public function __construct(\App\Models\LogPenyimpananLimbah $log, string $documentPath, string $originalFilename, int $fileSize)
    {
        $this->log = $log;
        $this->documentPath = $documentPath;
        $this->originalFilename = $originalFilename;
        $this->fileSize = $fileSize;
    }

    public function handle()
    {
        try {
            $usersToNotify = $this->getUsersToNotify();

            foreach ($usersToNotify as $user) {
                Notifikasi::create([
                    'user_id' => $user->user_id,
                    'judul' => 'Dokumen Limbah Baru Diunggah',
                    'pesan' => "Dokumen limbah '{$this->originalFilename}' telah diunggah untuk log {$this->log->kode_identitas}.",
                    'jenis_notifikasi' => 'dokumen_limbah',
                    'dibaca' => false,
                    'link_aksi' => route('log-penyimpanan.show', $this->log->log_id),
                    'data_tambahan' => json_encode([
                        'log_id' => $this->log->log_id,
                        'dokumen_path' => $this->documentPath,
                        'file_size' => $this->fileSize,
                    ]),
                ]);

                Log::info('Notification created', [
                    'user_id' => $user->user_id,
                    'log_id' => $this->log->log_id,
                ]);
            }

            Log::info('Waste document notification job completed', [
                'log_id' => $this->log->log_id,
                'document' => $this->originalFilename,
                'users_notified' => $usersToNotify->count(),
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send waste document notification', [
                'log_id' => $this->log->log_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    protected function getUsersToNotify()
    {
        $user = \App\Models\PenggunaSistem::find($this->log->penginput_id);
        
        if (!$user) {
            return collect();
        }

        $users = collect([$user]);

        if ($this->log->unit_id) {
            $unitAdmins = \App\Models\PenggunaSistem::where('unit_id', $this->log->unit_id)
                ->whereHas('peranPengguna', function ($q) {
                    $q->where('nama_peran', 'Administrator');
                })
                ->get();

            foreach ($unitAdmins as $admin) {
                if (!$users->contains('user_id', $admin->user_id)) {
                    $users->push($admin);
                }
            }
        }

        return $users;
    }

    public function failed(\Throwable $exception)
    {
        Log::error('SendWasteDocumentUploadedNotificationJob failed', [
            'log_id' => $this->log->log_id,
            'error' => $exception->getMessage(),
        ]);
    }
}
