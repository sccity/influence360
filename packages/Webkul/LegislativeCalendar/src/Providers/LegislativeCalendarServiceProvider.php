<?php

namespace Webkul\LegislativeCalendar\Providers;

use Illuminate\Support\ServiceProvider;

class LegislativeCalendarServiceProvider extends ServiceProvider
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
        
        $this->app->bind(
            \Webkul\LegislativeCalendar\Repositories\LegislativeCalendarRepository::class
        );
        
        $this->app->bind(\Webkul\Admin\Widgets\LegislativeCalendarWidget::class);
    }

}
