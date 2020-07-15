<?php
namespace App\Http\Controllers\Front;

use App\Shop\Addresses\Repositories\Interfaces\AddressRepositoryInterface;
use App\Shop\Cart\Requests\CartCheckoutRequest;
use App\Shop\Checkout\Requests\CheckoutCustomerDeliveryAddRequest;
use App\Shop\Checkout\Requests\CheckoutCustomerPaymentRequest;
use App\Shop\Checkout\Requests\CheckoutCustomerRequest;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Carts\Requests\PayPalCheckoutExecutionRequest;
use App\Shop\Carts\Requests\StripeExecutionRequest;
use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Shop\PaymentMethods\Paypal\Exceptions\PaypalRequestError;
use App\Shop\PaymentMethods\Paypal\Repositories\PayPalExpressCheckoutRepository;
use App\Shop\PaymentMethods\Stripe\Exceptions\StripeChargingErrorException;
use App\Shop\PaymentMethods\Stripe\StripeRepository;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Shipping\ShippingInterface;
use Exception;
use App\Http\Controllers\Controller;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use PayPal\Exception\PayPalConnectionException;
use App\Shop\Checkout\CheckoutRepository;
use Ramsey\Uuid\Uuid;
use App\Shop\Orders\Order;
//use App\Shop\OrderStatuses\Repositories\OrderStatusRepository;
//use App\Shop\OrderStatuses\OrderStatus;
use App\Helper\Payment;
use App\Helper\Generalfnv;
use DB;
use Session;
use App\Shop\Orders\Repositories\OrderRepository;

class CheckoutController extends Controller
{
    use ProductTransformable;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepo;
    
     /**
     * @var checkout repository
     */
   // private $checkoutRepo;

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
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;

    /**
     * @var PayPalExpressCheckoutRepository
     */
    private $payPal;

