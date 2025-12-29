<?php

namespace App\Jobs;

use App\Models\User;
use App\Welcome\Services\UserWelcomeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeNotifications implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly User $user,
    ) {
    }

    /**
     * Execute the job by delegating to the welcome service.
     */
    public function handle(UserWelcomeService $welcomeService): void
    {
        $welcomeService->welcome($this->user);
    }
}
