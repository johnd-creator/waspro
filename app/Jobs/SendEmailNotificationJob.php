<?php

namespace App\Jobs;

use App\Models\PenggunaSistem;
use App\Models\Notifikasi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $subject;
    protected $message;
    protected $actionUrl;

    public function __construct(PenggunaSistem $user, string $subject, string $message, ?string $actionUrl = null)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->message = $message;
        $this->actionUrl = $actionUrl;
    }

    public function handle()
    {
        try {
            Log::info('Sending email notification', [
                'user_id' => $this->user->user_id,
                'email' => $this->user->email_address,
                'subject' => $this->subject,
            ]);

            Notifikasi::create([
                'user_id' => $this->user->user_id,
                'judul' => $this->subject,
                'pesan' => $this->message,
                'jenis_notifikasi' => 'email',
                'dibaca' => false,
                'link_aksi' => $this->actionUrl,
            ]);

            if ($this->user->email_address) {
                Mail::raw($this->message, function ($message) {
                    $message->to($this->user->email_address)
                        ->subject($this->subject);
                });

                Log::info('Email sent successfully', [
                    'user_id' => $this->user->user_id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'user_id' => $this->user->user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('SendEmailNotificationJob failed', [
            'user_id' => $this->user->user_id,
            'error' => $exception->getMessage(),
        ]);
    }
}
