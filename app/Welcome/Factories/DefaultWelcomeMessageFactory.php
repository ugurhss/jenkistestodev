<?php

namespace App\Welcome\Factories;

use App\Welcome\Contracts\WelcomeMessageFactory;
use App\Welcome\Contracts\WelcomeNotifier;
use App\Welcome\Notifications\EmailWelcomeNotifier;
use App\Welcome\Notifications\TelegramWelcomeNotifier;

class DefaultWelcomeMessageFactory implements WelcomeMessageFactory
{

    public function makeEmailNotifier(): WelcomeNotifier
    {
        return new EmailWelcomeNotifier();
    }


    public function makeTelegramNotifier(): WelcomeNotifier
    {
        return new TelegramWelcomeNotifier();
    }
}
