<?php

namespace App\Notifications;

use App\Models\LogPenyimpananLimbah;
use Illuminate\Notifications\Notification;

class WasteDocumentUploadedNotification extends Notification
{
    public function __construct(
        protected LogPenyimpananLimbah $log,
        protected string $filename,
        protected string $path,
        protected int $size,
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Dokumen Limbah Terunggah',
            'message' => sprintf(
                'Dokumen "%s" telah diunggah untuk limbah %s.',
                $this->filename,
                $this->log->kode_identitas ?? $this->log->log_id
            ),
            'log_id' => $this->log->log_id,
            'kode_identitas' => $this->log->kode_identitas,
            'dokumen_path' => $this->path,
            'dokumen_name' => $this->filename,
            'dokumen_size' => $this->size,
            'url' => route('log-penyimpanan.show', $this->log->log_id),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
