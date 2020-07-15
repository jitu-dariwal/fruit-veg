<?php

namespace App\Shop\Coupons;


use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'name',
        'description',
        'coupon_amount',
        'coupon_minimum_order',
        'coupon_code',
        'uses_per_coupon',
        'uses_per_user',
        'restrict_to_products',
        'restrict_to_categories',
        'coupon_start_date',
        'coupon_expire_date'
        ];

    
}
