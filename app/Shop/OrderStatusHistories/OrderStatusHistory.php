<?php

namespace App\Shop\OrderStatusHistories;

use Illuminate\Database\Eloquent\Model;
use App\Shop\Orders\Order;
use App\Shop\OrderStatuses\OrderStatus;
class OrderStatusHistory extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'customer_notified',
        'comments',
        'order_status_id'
    ];
	
    public function order_status_history()
	{
		return $this->belongsTo(Order::class);
	}
	
	public function order_status()
	{
		return $this->belongsTo(OrderStatus::class);
	}
}
