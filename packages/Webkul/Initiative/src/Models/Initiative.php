<?php

namespace Webkul\Initiative\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Webkul\Activity\Models\ActivityProxy;
use Webkul\Activity\Traits\LogsActivity;
use Webkul\Attribute\Traits\CustomAttribute;
use Webkul\Contact\Models\PersonProxy;
use Webkul\Email\Models\EmailProxy;
use Webkul\Initiative\Contracts\Initiative as InitiativeContract;
use Webkul\Quote\Models\QuoteProxy;
use Webkul\Tag\Models\TagProxy;
use Webkul\User\Models\UserProxy;

class Initiative extends Model implements InitiativeContract
{
    use CustomAttribute, LogsActivity;

    protected $casts = [
        'closed_at'           => 'datetime',
        'expected_close_date' => 'date',
    ];

    /**
     * The attributes that are appended.
     *
     * @var array
     */
    protected $appends = [
        'rotten_days',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'initiative_value',
        'status',
        'lost_reason',
        'expected_close_date',
        'closed_at',
        'user_id',
        'person_id',
        'initiative_source_id',
        'initiative_type_id',
        'initiative_pipeline_id',
        'initiative_pipeline_stage_id',
    ];

    /**
     * Get the user that owns the initiative.
     */
    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }

    /**
     * Get the person that owns the initiative.
     */
    public function person()
    {
        return $this->belongsTo(PersonProxy::modelClass());
    }

    /**
     * Get the type that owns the initiative.
     */
    public function type()
    {
        return $this->belongsTo(TypeProxy::modelClass(), 'initiative_type_id');
    }

    /**
     * Get the source that owns the initiative.
     */
    public function source()
    {
        return $this->belongsTo(SourceProxy::modelClass(), 'initiative_source_id');
    }

    /**
     * Get the pipeline that owns the initiative.
     */
    public function pipeline()
    {
        return $this->belongsTo(PipelineProxy::modelClass(), 'initiative_pipeline_id');
    }

    /**
     * Get the pipeline stage that owns the initiative.
     */
    public function stage()
    {
        return $this->belongsTo(StageProxy::modelClass(), 'initiative_pipeline_stage_id');
    }

    /**
     * Get the activities.
     */
    public function activities()
    {
        return $this->belongsToMany(ActivityProxy::modelClass(), 'initiative_activities');
    }

    /**
     * Get the products.
     */
    public function products()
    {
        return $this->hasMany(ProductProxy::modelClass());
    }

    /**
     * Get the emails.
     */
    public function emails()
    {
        return $this->hasMany(EmailProxy::modelClass());
    }

    /**
     * The quotes that belong to the initiative.
     */
    public function quotes()
    {
        return $this->belongsToMany(QuoteProxy::modelClass(), 'initiative_quotes');
    }

    /**
     * The tags that belong to the initiative.
     */
    public function tags()
    {
        return $this->belongsToMany(TagProxy::modelClass(), 'initiative_tags');
    }

    /**
     * Returns the rotten days
     */
    public function getRottenDaysAttribute()
    {
        if (! $this->stage) {
            return 0;
        }

        if (in_array($this->stage->code, ['won', 'lost'])) {
            return 0;
        }

        if (! $this->created_at) {
            return 0;
        }

        $rottenDate = $this->created_at->addDays($this->pipeline->rotten_days);

        return $rottenDate->diffInDays(Carbon::now(), false);
    }
}
