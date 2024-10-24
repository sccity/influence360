<?php

namespace Webkul\Admin\DataGrids\Bills;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        
        // Ensure 'id' is included in the select
        $queryBuilder->addSelect('bills.id');
        
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
            'index'      => 'bill_number',
            'label'      => trans('admin::app.bills.datagrid.bill_number'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => function ($row) {
                return '<a href="' . route('admin.bills.view', $row->id) . '" class="text-blue-600 hover:underline">' . $row->bill_number    . '</a>';
            },
        ]);

        $this->addColumn([
            'index'      => 'short_title',
            'label'      => trans('admin::app.bills.datagrid.bill_title'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'sponsor',
            'label'      => trans('admin::app.bills.datagrid.sponsor'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'ai_impact_rating',
            'label'      => trans('admin::app.bills.datagrid.ai_impact_rating'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => function ($row) {
                $color = $this->getColorForRating($row->ai_impact_rating);
                return '<div class="flex items-center">
                            <span class="inline-block w-2 h-2 rounded-full mr-2" style="background-color: ' . $color . ';"></span>
                            <span>' . $row->ai_impact_rating . '</span>
                        </div>';
            },
        ]);

        $this->addColumn([
            'index'      => 'last_action_date',
            'label'      => trans('admin::app.bills.datagrid.last_action_date'),
            'type'       => 'datetime',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => function ($row) {
                return Carbon::parse($row->last_action_date)->format('F jS, Y');
            },
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
        // No mass actions for this grid
    }

    /**
     * Override the getCollection method to return all items without pagination
     */
    public function getCollection()
    {
        $this->prepareQueryBuilder();

        $queryBuilder = $this->getQueryBuilder();

        return $queryBuilder->get();
    }

    protected function getColorForRating($rating)
    {
        if ($rating == 0) return '#808080'; // Gray for 0
        if ($rating <= 3) return '#4CAF50'; // Green for 1-3
        if ($rating <= 6) return '#FFC107'; // Amber for 4-6
        if ($rating <= 9) return '#FF9800'; // Orange for 7-9
        return '#F44336'; // Red for 10
    }
}

