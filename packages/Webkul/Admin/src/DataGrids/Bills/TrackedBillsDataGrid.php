<?php

namespace Webkul\Admin\DataGrids\Bills;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class TrackedBillsDataGrid extends BillDataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = parent::prepareQueryBuilder();
        
        // Only show tracked bills
        $queryBuilder->where('bills.is_tracked', true);
        
        // Limit to 5 rows for the dashboard
        $queryBuilder->limit(5);

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        // We'll only show a subset of columns for the dashboard
        $this->addColumn([
            'index'      => 'tracking_id',
            'label'      => trans('admin::app.bills.datagrid.bill_id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'short_title',
            'label'      => trans('admin::app.bills.datagrid.bill_title'),
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
            'closure'    => function ($row) {
                $color = $this->getColorForRating($row->ai_impact_rating);
                return '<span style="background-color: ' . $color . '; color: white; padding: 2px 6px; border-radius: 3px;">' . $row->ai_impact_rating . '</span>';
            },
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
}
