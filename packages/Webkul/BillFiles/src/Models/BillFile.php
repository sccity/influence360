<?php

namespace Webkul\BillFiles\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Activity\Models\ActivityProxy;
use Webkul\Activity\Traits\LogsActivity;
use Webkul\BillFiles\Contracts\BillFile as BillFileContract;

class BillFile extends Model implements BillFileContract
{
    use LogsActivity;

    protected $table = 'bill_files';

    protected $fillable = [
        'billid',
        'name',
        'status',
        'session',
        'year',
        'is_tracked',
        'sponsor',
    ];

    protected $casts = [
        'is_tracked' => 'boolean',
    ];

    /**
     * Get the activities for the bill file.
     */
    public function activities()
    {
        return $this->belongsToMany(ActivityProxy::modelClass(), 'bill_file_activities');
    }

    /**
     * Override shouldLogActivity to only log creation
     */
    protected static function shouldLogActivity(Model $model, string $action): bool 
    {
        return $action === 'created';
    }

    /**
     * Generate activity title for bill files
     */
    protected static function generateActivityTitle(Model $model, string $action): string
    {
        return "Created Bill File '{$model->billid}'";
    }
}
