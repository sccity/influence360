<?php

namespace Webkul\Bills\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Activity\Models\ActivityProxy;
use Webkul\Bills\Contracts\Bill as BillContract;
use Webkul\Activity\Traits\LogsActivity;

class Bill extends Model implements BillContract
{
    use LogsActivity;

    protected $table = 'bills';

    protected $fillable = [
        'guid', 'tracking_id', 'bill_year', 'session', 'bill_number', 'short_title',
        'general_provisions', 'highlighted_provisions', 'subjects', 'code_sections',
        'appropriations', 'last_action', 'last_action_owner', 'last_action_date',
        'bill_link', 'sponsor', 'floor_sponsor', 'ai_analysis', 'ai_impact_rating',
        'ai_impact_rating_explanation', 'level', 'position', 'date_entered', 'is_tracked'
    ];

    protected $casts = [
        'bill_year' => 'integer',
        'ai_impact_rating' => 'integer',
        'level' => 'integer',
        'position' => 'integer',
        'last_action_date' => 'datetime',
        'date_entered' => 'datetime',
        'is_tracked' => 'boolean',
    ];

    /**
     * Get the activities for the bill.
     */
    public function activities()
    {
        return $this->belongsToMany(ActivityProxy::modelClass(), 'bill_activities');
    }

    /**
     * Override shouldLogActivity to only log creation
     */
    protected static function shouldLogActivity(Model $model, string $action): bool 
    {
        return $action === 'created';
    }

    /**
     * Generate activity title for bills
     */
    protected static function generateActivityTitle(Model $model, string $action): string
    {
        return "Created Bill '{$model->bill_number}'";
    }
}
