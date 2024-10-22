<?php

return [
    /**
     * Dashboard.
     */
    [
        'key'        => 'dashboard',
        'name'       => 'admin::app.layouts.dashboard',
        'route'      => 'admin.dashboard.index',
        'sort'       => 1,
        'icon-class' => 'icon-dashboard',
    ],

    [
        'key'        => 'legislation.legislativecalendar',
        'name'       => 'admin::app.legislative-calendar.menu_name',
        'route'      => 'admin.legislative-calendar.index',
        'sort'       => 4,
        'icon-class' => 'temp-icon',
    ],
    /**
     * Initiatives (formerly Leads).
     */
    [
        'key'        => 'initiatives',
        'name'       => 'Initiatives',
        'route'      => 'admin.initiatives.index',
        'sort'       => 2,
        'icon-class' => 'icon-dashboard',
    ],

    [
        'key'        => 'legislation',
        'name'       => 'Legislation',
        'route'      => 'admin.bills.index', // Make sure this is present
        'sort'       => 3,
        'icon-class' => 'icon-activity',
    ], [
        'key'        => 'legislation.bills',
        'name'       => 'Bills',
        'route'      => 'admin.bills.index',
        'sort'       => 2,
        'icon-class' => 'icon-activity',
    ], [
        'key'        => 'legislation.tracked_bills',
        'name'       => 'My Tracked Bills',
        'route'      => 'admin.bills.tracked',
        'sort'       => 1,
        'icon-class' => 'icon-star',
    ], [
        'key'        => 'legislation.bill_files',
        'name'       => 'Bill Files',
        'route'      => 'admin.bill-files.index',
        'sort'       => 3,
        'icon-class' => 'icon-activity',
    ],

    /**
     * Activities.
     */
    [
        'key'        => 'activities',
        'name'       => 'admin::app.layouts.activities',
        'route'      => 'admin.activities.index',
        'sort'       => 3,
        'icon-class' => 'icon-activity',
    ],

    /**
     * Contacts.
     */
    [
        'key'        => 'contacts',
        'name'       => 'admin::app.layouts.contacts',
        'route'      => 'admin.contacts.persons.index',
        'sort'       => 4,
        'icon-class' => 'icon-contact',
    ], [
        'key'        => 'contacts.persons',
        'name'       => 'admin::app.layouts.persons',
        'route'      => 'admin.contacts.persons.index',
        'sort'       => 1,
        'icon-class' => '',
    ], [
        'key'        => 'contacts.organizations',
        'name'       => 'admin::app.layouts.organizations',
        'route'      => 'admin.contacts.organizations.index',
        'sort'       => 2,
        'icon-class' => '',
    ],


    /**
     * Settings.
     */
    [
        'key'        => 'settings',
        'name'       => 'admin::app.layouts.settings',
        'route'      => 'admin.settings.index',
        'sort'       => 5,
        'icon-class' => 'icon-setting',
    ], [
        'key'        => 'settings.user',
        'name'       => 'admin::app.layouts.user',
        'route'      => 'admin.settings.groups.index',
        'info'       => 'admin::app.layouts.user-info',
        'sort'       => 1,
        'icon-class' => 'icon-settings-group',
    ], [
        'key'        => 'settings.user.groups',
        'name'       => 'admin::app.layouts.groups',
        'info'       => 'admin::app.layouts.groups-info',
        'route'      => 'admin.settings.groups.index',
        'sort'       => 1,
        'icon-class' => 'icon-settings-group',
    ], [
        'key'        => 'settings.user.roles',
        'name'       => 'admin::app.layouts.roles',
        'info'       => 'admin::app.layouts.roles-info',
        'route'      => 'admin.settings.roles.index',
        'sort'       => 2,
        'icon-class' => 'icon-role',
    ], [
        'key'        => 'settings.user.users',
        'name'       => 'admin::app.layouts.users',
        'info'       => 'admin::app.layouts.users-info',
        'route'      => 'admin.settings.users.index',
        'sort'       => 3,
        'icon-class' => 'icon-user',
    ], [
        'key'        => 'settings.initiative',
        'name'       => 'admin::app.layouts.initiative',
        'info'       => 'admin::app.layouts.initiative-info',
        'route'      => 'admin.settings.pipelines.index',
        'sort'       => 2,
        'icon-class' => '',
    ], [
        'key'        => 'settings.initiative.pipelines',
        'name'       => 'admin::app.layouts.pipelines',
        'info'       => 'admin::app.layouts.pipelines-info',
        'route'      => 'admin.settings.pipelines.index',
        'sort'       => 1,
        'icon-class' => 'icon-settings-pipeline',
    ], [
        'key'        => 'settings.initiative.sources',
        'name'       => 'admin::app.layouts.sources',
        'info'       => 'admin::app.layouts.sources-info',
        'route'      => 'admin.settings.sources.index',
        'sort'       => 2,
        'icon-class' => 'icon-settings-sources',
    ], [
        'key'        => 'settings.initiative.types',
        'name'       => 'admin::app.layouts.types',
        'info'       => 'admin::app.layouts.types-info',
        'route'      => 'admin.settings.types.index',
        'sort'       => 3,
        'icon-class' => 'icon-settings-type',
    ], [
        'key'        => 'settings.automation',
        'name'       => 'admin::app.layouts.automation',
        'info'       => 'admin::app.layouts.automation-info',
        'route'      => 'admin.settings.attributes.index',
        'sort'       => 3,
        'icon-class' => '',
    ], [
        'key'        => 'settings.automation.attributes',
        'name'       => 'admin::app.layouts.attributes',
        'info'       => 'admin::app.layouts.attributes-info',
        'route'      => 'admin.settings.attributes.index',
        'sort'       => 1,
        'icon-class' => 'icon-attribute',
    ], [
        'key'        => 'settings.automation.email_templates',
        'name'       => 'admin::app.layouts.email-templates',
        'info'       => 'admin::app.layouts.email-templates-info',
        'route'      => 'admin.settings.email_templates.index',
        'sort'       => 2,
        'icon-class' => 'icon-settings-mail',
    ], [
        'key'        => 'settings.automation.webhooks',
        'name'       => 'Webhooks',
        'info'       => 'Add Edit Delete Webhooks from CRM',
        'route'      => 'admin.settings.webhooks.index',
        'sort'       => 2,
        'icon-class' => 'icon-settings-webhooks',
    ], [
        'key'        => 'settings.automation.workflows',
        'name'       => 'admin::app.layouts.workflows',
        'info'       => 'admin::app.layouts.workflows-info',
        'route'      => 'admin.settings.workflows.index',
        'sort'       => 3,
        'icon-class' => 'icon-settings-flow',
    ], [
        'key'        => 'settings.other_settings',
        'name'       => 'admin::app.layouts.other-settings',
        'info'       => 'admin::app.layouts.other-settings-info',
        'route'      => 'admin.settings.tags.index',
        'sort'       => 4,
        'icon-class' => 'icon-settings',
    ], [
        'key'        => 'settings.other_settings.tags',
        'name'       => 'admin::app.layouts.tags',
        'info'       => 'admin::app.layouts.tags-info',
        'route'      => 'admin.settings.tags.index',
        'sort'       => 1,
        'icon-class' => 'icon-settings-tag',
    ],
];

