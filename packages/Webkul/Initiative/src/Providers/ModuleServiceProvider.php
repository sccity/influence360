<?php

namespace Webkul\Initiative\Providers;

use Webkul\Core\Providers\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Initiative\Models\Initiative::class,
        \Webkul\Initiative\Models\Pipeline::class,
        \Webkul\Initiative\Models\Product::class,
        \Webkul\Initiative\Models\Source::class,
        \Webkul\Initiative\Models\Stage::class,
        \Webkul\Initiative\Models\Type::class,
    ];
}
