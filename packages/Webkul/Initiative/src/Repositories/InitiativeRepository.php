<?php

namespace Webkul\Initiative\Repositories;

use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeValueRepository;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Initiative\Contracts\Initiative;
use Webkul\Initiative\Events\InitiativeCreated;
use Illuminate\Support\Facades\Log;

class InitiativeRepository extends Repository
{
    /**
     * Searchable fields.
     */
    protected $fieldSearchable = [
        'title',
        'initiative_value',
        'status',
        'user_id',
        'user.name',
        'person_id',
        'person.name',
        'initiative_source_id',
        'initiative_type_id',
        'initiative_pipeline_id',
        'initiative_pipeline_stage_id',
        'created_at',
        'closed_at',
        'expected_close_date',
    ];

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected StageRepository $stageRepository,
        protected PersonRepository $personRepository,
        protected ProductRepository $productRepository,
        protected AttributeRepository $attributeRepository,
        protected AttributeValueRepository $attributeValueRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return Initiative::class;
    }

    /**
     * Get initiatives query.
     *
     * @param  int  $pipelineId
     * @param  int  $pipelineStageId
     * @param  string  $term
     * @param  string  $createdAtRange
     * @return mixed
     */
    public function getInitiativesQuery($pipelineId, $pipelineStageId, $term, $createdAtRange)
    {
        return $this->with([
            'attribute_values',
            'pipeline',
            'stage',
        ])->scopeQuery(function ($query) use ($pipelineId, $pipelineStageId, $term, $createdAtRange) {
            return $query->select(
                'initiatives.id as id',
                'initiatives.created_at as created_at',
                'title',
                'initiative_value',
                'persons.name as person_name',
                'initiatives.person_id as person_id',
                'initiatives.initiative_pipeline_id as initiative_pipeline_id',
                'initiative_pipeline_stages.name as status',
                'initiative_pipeline_stages.id as initiative_pipeline_stage_id'
            )
                ->addSelect(DB::raw('DATEDIFF('.DB::getTablePrefix().'initiatives.created_at + INTERVAL initiative_pipelines.rotten_days DAY, now()) as rotten_days'))
                ->leftJoin('persons', 'initiatives.person_id', '=', 'persons.id')
                ->leftJoin('initiative_pipelines', 'initiatives.initiative_pipeline_id', '=', 'initiative_pipelines.id')
                ->leftJoin('initiative_pipeline_stages', 'initiatives.initiative_pipeline_stage_id', '=', 'initiative_pipeline_stages.id')
                ->where('title', 'like', "%$term%")
                ->where('initiatives.initiative_pipeline_id', $pipelineId)
                ->where('initiatives.initiative_pipeline_stage_id', $pipelineStageId)
                ->when($createdAtRange, function ($query) use ($createdAtRange) {
                    return $query->whereBetween('initiatives.created_at', $createdAtRange);
                })
                ->where(function ($query) {
                    if ($userIds = bouncer()->getAuthorizedUserIds()) {
                        $query->whereIn('initiatives.user_id', $userIds);
                    }
                });
        });
    }

    /**
     * Create a new initiative.
     *
     * @param  array  $data
     * @return \Webkul\Initiative\Contracts\Initiative
     */
    public function create(array $data)
    {
        if (! empty($data['person']['id'])) {
            $person = $this->personRepository->update(array_merge($data['person'], [
                'entity_type' => 'persons',
            ]), $data['person']['id']);
        } else {
            $person = $this->personRepository->create(array_merge($data['person'], [
                'entity_type' => 'persons',
            ]));
        }

        $initiative = parent::create(array_merge([
            'person_id'              => $person->id,
            'initiative_pipeline_id'       => 1,
            'initiative_pipeline_stage_id' => 1,
        ], $data));

        Log::info('Attempting to dispatch InitiativeCreated event');
        try {
            event(new InitiativeCreated($initiative));
            Log::info('InitiativeCreated event dispatched successfully');
        } catch (\Throwable $e) {
            Log::error('Error dispatching InitiativeCreated event: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
        }

        $this->attributeValueRepository->save(array_merge($data, [
            'entity_id' => $initiative->id,
        ]));

        if (isset($data['products'])) {
            foreach ($data['products'] as $product) {
                $this->productRepository->create(array_merge($product, [
                    'initiative_id' => $initiative->id,
                    'amount'  => $product['price'] * $product['quantity'],
                ]));
            }
        }

        return $initiative;
    }

    /**
     * Update.
     *
     * @param  int  $id
     * @param  array|\Illuminate\Database\Eloquent\Collection  $attributes
     * @return \Webkul\Initiative\Contracts\Initiative
     */
    public function update(array $data, $id, $attributes = [])
    {
        if (isset($data['person'])) {
            if (isset($data['person']['id'])) {
                $person = $this->personRepository->update(array_merge($data['person'], [
                    'entity_type' => 'persons',
                ]), $data['person']['id']);
            } else {
                $person = $this->personRepository->create(array_merge($data['person'], [
                    'entity_type' => 'persons',
                ]));
            }

            $data = array_merge([
                'person_id' => $person->id,
            ], $data);
        }

        if (isset($data['initiative_pipeline_stage_id'])) {
            $stage = $this->stageRepository->find($data['initiative_pipeline_stage_id']);

            if (in_array($stage->code, ['won', 'lost'])) {
                $data['closed_at'] = $data['closed_at'] ?? Carbon::now();
            } else {
                $data['closed_at'] = null;
            }
        }

        $initiative = parent::update($data, $id);

        /**
         * If attributes are provided, only save the provided attributes and return.
         * A collection of attributes may also be provided, which will be treated as valid,
         * regardless of whether it is empty or not.
         */
        if (! empty($attributes)) {
            /**
             * If attributes are provided as an array, then fetch the attributes from the database;
             * otherwise, use the provided collection of attributes.
             */
            if (is_array($attributes)) {
                $conditions = ['entity_type' => $data['entity_type']];

                if (isset($data['quick_add'])) {
                    $conditions['quick_add'] = 1;
                }

                $attributes = $this->attributeRepository->where($conditions)
                    ->whereIn('code', $attributes)
                    ->get();
            }

            $this->attributeValueRepository->save(array_merge($data, [
                'entity_id' => $initiative->id,
            ]), $attributes);

            return $initiative;
        }

        $this->attributeValueRepository->save(array_merge($data, [
            'entity_id' => $initiative->id,
        ]));

        $previousProductIds = $initiative->products()->pluck('id');

        if (isset($data['products'])) {
            foreach ($data['products'] as $productId => $productInputs) {
                if (Str::contains($productId, 'product_')) {
                    $this->productRepository->create(array_merge([
                        'initiative_id' => $initiative->id,
                    ], $productInputs));
                } else {
                    if (is_numeric($index = $previousProductIds->search($productId))) {
                        $previousProductIds->forget($index);
                    }

                    $this->productRepository->update($productInputs, $productId);
                }
            }
        }

        foreach ($previousProductIds as $productId) {
            $this->productRepository->delete($productId);
        }

        return $initiative;
    }

    /**
     * Get the average time to close initiatives.
     *
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return float
     */
    public function getAverageTimeToClose($startDate = null, $endDate = null)
    {
        $query = $this->model->whereNotNull('closed_at');

        if ($startDate && $endDate) {
            $query->whereBetween('closed_at', [$startDate, $endDate]);
        }

        return $query->avg(DB::raw('DATEDIFF(closed_at, created_at)')) ?? 0;
    }

    /**
     * Get initiatives count by stage.
     *
     * @return array
     */
    public function getInitiativeCountByStage()
    {
        return $this->model
            ->select('initiative_pipeline_stage_id', DB::raw('count(*) as count'))
            ->groupBy('initiative_pipeline_stage_id')
            ->pluck('count', 'initiative_pipeline_stage_id')
            ->toArray();
    }

    /**
     * Get open initiatives count.
     *
     * @return int
     */
    public function getOpenInitiativesCount()
    {
        return $this->model->whereNull('closed_at')->count();
    }

    /**
     * Get recent initiatives.
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentInitiatives($limit = 5)
    {
        return $this->model->with(['stage', 'person'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    public function attachActivity($initiativeId, $activityId)
    {
        $initiative = $this->find($initiativeId);
        $initiative->activities()->attach($activityId);
    }
}
