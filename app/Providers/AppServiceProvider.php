<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            if (request()->server('HTTP_X_FORWARDED_PROTO') === 'https' || request()->isSecure()) {
                URL::forceScheme('https');
            }
        }

        Password::defaults(function () {
            return Password::min(6);
        });
    }
}
