<?php

namespace Webkul\Activity\Traits;

use Webkul\Activity\Repositories\ActivityRepository;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function ($model) {
            static::logModelActivity($model, 'created');
        });

        static::updated(function ($model) {
            // Debug log the update event
            \Log::info('LogsActivity trait - updated event triggered:', [
                'model' => get_class($model),
                'dirty' => $model->getDirty(),
                'should_log' => method_exists($model, 'shouldLogActivity') ? $model->shouldLogActivity($model, 'updated') : true
            ]);

            if (!method_exists($model, 'shouldLogActivity') || $model->shouldLogActivity($model, 'updated')) {
                static::logModelActivity($model, 'updated');
            }
        });

        static::deleted(function ($model) {
            static::logModelActivity($model, 'deleted');
        });
    }

    /**
     * Create activity.
     */
    protected static function logModelActivity(Model $model, string $action): void
    {
        if (! method_exists($model, 'activities')) {
            return;
        }

        $activityData = [
            'type'    => method_exists($model, 'getActivityType') 
                ? $model->getActivityType() 
                : 'system',
            'title'   => method_exists($model, 'generateActivityTitle') 
                ? $model->generateActivityTitle($model, $action)
                : static::defaultGenerateActivityTitle($model, $action),
            'is_done' => 1,
            'user_id' => auth()->id(),
        ];

        if ($action !== 'created') {
            $updatedAttributes = method_exists($model, 'getUpdatedAttributes')
                ? $model->getUpdatedAttributes($model)
                : static::defaultGetUpdatedAttributes($model);

            if (!empty($updatedAttributes)) {
                $activityData['additional'] = $updatedAttributes;
            }
        }

        \Log::info('Creating activity with data:', $activityData);

        try {
            $activity = app(ActivityRepository::class)->create($activityData);
            $model->activities()->attach($activity->id);
            \Log::info('Activity created successfully:', ['activity_id' => $activity->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to create activity:', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Generate activity title for non-creation events
     */
    protected static function generateActivityTitle(Model $model, string $action): string
    {
        $changes = array_keys($model->getDirty());
        $attribute = reset($changes);
        $attributeLabel = str_replace('_', ' ', ucfirst($attribute));
        
        return "Updated Initiative '{$model->title}' {$attributeLabel}";
    }

    /**
     * Default generate activity title.
     */
    protected static function defaultGenerateActivityTitle(Model $model, string $action): string
    {
        $modelName = class_basename($model);
        $modelIdentifier = $model->name ?? $model->title ?? $model->id;

        return trans("admin::app.activities.{$action}", [
            'name' => "{$modelName} '{$modelIdentifier}'",
        ]);
    }

    /**
     * Default get updated attributes.
     */
    protected static function defaultGetUpdatedAttributes($model): array
    {
        $updatedAttributes = [];
        $changedAttributes = $model->getDirty();

        foreach ($changedAttributes as $key => $value) {
            if (in_array($key, ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $oldValue = $model->getOriginal($key);
            $newValue = $value;

            $updatedAttributes = [
                'attribute' => $key,
                'old' => [
                    'value' => $oldValue,
                    'label' => static::getAttributeLabel($oldValue),
                ],
                'new' => [
                    'value' => $newValue,
                    'label' => static::getAttributeLabel($newValue),
                ],
            ];
        }

        return $updatedAttributes;
    }

    /**
     * Default get attribute label.
     */
    protected static function getAttributeLabel($value)
    {
        return $value ?? 'None';
    }
}
