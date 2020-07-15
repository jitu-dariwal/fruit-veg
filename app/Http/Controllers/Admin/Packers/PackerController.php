<?php

namespace App\Http\Controllers\Admin\Packers;

use App\Shop\Addresses\Repositories\Interfaces\AddressRepositoryInterface;
use App\Shop\Addresses\Transformations\AddressTransformable;
use App\Shop\Couriers\Courier;
use App\Shop\Couriers\Repositories\CourierRepository;
use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Orders\Order;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Shop\Orders\Repositories\OrderRepository;
use App\Shop\OrderDetails\OrderDetail;
use App\Shop\OrderDetails\Repositories\Interfaces\OrderDetailRepositoryInterface;
use App\Shop\OrderDetails\Repositories\OrderDetailRepository;
use App\Shop\OrderDetails\Requests\CreateOrderDetailRequest;
use App\Shop\OrderStatuses\OrderStatus;
use App\Shop\Products\Product;
use App\Shop\OrderProducts\OrderProduct;
use App\Shop\OrderStatuses\Repositories\Interfaces\OrderStatusRepositoryInterface;
use App\Shop\OrderStatuses\Repositories\OrderStatusRepository;
use App\Shop\OrderStatusHistories\OrderStatusHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Shop\Categories\Category;
use App\Shop\Tools\MarkuppriceTrait;
use DB;

class PackerController extends Controller
{
    use AddressTransformable;
    use MarkuppriceTrait;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;

    /**
     * @var CourierRepositoryInterface
     */
    private $courierRepo;

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepo;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    /**
     * @var OrderStatusRepositoryInterface
     */
    private $orderStatusRepo;

    /**
     * @var OrderDetailRepositoryInterface
     */
    private $orderDetailRepo;
	
     /**
	 * @var CategoryRepositoryInterface
	 */
    private $category;
	
