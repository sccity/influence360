<?php

namespace Webkul\LegislativeCalendar\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\LegislativeCalendar\Contracts\LegislativeCalendar as LegislativeCalendarContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LegislativeCalendar extends Model implements LegislativeCalendarContract
{
    use HasFactory;

    protected $table = 'legislative_calendar';

    public $timestamps = false;

    protected $primaryKey = 'guid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'guid', 'committee', 'committee_id', 'mtg_time', 'mtg_place',
        'link', 'date_entered', 'date_modified'
    ];

    protected $casts = [
        'mtg_time' => 'datetime',
        'date_entered' => 'datetime',
        'date_modified' => 'datetime',
    ];
}
