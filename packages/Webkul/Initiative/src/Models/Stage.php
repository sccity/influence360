<?php

namespace Webkul\Initiative\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Initiative\Contracts\Stage as StageContract;

class Stage extends Model implements StageContract
{
    public $timestamps = false;

    protected $table = 'initiative_pipeline_stages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'probability',
        'sort_order',
        'initiative_pipeline_id',
    ];

    /**
     * Get the pipeline that owns the pipeline stage.
     */
    public function pipeline()
    {
        return $this->belongsTo(PipelineProxy::modelClass(), 'initiative_pipeline_id');
    }

    /**
     * Get the initiatives.
     */
    public function initiatives()
    {
        return $this->hasMany(InitiativeProxy::modelClass(), 'initiative_pipeline_stage_id');
    }
}
