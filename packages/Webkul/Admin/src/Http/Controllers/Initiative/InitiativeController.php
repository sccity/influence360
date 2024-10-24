<?php

namespace Webkul\Admin\Http\Controllers\Initiative;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Prettus\Repository\Criteria\RequestCriteria;
use Webkul\Admin\DataGrids\Initiative\InitiativeDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\InitiativeForm;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Admin\Http\Resources\InitiativeResource;
use Webkul\Admin\Http\Resources\StageResource;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\DataGrid\Enums\DateRangeOptionEnum;
use Webkul\Initiative\Repositories\InitiativeRepository;
use Webkul\Initiative\Repositories\PipelineRepository;
use Webkul\Initiative\Repositories\ProductRepository;
use Webkul\Initiative\Repositories\SourceRepository;
use Webkul\Initiative\Repositories\StageRepository;
use Webkul\Initiative\Repositories\TypeRepository;
use Webkul\Tag\Repositories\TagRepository;
use Webkul\User\Repositories\UserRepository;
use Webkul\Initiative\Events\InitiativeCreated;

class InitiativeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected AttributeRepository $attributeRepository,
        protected SourceRepository $sourceRepository,
        protected TypeRepository $typeRepository,
        protected PipelineRepository $pipelineRepository,
        protected StageRepository $stageRepository,
        protected InitiativeRepository $initiativeRepository,
        protected ProductRepository $productRepository,
    ) {
        request()->request->add(['entity_type' => 'initiatives']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(InitiativeDataGrid::class)->process();
        }

        if (request('pipeline_id')) {
            $pipeline = $this->pipelineRepository->find(request('pipeline_id'));
        } else {
            $pipeline = $this->pipelineRepository->getDefaultPipeline();
        }

        return view('admin::initiatives.index', [
            'pipeline' => $pipeline,
            'columns'  => $this->getKanbanColumns(),
        ]);
    }

    /**
     * Returns a listing of the resource.
     */
    public function get(): JsonResponse
    {
        if (request()->query('pipeline_id')) {
            $pipeline = $this->pipelineRepository->find(request()->query('pipeline_id'));
        } else {
            $pipeline = $this->pipelineRepository->getDefaultPipeline();
        }

        if ($stageId = request()->query('pipeline_stage_id')) {
            $stages = $pipeline->stages->where('id', request()->query('pipeline_stage_id'));
        } else {
            $stages = $pipeline->stages;
        }

        foreach ($stages as $stage) {
            /**
             * We have to create a new instance of the initiative repository every time, which is
             * why we're not using the injected one.
             */
            $query = app(InitiativeRepository::class)
                ->pushCriteria(app(RequestCriteria::class))
                ->where([
                    'initiative_pipeline_id'       => $pipeline->id,
                    'initiative_pipeline_stage_id' => $stage->id,
                ]);

            if ($userIds = bouncer()->getAuthorizedUserIds()) {
                $query->whereIn('initiatives.user_id', $userIds);
            }

            $stage->initiative_value = (clone $query)->sum('initiative_value');

            $data[$stage->id] = (new StageResource($stage))->jsonSerialize();

            $data[$stage->id]['initiatives'] = [
                'data' => InitiativeResource::collection($paginator = $query->with([
                    'tags',
                    'type',
                    'source',
                    'user',
                    'person',
                    'person.organization',
                    'pipeline',
                    'pipeline.stages',
                    'stage',
                    'attribute_values',
                ])->paginate(10)),

                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'from'         => $paginator->firstItem(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'to'           => $paginator->lastItem(),
                    'total'        => $paginator->total(),
                ],
            ];
        }

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin::initiatives.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InitiativeForm $request): RedirectResponse
    {
        Event::dispatch('initiative.create.before');

        $data = $request->all();

        $data['status'] = 1;

        if (request()->input('initiative_pipeline_stage_id')) {
            $stage = $this->stageRepository->findOrFail($data['initiative_pipeline_stage_id']);

            $data['initiative_pipeline_id'] = $stage->initiative_pipeline_id;
        } else {
            $pipeline = $this->pipelineRepository->getDefaultPipeline();

            $stage = $pipeline->stages()->first();

            $data['initiative_pipeline_id'] = $pipeline->id;

            $data['initiative_pipeline_stage_id'] = $stage->id;
        }

        if (in_array($stage->code, ['won', 'lost'])) {
            $data['closed_at'] = Carbon::now();
        }

        $data['person']['organization_id'] = empty($data['person']['organization_id']) ? null : $data['person']['organization_id'];

        $initiative = $this->initiativeRepository->create($data);

        // Dispatch the event using the event class
        event(new InitiativeCreated($initiative));

        // If you still need to use the old event system, you can keep this line as well
        Event::dispatch('initiative.create.after', $initiative);

        session()->flash('success', trans('admin::app.initiatives.create-success'));

        return redirect()->route('admin.initiatives.index', $data['initiative_pipeline_id']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $initiative = $this->initiativeRepository->findOrFail($id);

        return view('admin::initiatives.edit', compact('initiative'));
    }

    /**
     * Display a resource.
     */
    public function view(int $id)
    {
        $initiative = $this->initiativeRepository->findOrFail($id);

        // We don't need to fetch activities here, as they will be loaded via AJAX
        return view('admin::initiatives.view', compact('initiative'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InitiativeForm $request, int $id): RedirectResponse|JsonResponse
    {
        Event::dispatch('initiative.update.before', $id);

        $data = $request->all();

        if (isset($data['initiative_pipeline_stage_id'])) {
            $stage = $this->stageRepository->findOrFail($data['initiative_pipeline_stage_id']);

            $data['initiative_pipeline_id'] = $stage->initiative_pipeline_id;
        } else {
            $pipeline = $this->pipelineRepository->getDefaultPipeline();

            $stage = $pipeline->stages()->first();

            $data['initiative_pipeline_id'] = $pipeline->id;

            $data['initiative_pipeline_stage_id'] = $stage->id;
        }

        $data['person']['organization_id'] = empty($data['person']['organization_id']) ? null : $data['person']['organization_id'];

        $initiative = $this->initiativeRepository->update($data, $id);

        Event::dispatch('initiative.update.after', $initiative);

        if (request()->ajax()) {
            return response()->json([
                'message' => trans('admin::app.initiatives.update-success'),
            ]);
        }

        session()->flash('success', trans('admin::app.initiatives.update-success'));

        if (request()->has('closed_at')) {
            return redirect()->back();
        } else {
            return redirect()->route('admin.initiatives.index', $data['initiative_pipeline_id']);
        }
    }

    /**
     * Update the initiative attributes.
     */
    public function updateAttributes(int $id)
    {
        $data = request()->all();

        $attributes = $this->attributeRepository->findWhere([
            'entity_type' => 'initiatives',
            ['code', 'NOTIN', ['title', 'description']],
        ]);

        Event::dispatch('initiative.update.before', $id);

        $initiative = $this->initiativeRepository->update($data, $id, $attributes);

        Event::dispatch('initiative.update.after', $initiative);

        return response()->json([
            'message' => trans('admin::app.initiatives.update-success'),
        ]);
    }

    /**
     * Update the initiative stage.
     */
    public function updateStage(int $id)
    {
        $this->validate(request(), [
            'initiative_pipeline_stage_id' => 'required',
        ]);

        $initiative = $this->initiativeRepository->findOrFail($id);

        $stage = $initiative->pipeline->stages()
            ->where('id', request()->input('initiative_pipeline_stage_id'))
            ->firstOrFail();

        Event::dispatch('initiative.update.before', $id);

        $initiative = $this->initiativeRepository->update(
            [
                'entity_type'            => 'initiatives',
                'initiative_pipeline_stage_id' => $stage->id,
            ],
            $id,
            ['initiative_pipeline_stage_id']
        );

        Event::dispatch('initiative.update.after', $initiative);

        return response()->json([
            'message' => trans('admin::app.initiatives.update-success'),
        ]);
    }

    /**
     * Search person results.
     */
    public function search(): AnonymousResourceCollection
    {
        if ($userIds = bouncer()->getAuthorizedUserIds()) {
            $results = $this->initiativeRepository
                ->pushCriteria(app(RequestCriteria::class))
                ->findWhereIn('user_id', $userIds);
        } else {
            $results = $this->initiativeRepository
                ->pushCriteria(app(RequestCriteria::class))
                ->all();
        }

        return InitiativeResource::collection($results);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->initiativeRepository->findOrFail($id);

        try {
            Event::dispatch('initiative.delete.before', $id);

            $this->initiativeRepository->delete($id);

            Event::dispatch('initiative.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.initiatives.destroy-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.initiatives.destroy-failed'),
            ], 400);
        }
    }

    /**
     * Mass Update the specified resources.
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $initiatives = $this->initiativeRepository->findWhereIn('id', $massUpdateRequest->input('indices'));

        try {
            foreach ($initiatives as $initiative) {
                Event::dispatch('initiative.update.before', $initiative->id);

                $this->initiativeRepository->update(
                    ['initiative_pipeline_stage_id' => $massUpdateRequest->input('value')],
                    $initiative->id,
                    ['initiative_pipeline_stage_id']
                );

                Event::dispatch('initiative.update.before', $initiative->id);
            }

            return response()->json([
                'message' => trans('admin::app.initiatives.update-success'),
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'message' => trans('admin::app.initiatives.destroy-failed'),
            ], 400);
        }
    }

    /**
     * Mass Delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $initiatives = $this->initiativeRepository->findWhereIn('id', $massDestroyRequest->input('indices'));

        try {
            foreach ($initiatives as $initiative) {
                Event::dispatch('initiative.delete.before', $initiative->id);

                $this->initiativeRepository->delete($initiative->id);

                Event::dispatch('initiative.delete.after', $initiative->id);
            }

            return response()->json([
                'message' => trans('admin::app.initiatives.destroy-success'),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.initiatives.destroy-failed'),
            ]);
        }
    }

    /**
     * Attach product to initiative.
     */
    public function addProduct(int $initiativeId): JsonResponse
    {
        $product = $this->productRepository->updateOrCreate(
            [
                'initiative_id'    => $initiativeId,
                'product_id' => request()->input('product_id'),
            ],
            array_merge(
                request()->all(),
                [
                    'initiative_id' => $initiativeId,
                    'amount'  => request()->input('price') * request()->input('quantity'),
                ],
            )
        );

        return response()->json([
            'data'    => $product,
            'message' => trans('admin::app.initiatives.update-success'),
        ]);
    }

    /**
     * Remove product attached to initiative.
     */
    public function removeProduct(int $id): JsonResponse
    {
        try {
            Event::dispatch('initiative.product.delete.before', $id);

            $this->productRepository->deleteWhere([
                'initiative_id'    => $id,
                'product_id' => request()->input('product_id'),
            ]);

            Event::dispatch('initiative.product.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.initiatives.destroy-success'),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.initiatives.destroy-failed'),
            ]);
        }
    }

    /**
     * Kanban lookup.
     */
    public function kanbanLookup()
    {
        $params = $this->validate(request(), [
            'column'      => ['required'],
            'search'      => ['required', 'min:2'],
        ]);

        /**
         * Finding the first column from the collection.
         */
        $column = collect($this->getKanbanColumns())->where('index', $params['column'])->firstOrFail();

        /**
         * Fetching on the basis of column options.
         */
        return app($column['filterable_options']['repository'])
            ->select([$column['filterable_options']['column']['label'].' as label', $column['filterable_options']['column']['value'].' as value'])
            ->where($column['filterable_options']['column']['label'], 'LIKE', '%'.$params['search'].'%')
            ->get()
            ->map
            ->only('label', 'value');
    }

    /**
     * Get columns for the kanban view.
     */
    private function getKanbanColumns(): array
    {
        return [
            [
                'index'                 => 'id',
                'label'                 => trans('admin::app.initiatives.index.kanban.columns.id'),
                'type'                  => 'integer',
                'searchable'            => false,
                'search_field'          => 'in',
                'filterable'            => true,
                'filterable_type'       => null,
                'filterable_options'    => [],
                'allow_multiple_values' => true,
                'sortable'              => true,
                'visibility'            => true,
            ],
            [
                'index'                 => 'initiative_value',
                'label'                 => trans('admin::app.initiatives.index.kanban.columns.initiative-value'),
                'type'                  => 'string',
                'searchable'            => false,
                'search_field'          => 'in',
                'filterable'            => true,
                'filterable_type'       => null,
                'filterable_options'    => [],
                'allow_multiple_values' => true,
                'sortable'              => true,
                'visibility'            => true,
            ],
            [
                'index'                 => 'user_id',
                'label'                 => trans('admin::app.initiatives.index.kanban.columns.sales-person'),
                'type'                  => 'string',
                'searchable'            => false,
                'search_field'          => 'in',
                'filterable'            => true,
                'filterable_type'       => 'searchable_dropdown',
                'filterable_options'    => [
                    'repository' => UserRepository::class,
                    'column'     => [
                        'label' => 'name',
                        'value' => 'id',
                    ],
                ],
                'allow_multiple_values' => true,
                'sortable'              => true,
                'visibility'            => true,
            ],
            [
                'index'                 => 'person.id',
                'label'                 => trans('admin::app.initiatives.index.kanban.columns.contact-person'),
                'type'                  => 'string',
                'searchable'            => false,
                'search_field'          => 'in',
                'filterable'            => true,
                'allow_multiple_values' => true,
                'sortable'              => true,
                'visibility'            => true,
                'filterable_type'       => 'searchable_dropdown',
                'filterable_options'    => [
                    'repository' => PersonRepository::class,
                    'column'     => [
                        'label' => 'name',
                        'value' => 'id',
                    ],
                ],
            ],
            [
                'index'                 => 'initiative_type_id',
                'label'                 => trans('admin::app.initiatives.index.kanban.columns.initiative-type'),
                'type'                  => 'string',
                'searchable'            => false,
                'search_field'          => 'in',
                'filterable'            => true,
                'filterable_type'       => 'dropdown',
                'filterable_options'    => $this->typeRepository->all(['name as label', 'id as value'])->toArray(),
                'allow_multiple_values' => true,
                'sortable'              => true,
                'visibility'            => true,
            ],
            [
                'index'                 => 'initiative_source_id',
                'label'                 => trans('admin::app.initiatives.index.kanban.columns.source'),
                'type'                  => 'string',
                'searchable'            => false,
                'search_field'          => 'in',
                'filterable'            => true,
                'filterable_type'       => 'dropdown',
                'filterable_options'    => $this->sourceRepository->all(['name as label', 'id as value'])->toArray(),
                'allow_multiple_values' => true,
                'sortable'              => true,
                'visibility'            => true,
            ],

            [
                'index'                 => 'tags.name',
                'label'                 => trans('admin::app.initiatives.index.kanban.columns.tags'),
                'type'                  => 'string',
                'searchable'            => false,
                'search_field'          => 'in',
                'filterable'            => true,
                'allow_multiple_values' => true,
                'sortable'              => true,
                'visibility'            => true,
                'filterable_type'       => 'searchable_dropdown',
                'filterable_options'    => [
                    'repository' => TagRepository::class,
                    'column'     => [
                        'label' => 'name',
                        'value' => 'name',
                    ],
                ],
            ],

            [
                'index'              => 'expected_close_date',
                'label'              => trans('admin::app.initiatives.index.kanban.columns.expected-close-date'),
                'type'               => 'date',
                'searchable'         => false,
                'sortable'           => true,
                'filterable'         => true,
                'filterable_type'    => 'date_range',
                'filterable_options' => DateRangeOptionEnum::options(),
            ],

            [
                'index'              => 'created_at',
                'label'              => trans('admin::app.initiatives.index.kanban.columns.created-at'),
                'type'               => 'date',
                'searchable'         => false,
                'sortable'           => true,
                'filterable'         => true,
                'filterable_type'    => 'date_range',
                'filterable_options' => DateRangeOptionEnum::options(),
            ],
        ];
    }
}
