<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;

class WelcomeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly User $user,
    ) {
    }


    public function build(): self
    {
        return $this->subject('Hoş Geldin!')
            ->view('emails.welcome')
            ->with([
                'name' => $this->user->name,
            ]);
    }
}
