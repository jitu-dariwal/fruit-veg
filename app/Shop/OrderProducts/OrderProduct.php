<?php

namespace App\Shop\OrderProducts;

use Illuminate\Database\Eloquent\Model;
use App\Shop\Products\Product;
use App\Shop\Orders\Order;
use Nicolaslopezj\Searchable\SearchableTrait;

class OrderProduct extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'product_id', // @deprecated
        'quantity',
        'weight',
        'actual_weight',
        'weight_unit',
        'type',
        'packet_size',
        'product_name',
        'product_sku',
        'product_code',
        'product_description',
        'product_price',
        'final_price',
        'is_available',
        'is_short',
		'is_packed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
