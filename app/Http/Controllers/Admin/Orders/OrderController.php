<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Helper\Generalfnv;
use App\Http\Controllers\Controller;
use App\Shop\Addresses\Repositories\Interfaces\AddressRepositoryInterface;
use App\Shop\Addresses\Transformations\AddressTransformable;
use App\Shop\Categories\Category;
use App\Shop\Couriers\Courier;
use App\Shop\Couriers\Repositories\CourierRepository;
use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\OrderDetails\OrderDetail;
use App\Shop\OrderDetails\Repositories\Interfaces\OrderDetailRepositoryInterface;
use App\Shop\OrderDetails\Repositories\OrderDetailRepository;
use App\Shop\OrderDetails\Requests\CreateOrderDetailRequest;
use App\Shop\OrderProducts\OrderProduct;
use App\Shop\OrderStatuses\OrderStatus;
use App\Shop\OrderStatuses\Repositories\Interfaces\OrderStatusRepositoryInterface;
use App\Shop\OrderStatuses\Repositories\OrderStatusRepository;
use App\Shop\OrderStatusHistories\OrderStatusHistory;
use App\Shop\Orders\Order;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Shop\Orders\Repositories\OrderRepository;
use App\Shop\Products\Product;
use App\Shop\Tools\MarkuppriceTrait;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOrderStatus;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OrderController extends Controller
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

    private $permission;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        Generalfnv $per_check,
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
        $this->addressRepo     = $addressRepository;
        $this->customerRepo    = $customerRepository;
        $this->orderStatusRepo = $orderStatusRepository;
        $this->orderDetailRepo = $orderDetailRepository;
        $this->category        = $category;
        $this->order_rep       = $order_rep;
        $this->permission      = $per_check;
        $this->middleware(['permission:update-order, guard:employee'], ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
         * check permission
         */
        $is_allow = $this->permission->check_permission('view-order');

        if (isset($is_allow) && $is_allow == 0) {

            return view('admin.permissions.permission_denied');
            exit;
        }
        // end permission

        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $orders_status     = OrderStatus::get();
        //$list = $this->orderRepo->listOrders('created_at', 'desc');
        $orders = Order::orderBy('created_at', 'desc')->paginate(config('constants.RECORDS_PER_PAGE'));
        if (request()->has('q')) {
            //$list = $this->orderRepo->searchOrder(request()->input('q') ?: '');
            //$orders = $this->orderRepo->paginateArrayResults($this->transFormOrder($list), $_RECORDS_PER_PAGE);
            $orders = Order::orderBy('created_at', 'desc')->search(request()->input('q'));
            if (request()->has('status') && !empty(request()->input('status'))) {
                $orders = $orders->where('order_status_id', request()->input('status'));
            }
            $orders = $orders->paginate(config('constants.RECORDS_PER_PAGE'));
        }

        return view('admin.orders.list', ['orders' => $orders, 'orders_status' => $orders_status]);
    }

     /**
     * Update Order Status in bulk
     *
     * @return \Illuminate\Http\Response
     */
    public function updateMultiOrderStatus(Request $request)
    {
        $orderids = $request->orderids;
        if(empty($orderids) || count($orderids)<=0){
           return back()->with('error', 'You didn\'t enter any values for update order status. Please first select order.');
        }
        $status   = $request->updatestatus;
        if(empty($status)){
           return back()->with('error', 'You didn\'t enter any values for order status. Please select order status.');
        }
        foreach($orderids as $order)
        {
            Order::where('id', $order)->update([
                'order_status_id' => $status,
            ]);
            $orderDetails = Order::where('id', $order)->first();
            Mail::to($orderDetails->customer->email)->send(new SendOrderStatus($orderDetails));
        }
        return back()->with('message', 'Order Status Updated Successfully.');
    }

    /**
     * Display a customer order listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerOrdersList($customer)
    {
        /*
         * check permission
         */
        $is_allow = $this->permission->check_permission('view-order');

        if (isset($is_allow) && $is_allow == 0) {

            return view('admin.permissions.permission_denied');
            exit;
        }
        // end permission
        $orders_status     = OrderStatus::get();
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        //$list = $this->orderRepo->listCustomerOrders('created_at', 'desc', ['*'], 'customer_id',request('customer'));
        $orders = Order::orderBy('created_at', 'desc')->where('customer_id', request('customer'))->paginate($_RECORDS_PER_PAGE);

        if (request()->has('q')) {
            //$list = $this->orderRepo->searchCustomerOrder(request()->input('q') ?: '', request('customer'));
            $orders = Order::orderBy('created_at', 'desc')->where('customer_id', request('customer'))->search(request()->input('q'));
            if (request()->has('status') && !empty(request()->input('status'))) {
                $orders = $orders->where('order_status_id', request()->input('status'));
            }
            $orders = $orders->paginate(config('constants.RECORDS_PER_PAGE'));
        }

        //$orders = $this->orderRepo->paginateArrayResults($this->transFormOrder($list), $_RECORDS_PER_PAGE);

        return view('admin.orders.list', ['orders' => $orders, 'customer' => $customer, 'orders_status' => $orders_status]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        /*
         * check permission
         */
        $is_allow = $this->permission->check_permission('create-order');

        if (isset($is_allow) && $is_allow == 0) {

            return view('admin.permissions.permission_denied');
            exit;
        }
        // end permission

        $customers = DB::table('customers')->join('addresses', 'customers.default_address_id','=','addresses.id')->select('customers.id', 'customers.first_name', 'customers.last_name', 'customers.email', 'addresses.company_name')->orderBy('first_name', 'asc')->get();
        
        return view('admin.orders.create', compact('customers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getCustomerDetailForm($id)
    {
        $states   = DB::table('states')->select('state')->get();
        $customer = DB::table('customers')
            ->leftJoin('addresses', 'customers.default_address_id', '=', 'addresses.id')
            ->select('customers.*', 'addresses.company_name', 'addresses.street_address', 'addresses.address_line_2'
                , 'addresses.post_code', 'addresses.city', 'addresses.county_state', 'addresses.country_id', 'addresses.customer_id')
            ->where('customers.id', $id)
            ->first();
        return view('admin.orders.customer_detail_form', ['states' => $states, 'customer' => $customer]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateOrderDetailRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrderDetailRequest $request)
    {
        $order = Order::create(
            [
                'customer_id'     => $request->customer_id,
                'order_status_id' => 1,
                'is_internet_order' => 1,
            ]
        );
        $order_id      = $order->id;
        $order_details = OrderDetail::create(
            [
                'order_id'               => $order_id,
                'first_name'             => $request->first_name,
                'last_name'              => $request->last_name,
                'email'                  => $request->email,
                'tel_num'                => $request->tel_num,
                'company_name'           => $request->company_name,
                'street_address'         => $request->street_address,
                'address_line_2'         => $request->address_line_2,
                'post_code'              => $request->post_code,
                'city'                   => $request->city,
                'country_state'          => $request->country_state,
                'country'                => $request->country,
                'billing_name'           => $request->first_name,
                'billing_company'        => $request->company_name,
                'billing_street_address' => $request->street_address,
                'billing_address_line_2' => $request->address_line_2,
                'billing_postcode'       => $request->post_code,
                'billing_city'           => $request->city,
                'billing_state'          => $request->country_state,
                'billing_country'        => $request->country,
            ]
        );
        OrderStatusHistory::create([
            'order_id'          => $order_id,
            'customer_notified' => 0,
            'comments'          => '',
            'order_status_id'   => 1,

        ]);
        $request->session()->flash('message', 'Order Created successfuly');
        return redirect('admin/order/' . $order_id . '/add-products');
    }

    /**
     * Display the Orders and Add Product List.
     *
     * @param  int $id Order
     * @return \Illuminate\Http\Response
     */
    public function addProductstoOrder($id)
    {

        $order        = $this->orderRepo->findOrderById($id);
        $order_status = $this->orderStatusRepo->listOrderStatuses();
        $states       = DB::table('states')->select('state')->get();
        $categories   = $this->category->listMyaccoutcategories();
		
		$customer = Customer::where('customers.id', $order->customer_id)
		->leftJoin('addresses', 'customers.default_address_id', '=', 'addresses.id')
		->select('customers.*', 'addresses.company_name', 'addresses.street_address', 'addresses.address_line_2', 'addresses.post_code', 'addresses.city', 'addresses.county_state', 'addresses.country_id', 'addresses.customer_id')
		->first();
		
		//find delivery dates
		$delivery_details = Generalfnv::getDeliverydates();
		
		$postCodesDeliveries = [];
		$selectedPostCode = (!empty($order->orderDetail->shipping_post_code) ? $order->orderDetail->shipping_post_code : $order->orderDetail->post_code);
		
		$tempDetails = DB::table('post_codes')->whereRaw('find_in_set("\''.$selectedPostCode.'\'",post_codes.post_codes)')->first();
				
		$postCodesDeliveries[$selectedPostCode] = (!empty($tempDetails) && isset($tempDetails->week_days)) ? $tempDetails->week_days : [];
		
		$bankholidays = DB::table('bankholidays')->where('holiday_date', '!=', null)->select('id','name', DB::raw("DATE_FORMAT(holiday_date, '%d-%m-%Y') as holiday_date"))->pluck('holiday_date')->all();
		
		//pr($postCodesDeliveries);die;
		
		
        return view('admin.orders.add_product', [
			'states' => $states,
			'order' => $order,
			'categories' => $categories,
			'order_status' => $order_status,
			'customer' => $customer,
            'postCodesDeliveries' => $postCodesDeliveries,
            'selectedPostCode' => $selectedPostCode,
            'all_holidays' => $delivery_details['all_holiday_dates'],
            'bankholidays' => $bankholidays
		]);
    }

    /**
     * Display the category Product List.
     *
     * @param  int $cat
     * @param  string $type (Bulk/Split)
     * @return \Illuminate\Http\Response
     */
    public function getCategoryProducts($type, $cat)
    {
        $categoryProducts = DB::table('category_product')
            ->Join('products', 'category_product.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', 'products.price', 'products.sale_price'
                , 'products.split_price')
            ->where('category_product.category_id', $cat)
            ->where('products.type', $type)
            ->get();
        return view('admin.orders.category-products', compact('categoryProducts'));
    }

    /**
     * Store a Order Products
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */

    public function addOrderProduct(Request $request)
    {
        $orderinfo        = Order::select('customer_id')->findOrFail($request->order_id);
        $productinfo      = Product::select()->where('id', $request->product)->firstOrfail();
        $pricewith_markup = $this->product_price_with_markup($request->type, $productinfo->price, $request->category, $orderinfo->customer_id);
        $actual_weight    = $productinfo->weight * $request->qty;
        $final_price      = $this->order_rep->getProductFinalPrice($pricewith_markup, $productinfo->weight, $actual_weight, $request->qty);
        $addOrderProduct  = OrderProduct::create(
            [
                'order_id'            => $request->order_id,
                'product_id'          => $request->product,
                'quantity'            => $request->qty,
                'weight'              => $productinfo->weight,
                'actual_weight'       => $actual_weight,
                'weight_unit'         => $productinfo->mass_unit,
                'product_name'        => $productinfo->name,
                'product_sku'         => $productinfo->product_code,
                'product_code'        => $productinfo->product_code,
                'product_description' => $productinfo->description,
                'packet_size'         => $productinfo->packet_size,
                'type'                => $request->type,
                'product_price'       => $pricewith_markup,
                'final_price'         => $final_price,
            ]
        );
        $this->order_rep->updateOrderTotal($request->order_id);
        $request->session()->flash('message', 'Product Added successfuly');
        return back();
    }

    /**
     * Update a Order Products
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrderProduct(Request $request)
    {
        $order_id    = $request->order_id;
        $customer_id = $request->customer_id;
        $order       = Order::findOrFail($order_id);
        $order->update([
            'customer_discount' => $request->customer_discount,
            'discount_type'     => $request->discount_type,
            'shipping_charges'  => $request->delivery_charges,
            'shipping_method'   => $request->shipping_method,
            'total_products'    => $request->sub_total,
            'total'             => $request->total_amount,
        ]);
        $order->orderDetail()->update([
            'shipping_add_name'       => $request->sname,
            'shipping_add_company'    => $request->scompany_name,
            'shipping_street_address' => $request->shipping_address,
            'shipping_address_line2'  => $request->shipping_address_line_2,
            'shipping_city'           => $request->scity,
            'shipping_state'          => $request->sstate,
            'shipping_post_code'      => $request->spost_code,
            'shipping_country'        => $request->scountry,
            'shipping_tel_num'        => $request->stel_number,
            'shipping_email'          => $request->semail_address,
            'agent_name'              => $request->agent,
            'po_number'               => $request->po_number,
            'recurr_status'           => $request->recurr_order_status,
        ]);
        if($request->order_product){
        foreach ($request->order_product as $key => $value) {

            $orderproduct_is = OrderProduct::where('order_id', $order_id)->where('product_id', $value)->first();
            $productinfo     = Product::where('id', $value)->first();
            $actual_weight   = $productinfo->weight * $request->quantity[$key];
            $final_price     = $this->order_rep->getProductFinalPrice($orderproduct_is->product_price, $productinfo->weight, $actual_weight, $request->quantity[$key]);
            $orderproduct_is->update([
                'quantity'      => $request->quantity[$key],
                'weight'        => $productinfo->weight,
                'actual_weight' => $actual_weight,
                'weight_unit'   => $productinfo->mass_unit,
                'final_price'   => $final_price,
                'is_packed'     => $request->{'packed_' . $value},
            ]);
        }
        $this->order_rep->updateOrderTotal($order_id);
        return view('admin.orders.order-product-list', compact('order'));
    }else{
        return view('admin.orders.order-product-list', compact('order'));
    }
    }

    /**
     * Update a Order Delivery Info
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateOrderDeliveryinfo(Request $request)
    {
        $order_id    = $request->order_id;
        $customer_id = $request->customer_id;
        $order       = Order::findOrFail($order_id);
        $order->update([
            'payment_method'  => $request->update_info_payment_method,
            'order_status_id' => $request->status,
        ]);
		
		/*** Remove this functionality
        $o_frequency = "";
        if ($request->order_frequency && count($request->order_frequency > 0)) {
            foreach ($request->order_frequency as $freq) {
                $o_frequency .= $freq . ',';
            }
        } ***/
		
        $order->orderDetail()->update([
            'shipdate'                => date('Y-m-d', strtotime($request->delivery_date)),
            //'arrival_time'            => $request->hour . ':' . $request->minute,
            //'order_frequency'         => rtrim($o_frequency, ','),
            //'preferred_delivery_time' => $request->delivery_pref,
            'Access_Time'                 => $request->delivery_window,
            'comment'                 => $request->comments,
            'delivery_procedure'      => $request->delivery_notes,
            //'delivery_message'        => $request->delivery_message,
        ]);
        $notified      = 0;
        $order_comment = '';
        if ($request->notify_user) {
            $notified = $request->notify_user;

        }
        if (!empty($o_frequency)) {
            //$order_comment .= '<b>Shipping frequency: </b>' . $o_frequency . '<br>';
        }

        if (!empty($request->comments)) {
            $order_comment .= '<b>Comment: </b>' . $request->comments . '<br>';
        }

        if (!empty($request->delivery_notes)) {
            $order_comment .= '<b>Delivery Procedure eg leave with 24hour security / loading bay: </b>' . $request->delivery_notes . '<br>';
        }

        if (!empty($request->delivery_message)) {
            //$order_comment .= '<b>Message On Delivery: </b>' . $request->delivery_message . '<br>';
        }
        if ($request->append_comments) {
            OrderStatusHistory::create([
                'order_id'          => $order_id,
                'customer_notified' => $notified,
                'comments'          => $order_comment,
                'order_status_id'   => $request->status,

            ]);
        }

        if($notified==1){
            $orderDetails = Order::where('id', $order_id)->first();
            Mail::to($orderDetails->customer->email)->send(new SendOrderStatus($orderDetails));
        }

        return 1;
    }

    /**
     * Destroy a Order Products
     *
     * @param  int $product_id
     * @param  int $order_id
     * @return \Illuminate\Http\Response
     */
    public function destroyOrderProduct($productid, $orderid)
    {
        $orderProduct = OrderProduct::where('product_id', $productid)->where('order_id', $orderid)->delete();
        return back()->with('message', 'Product Removed successfuly');
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
            'order'         => $order,
            'customer'      => $this->customerRepo->findCustomerById($order->customer_id),
            'currentStatus' => $this->orderStatusRepo->findOrderStatusById($order->order_status_id),
            'user'          => auth()->guard('employee')->user(),
        ]);
    }

    /**
     * @param $orderId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($orderId)
    {
        $order          = $this->orderRepo->findOrderById($orderId);
        $order->courier = $this->courierRepo->findCourierById($order->courier_id);
        $order->address = $this->addressRepo->findAddressById($order->address_id);

        $orderRepo = new OrderRepository($order);

        $items = $orderRepo->listOrderedProducts();

        return view('admin.orders.edit', [
            'statuses'      => $this->orderStatusRepo->listOrderStatuses(),
            'order'         => $order,
            'items'         => $items,
            'customer'      => $this->customerRepo->findCustomerById($order->customer_id),
            'currentStatus' => $this->orderStatusRepo->findOrderStatusById($order->order_status_id),
            'payment'       => $order->payment,
            'user'          => auth()->guard('employee')->user(),
        ]);
    }

    /**
     * @param Request $request
     * @param $orderId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $orderId)
    {
        $order     = $this->orderRepo->findOrderById($orderId);
        $orderRepo = new OrderRepository($order);

        if ($request->has('total_paid') && $request->input('total_paid') != null) {
            $orderData = $request->except('_method', '_token');
        } else {
            $orderData = $request->except('_method', '_token', 'total_paid');
        }

        $orderRepo->updateOrder($orderData);

        return redirect()->route('admin.orders.edit', $orderId);
    }

    /**
     * Generate order invoice
     *
     * @param int $id
     * @return mixed
     */
    public function generateInvoice(int $id)
    {
        $order = $this->orderRepo->findOrderById($id);

        $data = [
            'order'    => $order,
            'products' => $order->orderproducts,
            'customer' => $order->customer,
            'status'   => $order->orderStatus,
        ];
        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadView('invoices.orders', $data)->stream();
        return $pdf->stream();
    }

    /**
     * Generate order packing slip
     *
     * @param int $order_id
     * @return mixed
     */
    public function generatePackingslip($id)
    {
        $order = $this->orderRepo->findOrderById($id);
        $data  = [
            'order'    => $order,
            'products' => $order->orderproducts,
            'customer' => $order->customer,
            'status'   => $order->orderStatus,
        ];

        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadView('admin.orders.partials.packingslip', $data)->stream();
        return $pdf->stream();
    }


    /**
     * Pay With CC
     *
     * @param int $order_id
     * @return mixed
     */
    public function payWithCC($id)
    {
        $order = $this->orderRepo->findOrderById($id);
        $orderInfo = $order->orderDetail;
        if(count($order->orderproducts)<1){
            return back()->with('error', 'Soory! No product found in order, Please first add the products and than pay with cc.');
        }

        if(empty($order->total) && $order->total=='' && $order->total<=0){
            return back()->with('error', 'Soory! unable to process. To pay with cc amount should be greater than 0. So, please check and update order details and than process to pay.');
        }

        if($order->total<=$order->total_paid){
            return back()->with('error', 'Soory! amount is already paid for this order.');
        }
        
        $amount_to_pay = $order->total - $order->total_paid;

        if($amount_to_pay<=0){
            return back()->with('error', 'Soory! amount should be greater than 0.');
        }
          //echo "ok"; exit;
             ?>
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <script type="text/javascript">
                    function closethisasap() {
                        document.forms["redirectpost"].submit();
                    }
                </script>
            </head>
            <body onload="closethisasap();">
            <form name="redirectpost" method="post" action="https://www.secpay.com/java-bin/ValCard">
                <input type="hidden" name="merchant" value="fruitf01-jredir">
                <input type="hidden" name="trans_id" value="FruitAndVeg<?php echo date('Ymd').time(); ?>">
                <input type="hidden" name="amount" value="<?php echo $amount_to_pay; ?>">
                <input type="hidden" name="bill_name" value="<?php echo $orderInfo->billing_name; ?>">
                <input type="hidden" name="bill_addr_1" value="<?php echo $orderInfo->billing_street_address; ?>">
                <input type="hidden" name="bill_addr_2" value="<?php echo $orderInfo->billing_address_line_2; ?>">
                <input type="hidden" name="bill_city" value="<?php echo $orderInfo->billing_city; ?>">
                <input type="hidden" name="bill_state" value="<?php echo $orderInfo->billing_state; ?>">
                <input type="hidden" name="bill_post_code" value="<?php echo $orderInfo->billing_postcode; ?>">
                <input type="hidden" name="bill_country" value="<?php echo $orderInfo->billing_country; ?>">
                <input type="hidden" name="bill_tel" value="<?php echo $orderInfo->billing_tel_num; ?>">
                <input type="hidden" name="bill_email" value="<?php echo $orderInfo->billing_email; ?>">
                <input type="hidden" name="ship_name" value="<?php echo $orderInfo->shipping_add_name; ?>">
                <input type="hidden" name="ship_addr_1" value="<?php echo $orderInfo->shipping_street_address; ?>">
                <input type="hidden" name="ship_addr_2" value="<?php echo $orderInfo->shipping_address_line2; ?>">
                <input type="hidden" name="ship_city" value="<?php echo $orderInfo->shipping_city; ?>">
                <input type="hidden" name="ship_state" value="<?php echo $orderInfo->shipping_state; ?>">
                <input type="hidden" name="ship_post_code" value="<?php echo $orderInfo->shipping_post_code; ?>">
                <input type="hidden" name="ship_country" value="United Kingdom">
                <input type="hidden" name="currency" value="GBP">
                <input type="hidden" name="template" value="http://www.secpay.com/users/fruitf01/template_fnv.html">
                <input type="hidden" name="callback" value="<?php echo route('admin.orders.order-payment-status', $order->id); ?>;<?php echo route('admin.orders.order-payment-status', $order->id); ?>">
                <input type="hidden" name="osCsid" value="ef0502b22ff1170c297c65a8913ff7eb">
                <input type="hidden" name="options" value="test_status=true,dups=false,cb_post=true,cb_flds=osCsid">
            </form>
            </body> 
            </html>
             
        <?php    
        exit();
        
    }

    /**
     * Update Order PAyment Status
     *
     * @param int $order_id
     * @return mixed
     */
    public function orderPaymentStatus(Request $request, $order_id)
    {
        $data = $request->all();
        $update_arr = array('total_paid' => $data['amount'], 'tracking_number' => $data['trans_id'], 'ip' => $data['ip']);
        DB::table('orders')->where('id',$order_id)->update($update_arr);
        return redirect()->route('admin.orders.addproducts', $order_id)->with('message', 'Order payment has been completed successfully!');
    }

    /**
     * @param Collection $list
     * @return array
     */
    private function transFormOrder(Collection $list)
    {
        $courierRepo     = new CourierRepository(new Courier());
        $customerRepo    = new CustomerRepository(new Customer());
        $orderStatusRepo = new OrderStatusRepository(new OrderStatus());

        return $list->transform(function (Order $order) use ($courierRepo, $customerRepo, $orderStatusRepo) {
            //$order->courier = $courierRepo->findCourierById($order->courier_id);
            $order->customer = $customerRepo->findCustomerById($order->customer_id);
            $order->status   = $orderStatusRepo->findOrderStatusById($order->order_status_id);
            return $order;
        })->all();
    }
}
