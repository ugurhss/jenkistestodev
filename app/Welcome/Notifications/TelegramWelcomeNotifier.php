<?php

namespace App\Welcome\Notifications;

use App\Models\User;
use App\Welcome\Contracts\WelcomeNotifier;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramWelcomeNotifier implements WelcomeNotifier
{

    public function send(User $user): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (! $token || ! $chatId) {
            Log::warning('step 1 send');
            return;
        }

        $text = sprintf('Yöneticimm Hoş geldin %s! Artık sistemde yeni kayıt var.', $user->name);

        $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $text,
        ]);

        if ($response->failed()) {
            Log::warning('step-2 send', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }
    }
}
