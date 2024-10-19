<?php

namespace Webkul\DataGrid\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Webkul\DataGrid\Column;

class DataGridServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        include __DIR__.'/../Http/helpers.php';

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        Blade::component('datagrid::column', Column::class);
    }

    /**
     * Register any application services.
     */
    public function register(): void {}
}
