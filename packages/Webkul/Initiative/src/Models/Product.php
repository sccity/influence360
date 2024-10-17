<?php

namespace Webkul\Initiative\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Initiative\Contracts\Product as ProductContract;
use Webkul\Product\Models\ProductProxy;

class Product extends Model implements ProductContract
{
    protected $table = 'initiative_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity',
        'price',
        'amount',
        'product_id',
        'initiative_id',
    ];

    /**
     * Get the product owns the initiative product.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get the initiative that owns the initiative product.
     */
    public function initiative()
    {
        return $this->belongsTo(InitiativeProxy::modelClass());
    }

    /**
     * Get the customer full name.
     */
    public function getNameAttribute()
    {
        return $this->product->name;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['name'] = $this->name;

        return $array;
    }
}
