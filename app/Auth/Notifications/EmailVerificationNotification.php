<?php

namespace App\Auth\Notifications;

class EmailVerificationNotification extends \Illuminate\Notifications\Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Verifikasi Email - WASPRO')
            ->greeting('Halo!')
            ->line('Berikut adalah link verifikasi email Anda:')
            ->action(new \Illuminate\Notifications\Actions\VerifyEmailWithUrl($this->token))
            ->line('Verifikasi sekarang untuk mengaktifkan akun Anda.')
            ->line('Link berlaku selama 5 menit. Jika tidak berhasil, silakan request link baru.')
            ->line('Jika Anda tidak membuat request ini, abaikan email ini.');
    }
}
