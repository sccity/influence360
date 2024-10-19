<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Webkul\BillFiles\Providers\BillFileServiceProvider::class);
        $this->app->register(\Webkul\Bills\Providers\BillServiceProvider::class);
        $this->app->register(\Webkul\Initiative\Providers\InitiativeServiceProvider::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (env('APP_ENV') === 'prod') {
            URL::forceScheme('https');
        }
    }
}
