<?php

namespace App\Auth\Notifications;

use Illuminate\Auth\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class MustVerifyEmail implements MustVerifyEmailContract
{
    use AccessAuthorizesRequests, Notifiable;

    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    public function markEmailAsVerified()
    {
        $this->forceFill(['email_verified_at' => now()]);

        $this->save();
    }
}
