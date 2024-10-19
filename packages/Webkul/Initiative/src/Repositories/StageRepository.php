<?php

namespace Webkul\Initiative\Repositories;

use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\DB;

class StageRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return 'Webkul\Initiative\Contracts\Stage';
    }

    /**
     * Get the top stage (stage with the highest number of initiatives)
     *
     * @return \Webkul\Initiative\Contracts\Stage|null
     */
    public function getTopStage()
    {
        return $this->model
            ->select('initiative_pipeline_stages.*')
            ->addSelect(DB::raw('COUNT(initiatives.id) as initiatives_count'))
            ->leftJoin('initiatives', 'initiatives.initiative_pipeline_stage_id', '=', 'initiative_pipeline_stages.id')
            ->groupBy('initiative_pipeline_stages.id')
            ->orderByDesc('initiatives_count')
            ->first();
    }
}
