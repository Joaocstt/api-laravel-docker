<?php

namespace App\Providers;

use App\Auth\TymonJwtAuthenticator;
use App\Interfaces\AuthenticatorInterface;
use App\Services\AuthenticateService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthenticatorInterface::class, TymonJwtAuthenticator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
