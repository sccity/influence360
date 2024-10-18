<?php

namespace Webkul\Admin\DataGrids\Initiative;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Initiative\Repositories\PipelineRepository;
use Webkul\Initiative\Repositories\SourceRepository;
use Webkul\Initiative\Repositories\StageRepository;
use Webkul\Initiative\Repositories\TypeRepository;
use Webkul\Tag\Repositories\TagRepository;
use Webkul\User\Repositories\UserRepository;

class InitiativeDataGrid extends DataGrid
{
    /**
     * Pipeline instance.
     *
     * @var \Webkul\Contract\Repositories\Pipeline
     */
    protected $pipeline;

    /**
     * Create data grid instance.
     *
     * @return void
     */
    public function __construct(
        protected PipelineRepository $pipelineRepository,
        protected StageRepository $stageRepository,
        protected SourceRepository $sourceRepository,
        protected TypeRepository $typeRepository,
        protected UserRepository $userRepository,
        protected TagRepository $tagRepository,
    ) {
        if (request('pipeline_id')) {
            $this->pipeline = $this->pipelineRepository->find(request('pipeline_id'));
        } else {
            $this->pipeline = $this->pipelineRepository->getDefaultPipeline();
        }
    }

    /**
     * Prepare query builder.
     */
    public function prepareQueryBuilder(): Builder
    {
        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('initiatives')
            ->addSelect(
                'initiatives.id',
                'initiatives.title',
                'initiatives.status',
                'initiatives.initiative_value',
                'initiatives.expected_close_date',
                'initiative_sources.name as initiative_source_name',
                'initiative_types.name as initiative_type_name',
                'initiatives.created_at',
                'initiative_pipeline_stages.name as stage',
                'initiative_tags.tag_id as tag_id',
                'users.id as user_id',
                'users.name as sales_person',
                'persons.id as person_id',
                'persons.name as person_name',
                'tags.name as tag_name',
                'initiative_pipelines.rotten_days as pipeline_rotten_days',
                'initiative_pipeline_stages.code as stage_code',
                DB::raw('CASE WHEN DATEDIFF(NOW(),'.$tablePrefix.'initiatives.created_at) >='.$tablePrefix.'initiative_pipelines.rotten_days THEN 1 ELSE 0 END as rotten_initiative'),
            )
            ->leftJoin('users', 'initiatives.user_id', '=', 'users.id')
            ->leftJoin('persons', 'initiatives.person_id', '=', 'persons.id')
            ->leftJoin('initiative_types', 'initiatives.initiative_type_id', '=', 'initiative_types.id')
            ->leftJoin('initiative_pipeline_stages', 'initiatives.initiative_pipeline_stage_id', '=', 'initiative_pipeline_stages.id')
            ->leftJoin('initiative_sources', 'initiatives.initiative_source_id', '=', 'initiative_sources.id')
            ->leftJoin('initiative_pipelines', 'initiatives.initiative_pipeline_id', '=', 'initiative_pipelines.id')
            ->leftJoin('initiative_tags', 'initiatives.id', '=', 'initiative_tags.initiative_id')
            ->leftJoin('tags', 'tags.id', '=', 'initiative_tags.tag_id')
            ->groupBy('initiatives.id')
            ->where('initiatives.initiative_pipeline_id', $this->pipeline->id);

        if ($userIds = bouncer()->getAuthorizedUserIds()) {
            $queryBuilder->whereIn('initiatives.user_id', $userIds);
        }

        if (! is_null(request()->input('rotten_initiative.in'))) {
            $queryBuilder->havingRaw($tablePrefix.'rotten_initiative = '.request()->input('rotten_initiative.in'));
        }

        $this->addFilter('id', 'initiatives.id');
        $this->addFilter('user', 'initiatives.user_id');
        $this->addFilter('sales_person', 'users.name');
        $this->addFilter('initiative_source_name', 'initiative_sources.name');
        $this->addFilter('initiative_type_name', 'initiative_types.name');
        $this->addFilter('person_name', 'persons.name');
        $this->addFilter('type', 'initiative_pipeline_stages.code');
        $this->addFilter('stage', 'initiative_pipeline_stages.id');
        $this->addFilter('tag_name', 'tags.name');
        $this->addFilter('expected_close_date', 'initiatives.expected_close_date');
        $this->addFilter('created_at', 'initiatives.created_at');
        $this->addFilter('rotten_initiative', DB::raw('DATEDIFF(NOW(), '.$tablePrefix.'initiatives.created_at) >= '.$tablePrefix.'initiative_pipelines.rotten_days'));

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     */
    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.initiatives.index.datagrid.id'),
            'type'       => 'integer',
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'              => 'sales_person',
            'label'              => trans('admin::app.initiatives.index.datagrid.sales-person'),
            'type'               => 'string',
            'searchable'         => false,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'searchable_dropdown',
            'filterable_options' => [
                'repository' => UserRepository::class,
                'column'     => [
                    'label' => 'name',
                    'value' => 'name',
                ],
            ],
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('admin::app.initiatives.index.datagrid.subject'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'              => 'initiative_source_name',
            'label'              => trans('admin::app.initiatives.index.datagrid.source'),
            'type'               => 'string',
            'searchable'         => false,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => $this->sourceRepository->all(['name as label', 'id as value'])->toArray(),
        ]);

