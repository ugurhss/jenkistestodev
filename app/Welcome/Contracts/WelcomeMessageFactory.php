<?php

namespace App\Welcome\Contracts;

interface WelcomeMessageFactory
{

    public function makeEmailNotifier(): WelcomeNotifier;


    public function makeTelegramNotifier(): WelcomeNotifier;
}
