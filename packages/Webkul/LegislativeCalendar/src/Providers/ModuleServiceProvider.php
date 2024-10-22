<?php

namespace Webkul\LegislativeCalendar\Providers;

use Webkul\Core\Providers\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\LegislativeCalendar\Contracts\LegislativeCalendar::class => \Webkul\LegislativeCalendar\Models\LegislativeCalendar::class,
    ];

    public function register()
    {
        $this->app->bind(\Webkul\LegislativeCalendar\Contracts\LegislativeCalendar::class, \Webkul\LegislativeCalendar\Models\LegislativeCalendarProxy::class);

        parent::register();
    }
}
