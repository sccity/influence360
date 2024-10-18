<?php

namespace Webkul\Activity\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Activity\Contracts\Activity as ActivityContract;
use Webkul\Contact\Models\PersonProxy;
use Webkul\Initiative\Models\InitiativeProxy;
use Webkul\Product\Models\ProductProxy;
use Webkul\User\Models\UserProxy;
use Webkul\Warehouse\Models\WarehouseProxy;
use Webkul\BillFiles\Models\BillFile;
use Webkul\Bills\Models\Bill;

class Activity extends Model implements ActivityContract
{
    /**
     * Define table name of property
     *
     * @var string
     */
    protected $table = 'activities';

    /**
     * Define relationships that should be touched on save
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * Cast attributes to date time
     *
     * @var array
     */
    protected $casts = [
        'schedule_from' => 'datetime',
        'schedule_to'   => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'type',
        'location',
        'comment',
        'additional',
        'schedule_from',
        'schedule_to',
        'is_done',
        'user_id',
    ];

    /**
     * Get the user that owns the activity.
     */
    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }

    /**
     * The participants that belong to the activity.
     */
    public function participants()
    {
        return $this->hasMany(ParticipantProxy::modelClass());
    }

    /**
     * Get the file associated with the activity.
     */
    public function files()
    {
        return $this->hasMany(FileProxy::modelClass(), 'activity_id');
    }

    /**
     * The initiatives that belong to the activity.
     */
    public function initiatives()
    {
        return $this->belongsToMany(InitiativeProxy::modelClass(), 'initiative_activities');
    }

    /**
     * The Person that belong to the activity.
     */
    public function persons()
    {
        return $this->belongsToMany(PersonProxy::modelClass(), 'person_activities');
    }

    /**
     * The initiatives that belong to the activity.
     */
    public function products()
    {
        return $this->belongsToMany(ProductProxy::modelClass(), 'product_activities');
    }

    /**
     * The Warehouse that belong to the activity.
     */
    public function warehouses()
    {
        return $this->belongsToMany(WarehouseProxy::modelClass(), 'warehouse_activities');
    }

    /**
     * The Bill Files that belong to the activity.
     */
    public function billFiles()
    {
        return $this->belongsToMany(BillFile::class, 'bill_file_activities', 'activity_id', 'bill_file_id');
    }

    public function bills()
    {
        return $this->belongsToMany(Bill::class, 'bill_activities', 'activity_id', 'bill_id');
    }
}
