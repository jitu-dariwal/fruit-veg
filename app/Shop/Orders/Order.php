<?php

namespace App\Shop\Orders;

use App\Shop\Addresses\Address;
use App\Shop\OrderDetails\OrderDetail;
use App\Shop\Couriers\Courier;
use App\Shop\Customers\Customer;
use App\Shop\OrderStatuses\OrderStatus;
use App\Shop\OrderProducts\OrderProduct;
use App\Shop\OrderStatusHistories\OrderStatusHistory;
use App\Shop\Products\Product;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Order extends Model
{
    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * Columns and their priority in search results.
     * Columns with higher values are more important.
     * Columns with equal values have equal importance.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'orders.id' => 10,
            'customers.first_name' => 10,
            'orders.reference' => 8,
            'products.name' => 5
        ],
        'joins' => [
            'customers' => ['customers.id', 'orders.customer_id'],
            'order_product' => ['orders.id', 'order_product.order_id'],
            'products' => ['products.id', 'order_product.product_id'],
        ],
        'groupBy' => ['orders.id']
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference',
        'courier_id', // @deprecated
        'courier',
        'customer_id',
        'address_id',
        'order_status_id',
        'payment',
        'coupon_code',
        'discounts',
        'total_products',
        'sub_total',
        'total',
        'tax',
        'total_paid',
        'invoice',
        'label_url',
        'tracking_number',
        'customer_discount',
        'discount_type',
        'shipping_charges',
        'shipping_method',
        'payment_method',
        'payment_card_id',
        'transaction_id',
		'driver'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    
	
	/**
     * Get Product Final Price.
     *
     * @param  int $price Product Price
     * @param  double $weight Product Weight
     * @param  double $actualWeight Product Actual Weight as per qty
     * @return Product final price as per product weight
     */
    public function getProductFinalPrice($price,$weight,$actualWeight,$qty){
		if(empty($weight) || empty($actualWeight)){
		   $final_price=$qty*$price;
		   return $final_price;
		}
        $pricePerWeight=$price/$weight;
		$final_price=$actualWeight*$pricePerWeight;
        return $final_price;
    }
	
	/**
     * Update ORder Total and subtotal.
     *
     * @param  int $orderid Order
     * @return true
     */
    public function updateOrderTotal($order_id){
		$orderinfo=Order::find($order_id);
		$subtotal=0;
		foreach($orderinfo->orderproducts as $order_product){
			$subtotal+=$order_product->final_price;
		}
		$grandtotal=$subtotal+$orderinfo->shipping_charges+$orderinfo->tax-$orderinfo->customer_discount;
		$orderinfo->total_products=$subtotal;
		$orderinfo->total=$grandtotal;
		$orderinfo->update();
		return true;
    }
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot([
                        'quantity',
                        'product_name',
                        'product_sku',
                        'product_description',
                        'product_price'
                    ]);
    }
	
	/**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function orderproducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class)->withDefault();;
    }
	
	/**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
    */
	public function orderDetail()
    {
        return $this->hasOne(OrderDetail::class)->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
    */
    public function order_status_historys()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    /**
     * @param string $term
     *
     * @return mixed
     */
    public function searchForOrder(string $term)
    {
        return self::search($term);
    }
}
