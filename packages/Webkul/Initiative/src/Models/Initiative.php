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
use Illuminate\Support\Facades\DB;
use Webkul\Activity\Repositories\ActivityRepository;

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

    protected $dispatchesEvents = [
        'created' => \Webkul\Initiative\Events\InitiativeCreated::class,
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
     * Get the activities for the initiative.
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

    /**
     * The attributes that should trigger activity logging
     */
    protected static $logAttributes = [
        'status',
        'title',
        'priority',
        'stage_id',
        // Add any other attributes you want to track
    ];

    /**
     * Custom method to determine if the change should be logged
     */
    protected static function shouldLogActivity(Model $model, string $action): bool 
    {
        // Always log creation
        if ($action === 'created') {
            return true;
        }

        // For updates, only log if specific attributes changed
        if ($action === 'updated') {
            $changedAttributes = $model->getDirty();
            $trackedChanges = array_intersect(array_keys($changedAttributes), static::$logAttributes);
            return !empty($trackedChanges);
        }

        return false;
    }

    /**
     * Override the logModelActivity method to customize logging
     */
    protected static function logModelActivity(Model $model, string $action): void
    {
        if (! method_exists($model, 'activities')) {
            return;
        }

        $activityData = [
            'type'    => 'system',
            'title'   => static::generateActivityTitle($model, $action),
            'is_done' => 1,
            'user_id' => auth()->id(),
        ];

        if ($action !== 'created') {
            $activityData['additional'] = static::getUpdatedAttributes($model);
        }

        $activity = app(ActivityRepository::class)->create($activityData);

        $model->activities()->attach($activity->id);
    }

    /**
     * Get updated attributes.
     */
    protected static function getUpdatedAttributes($model): array
    {
        $changedAttributes = $model->getDirty();

        \Log::info('Changed attributes:', $changedAttributes);

        foreach ($changedAttributes as $key => $value) {
            if (in_array($key, ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $oldValue = $model->getOriginal($key);
            $newValue = $value;

            \Log::info('Attribute change:', [
                'attribute' => $key,
                'old' => $oldValue,
                'new' => $newValue
            ]);

            // Return a single change at a time in the correct format
            return [
                'attribute' => $key,
                'old' => [
                    'value' => $oldValue,
                    'label' => static::getAttributeLabel($oldValue, $key),
                ],
                'new' => [
                    'value' => $newValue,
                    'label' => static::getAttributeLabel($newValue, $key),
                ],
            ];
        }

        return [];
    }

    /**
     * Get attribute label.
     */
    protected static function getAttributeLabel($value, $key): string
    {
        switch ($key) {
            case 'status':
                return ucfirst($value); // or map to your status labels
            case 'priority':
                $priorities = [
                    'low' => 'Low Priority',
                    'medium' => 'Medium Priority',
                    'high' => 'High Priority',
                ];
                return $priorities[$value] ?? ucfirst($value);
            default:
                return (string) $value;
        }
    }

    /**
     * Generate activity title for initiatives
     */
    protected static function generateActivityTitle(Model $model, string $action): string
    {
        if ($action === 'created') {
            return "Created Initiative '{$model->title}'";
        }

        $changes = array_keys($model->getDirty());
        $attribute = reset($changes);
        
        // For updates, include the attribute that changed
        return "Updated Initiative '{$model->title}' {$attribute}";
    }
}
