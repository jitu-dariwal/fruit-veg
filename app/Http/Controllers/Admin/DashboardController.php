<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Shop\Orders\Order;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Shop\Orders\Repositories\OrderRepository;
use App\Shop\Products\Product;
use App\Shop\Categories\Category;
use App\Shop\Customers\Customer;

class DashboardController extends Controller
{
	/**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;

	/**
	 * @var OrderRepositoryInterface
	 */
    private $order_rep;
	
    public function __construct(
        OrderRepositoryInterface $orderRepository,
		Order $order_rep
    ) {
        $this->orderRepo = $orderRepository;
		$this->order_rep = $order_rep;
    }
	
    public function index()
    {
        $_RECORDS_PER_PAGE = 6;
        $orders = Order::orderBy('id','desc')->take($_RECORDS_PER_PAGE)->get();
        $totalOrders = Order::orderBy('id','desc')->count();
        $pendingOrders=Order::where('order_status_id',1)->count();
        $deliveredOrders=Order::where('order_status_id',3)->count();
        $processingOrders=Order::where('order_status_id',2)->count();
	$shortOrders=Order::where('order_status_id',4)->count();
        $canceledOrders=Order::where('order_status_id',5)->count();
        $totalProducts=Product::where('type','Bulk')->count();
        $totalCategory=Category::count();
        $activeProducts=Product::where('status',1)->where('type','Bulk')->count();
        $totalCustomers=Customer::count();
	$customers=Customer::orderBy('id','desc')->take(8)->get();

        
        
        return view('admin.dashboard', compact('orders','pendingOrders','deliveredOrders','processingOrders','totalProducts','activeProducts','totalCategory','totalOrders','canceledOrders','shortOrders','customers','totalCustomers'));
    }
	
	
}
