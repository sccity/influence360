<?php

return [
    'initiatives'         => [
        'name'       => 'admin::app.initiatives.index.title',
        'repository' => 'Webkul\Initiative\Repositories\InitiativeRepository',
    ],

    'persons'       => [
        'name'       => 'admin::app.contacts.persons.index.title',
        'repository' => 'Webkul\Contact\Repositories\PersonRepository',
    ],

    'organizations' => [
        'name'       => 'admin::app.contacts.organizations.index.title',
        'repository' => 'Webkul\Contact\Repositories\OrganizationRepository',
    ],

    'products'      => [
        'name'       => 'admin::app.products.index.title',
        'repository' => 'Webkul\Product\Repositories\ProductRepository',
    ],

    'quotes'      => [
        'name'       => 'admin::app.quotes.index.title',
        'repository' => 'Webkul\Quote\Repositories\QuoteRepository',
    ],

    'warehouses'      => [
        'name'       => 'admin::app.settings.warehouses.index.title',
        'repository' => 'Webkul\Warehouse\Repositories\WarehouseRepository',
    ],
];
