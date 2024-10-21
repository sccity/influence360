<?php

namespace Webkul\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Traits\CustomAttribute;
use Webkul\Contact\Contracts\Organization as OrganizationContract;
use Webkul\User\Models\UserProxy;

class Organization extends Model implements OrganizationContract
{
    use CustomAttribute;

    protected $casts = [
        'address' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'user_id',
    ];

    public function getNameAttribute($value)
    {
        if ($value) {
            return $value;
        }

        $nameAttribute = $this->attribute_values->where('attribute_id', 34)->first();
        return $nameAttribute ? $nameAttribute->text_value : null;
    }

    /**
     * Get persons.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function persons()
    {
        return $this->hasMany(PersonProxy::modelClass());
    }

    /**
     * Get the user that owns the initiative.
     */
    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }
}
