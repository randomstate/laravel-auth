<?php


namespace RandomState\LaravelAuth;


use Illuminate\Support\ServiceProvider;

class LaravelAuthServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(AuthManager::class);
    }
}