    /**
     * @var ShippingInterface
     */
    private $shippingRepo;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        //CheckoutRepository $checkoutRepository,
        CourierRepositoryInterface $courierRepository,
        AddressRepositoryInterface $addressRepository,
        CustomerRepositoryInterface $customerRepository,
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository,
        ShippingInterface $shipping
    ) {
        $this->cartRepo = $cartRepository;
        //$this->checkoutRepo = $checkoutRepository;
        $this->courierRepo = $courierRepository;
        $this->addressRepo = $addressRepository;
        $this->customerRepo = $customerRepository;
        $this->productRepo = $productRepository;
        $this->orderRepo = $orderRepository;

        $payPalRepo = new PayPalExpressCheckoutRepository;
        $this->payPal = $payPalRepo;
        $this->shippingRepo = $shipping;
    }

    /**
     * Select delivery address for checkout.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		//session()->forget('checkout');
		
		$customer = $request->user();
        
        $cart_total = $this->cartRepo->getSubTotal();
        
		$default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
		if(isset(Auth()->User()->id)) {
			$customer_details = DB::table('customers')->where('id', Auth()->user()->id)->first();

			if(isset($customer_details->minimum_order) && !empty($customer_details->minimum_order)) {
				$default_minimum_order = $customer_details->minimum_order;
			}
		}else{
			$customer_details = DB::table('customers')->where('id', $customer->id)->first();
		}
       
		if(str_replace(",", "", $cart_total) < $default_minimum_order){
			$msg = "You need to meet our minimum order of £ ".$default_minimum_order;
			return redirect()->route('cart.index')->with('error', $msg);
		}
        
		$default_address_id = $customer_details->default_address_id;
		
		if(!empty($request->get('add')))
			$default_address_id = $request->get('add');
		elseif(!empty(session('checkout')) && !empty(session('checkout.step1')))
			$default_address_id = session('checkout.step1.address');
		
		//find delivery dates
		$delivery_details = Generalfnv::getDeliverydates();
		
		$addresses = $customer->addresses()->get();
		$postCodesDeliveries = [];
		$selectedPostCode = '';
		
		if(!empty($addresses) && $addresses->count() > 0){
			foreach($addresses as $add){
				if($default_address_id == $add->id)
					$selectedPostCode = $add->post_code;
				
				$tempDetails = DB::table('post_codes')->whereRaw('find_in_set("\''.$add->post_code.'\'",post_codes.post_codes)')->first();
				
				$postCodesDeliveries[$add->post_code] = (!empty($tempDetails) && isset($tempDetails->week_days)) ? $tempDetails->week_days : [];
			}
		}
		
		$bankholidays = DB::table('bankholidays')->where('holiday_date', '!=', null)->select('id','name', DB::raw("DATE_FORMAT(holiday_date, '%d-%m-%Y') as holiday_date"))->pluck('holiday_date')->all();
		
		//pr($bankholidays);die;
		
		$temp = explode(',',$customer->shipping_disabled_dates);
		foreach($temp as $k=>$v){
			$temp[$k] = date("d-m-Y", strtotime($v));
		}
		
		$customer->shipping_disabled_dates = implode(',', $temp);
		
		return view('front.checkout.checkout-delivery', [
            'customer' => $customer,
            'addresses' => $addresses,
            'postCodesDeliveries' => $postCodesDeliveries,
            'selectedPostCode' => $selectedPostCode,
            'default_address_id' => $default_address_id,
            'all_holidays' => $delivery_details['all_holiday_dates'],
            'bankholidays' => $bankholidays
        ]);
    }
	
	/**
     * Save delivery address for checkout.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
	public function storeDeliveryAdd(CheckoutCustomerDeliveryAddRequest $request){
		session(['checkout.step1' => $request->except('_method', '_token')]);
		
		return redirect()->route('checkout.confirm');
	}
	
	/**
     * Get confirmation from customer on cart products for order.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
	public function confirm(Request $request){
		
		//Session::put('discount_coupon_amount', $coupon_detail->coupon_amount);
		//echo $request['discount_coupon_amount']; exit;
		$subtotal = $this->cartRepo->getSubTotal();
		
	    $cartItems = $this->cartRepo->getCartItemsTransformed();
		
		$redeem_code = '';
		$discount_coupon_amount = 0;
		if(!empty(session('coupon_code'))) {
            if($this->checkCouponCodeExistOrNot()) {
                $discount_coupon_amount = session('discount_coupon_amount');
				$redeem_code = session('coupon_code');
            }
		}
		
		$sub_total = str_replace(",", "", $this->cartRepo->getSubTotal());

		$total = $sub_total - $discount_coupon_amount;
		//$total = $this->cartRepo->getTotal(2,'',$discount_coupon_amount);
		
		$deliveryAddress = $request->user()->addresses()->where('id', session('checkout.step1.address'))->first();
		
		$billingAdd = [];
		
		$customer = Customer::where('id', auth()->user()->id)->first();
		
		if($customer){
			$billingAdd['first_name'] = $customer->first_name;
			$billingAdd['last_name'] = $customer->last_name;
			$billingAdd['street_address'] = $customer->invoice_street_address;
			$billingAdd['address_line_2'] = $customer->invoice_suburb;
			$billingAdd['city'] = $customer->invoice_city;
			$billingAdd['county_state'] = $customer->invoice_state;
			$billingAdd['post_code'] = $customer->invoice_postcode;
		}
		
		return view('front.checkout.checkout-confirm',compact('cartItems','subtotal','discount_coupon_amount','total','deliveryAddress','billingAdd','redeem_code'));
	}
	
	public function storeConfirmation(Request $request) {
		
		$orderbasicinfo = Session::get('checkout');
		
        $products = $this->cartRepo->getCartItems();
		$sub_total = str_replace(",", "", $this->cartRepo->getSubTotal());
		
		$cart_total = $this->cartRepo->getSubTotal();
		
		$data = $request->except('_method', '_token');
		
		//apply discount coupon
        if(isset($data['submit_coupon'])) {
            
            if(!isset($data['redeem_code']) && empty($data['redeem_code'])) {
                $error_msg = "Please Enter Redeem Code";
                return redirect()->route('checkout.confirm')->with('error', $error_msg);
            }
            
            $product_ids = '';
            $category_ids = '';

            $coupon_details = DB::table('coupons')->where('coupon_code', $data['redeem_code'])->first();
            
            if((isset($coupon_details->restrict_to_products) && $coupon_details->restrict_to_products != '') || (isset($coupon_details->restrict_to_categories) && $coupon_details->restrict_to_categories != '')) {
            
                foreach($products as $product){
                   $category_obj = DB::table('category_product')->select('category_id')->where('product_id', $product->id)->first();
                   $category_ids .= $category_obj->category_id.",";
                   $product_ids .= $product->id.",";
                }
            
            }

            $category_ids = rtrim($category_ids, ",");
            $product_ids = rtrim($product_ids, ",");
            
            $check_coupon = Generalfnv::verifyCouponcode($data['redeem_code'], $category_ids, $product_ids, $cart_total);
            
            if($check_coupon) {
				
                $discount_coupon_amount = session('discount_coupon_amount');
				$total = $this->cartRepo->getTotal(2,'',$discount_coupon_amount);

                $msg = "Congratulations, you have redeemed £ ".number_format($discount_coupon_amount, 2);
                        return redirect()->route('checkout.confirm')->with(['status'=>$msg]);
                
            } else {
                $error_msg = "Wrong Redeem Code or Expired, Please check and try again";
                return redirect()->route('checkout.confirm')->with('error', $error_msg);
            }
            
         }
         //end discount coupon functionality
	}
	
	/**
     * Save confirmation from customer on cart products for order.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
	public function saveCartOrder($request){

		$orderbasicinfo = Session::get('checkout');
		
        $products = $this->cartRepo->getCartItems();
		$sub_total = str_replace(",", "", $this->cartRepo->getSubTotal());
		
		$cart_total = $this->cartRepo->getSubTotal();
		
		$data = $request->except('_method', '_token');
		
		
		/* Check coupon code discounted account is set or not */
			if(Session::get('discount_coupon_amount') > 0) {
				$discount_coupon_amount = Session::get('discount_coupon_amount');
			} else {
				$discount_coupon_amount = 0;
			}
		/* End coupon code discounted account is set or not */
		
		$total = $this->cartRepo->getTotal(2,'',$discount_coupon_amount);
		
		$checkoutRepo = new CheckoutRepository;
		
		$order = $checkoutRepo->buildCheckoutItems([
            'reference' => Uuid::uuid4()->toString(),
            'courier_id' => 1, // @deprecated
            'customer_id' => $request->user()->id,
            'coupon_code' => session('coupon_code'),
            'address_id' => session('checkout.step1.address'),
            'order_status_id' => 1,
            'payment_method' => null,
            'total_products' => $sub_total,
            'total' => $total,
            'customer_discount' => $discount_coupon_amount,
            'sub_total' => $sub_total,
            'total_paid' => $total,
            'tax' => $this->cartRepo->getTax()
        ]);
		
		$customer = Customer::where('id', auth()->user()->id)->with('defaultaddress')->first();
		
		/* Get billing address */
			$billingAdd = [];
			
			if($customer){
				$billingAdd['first_name'] = $customer->first_name;
				$billingAdd['last_name'] = $customer->last_name;
				$billingAdd['street_address'] = $customer->invoice_street_address;
				$billingAdd['address_line_2'] = $customer->invoice_suburb;
				$billingAdd['city'] = $customer->invoice_city;
				$billingAdd['county_state'] = $customer->invoice_state;
				$billingAdd['post_code'] = $customer->invoice_postcode;
			}
		/* End billing address */
		
		/* Get delivery address */
			$deliveryAddress = $request->user()->addresses()->where('id', session('checkout.step1.address'))->first();
		/* End delivery address */
		
		$country = 'United Kingdom';
		
		$earliest_delivery = null;
		$access_time = $orderbasicinfo['step1']['delivery_window'];
		
		$delivery_date = date("Y-m-d H:i:s", strtotime($orderbasicinfo['step1']['delivery_date']));
		
		$update_arr = [
			'order_id' => $order->id,
			'first_name' => $customer->first_name,
			'last_name' => $customer->last_name,
			'email' => $customer->email,
			'tel_num' => $customer->tel_num,
			'company_name' => $customer->defaultaddress->company_name,
			'street_address' => $customer->defaultaddress->street_address,
			'address_line_2' => $customer->defaultaddress->address_line_2,
			'post_code' => $customer->defaultaddress->post_code,
			'city' => $customer->defaultaddress->city,
			'country_state' => $customer->defaultaddress->county_state,
			'country' => $country,
			'shipping_add_name' => $deliveryAddress->first_name.' '.$deliveryAddress->last_name,
			'shipping_add_company' => $deliveryAddress->company_name,
			'shipping_street_address' => $deliveryAddress->street_address,
			'shipping_address_line2' => $deliveryAddress->address_line_2,
			'shipping_city' => $deliveryAddress->city,
			'shipping_state' => $deliveryAddress->county_state,
			'shipping_post_code' => $deliveryAddress->post_code,
			'shipping_country' => $country,
			'billing_name' => $billingAdd['first_name'].' '.$billingAdd['last_name'],
			'billing_company' => '',
			'billing_street_address' => $billingAdd['street_address'],
			'billing_address_line_2' => $billingAdd['address_line_2'],
			'billing_city' => $billingAdd['city'],
			'billing_postcode' => $billingAdd['post_code'],
			'billing_state' => $billingAdd['county_state'],
			'billing_country' => $country,
			'earliest_delivery' => $earliest_delivery,
			'Access_Time' => $access_time,
			'comment' => $orderbasicinfo['step1']['delivery_notes'],
			'delivery_procedure' => $orderbasicinfo['step1']['delivery_notes'],
			'shipdate' => $delivery_date,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		];
		
		DB::table('order_details')->insert($update_arr);
		
		session()->forget('checkout');
		$this->cartRepo->clearCart();
		
		return $order;
	}
	
	/**
     * Select payment from customer for place cart order.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
	public function payment(Request $request,$order_id = null){
		//Payment::getCardDetails('9902000000000018');die;
		
		if(!empty($order_id)){
			$order = Order::where('id',$order_id)->with(['orderDetail','customer','orderproducts'])->first();
			
			if(empty($order) || $order->count() < 1){
				return redirect()->route('accounts.orders')->with(['error' => 'Order not exist.']);
			}else if($order->customer->id != Auth()->User()->id){
				return redirect()->route('accounts.orders')->with(['error' => 'This order not valid for you.']);
			}else if($order->order_status_id > 1){
				return redirect()->route('accounts.orders')->with(['error' => 'Payment already done on this order.']);
			}
			
			$customer = $order->customer;
		}else{
			$order = [];
			if(empty($this->cartRepo->getCartItemsTransformed()->toArray())){
				return redirect()->route('home')->with(['error' => 'Please add product first into the cart.']);
			}
			
			$customer = Customer::where('id', Auth()->User()->id)->first();
		}
		
		$cards = DB::table('customers_pay360_tokens')->where('customers_id', Auth()->User()->id)->get();
		
		$paymentMethods = DB::table('payment_methods')->where('status', 1)->pluck('name','value')->all();
		
		
		$sub_total = str_replace(",", "", $this->cartRepo->getSubTotal());

		/* Check coupon code discounted account is set or not */
			$discount_coupon_amount = 0;
			if(!empty(session('coupon_code')) && empty($order_id)) {
				if($this->checkCouponCodeExistOrNot()) {
					$discount_coupon_amount = session('discount_coupon_amount');
				}else{
					$error_msg = "Wrong Redeem Code or Expired, Please check and try again";
					return redirect()->route('checkout.confirm')->with('error', $error_msg);
				}
			}
			
		/* End coupon code discounted account is set or not */
		$total = $sub_total - $discount_coupon_amount;
		
		//pr($order->toArray());die;
		
		return view('front.checkout.checkout-payment',compact('order','order_id','cards','paymentMethods','customer','total'));
	}
	
	/**
     * Select payment from customer for place cart order.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
	public function storePayment(CheckoutCustomerPaymentRequest $request,$order_id = null){
		$data = $request->all();
		//pr($data);die;
		
		if(isset($data['pay_by']) && $data['pay_by'] == 'paypal'){
			$res = [];
			$res['status'] = false;
			$res['order'] = [];
			$res['error_code'] = '';
			$res['message'] = '';
			
			//$transaction = Payment::getPayPalTransaction('4U10912423277301H');
			
			if(array_key_exists('type', $data) && $data['type'] == 'saveOrder'){
				if(!empty(session('coupon_code')) && empty($order_id)){
					if(!$this->checkCouponCodeExistOrNot()) {
						$error_msg = "Wrong Redeem Code or Expired, Please check and try again";
						\Session::flash('error', $error_msg);
						
						$res['status'] = false;
						$res['message'] = $error_msg;
						
						return json_encode($res);
					}
				}

				if(empty($order_id))
					$order = $this->saveCartOrder($request);
				else
					$order = Order::where('id',$order_id)->first();
				
				$res['status'] = true;
				$res['order']['id'] = $order->id;
				$res['order']['total'] = $order->total;
				$res['message'] = '';
			}else if(array_key_exists('orderID', $data) && !empty($data['orderID'])){
				if(empty($order_id))
					$order = $this->saveCartOrder($request);
				else
					$order = Order::where('id',$order_id)->first();
				
				$transaction = Payment::getPayPalTransaction($data['orderID']);
				
				if($transaction['status']){
					$order->update([
						'order_status_id' => 2,
						'payment_method' => 'paypal',
						'transaction_id' => $data['orderID'],
					]);
					
					$res['status'] = true;
					$res['message'] = '';
					
					\Session::flash('status', 'Payment is processed successful.');
				}else{
					$res['error_code'] = $transaction['name'];
					$res['message'] = 'Payment not done successful,\n '.$transaction['name'].': '.$transaction['message'];
				}
			}else{
				$res['error_code'] = '';
				$res['message'] = 'Payment not done successful.';
			}
			return json_encode($res);
		}
		else if(isset($data['pay_by']) && $data['pay_by'] == 'invoice'){
			if($data['pay_by_invoice'] == 'on'){
				if(!empty(session('coupon_code')) && empty($order_id)){
					if(!$this->checkCouponCodeExistOrNot()) {
						$error_msg = "Wrong Redeem Code or Expired, Please check and try again";
						return redirect()->route('checkout.confirm')->with('error', $error_msg);
					}
				}
				
				if(empty($order_id))
					$order = $this->saveCartOrder($request);
				else
					$order = Order::where('id',$order_id)->first();
				
				$order->update([
					'order_status_id' => 2,
					'payment_method' => 'pay-by-invoice',
				]);
				
				return redirect()->route('checkout.thankYou',$order->id)->with(['status' => "Order successful."]);
			}
		}
		else if(isset($data['pay_by']) && $data['pay_by'] == 'pay_by_card'){
			if(!empty(session('coupon_code')) && empty($order_id)){
				if(!$this->checkCouponCodeExistOrNot()) {
					$error_msg = "Wrong Redeem Code or Expired, Please check and try again";
					return redirect()->route('checkout.confirm')->with('error', $error_msg);
				}
			}
			
			if(empty($order_id))
				$order = $this->saveCartOrder($request);
			else
				$order = Order::where('id',$order_id)->first();
			
			if($data['payBySaveCard'] == 'new'){
				$cardId = null;
			}else{
				$cardId = $data['payBySaveCard'];
			}
			
			$payment = Payment::pay360($data, $order, $cardId);
			
			if($payment['status']){
				$order->update([
					'order_status_id' => 2,
					'payment_method' => 'credit-card',
					'payment_card_id' => $payment['payment_card_id'],
					'transaction_id' => $payment['transactionId'],
				]);
				
				return redirect()->route('checkout.thankYou',$order->id)->with(['status' => $payment['message']]);
			}else{
				return redirect()->route('checkout.payment',$order->id)->with(['error' => $payment['message']])->withInput($request->all());
			}
		}
	}
	
	public function thankYou(Request $request, $order_id){
		$order = Order::where('orders.id',$order_id)
		->with(['orderDetail','customer','orderproducts'])
		->select('orders.*','card.pay360_token','card.number_filtered','card.card_type')
		->leftJoin('customers_pay360_tokens as card','card.id','=','orders.payment_card_id')
		->first();
		
		if(empty($order) || $order->count() < 1){
			return redirect()->route('accounts.orders')->with(['error' => 'Order not exist.']);
		}else if($order->customer->id != Auth()->User()->id){
			return redirect()->route('accounts.orders')->with(['error' => 'This order not valid for you.']);
		}else if(!session('status') && $order->order_status_id > 1){
			return redirect()->route('accounts.orders')->with(['error' => 'Payment already done on this order.']);
		}
		
		return view('front.checkout.checkout-thank-you',compact('order','order_id'));
	}
	
	public function testIndex(Request $request)
    {
        $products = $this->cartRepo->getCartItems();
        $customer = $request->user();
        
        $cart_total = $this->cartRepo->getSubTotal();
        
        
       $default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
       if(isset(Auth()->User()->id)) {
           
        $customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
        
         if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {
             
              $default_minimum_order = $customer_minimum_order->minimum_order;
              
         } 
        
       }
       
       if(str_replace(",", "", $cart_total) < $default_minimum_order)
       {
           $msg = "Your total is less than £".$default_minimum_order.". Please add some more products to your basket then return to the checkout";
           
           return redirect()->route('cart.index')->with('error', $msg);
           
       }
      
        
        $rates = null;
        $shipment_object_id = null;

        if (env('ACTIVATE_SHIPPING') == 1) {
            $shipment = $this->createShippingProcess($customer, $products);
            if (!is_null($shipment)) {
                $shipment_object_id = $shipment->object_id;
                $rates = $shipment->rates;
            }
        }

        // Get payment gateways
        $paymentGateways = collect(explode(',', config('payees.name')))->transform(function ($name) {
            return config($name);
        })->all();
        
       //echo "<pre>"; print_r($paymentGateways); exit;

        //$billingAddress = $customer->addresses()->first();
        //echo "<pre>";
       // print_r($customer->addresses()->get());
       // exit;
        
        $default_address_id = DB::table('customers')->select('default_address_id')->where("id", $customer->id)->first();
        
        $total_address = DB::table('addresses')
                        ->where('customer_id', auth()->user()->id)
                        ->WhereNull('deleted_at')
                        ->count();
        
        $states = DB::table('states')->select('state')->get();
        
        //echo "<pre>";
        //print_r($customer); exit;
        
        if($customer->customers_payment_allowed != '') {
            $paymentGateways = array();
            
            foreach(explode(",", $customer->customers_payment_allowed) as $key=>$customers_payment_allowed) {
                $paymentGateways[$key]['name'] = $customers_payment_allowed;
            }
            
        }
        
	//find delivery dates
	$delivery_details = Generalfnv::getDeliverydates();
       
	return view('front.checkout-test', [
            'customer' => $customer,
            //'billingAddress' => $billingAddress,
            'addresses' => $customer->addresses()->get(),
            'default_address_id' => $default_address_id->default_address_id,
            'total_address' => $total_address,
            'products' => $this->cartRepo->getCartItems(),
            'subtotal' => $this->cartRepo->getSubTotal(),
            'states' => $states,
            'tax' => $this->cartRepo->getTax(),
            'total' => $this->cartRepo->getTotal(2),
            'payments' => $paymentGateways,
	    'cartItems' => $this->cartRepo->getCartItemsTransformed(),
            'shipment_object_id' => $shipment_object_id,
            'delivery_date_total_days' => $delivery_details['delivery_date_total_days'],
	    'all_holidays' => $delivery_details['all_holiday_dates']
        ]);
    }
    
    
     /**
     * checkout confirm page.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function confirmcheckout(CheckoutCustomerRequest $request, $customerid)
    {
        
        $data = $request->except('_method', '_token');
        
       // Session::put('discount_coupon_amount', $discount_coupon_amount);
        
       $cart_total = $this->cartRepo->getSubTotal();
                
        $products = $this->cartRepo->getCartItems();
        
        $customer = $request->user();
        
       $default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
       if(isset(Auth()->User()->id)) {
           
        $customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
        
         if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {
             
              $default_minimum_order = $customer_minimum_order->minimum_order;
              
         } 
        
       }
       
       if(str_replace(",", "", $cart_total) < $default_minimum_order)
       {
           $msg = "You need to meet our minimum order of £ ".$default_minimum_order;
           
           return redirect()->route('checkout.index')->with('error', $msg);
           
       }
       
       //echo $customerid; exit;
        
       
       $billingAddress = DB::table('addresses')->where('id', $data['billing_address'])->first();
               
       $deliveryAddress = DB::table('addresses')->where('id', $data['delivery_address'])->first();
       
        Session::put('orderbasicinfo', $data);  
        Session::put('shipdate', $data['shipdate']);
        Session::put('hour', $data['hour']);
        Session::put('minute', $data['minute']);
        Session::put('comments', $data['comments']);
        Session::put('delivery_procedure', $data['delivery_procedure']);
        Session::put('billing_address', $data['billing_address']);
        Session::put('delivery_address', $data['delivery_address']);
        Session::put('payment_name', $data['payment_name']);
     
      //apply discount coupon
        if(isset($data['submit_coupon'])) {
            
            if(!isset($data['redeem_code']) && empty($data['redeem_code'])) {
                $error_msg = "Please Enter Redeem Code";
                return redirect()->route('checkout.index')->with('error', $error_msg);
            }
            
            $product_ids = '';
            $category_ids = '';

            $coupon_details = DB::table('coupons')->where('coupon_code', $data['redeem_code'])->first();
            
            if($coupon_details->restrict_to_products != '' || $coupon_details->restrict_to_categories != '') {
            
                foreach($products as $product){
                   $category_obj = DB::table('category_product')->select('category_id')->where('product_id', $product->id)->first();
                   $category_ids .= $category_obj->category_id;
                   $product_ids .= $product->id.",";
                }
            
            }

            $category_ids = rtrim($category_ids, ",");
            $product_ids = rtrim($product_ids, ",");
            
            $check_coupon = Generalfnv::verifyCouponcode($data['redeem_code'], $category_ids, $product_ids, $cart_total);
            
            if($check_coupon) {
                
                $discount_coupon_amount = Session::get('discount_coupon_amount');
               
                $msg = "Congratulations, you have redeemed £ ".number_format($discount_coupon_amount, 2);
                        return redirect()->route('checkout.index')->with('message', $msg);
                
            } else {
                $error_msg = "Wrong Redeem Code or Expired, Please check and try again";
                return redirect()->route('checkout.index')->with('error', $error_msg);
            }
            
         }
         //end discount coupon functionality
     
        if(Session::get('discount_coupon_amount') > 0) {
           $discount_coupon_amount = Session::get('discount_coupon_amount');
        } else {
            $discount_coupon_amount = 0;
        }
     
     
    return view('front.checkout.checkoutconfirm', [
            'customer' => $customer,
            'billingAddress' => $billingAddress,
            'deliveryAddress' => $deliveryAddress,
            'products' => $this->cartRepo->getCartItems(),
            'tax' => $this->cartRepo->getTax(),
            'discount_coupon_amount' => $discount_coupon_amount,
            'subtotal' => $this->cartRepo->getSubTotal(),
            'total' => $this->cartRepo->getTotal(2,'',$discount_coupon_amount),
            'cartItems' => $this->cartRepo->getCartItemsTransformed(),
            'infos' => $data
        ]);
        
  }
  
  /**
     * Checkout the items
     *
     * @param CartCheckoutRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @codeCoverageIgnore
     * @throws \App\Shop\Customers\Exceptions\CustomerPaymentChargingErrorException
     */
    public function store(CartCheckoutRequest $request)
    {
        $shippingFee = 0;

        switch ($request->input('payment')) {
            case 'paypal':
                return $this->payPal->process($shippingFee, $request);
                break;
            case 'stripe':

                $details = [
                    'description' => 'Stripe payment',
                    'metadata' => $this->cartRepo->getCartItems()->all()
                ];

                $customer = $this->customerRepo->findCustomerById(auth()->id());
                $customerRepo = new CustomerRepository($customer);
                $customerRepo->charge($this->cartRepo->getTotal(2, $shippingFee), $details);
                break;
            default:
        }
    }
    
    /**
     * Checkout the items
     *
     * @param CartCheckoutRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @codeCoverageIgnore
     * @throws \App\Shop\Customers\Exceptions\CustomerPaymentChargingErrorException
     */
    public function addorder(Request $request, $customerid)
    {
        
        $orderbasicinfo = Session::get('orderbasicinfo');
        $data = $request->all();
        
        //echo "<pre>";
       // print_r($data);
       // exit;
        
        $checkoutRepo = new CheckoutRepository;
        //$orderStatusRepo = new OrderStatusRepository(new OrderStatus);
        //$os = $orderStatusRepo->findByName('ordered');
        
        //echo "<pre>"; print_r($os); exit;
        
        $sub_total = str_replace(",", "", $this->cartRepo->getSubTotal());
        $products = $this->cartRepo->getCartItems();
        
    //check discount coupon
        if(!empty(Session::get('coupon_code'))) {
            
            $product_ids = '';
            $category_ids = '';

            $coupon_details = DB::table('coupons')->where('coupon_code', Session::get('coupon_code'))->first();
            
            if($coupon_details->restrict_to_products != '' || $coupon_details->restrict_to_categories != '') {
            
                foreach($products as $product){
                   $category_obj = DB::table('category_product')->select('category_id')->where('product_id', $product->id)->first();
                   $category_ids .= $category_obj->category_id;
                   $product_ids .= $product->id.",";
                }
            
            }

            $category_ids = rtrim($category_ids, ",");
            $product_ids = rtrim($product_ids, ",");

                $check_coupon = Generalfnv::verifyCouponcode(Session::get('coupon_code'), $category_ids, $product_ids, $sub_total);

                 if(!$check_coupon) {

                     $error_msg = "Wrong Redeem Code or Expired, Please check and try again";
                     return redirect()->route('checkout.index')->with('error', $error_msg);

                 } 
            }
        //end discount coupon code
        
        if(Session::get('discount_coupon_amount') > 0) {
            $discount_coupon_amount = Session::get('discount_coupon_amount');
        } else {
            $discount_coupon_amount = 0;
        }
        
        
        $total_p = $sub_total - $discount_coupon_amount;

        $order = $checkoutRepo->buildCheckoutItems([
            'reference' => Uuid::uuid4()->toString(),
            'courier_id' => 1, // @deprecated
            'customer_id' => $request->user()->id,
            'order_status_id' => 2,
            'payment_method' => $orderbasicinfo['payment_name'],
            'total_products' => str_replace(",", "", $this->cartRepo->getSubTotal()),
            'total' => $total_p,
            'customer_discount' => $discount_coupon_amount,
            'sub_total' => str_replace(",", "", $this->cartRepo->getSubTotal()),
            'total_paid' => 0,
            'tax' => $this->cartRepo->getTax()
        ]);
        
        
        //get billing address
    $billing_address = DB::table('addresses')->where('id', $orderbasicinfo['billing_address'])->first();
    $shipping_address = DB::table('addresses')->where('id', $orderbasicinfo['delivery_address'])->first();
    $customer_details = DB::table('customers')->leftJoin('addresses', 'customers.default_address_id', '=', 'addresses.id')
                        ->select('customers.first_name', 'customers.last_name', 'customers.email', 'customers.tel_num','addresses.company_name','addresses.street_address','addresses.address_line_2'
                                  ,'addresses.post_code','addresses.city','addresses.county_state','addresses.country_id','addresses.customer_id')
                        ->where('customers.id', $customerid)->first();
    
    $shipping_add_name = $shipping_address->first_name." ".$shipping_address->last_name;
    $billing_add_name = $billing_address->first_name." ".$billing_address->last_name;
    $country = 'United Kingdom';
    $earliest_delivery = '';
    if (isset($orderbasicinfo['hour']) && isset($orderbasicinfo['minute'])) $earliest_delivery = (intval($orderbasicinfo['hour'])*60+intval($orderbasicinfo['minute']))*60;
    
    $shipdate = date("Y-m-d H:i:s", strtotime($orderbasicinfo['shipdate']));
        
    $update_arr = array('order_id'=>$order->id,'first_name'=>$customer_details->first_name, 'last_name'=>$customer_details->last_name, 'email'=>$customer_details->email,'tel_num'=>$customer_details->tel_num,'company_name'=>$customer_details->company_name,'street_address'=>$customer_details->street_address,'address_line_2'=>$customer_details->address_line_2,'post_code'=>$customer_details->post_code,
                        'city'=>$customer_details->city,'country_state'=>$customer_details->county_state,'country'=>$country,'shipping_add_name'=>$shipping_add_name,'shipping_add_company'=>$shipping_address->company_name,'shipping_street_address'=>$shipping_address->street_address,
                        'shipping_address_line2'=>$shipping_address->address_line_2,'shipping_city'=>$shipping_address->city,'shipping_state'=>$shipping_address->county_state,'shipping_post_code'=>$shipping_address->post_code,'shipping_country'=>$country,
                        'billing_name'=>$billing_add_name,'billing_company'=>$billing_address->company_name,'billing_street_address'=>$billing_address->street_address,'billing_address_line_2'=>$billing_address->address_line_2,'billing_city'=>$billing_address->city,'billing_postcode'=>$billing_address->post_code,'billing_state'=>$billing_address->county_state,'billing_country'=>$country,
                        'earliest_delivery'=>$earliest_delivery,'comment'=>$orderbasicinfo['comments'],'delivery_procedure'=>$orderbasicinfo['delivery_procedure'],'shipdate'=>$shipdate,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s'));
    
    DB::table('order_details')->insert($update_arr);
    
    //order amount
    
    $order_amount = DB::table('orders')->select('total')->where('id', $order->id)->first();
    
   
      /*
        exit;

        $shipment = Shippo_Shipment::retrieve($this->shipmentObjId);

        $details = [
            'shipment' => [
                'address_to' => json_decode($shipment->address_to, true),
                'address_from' => json_decode($shipment->address_from, true),
                'parcels' => [json_decode($shipment->parcels[0], true)]
            ],
            'carrier_account' => $this->carrier->carrier_account,
            'servicelevel_token' => $this->carrier->servicelevel->token
        ];

        $transaction = Shippo_Transaction::create($details);

        if ($transaction['status'] != 'SUCCESS'){
            Log::error($transaction['messages']);
            return redirect()->route('checkout.index')->with('error', 'There is an error in the shipment details. Check logs.');
        }

        $orderRepo = new OrderRepository($order);
        $orderRepo->updateOrder([
            'courier' => $this->carrier->provider,
            'label_url' => $transaction['label_url'],
            'tracking_number' => $transaction['tracking_number']
        ]);
         * 
         */
    //echo $orderbasicinfo['payment_name'];
         if($orderbasicinfo['payment_name'] == 'secpay') {
             
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
                <input type="hidden" name="amount" value="<?php echo $order_amount->total; ?>">
                <input type="hidden" name="bill_name" value="<?php echo $data['bill_name']; ?>">
                <input type="hidden" name="bill_addr_1" value="<?php echo $data['bill_addr_1']; ?>">
                <input type="hidden" name="bill_addr_2" value="<?php echo $data['bill_addr_2']; ?>">
                <input type="hidden" name="bill_city" value="<?php echo $data['bill_city']; ?>">
                <input type="hidden" name="bill_state" value="<?php echo $data['bill_state']; ?>">
                <input type="hidden" name="bill_post_code" value="<?php echo $data['bill_post_code']; ?>">
                <input type="hidden" name="bill_country" value="<?php echo $data['bill_country']; ?>">
                <input type="hidden" name="bill_tel" value="<?php echo $data['bill_tel']; ?>">
                <input type="hidden" name="bill_email" value="<?php echo $data['bill_email']; ?>">
                <input type="hidden" name="ship_name" value="<?php echo $data['ship_name']; ?>">
                <input type="hidden" name="ship_addr_1" value="<?php echo $data['ship_addr_1']; ?>">
                <input type="hidden" name="ship_addr_2" value="<?php echo $data['ship_addr_2']; ?>">
                <input type="hidden" name="ship_city" value="<?php echo $data['ship_city']; ?>">
                <input type="hidden" name="ship_state" value="<?php echo $data['ship_state']; ?>">
                <input type="hidden" name="ship_post_code" value="<?php echo $data['ship_post_code']; ?>">
                <input type="hidden" name="ship_country" value="United Kingdom">
                <input type="hidden" name="currency" value="GBP">
                <input type="hidden" name="template" value="http://www.secpay.com/users/fruitf01/template_fnv.html">
                <input type="hidden" name="callback" value="<?php echo config('shop.url'); ?>/checkout/updatestatus/<?php echo $order->id; ?>;<?php echo config('shop.url'); ?>/checkout/updatestatus/<?php echo $order->id; ?>">
                <input type="hidden" name="osCsid" value="ef0502b22ff1170c297c65a8913ff7eb">
                <input type="hidden" name="options" value="test_status=true,dups=false,cb_post=true,cb_flds=osCsid">
            </form>
            </body> 
            </html>
             
        <?php    
        exit();
        }
        
        if(Session::get('coupon_id') != '' &&  $orderbasicinfo['payment_name'] != 'secpay') {  
            $coupon_track_data = array('coupon_id' => Session::get('coupon_id'), 'customer_id' => $request->user()->id, 'redeem_date' => date('Y-m-d H:i:s'), 'redeem_ip' => $_SERVER['REMOTE_ADDR'], 'order_id' => $order->id);
            DB::table('coupon_redeem_track')->insert($coupon_track_data);
          }
        
      Cart::destroy();
        
        DB::table('customers_temp_basket')->where('customers_id',$request->user()->id)->delete();
        
        Session::put('discount_coupon_amount', '');
        Session::put('coupon_id', '');
        Session::put('coupon_code', '');
        Session::put('orderbasicinfo', '');
        Session::put('shipdate', '');
        Session::put('hour', '');
        Session::put('minute', '');
        Session::put('comments', '');
        Session::put('delivery_procedure', '');
        Session::put('billing_address', '');
        Session::put('delivery_address', '');
        Session::put('payment_name', '');

        //return redirect()->route('accounts', ['tab' => 'orders'])->with('message', 'Order successful!');
        
        return redirect()->route('checkout.success', $order->id)->with('message', 'Order successful!');
        
        
    }
    
    
    public function updatestatus(Request $request, $order_id) {
        
        $data = $request->all();
        
        $update_arr = array('total_paid' => $data['amount'], 'tracking_number' => $data['trans_id'], 'ip' => $data['ip']);
        
        DB::table('orders')->where('id',$order_id)->update($update_arr);
        
        Cart::destroy();
        
        DB::table('customers_temp_basket')->where('customers_id',Auth()->User()->id)->delete();
        
        if(Session::get('coupon_id') != '') {  
            $coupon_track_data = array('coupon_id' => Session::get('coupon_id'), 'customer_id' => Auth()->User()->id, 'redeem_date' => date('Y-m-d H:i:s'), 'redeem_ip' => $_SERVER['REMOTE_ADDR'], 'order_id' => $order_id);
            DB::table('coupon_redeem_track')->insert($coupon_track_data);
          }
        
        
        Session::put('discount_coupon_amount', '');
        Session::put('coupon_id', '');
        Session::put('coupon_code', '');
        Session::put('orderbasicinfo', '');
        Session::put('shipdate', '');
        Session::put('hour', '');
        Session::put('minute', '');
        Session::put('comments', '');
        Session::put('delivery_procedure', '');
        Session::put('billing_address', '');
        Session::put('delivery_address', '');
        Session::put('payment_name', '');

        //return redirect()->route('accounts', ['tab' => 'orders'])->with('message', 'Order successful!');
        
        return redirect()->route('checkout.success', $order_id)->with('message', 'Order successful!');
        
    }
    
    /**
     * order success page
     *
     * @param PayPalCheckoutExecutionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkoutsuccess(Request $request, $order_id) {
        
        $order_details = DB::table('orders')
                                    ->leftJoin('order_details', 'order_details.order_id', '=', 'orders.id')
                                    ->leftJoin('order_product', 'order_product.order_id', '=', 'orders.id')
                                    ->select('orders.id', 'orders.customer_id', 'orders.payment_method','orders.customer_discount','orders.sub_total', 'orders.created_at', 'order_details.billing_name', 'order_details.billing_company', 'order_details.billing_street_address', 'order_details.billing_address_line_2', 'order_details.billing_city'
                                                , 'order_details.billing_postcode', 'order_details.billing_state', 'order_details.billing_country', 'order_details.shipping_add_name', 'order_details.shipping_add_company', 'order_details.shipping_street_address', 'order_details.shipping_address_line2', 'order_details.shipping_city'
                                                , 'order_details.shipping_state', 'order_details.shipping_post_code', 'order_details.shipping_country', 'order_details.shipping_tel_num', 'order_details.shipping_email', 'order_product.product_name', 'order_product.quantity', 'order_product.product_price')
                                    ->where('orders.id', $order_id)->first();
        
        $order_products = DB::table('orders')
                                    ->leftJoin('order_product', 'order_product.order_id', '=', 'orders.id')
                                    ->select('orders.id', 'order_product.product_name', 'order_product.quantity', 'order_product.type', 'order_product.product_price')
                                    ->where('orders.id', $order_id)->get();
        
        $total_discount = Session::get('discount_coupon_amount');
        
        return view('front.checkout.checkoutsuccess', [
            'order_details' => $order_details, 'order_products' => $order_products, 'total_discount' => $total_discount, 'customer_id' => Auth()->User()->id
        ]);
        
    }

    /**
     * Execute the PayPal payment
     *
     * @param PayPalCheckoutExecutionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function executePayPalPayment(PayPalCheckoutExecutionRequest $request)
    {
        try {
            $this->payPal->execute($request);
            $this->cartRepo->clearCart();

            return redirect()->route('checkout.success');
        } catch (PayPalConnectionException $e) {
            throw new PaypalRequestError($e->getData());
        } catch (Exception $e) {
            throw new PaypalRequestError($e->getMessage());
        }
    }

    /**
     * @param StripeExecutionRequest $request
     * @return \Stripe\Charge
     */
    public function charge(StripeExecutionRequest $request)
    {
        try {
            $customer = $this->customerRepo->findCustomerById(auth()->id());
            $stripeRepo = new StripeRepository($customer);

            $stripeRepo->execute(
                $request->all(),
                Cart::total(),
                Cart::tax()
            );
            return redirect()->route('checkout.success')->with('message', 'Stripe payment successful!');
        } catch (StripeChargingErrorException $e) {
            Log::info($e->getMessage());
            return redirect()->route('checkout.index')->with('error', 'There is a problem processing your request.');
        }
    }

    /**
     * Cancel page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cancel(Request $request)
    {
        return view('front.checkout-cancel', ['data' => $request->all()]);
    }

    /**
     * Success page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success()
    {
        return view('front.checkout-success');
    }

    /**
     * @param Customer $customer
     * @param Collection $products
     *
     * @return mixed
     */
    private function createShippingProcess(Customer $customer, Collection $products)
    {
        $customerRepo = new CustomerRepository($customer);

        if ($customerRepo->findAddresses()->count() > 0 && $products->count() > 0) {

            $this->shippingRepo->setPickupAddress();
            $deliveryAddress = $customerRepo->findAddresses()->first();
            $this->shippingRepo->setDeliveryAddress($deliveryAddress);
            $this->shippingRepo->readyParcel($this->cartRepo->getCartItems());

            return $this->shippingRepo->readyShipment();
        }
    }

	public function checkCouponCodeExistOrNot(){
		$products = $this->cartRepo->getCartItems();
		$cart_total = $this->cartRepo->getSubTotal();
		
		$product_ids = '';
		$category_ids = '';

		$coupon_details = DB::table('coupons')->where('coupon_code', session('coupon_code'))->first();
		
		if((isset($coupon_details->restrict_to_products) && $coupon_details->restrict_to_products != '') || (isset($coupon_details->restrict_to_categories) && $coupon_details->restrict_to_categories != '')) {
		
			foreach($products as $product){
			   $category_obj = DB::table('category_product')->select('category_id')->where('product_id', $product->id)->first();
			   $category_ids .= $category_obj->category_id.",";
			   $product_ids .= $product->id.",";
			}
		}

		$category_ids = rtrim($category_ids, ",");
		$product_ids = rtrim($product_ids, ",");
		
		$check_coupon = Generalfnv::verifyCouponcode(session('coupon_code'), $category_ids, $product_ids, $cart_total);
		
		return $check_coupon;
	}
}
