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

        // Add this condition
        if (request()->has('is_tracked')) {
            $queryBuilder->where('bills.is_tracked', request()->get('is_tracked'));
        }

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
            'label'      => trans('admin::app.bills.datagrid.bill_id'),
            'type'       => 'string',
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
            'label'      => trans('admin::app.bills.datagrid.bill_title'),
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
            'index'      => 'sponsor',
            'label'      => trans('admin::app.bills.datagrid.sponsor'),
            'type'       => 'string',
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

        $this->addColumn([
            'index'      => 'is_tracked',
            'label'      => trans('admin::app.bills.datagrid.track'),
            'type'       => 'boolean',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return view('admin::bills.datagrid.is-tracked', ['row' => $row])->render();
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

    private function getColorForRating($rating)
    {
        if ($rating == 0) return '#000000'; // Black for 0
        if ($rating <= 3) return '#006400'; // Dark Green for 1-3
        if ($rating <= 6) return '#FFA500'; // Orange for 4-6
        if ($rating <= 9) return '#FF4500'; // Red-Orange for 7-9
        return '#FF0000'; // Bright Red for 10
    }
}
