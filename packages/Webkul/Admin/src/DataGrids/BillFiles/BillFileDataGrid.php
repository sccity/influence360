<?php

namespace Webkul\Admin\DataGrids\BillFiles;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class BillFileDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('bill_files')
            ->addSelect(
                'bill_files.id',
                'bill_files.billid',
                'bill_files.name',
                'bill_files.status',
                'bill_files.session',
                'bill_files.year',
                'bill_files.is_tracked'
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
            'label'      => trans('admin::app.bill-files.table.billid'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.bill-files.table.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.bill-files.table.status'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'session',
            'label'      => trans('admin::app.bill-files.table.session'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'year',
            'label'      => trans('admin::app.bill-files.table.year'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'is_tracked',
            'label'      => trans('admin::app.bill-files.table.is_tracked'),
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
            'title'  => trans('admin::app.bill-files.index.datagrid.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.bill-files.view', $row->id);
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
