<?php

namespace App\Jobs;

use App\Models\Notifikasi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendInAppNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $title;
    protected $message;
    protected $notificationType;
    protected $actionUrl;

    public function __construct(\App\Models\PenggunaSistem $user, string $title, string $message, string $notificationType, ?string $actionUrl = null)
    {
        $this->user = $user;
        $this->title = $title;
        $this->message = $message;
        $this->notificationType = $notificationType;
        $this->actionUrl = $actionUrl;
    }

    public function handle()
    {
        try {
            Log::info('Creating in-app notification', [
                'user_id' => $this->user->user_id,
                'title' => $this->title,
                'type' => $this->notificationType,
            ]);

            Notifikasi::create([
                'user_id' => $this->user->user_id,
                'judul' => $this->title,
                'pesan' => $this->message,
                'jenis_notifikasi' => $this->notificationType,
                'dibaca' => false,
                'link_aksi' => $this->actionUrl,
                'created_at' => now(),
            ]);

            Log::info('In-app notification created successfully', [
                'user_id' => $this->user->user_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create in-app notification', [
                'user_id' => $this->user->user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('SendInAppNotificationJob failed', [
            'user_id' => $this->user->user_id,
            'error' => $exception->getMessage(),
        ]);
    }
}
