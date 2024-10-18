<?php

return [
    'initiatives' => [
        'name'         => 'Initiatives',
        'repository'   => 'Webkul\Initiative\Repositories\InitiativeRepository',
        'label_column' => 'title',
    ],

    'initiative_sources' => [
        'name'         => 'Initiative Sources',
        'repository'   => 'Webkul\Initiative\Repositories\SourceRepository',
    ],

    'initiative_types' => [
        'name'         => 'Initiative Types',
        'repository'   => 'Webkul\Initiative\Repositories\TypeRepository',
    ],

    'initiative_pipelines' => [
        'name'         => 'Initiative Pipelines',
        'repository'   => 'Webkul\Initiative\Repositories\PipelineRepository',
    ],

    'initiative_pipeline_stages' => [
        'name'         => 'Initiative Pipeline Stages',
        'repository'   => 'Webkul\Initiative\Repositories\StageRepository',
    ],

    'users' => [
        'name'         => 'Initiative Owners',
        'repository'   => 'Webkul\User\Repositories\UserRepository',
    ],

    'organizations' => [
        'name'         => 'Organizations',
        'repository'   => 'Webkul\Contact\Repositories\OrganizationRepository',
    ],

    'persons' => [
        'name'         => 'Persons',
        'repository'   => 'Webkul\Contact\Repositories\PersonRepository',
    ],

    'warehouses' => [
        'name'         => 'Warehouses',
        'repository'   => 'Webkul\Warehouse\Repositories\WarehouseRepository',
    ],

    'locations' => [
        'name'         => 'Locations',
        'repository'   => 'Webkul\Warehouse\Repositories\LocationRepository',
    ],
];
