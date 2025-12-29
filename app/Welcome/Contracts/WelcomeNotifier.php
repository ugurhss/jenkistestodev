<?php

namespace App\Welcome\Contracts;

use App\Models\User;

interface WelcomeNotifier
{

    public function send(User $user): void;
}
