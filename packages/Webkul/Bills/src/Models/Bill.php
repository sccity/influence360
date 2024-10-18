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
        'billid',
        'name',
        'status',
        'session',
        'year',
        'is_tracked',
    ];

    protected $casts = [
        'is_tracked' => 'boolean',
    ];

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'bill_activities', 'bill_id', 'activity_id');
    }
}
