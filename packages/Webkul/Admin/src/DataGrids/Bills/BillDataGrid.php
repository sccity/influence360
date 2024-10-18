<?php

namespace Webkul\Admin\DataGrids\Bills;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;


class BillDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('bills')
            ->addSelect(
                'bills.id',
                'bills.tracking_id',
                'bills.bill_year',
                'bills.session',
                'bills.short_title',
                'bills.sponsor',
                'bills.ai_impact_rating',
                'bills.is_tracked',
                'bills.last_action_date'
            );

        $this->addFilter('id', 'bills.id');
        $this->addFilter('tracking_id', 'bills.tracking_id');
        $this->addFilter('bill_year', 'bills.bill_year');
        $this->addFilter('session', 'bills.session');
        $this->addFilter('short_title', 'bills.short_title');
        $this->addFilter('sponsor', 'bills.sponsor');
        $this->addFilter('ai_impact_rating', 'bills.ai_impact_rating');
        $this->addFilter('is_tracked', 'bills.is_tracked');
        $this->addFilter('last_action_date', 'bills.last_action_date');

        $this->setQueryBuilder($queryBuilder);

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'tracking_id',
            'label'      => trans('admin::app.bills.datagrid.tracking_id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'bill_year',
            'label'      => trans('admin::app.bills.datagrid.year'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'session',
            'label'      => trans('admin::app.bills.datagrid.session'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'short_title',
            'label'      => trans('admin::app.bills.datagrid.short_title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'sponsor',
            'label'      => trans('admin::app.bills.datagrid.sponsor'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'ai_impact_rating',
            'label'      => trans('admin::app.bills.datagrid.ai_impact_rating'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'is_tracked',
            'label'      => trans('admin::app.bills.datagrid.is_tracked'),
            'type'       => 'boolean',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'last_action_date',
            'label'      => trans('admin::app.bills.datagrid.last_action_date'),
            'type'       => 'datetime',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.view'),
            'method' => 'GET',
            'route'  => 'admin.bills.view',
            'icon'   => 'icon-eye',
            'url'    => function ($row) {
                return route('admin.bills.view', $row->id);
            },
        ]);
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'type'   => 'delete',
            'title'  => trans('admin::app.datagrid.delete'),
            'action' => route('admin.bills.mass_delete'),
            'method' => 'POST',
            'url'    => route('admin.bills.mass_delete'),
        ]);
    }
}
