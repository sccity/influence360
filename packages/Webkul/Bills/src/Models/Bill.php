<?php

namespace Webkul\Bills\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Activity\Models\Activity;
use Webkul\Bills\Contracts\Bill as BillContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model implements BillContract
{
    use HasFactory;

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

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'bill_activities', 'bill_id', 'activity_id');
    }
}
