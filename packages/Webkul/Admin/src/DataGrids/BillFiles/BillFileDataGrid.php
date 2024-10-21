<?php

namespace Webkul\Admin\DataGrids\BillFiles;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class BillFileDataGrid extends DataGrid
{
    protected $index = 'id';

    protected $sortOrder = 'desc';

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
                'bill_files.sponsor',
                'bill_files.status',
                'bill_files.session',
                'bill_files.year',
                'bill_files.is_tracked'
            );

        $this->addFilter('id', 'bill_files.id');
        $this->addFilter('billid', 'bill_files.billid');
        $this->addFilter('name', 'bill_files.name');
        $this->addFilter('sponsor', 'bill_files.sponsor');
        $this->addFilter('status', 'bill_files.status');
        $this->addFilter('session', 'bill_files.session');
        $this->addFilter('year', 'bill_files.year');
        $this->addFilter('is_tracked', 'bill_files.is_tracked');

        $this->setQueryBuilder($queryBuilder);

        return $queryBuilder;
    }

    /**
     * Add columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'billid',
            'label'      => trans('admin::app.bills.datagrid.bill_id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return '<a href="' . route('admin.bill-files.view', $row->id) . '" style="color: blue; text-decoration: underline;">' . $row->billid . '</a>';
            },
        ]);

        $this->addColumn([
            'index'      => 'name',
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
            'index'      => 'status',
            'label'      => trans('admin::app.bills.datagrid.status'),
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
            'index'      => 'year',
            'label'      => trans('admin::app.bills.datagrid.year'),
            'type'       => 'integer',
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
                return view('admin::bill-files.datagrid.is-tracked', ['row' => $row])->render();
            },
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        // No actions needed as the Bill Number column is now a link
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        // No mass actions needed
    }
}
