<?php

namespace Webkul\BillFiles\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Activity\Models\Activity;
use Webkul\BillFiles\Contracts\BillFile as BillFileContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BillFile extends Model implements BillFileContract
{
    use HasFactory;

    protected $table = 'bill_files';

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
        return $this->belongsToMany(Activity::class, 'bill_file_activities', 'bill_file_id', 'activity_id');
    }
}