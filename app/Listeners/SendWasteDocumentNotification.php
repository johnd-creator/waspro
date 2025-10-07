<?php

namespace App\Listeners;

use App\Events\WasteDocumentUploaded;
use App\Models\PenggunaSistem;
use App\Notifications\WasteDocumentUploadedNotification;
use Illuminate\Support\Facades\Notification;

class SendWasteDocumentNotification
{
    /**
     * Handle the event.
     */
    public function handle(WasteDocumentUploaded $event): void
    {
        $recipients = PenggunaSistem::query()
            ->where('aktif', true)
            ->whereHas('peranPengguna', function ($query): void {
                $query->whereIn('nama_peran', ['Super Admin', 'Administrator']);
            })
            ->get()
            ->reject(fn (PenggunaSistem $user) => $user->user_id === $event->log->user_id);

        if ($recipients->isEmpty()) {
            return;
        }

        Notification::send(
            $recipients,
            new WasteDocumentUploadedNotification(
                $event->log,
                $event->originalName,
                $event->path,
                $event->size
            )
        );
    }
}
