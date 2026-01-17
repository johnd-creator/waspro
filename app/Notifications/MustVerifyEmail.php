<?php

namespace App\Notifications;

use Illuminate\Auth\Access\AuthorizesRequests;
use Illuminate\Auth\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class MustVerifyEmail implements MustVerifyEmailContract
{
    use AuthorizesRequests, Notifiable;

    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    public function markEmailAsVerified()
    {
        $this->forceFill(['email_verified_at' => now()]);

        $this->save();

        $this->sendEmailVerificationNotification();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerificationNotification);
    }
}
