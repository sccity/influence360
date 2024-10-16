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

        $this->addFilter('id', 'bill_files.id');
        $this->addFilter('billid', 'bill_files.billid');
        $this->addFilter('name', 'bill_files.name');
        $this->addFilter('status', 'bill_files.status');
        $this->addFilter('session', 'bill_files.session');
        $this->addFilter('year', 'bill_files.year');
        $this->addFilter('is_tracked', 'bill_files.is_tracked');

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'billid',
            'label'      => trans('admin::app.bill-files.index.datagrid.billid'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.bill-files.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.bill-files.index.datagrid.status'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'session',
            'label'      => trans('admin::app.bill-files.index.datagrid.session'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'year',
            'label'      => trans('admin::app.bill-files.index.datagrid.year'),
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'is_tracked',
            'label'      => trans('admin::app.bill-files.index.datagrid.is_tracked'),
            'type'       => 'boolean',
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
        if (bouncer()->hasPermission('bill-files.view')) {
            $this->addAction([
                'icon'   => 'icon-eye',
                'title'  => trans('admin::app.bill-files.index.datagrid.view'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.bill-files.view', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('bill-files.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('admin::app.bill-files.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.bill-files.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('bill-files.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.bill-files.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.bill-files.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        if (bouncer()->hasPermission('bill-files.delete')) {
            $this->addMassAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.bill-files.index.datagrid.delete'),
                'method' => 'POST',
                'url'    => route('admin.bill-files.mass_delete'),
            ]);
        }
    }
}
