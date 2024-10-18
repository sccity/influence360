<?php

namespace Webkul\Bills\Providers;

use Illuminate\Support\ServiceProvider;

class BillServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        if ($this->app->runningInConsole()) {
            $this->loadFactoriesFrom(__DIR__ . '/../Database/Factories');
        }
    }

    public function register()
    {
        $this->app->register(ModuleServiceProvider::class);
    }
}

