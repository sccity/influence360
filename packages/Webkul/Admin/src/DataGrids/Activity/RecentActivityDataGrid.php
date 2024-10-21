<?php

namespace Webkul\Admin\DataGrids\Activity;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class RecentActivityDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('activities')
            ->leftJoin('users', 'activities.user_id', '=', 'users.id')
            ->select('activities.id', 'activities.title', 'activities.type', 'activities.comment', 'activities.description', 'activities.created_at', 'users.name as user_name')
            ->where('activities.created_at', '>=', now()->subDays(7)) // Show activities from the last 7 days
            ->orderBy('activities.created_at', 'desc');

        $this->addFilter('id', 'activities.id');
        $this->addFilter('title', 'activities.title');
        $this->addFilter('type', 'activities.type');
        $this->addFilter('user_name', 'users.name');
        $this->addFilter('created_at', 'activities.created_at');

        return $queryBuilder;
    }

    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'type',
            'label'      => trans('admin::app.datagrid.type'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'user_name',
            'label'      => trans('admin::app.datagrid.user'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('admin::app.datagrid.created_at'),
            'type'       => 'datetime',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'description',
            'label'      => trans('admin::app.datagrid.description'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route'  => 'admin.activities.view',
            'icon'   => 'icon eye-icon',
        ]);
    }
}
