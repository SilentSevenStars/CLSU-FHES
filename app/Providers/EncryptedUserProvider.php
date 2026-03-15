<?php

namespace App\Providers;

use App\Providers\EncryptedEloquentUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class EncryptedUserProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::provider('encrypted_eloquent', function ($app, array $config) {
            return new EncryptedEloquentUserProvider($app['hash'], $config['model']);
        });
    }
}
