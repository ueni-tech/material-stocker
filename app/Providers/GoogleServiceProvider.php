<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Google\GoogleDriveService;

class GoogleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GoogleDriveService::class, function ($app) {
            return new GoogleDriveService();
        });
    }

    public function boot(): void
    {
        //
    }
}
