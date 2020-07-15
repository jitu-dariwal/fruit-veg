<?php

namespace App\Shop\Checkout;

use App\Shop\Carts\Repositories\CartRepository;
use App\Shop\Carts\ShoppingCart;
use App\Shop\Orders\Order;
use App\Shop\Orders\Repositories\OrderRepository;

class CheckoutRepository
{
    /**
     * @param array $data
     *
     * @return Order
     */
    public function buildCheckoutItems(array $data) : Order
    {
        $orderRepo = new OrderRepository(new Order);
        $cartRepo = new CartRepository(new ShoppingCart);
        
        //echo "<pre>"; print_r($data); exit;

        $order = $orderRepo->create([
            'reference' => $data['reference'],
            'courier_id' => $data['courier_id'],
            'customer_id' => $data['customer_id'],
            'order_status_id' => $data['order_status_id'],
            'payment_method' => $data['payment_method'],
            'total_products' => $data['total_products'],
            'customer_discount' => $data['customer_discount'],
            'sub_total' => $data['sub_total'],
            'total' => $data['total'],
            'total_paid' => $data['total_paid'],
            'tax' => $data['tax'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
       

        $orderRepo = new OrderRepository($order);
        $orderRepo->buildOrderDetails($cartRepo->getCartItems());

        return $order;
    }
}
