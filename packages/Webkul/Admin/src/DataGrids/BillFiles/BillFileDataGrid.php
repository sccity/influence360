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
                'bill_files.year',
                'bill_files.session',
                'bill_files.name',
                'bill_files.sponsor',
                'bill_files.status',
                'bill_files.created_at',
                'bill_files.updated_at',
                'bill_files.is_tracked'
            );

        $this->addFilter('id', 'bill_files.id');
        $this->addFilter('year', 'bill_files.year');
        $this->addFilter('session', 'bill_files.session');
        $this->addFilter('name', 'bill_files.name');
        $this->addFilter('sponsor', 'bill_files.sponsor');
        $this->addFilter('status', 'bill_files.status');
        $this->addFilter('created_at', 'bill_files.created_at');
        $this->addFilter('updated_at', 'bill_files.updated_at');
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
            'index'      => 'id',
            'label'      => 'ID',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return '<a href="' . route('admin.bill-files.view', $row->id) . '" style="color: blue; text-decoration: underline;">' . $row->id . '</a>';
            },
        ]);

        $this->addColumn([
            'index'      => 'year',
            'label'      => 'Year',
            'type'       => 'integer',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'session',
            'label'      => 'Session',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => 'Title',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'sponsor',
            'label'      => 'Sponsor',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => 'Status',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => 'Created',
            'type'       => 'datetime',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('Y/m/d');
            },
        ]);

        $this->addColumn([
            'index'      => 'updated_at',
            'label'      => 'Updated',
            'type'       => 'datetime',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                return \Carbon\Carbon::parse($row->updated_at)->format('Y/m/d');
            },
        ]);

        $this->addColumn([
            'index'      => 'is_tracked',
            'label'      => 'Track',
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
        // No actions needed as the Bill File ID column is now a link
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        // No mass actions needed
    }
}