        $this->addColumn([
            'index'      => 'initiative_value',
            'label'      => trans('admin::app.initiatives.index.datagrid.initiative-value'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'closure'    => fn ($row) => core()->formatBasePrice($row->initiative_value, 2),
        ]);

        $this->addColumn([
            'index'              => 'initiative_type_name',
            'label'              => trans('admin::app.initiatives.index.datagrid.initiative-type'),
            'type'               => 'string',
            'searchable'         => false,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => $this->typeRepository->all(['name as label', 'id as value'])->toArray(),
        ]);

        $this->addColumn([
            'index'              => 'tag_name',
            'label'              => trans('admin::app.initiatives.index.datagrid.tag-name'),
            'type'               => 'string',
            'searchable'         => false,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'searchable_dropdown',
            'closure'            => fn ($row) => $row->tag_name ?? '--',
            'filterable_options' => [
                'repository' => TagRepository::class,
                'column'     => [
                    'label' => 'name',
                    'value' => 'name',
                ],
            ],
        ]);

        $this->addColumn([
            'index'              => 'person_name',
            'label'              => trans('admin::app.initiatives.index.datagrid.contact-person'),
            'type'               => 'string',
            'searchable'         => false,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'searchable_dropdown',
            'filterable_options' => [
                'repository' => \Webkul\Contact\Repositories\PersonRepository::class,
                'column'     => [
                    'label' => 'name',
                    'value' => 'name',
                ],
            ],
            'closure'    => function ($row) {
                $route = route('admin.contacts.persons.view', $row->person_id);

                return "<a class=\"text-brandColor transition-all hover:underline\" href='".$route."'>".$row->person_name.'</a>';
            },
        ]);

        $this->addColumn([
            'index'              => 'stage',
            'label'              => trans('admin::app.initiatives.index.datagrid.stage'),
            'type'               => 'string',
            'searchable'         => false,
            'sortable'           => true,
            'filterable'         => true,
            'filterable_type'    => 'dropdown',
            'filterable_options' => $this->pipeline->stages->pluck('name', 'id')
                ->map(function ($name, $id) {
                    return ['value' => $id, 'label' => $name];
                })
                ->values()
                ->all(),
        ]);

        $this->addColumn([
            'index'      => 'rotten_initiative',
            'label'      => trans('admin::app.initiatives.index.datagrid.rotten-initiative'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => false,
            'closure'    => function ($row) {
                if (! $row->rotten_initiative) {
                    return trans('admin::app.initiatives.index.datagrid.no');
                }

                if (in_array($row->stage_code, ['won', 'lost'])) {
                    return trans('admin::app.initiatives.index.datagrid.no');
                }

                return trans('admin::app.initiatives.index.datagrid.yes');
            },
        ]);

        $this->addColumn([
            'index'           => 'expected_close_date',
            'label'           => trans('admin::app.initiatives.index.datagrid.expected-close-date'),
            'type'            => 'date',
            'searchable'      => false,
            'sortable'        => true,
            'filterable'      => true,
            'filterable_type' => 'date_range',
            'closure'         => function ($row) {
                if (! $row->expected_close_date) {
                    return '--';
                }

                return $row->expected_close_date;
            },
        ]);

        $this->addColumn([
            'index'           => 'created_at',
            'label'           => trans('admin::app.initiatives.index.datagrid.created-at'),
            'type'            => 'date',
            'searchable'      => false,
            'sortable'        => true,
            'filterable'      => true,
            'filterable_type' => 'date_range',
        ]);
    }

    /**
     * Prepare actions.
     */
    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('initiatives.view')) {
            $this->addAction([
                'icon'   => 'icon-eye',
                'title'  => trans('admin::app.initiatives.index.datagrid.view'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.initiatives.view', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('initiatives.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('admin::app.initiatives.index.datagrid.delete'),
                'method' => 'delete',
                'url'    => fn ($row) => route('admin.initiatives.delete', $row->id),
            ]);
        }
    }

    /**
     * Prepare mass actions.
     */
    public function prepareMassActions(): void
    {
        $this->addMassAction([
            'icon'   => 'icon-delete',
            'title'  => trans('admin::app.initiatives.index.datagrid.mass-delete'),
            'method' => 'POST',
            'url'    => route('admin.initiatives.mass_delete'),
        ]);

        $this->addMassAction([
            'title'   => trans('admin::app.initiatives.index.datagrid.mass-update'),
            'url'     => route('admin.initiatives.mass_update'),
            'method'  => 'POST',
            'options' => $this->pipeline->stages->map(fn ($stage) => [
                'label' => $stage->name,
                'value' => $stage->id,
            ])->toArray(),
        ]);
    }
}