	/**
	 * @var OrderRepositoryInterface
	 */
    private $order_rep;
	
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CourierRepositoryInterface $courierRepository,
        AddressRepositoryInterface $addressRepository,
        CustomerRepositoryInterface $customerRepository,
        OrderStatusRepositoryInterface $orderStatusRepository,
        OrderDetailRepositoryInterface $orderDetailRepository,
		Category $category,
		Order $order_rep
    ) {
        $this->orderRepo = $orderRepository;
        //$this->courierRepo = $courierRepository;
        $this->addressRepo = $addressRepository;
        $this->customerRepo = $customerRepository;
        $this->orderStatusRepo = $orderStatusRepository;
        $this->orderDetailRepo = $orderDetailRepository;
        $this->category = $category;
		$this->order_rep = $order_rep;
        $this->middleware(['permission:update-order, guard:employee'], ['only' => ['edit', 'update']]);
        $this->middleware('checkpacker');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $orders = Order::orderBy('created_at', 'desc')->paginate(config('constants.RECORDS_PER_PAGE'));
        $list = Order::orderBy('created_at', 'desc')->paginate(config('constants.RECORDS_PER_PAGE'));
        $pendingOrders=Order::where('order_status_id',1)->count();
        $deliveredOrders=Order::where('order_status_id',3)->count();
        $processingOrders=Order::where('order_status_id',2)->count();
        
		
        if (request()->has('q')) {
            $list = Order::orderBy('created_at', 'desc')->search(request()->input('q') ?: '');
        }        
        $orders = $list;
        return view('admin.packers.index', compact('orders','list','pendingOrders','deliveredOrders','processingOrders'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ordersbydate($dt)
    {
		$_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $orders = OrderDetail::where('shipdate',$dt)->orderBy('created_at','desc')->paginate($_RECORDS_PER_PAGE);
        return view('admin.packers.orders', compact('orders'));
    }
	
	/**
     * Display a order detials .
     *
     * @return \Illuminate\Http\Response
     */
    public function orderdetail($order_id)
    {
		$order = $this->orderRepo->findOrderById($order_id);
		$order_status=$this->orderStatusRepo->listOrderStatuses();
        $states = DB::table('states')->select('state')->get();
        $categories = DB::table('categories')->select('id','name')->get();
        return view('admin.packers.order_detail', ['states' => $states, 'order' =>$order, 'categories' => $categories, 'order_status' => $order_status]);
    }
	

    /**
     * Display the specified resource.
     *
     * @param  int $orderId
     * @return \Illuminate\Http\Response
     */
    public function show($orderId)
    {
        $order = $this->orderRepo->findOrderById($orderId);
        //$order->courier = $this->courierRepo->findCourierById($order->courier_id);
        //$order->address = $this->addressRepo->findAddressById($order->address_id);

        $orderRepo = new OrderRepository($order);

        $items = $orderRepo->listOrderedProducts();

        return view('admin.orders.show', [
            'order' => $order,
            'customer' => $this->customerRepo->findCustomerById($order->customer_id),
            'currentStatus' => $this->orderStatusRepo->findOrderStatusById($order->order_status_id),
            'user' => auth()->guard('employee')->user()
        ]);
    }
	
	/**
     * Update a Order Products
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrderProductstatus(Request $request)
    {
        $order_id=$request->order_id;
        $customer_id=$request->customer_id;
        $order=Order::findOrFail($order_id);
       
        foreach($request->order_product as $key=>$value)
        {
		$orderproduct_is=OrderProduct::where('order_id',$order_id)->where('product_id',$value)->first();
		$productinfo=Product::where('id',$value)->first();
		$actual_weight= $request->actual_weight[$key];
		    $final_price=$this->order_rep->getProductFinalPrice($orderproduct_is->product_price,$productinfo->weight,$actual_weight,$orderproduct_is->quantity);
			$is_avilable = 1;
			$is_short = 0;
		if(isset($request->{'product_status_'.$value})){
			$is_avilable=0;
			$final_price=0;
		}
		if(isset($request->{'product_short_'.$value})){
			
			$is_short=1;
		}
        
		$orderproduct_is->update([
             'actual_weight' => $actual_weight,
             'final_price' => $final_price,
             'is_packed' => $request->{'packed_'.$value},
             'is_available' => $is_avilable,
             'is_short' => $is_short
        ]);
        }
        $this->order_rep->updateOrderTotal($order_id);
        return view('admin.packers.order-product-list', compact('order'));
    }
	
	/**
     * Update a Order Delivery Info
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrderstatus(Request $request)
    {
        $order_id=$request->order_id;
        $customer_id=$request->customer_id;
        $order=Order::findOrFail($order_id);
        $order->update([
            'order_status_id' => $request->status
        ]);
       
        $order->orderDetail()->update([
            'delivery_message' => $request->delivery_message,
        ]);
        $notified=0;
        $order_comment='';
        if($request->notify_user){
            $notified=$request->notify_user;
        }
       

        if(!empty($request->delivery_message)){
            $order_comment.='<b>Message On Packing: </b>'.$request->delivery_message.'<br>';
        }
        if($request->append_comments){
        OrderStatusHistory::create([
            'order_id' => $order_id,
            'customer_notified' => $notified,
            'comments' => $order_comment,
            'order_status_id' => $request->status

        ]);
        }
        
        return 1;
    }
    /**
     * View Calculator for calaculation
     * @param Order $order_id
     * @param Product $product_id
     * @return view
     */
    public function viewCal($order_id,$product_id)
	{
		return view('admin.packers.calculate-weight',compact('order_id','product_id'));
	}
	/**
     * Update Actual Weight
     * @param Order $order_id
     * @param Product $product_id
     * @return to packer order detail page
     */
	public function calculateActualWeight(Request $request, $order_id, $product_id)
	{
		$orderproduct_is=OrderProduct::where('order_id',$order_id)->where('product_id',$product_id)->first();
		$productinfo=Product::where('id',$product_id)->first();
		$actual_weight= $request->wtcalc;
		    $final_price=$this->order_rep->getProductFinalPrice($orderproduct_is->product_price,$productinfo->weight,$actual_weight,$orderproduct_is->quantity);
			
        
		$orderproduct_is->update([
             'actual_weight' => $actual_weight,
             'final_price' => $final_price
        ]);
		$this->order_rep->updateOrderTotal($order_id);
		return back()->with('sucess_status',1);
	}
    /**
     * @param Collection $list
     * @return array
     */
    private function transFormOrder(Collection $list)
    {
        $courierRepo = new CourierRepository(new Courier());
        $customerRepo = new CustomerRepository(new Customer());
        $orderStatusRepo = new OrderStatusRepository(new OrderStatus());

        return $list->transform(function (Order $order) use ($courierRepo, $customerRepo, $orderStatusRepo) {
            //$order->courier = $courierRepo->findCourierById($order->courier_id);
            $order->customer = $customerRepo->findCustomerById($order->customer_id);
            $order->status = $orderStatusRepo->findOrderStatusById($order->order_status_id);
            return $order;
        })->all();
    }
}
