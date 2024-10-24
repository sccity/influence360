<?php

namespace Webkul\Activity\Traits;

use Webkul\Activity\Repositories\ActivityRepository;
use Webkul\Attribute\Contracts\AttributeValue;
use Webkul\Attribute\Repositories\AttributeValueRepository;
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
            static::logModelActivity($model, 'updated');
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
     * Generate activity title.
     */
    protected static function generateActivityTitle(Model $model, string $action): string
    {
        $modelName = class_basename($model);
        $modelIdentifier = $model->name ?? $model->title ?? $model->id;

        return trans("admin::app.activities.{$action}", [
            'name' => "{$modelName} '{$modelIdentifier}'",
        ]);
    }

    /**
     * Get attribute label.
     */
    protected static function getAttributeLabel($value, $attribute = null)
    {
        if (! $attribute) {
            return $value;
        }

        $attributeType = $attribute->type ?? null;

        switch ($attributeType) {
            case 'boolean':
                return $value ? trans('admin::app.common.yes') : trans('admin::app.common.no');
            case 'select':
            case 'multiselect':
                return $attribute->options()->where('id', $value)->first()?->admin_name ?? $value;
            default:
                return $value;
        }
    }

    /**
     * Get updated attributes.
     */
    protected static function getUpdatedAttributes($model): array
    {
        $updatedAttributes = [];
        $changedAttributes = $model->getDirty();

        foreach ($changedAttributes as $key => $value) {
            if (in_array($key, ['id', 'created_at', 'updated_at'])) {
                continue;
            }

            $oldValue = $model->getOriginal($key);
            $newValue = $value;

            $updatedAttributes[$key] = [
                'attribute' => $key,
                'old' => [
                    'value' => $oldValue,
                    'label' => static::getAttributeLabel($oldValue, $model->$key),
                ],
                'new' => [
                    'value' => $newValue,
                    'label' => static::getAttributeLabel($newValue, $model->$key),
                ],
            ];
        }

        return $updatedAttributes;
    }
}
