<?php

namespace App\Shop\OrderDetails;

use App\Shop\Orders\Order;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'email',
        'tel_num',
        'company_name',
        'street_address',
        'address_line_2',
        'post_code',
        'city',
        'country_state',
        'country',
        'shipping_add_name',
        'shipping_add_company',
        'shipping_street_address',
        'shipping_address_line2',
        'shipping_city',
        'shipping_state',
        'shipping_post_code',
        'shipping_country',
        'shipping_tel_num',
        'shipping_email',
        'billing_name',
        'billing_company',
        'billing_street_address',
        'billing_address_line_2',
        'billing_city',
        'billing_postcode',
        'billing_state',
        'billing_country',
        'agent_name',
        'po_number',
        'arrival_time',
        'recurr_status',
        'order_frequency',
        'preferred_delivery_time',
        'earliest_delivery',
        'comment',
        'delivery_procedure',
        'shipdate',
        'delivery_message',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function order()
    {
        return $this->BelongsTo(Order::class);
    }
}
