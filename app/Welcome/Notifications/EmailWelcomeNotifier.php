<?php

namespace App\Welcome\Notifications;

use App\Mail\WelcomeMail;
use App\Models\User;
use App\Welcome\Contracts\WelcomeNotifier;
use Illuminate\Support\Facades\Mail;

class EmailWelcomeNotifier implements WelcomeNotifier
{
      public function send(User $user): void
    {
        Mail::to($user->email)->send(new WelcomeMail($user));
    }
}
