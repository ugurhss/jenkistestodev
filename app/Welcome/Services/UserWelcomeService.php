<?php

namespace App\Welcome\Services;

use App\Models\User;
use App\Welcome\Contracts\WelcomeMessageFactory;
use Illuminate\Support\Facades\Log;

class UserWelcomeService
{
    public function __construct(
        private readonly WelcomeMessageFactory $factory,
    ) {
    }


    public function welcome(User $user): void
    {
        $notifiers = [
            $this->factory->makeEmailNotifier(),
            $this->factory->makeTelegramNotifier(),
        ];

        foreach ($notifiers as $notifier) {
            try {
                $notifier->send($user);
            } catch (\Throwable $exception) {
                Log::error('welcome fonkiyonu', [
                    'user_id' => $user->id,
                    'error' => $exception->getMessage(),
                ]);
            }
        }
    }
}
