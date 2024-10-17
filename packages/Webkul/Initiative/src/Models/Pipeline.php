<?php

namespace Webkul\Initiative\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Initiative\Contracts\Pipeline as PipelineContract;

class Pipeline extends Model implements PipelineContract
{
    protected $table = 'initiative_pipelines';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'rotten_days',
        'is_default',
    ];

    /**
     * Get the initiatives.
     */
    public function initiatives()
    {
        return $this->hasMany(InitiativeProxy::modelClass(), 'initiative_pipeline_id');
    }

    /**
     * Get the stages that owns the pipeline.
     */
    public function stages()
    {
        return $this->hasMany(StageProxy::modelClass(), 'initiative_pipeline_id')->orderBy('sort_order', 'ASC');
    }
}
