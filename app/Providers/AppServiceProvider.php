<?php

namespace App\Providers;

use App\Services\Announcements\Adapters\DocumentAttachmentAdapter;
use App\Services\Announcements\Adapters\ImageAttachmentAdapter;
use App\Services\Announcements\AnnouncementAttachmentManager;
use App\Welcome\Contracts\WelcomeMessageFactory;
use App\Welcome\Factories\DefaultWelcomeMessageFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WelcomeMessageFactory::class, DefaultWelcomeMessageFactory::class);

        $this->app->bind(ImageAttachmentAdapter::class, fn () => new ImageAttachmentAdapter());
        $this->app->bind(DocumentAttachmentAdapter::class, fn () => new DocumentAttachmentAdapter());

        $this->app->singleton(AnnouncementAttachmentManager::class, function ($app) {
            return new AnnouncementAttachmentManager([
                $app->make(ImageAttachmentAdapter::class),
                $app->make(DocumentAttachmentAdapter::class),
            ]);
        });
    }

    /**Browser
 ↓
public/index.php
 ↓
bootstrap/app.php
 ↓
HTTP Kernel
 ↓
Service Providers (register)
 ↓
Service Providers (boot)
 ↓
Middleware
 ↓
Routes
 ↓
Controller
 ↓
Service / Business Logic
 ↓
Response
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        Inertia::share('flash', function () {
            return [
                'success' => Session::get('success'),
                'error'   => Session::get('error'),
            ];
        });
    }
}
