<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('admin_provider', function ($app, array $config) {
            return new \Illuminate\Auth\EloquentUserProvider($app['hash'], \App\Models\User::class);
        });

        Auth::provider('customer_provider', function ($app, array $config) {
            return new \Illuminate\Auth\EloquentUserProvider($app['hash'], \App\Models\User::class);
        });
    }
}
