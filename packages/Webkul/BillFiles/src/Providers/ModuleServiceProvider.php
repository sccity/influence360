<?php

namespace Webkul\BillFiles\Providers;

use Webkul\Core\Providers\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\BillFiles\Contracts\BillFile::class => \Webkul\BillFiles\Models\BillFile::class,
    ];

    public function register()
    {
        $this->app->bind(\Webkul\BillFiles\Contracts\BillFile::class, \Webkul\BillFiles\Models\BillFileProxy::class);

        parent::register();
    }
}
