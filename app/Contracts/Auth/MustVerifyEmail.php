<?php

namespace App\Contracts\Auth;

interface MustVerifyEmail
{
    public function hasVerifiedEmail();

    public function markEmailAsVerified();
}
