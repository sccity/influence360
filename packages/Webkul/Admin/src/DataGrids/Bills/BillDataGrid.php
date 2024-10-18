<?php

namespace Webkul\Admin\DataGrids\Bills;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class BillDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('bills')
            ->addSelect(
                'bills.id',
                'bills.billid',
                'bills.name',
                'bills.status',
                'bills.session',
                'bills.year',
                'bills.is_tracked'
            );

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'billid',
            'label'      => trans('admin::app.bills.table.billid'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.bills.table.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.bills.table.status'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'session',
            'label'      => trans('admin::app.bills.table.session'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'year',
            'label'      => trans('admin::app.bills.table.year'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'is_tracked',
            'label'      => trans('admin::app.bills.table.is_tracked'),
            'type'       => 'boolean',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                $checked = $row->is_tracked ? 'checked' : '';
                return <<<HTML
                    <span class="checkbox">
                        <input type="checkbox" id="is_tracked_{$row->id}" {$checked} onchange="toggleTracked({$row->id}, this.checked)">
                        <label for="is_tracked_{$row->id}" class="checkbox-view"></label>
                    </span>
                HTML;
            },
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        $this->addAction([
            'icon'   => 'icon-eye',
            'title'  => trans('admin::app.bills.index.datagrid.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.bills.view', $row->id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-edit',
            'title'  => trans('admin::app.bills.index.datagrid.edit'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.bills.edit', $row->id);
            },
        ]);

        $this->addAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.bills.index.datagrid.delete'),
            'method' => 'DELETE',
            'url'    => function ($row) {
                return route('admin.bills.delete', $row->id);
            },
        ]);
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        // No mass actions needed
    }
}
