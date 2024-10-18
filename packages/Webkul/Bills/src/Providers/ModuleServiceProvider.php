<?php

namespace Webkul\Bills\Providers;

use Webkul\Core\Providers\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Bills\Contracts\Bill::class => \Webkul\Bills\Models\Bill::class,
    ];

    public function register()
    {
        $this->app->bind(\Webkul\Bills\Contracts\Bill::class, \Webkul\Bills\Models\BillProxy::class);

        parent::register();
    }
}

