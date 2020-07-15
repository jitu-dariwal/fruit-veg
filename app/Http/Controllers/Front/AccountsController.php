<?php

namespace App\Http\Controllers\Front;

use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Requests\UpdateCustomerRequest;
use App\Shop\Customers\Requests\CustomerCardRequest;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Orders\Order;
use App\Shop\Orders\Transformers\OrderTransformable;
use Illuminate\Support\Facades\Hash;
use App\Shop\Categories\Category;
use App\Http\Controllers\Admin\Products;
use App\Shop\Tools\MarkuppriceTrait;
use Illuminate\Http\Request;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Helper\Payment;
use App\Helper\Generalfnv;
use DB;
use PDF;


class AccountsController extends Controller
{
    use OrderTransformable;
    use MarkuppriceTrait;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;
    
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepo;
	
	/**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var CourierRepositoryInterface
     */
    private $courierRepo;
    
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepo;
    
	/**
	* @var CategoryRepositoryInterface
	*/
	private $category;
	
	private $default_minimum_order;
	// private $product;
    /**
     * AccountsController constructor.
     *
     * @param CourierRepositoryInterface $courierRepository
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CourierRepositoryInterface $courierRepository,
        CartRepositoryInterface $cartRepository,
		ProductRepositoryInterface $productRepository,
        CustomerRepositoryInterface $customerRepository,
        OrderRepositoryInterface $orderRepository,
        Category $category
    ) {
        $this->customerRepo = $customerRepository;
        $this->cartRepo = $cartRepository;
		$this->productRepo = $productRepository;
        $this->courierRepo = $courierRepository;
        $this->orderRepo = $orderRepository;
        $this->category = $category;
		
		
		$this->default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
       if(isset(Auth()->User()->id)) {
           
        $customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
        

         if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {

             $this->default_minimum_order = $customer_minimum_order->minimum_order;
              
         } 
        
       }
		
		
    }
	
    public function index()
    {
		$order = DB::table('orders')
                    ->leftJoin('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
                    ->select('orders.id', 'orders.created_at', 'orders.total', 'orders.order_status_id', 'order_statuses.name')
                    ->orderBy('orders.created_at', 'desc')
                    ->where([
						'orders.order_status_id' => config('constants.order_delivered_id'),
						'orders.customer_id' => \Auth::user()->id
					])
					->first();
		
		//To show the cart items on right sidebar
		$total_price = 0;
		
		$cartItems = $this->cartRepo->getCartItemsTransformed();
        
		$cart_total = $this->cartRepo->getSubTotal();
        
        $total_products_price = str_replace(",", "", $cart_total);
	    $total_price = str_replace(",", "", $cart_total);
        
       	return view('front.account.accounts', compact(['order']))->with(['cartItems'=> $cartItems, 'total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $this->default_minimum_order]);
    }
	
	/**
     * customer account details.
	 *@param $id - customer id
     *
    */
	public function accountdetails($id) {
		$customer = $this->customerRepo->findCustomerById(auth()->user()->id);
		
		//To show the cart items on right sidebar
		$total_price = 0;
		
		$cartItems = $this->cartRepo->getCartItemsTransformed();
        
		$cart_total = $this->cartRepo->getSubTotal();
        
        $total_products_price = str_replace(",", "", $cart_total);
	    $total_price = str_replace(",", "", $cart_total);
		
		return view('front.account.accountdetails', compact(['customer']))->with(['cartItems'=> $cartItems, 'total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $this->default_minimum_order]);
	}
	
    /**
     * update account.
     *
    */
	public function update(UpdateCustomerRequest $request, $id) {
		if($request->isMethod('post')){
			$customer = $this->customerRepo->findCustomerById($id);
			
			//pr($customer);die;
			
			$update = new CustomerRepository($customer);
			
			if($request->input('form_type') == 'profile'){
				$data = $request->except('_method', '_token', 'password','form_type');
				
				DB::table('addresses')->where('id', $customer->default_address_id)->update(['first_name' => $data['first_name'], 'last_name' => $data['last_name'] ]);
					
				$update->updateCustomer($data);
				
			}else if($request->input('form_type') == 'password'){
				if ($request->has('password') && !empty($request->input('password'))) {
					$data = $request->except('_method', '_token', 'password','form_type');
					$data['password'] = Hash::make($request->input('password'));
					$update->updateCustomer($data);
				}
			}
			 
			return redirect()->route('accounts.accountdetails', $id)->with('status', 'Update successful');
        }
    }
    
	/**
     * customer address book details.
	 *@param $id - customer id
     *
    */
	public function addressbook(){
		$addresses = DB::table('addresses')->where('addresses.customer_id', auth()->user()->id)->leftJoin('countries','countries.id','=','addresses.country_id')->WhereNull('addresses.deleted_at')->select('addresses.*','countries.name as country_name')->get();
		
		//pr($addresses->toArray());die;
		//pr(auth()->user());die;
		
		$billingAdd = [];
		$billingAdd['first_name'] = auth()->user()->first_name;
		$billingAdd['last_name'] = auth()->user()->last_name;
		$billingAdd['street_address'] = auth()->user()->invoice_street_address;
		$billingAdd['address_line_2'] = auth()->user()->invoice_suburb;
		$billingAdd['city'] = auth()->user()->invoice_city;
		$billingAdd['county_state'] = auth()->user()->invoice_state;
		$billingAdd['post_code'] = auth()->user()->invoice_postcode;
		//pr($billingAdd);die;
		
		//To show the cart items on right sidebar
		$total_price = 0;
		
		$cartItems = $this->cartRepo->getCartItemsTransformed();
        
		$cart_total = $this->cartRepo->getSubTotal();
        
        $total_products_price = str_replace(",", "", $cart_total);
	    $total_price = str_replace(",", "", $cart_total);
		
		return view('front.account.accountaddressbook', compact(['addresses','billingAdd']))->with(['cartItems'=> $cartItems, 'total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $this->default_minimum_order]);
	}
	
	/**
     * customer all orders list.
	 *@param $id - customer id
     *
    */
	public function orders() {
		$orders = Order::where([
						'orders.customer_id' => \Auth::user()->id
					])
                    ->leftJoin('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
                    ->select('orders.id', 'orders.created_at',  'orders.order_status_id','orders.discounts','orders.shipping_charges','orders.sub_total','orders.total','orders.coupon_code','orders.customer_discount', 'order_statuses.name')
					->with(['orderproducts' => function($q){
						$q->select('id','order_id','product_id','quantity','product_name','product_price','final_price');
					},
					'orderDetail' => function($q1){
						$q1->select('id','order_id','first_name','last_name','shipping_add_name','shipping_add_company','shipping_street_address','shipping_address_line2','shipping_city','shipping_state','shipping_post_code','shipping_country','shipping_tel_num','shipping_email');
					}])
                    ->orderBy('orders.created_at', 'desc')
                    ->get();
					
		//To show the cart items on right sidebar
		$total_price = 0;
		
		$cartItems = $this->cartRepo->getCartItemsTransformed();
        
		$cart_total = $this->cartRepo->getSubTotal();
        
        $total_products_price = str_replace(",", "", $cart_total);
	    $total_price = str_replace(",", "", $cart_total);
		
		return view('front.account.orders', compact(['orders']))->with(['cartItems'=> $cartItems, 'total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $this->default_minimum_order]);
	}
	
    /**
     * show order details
     * param order id
    */
    public function orderdetail($order_id)
    {
		$order = Order::where([
			'orders.customer_id' => \Auth::user()->id,
			'orders.id' => $order_id
		])
		->leftJoin('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
		->select('orders.id', 'orders.created_at',  'orders.order_status_id','orders.customer_discount','orders.shipping_charges','orders.sub_total','orders.total','orders.shipping_method','orders.payment_method', 'order_statuses.name as statusName')
		->with(['orderproducts' => function($q){
			$q->select('id','order_id','product_id','quantity','product_name','product_price','final_price');
		},
		'orderDetail','order_status_historys' => function($q1){
			$q1->leftJoin('order_statuses as os', 'os.id', '=', 'order_status_histories.order_status_id');
			$q1->select('order_status_histories.*','os.name as statusName');
			$q1->orderBy('order_status_histories.created_at','ASC')->groupBy('order_status_histories.order_status_id');
		},
		])
		->orderBy('orders.created_at', 'desc')
		->first();
        
		//pr($order->toArray());die;
		
		//To show the cart items on right sidebar
		$total_price = 0;
		
		$cartItems = $this->cartRepo->getCartItemsTransformed();
        
		$cart_total = $this->cartRepo->getSubTotal();
        
        $total_products_price = str_replace(",", "", $cart_total);
	    $total_price = str_replace(",", "", $cart_total);
		 
		return view('front.account.orderdetail', compact(['order']))->with(['cartItems'=> $cartItems, 'total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $this->default_minimum_order]);
    }
    
	/**
     * customer order print and download the order details.
	 *@param $id - customer id,$type - download or print option
     *
    */
	public function orderprint($id = null,$type='print'){
		$order = Order::where([
			'orders.customer_id' => \Auth::user()->id,
			'orders.id' => $id
		])
		->leftJoin('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
		->select('orders.id', 'orders.created_at',  'orders.order_status_id','orders.customer_discount','orders.shipping_charges','orders.sub_total','orders.total', 'order_statuses.name')
		->with(['orderproducts' => function($q){
			$q->select('id','order_id','product_id','quantity','product_name','product_price','final_price');
		},
		'orderDetail'])
		->orderBy('orders.created_at', 'desc')
		->first();
		
		if($id != null && $type == 'print'){
			return view('front.account.orderprint', compact(['order','type']));
		}else if($id != null && $type == 'download'){
			$pdf = PDF::loadView('front.account.orderprint', compact(['order','type']));
			
			$pdf_file = "Order#".$order->id;
			
			return $pdf->stream($pdf_file.'.pdf');
		}
	}
	
	/**
     * customer account statement details.
	 *@param $id - customer id
     *
    */
	public function accountstatements($id) {
		
		$customer = $this->customerRepo->findCustomerById(auth()->user()->id);
		
		$orders = Order::where(['orders.customer_id' => \Auth::user()->id])
				->leftJoin('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
				->select('orders.id', 'orders.created_at',  'orders.order_status_id','orders.discounts','orders.shipping_charges','orders.sub_total','orders.total', 'order_statuses.name')
				->orderBy('orders.created_at', 'desc')
				->get();
		//pr($orders->toArray());die;
		//To show the cart items on right sidebar
		$total_price = 0;
		
		$cartItems = $this->cartRepo->getCartItemsTransformed();
		
		$cart_total = $this->cartRepo->getSubTotal();
		
		$total_products_price = str_replace(",", "", $cart_total);
		$total_price = str_replace(",", "", $cart_total);
	
		return view('front.account.accountstatements', ['customer' => $customer,'orders' => $orders])->with(['cartItems'=> $cartItems, 'total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $this->default_minimum_order]);
	}
	
	/**
     * customer payment details.
	 *@param $id - customer id
     *
    */
	public function accountpayments($id) {
		
		$customer = $this->customerRepo->findCustomerById(auth()->user()->id);
		
		$cards = DB::table('customers_pay360_tokens')->where('customers_id', $customer->id)->get();
		
		//To show the cart items on right sidebar
		$total_price = 0;
		
		$cartItems = $this->cartRepo->getCartItemsTransformed();
		
		$cart_total = $this->cartRepo->getSubTotal();
		
		$total_products_price = str_replace(",", "", $cart_total);
		$total_price = str_replace(",", "", $cart_total);
		
		return view('front.account.accountpayments', ['customer' => $customer,'cards' => $cards])->with(['cartItems'=> $cartItems, 'total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $this->default_minimum_order]);
			
	}
    
	/**
     * customer payment card add/edit form show.
	 *@param $id - customer id
     *
    */
	public function accountpaymentsaddedit($id = null) {
		$customer = $this->customerRepo->findCustomerById(auth()->user()->id);
		
		$card = [];
		if($id != null)
			$card = DB::table('customers_pay360_tokens')->where(['customers_id' => $customer->id, 'id' => $id])->first();
		
		//To show the cart items on right sidebar
		$total_price = 0;
		
		$cartItems = $this->cartRepo->getCartItemsTransformed();
		
		$cart_total = $this->cartRepo->getSubTotal();
		
		$total_products_price = str_replace(",", "", $cart_total);
		$total_price = str_replace(",", "", $cart_total);
		
		$current_year = date('Y');
		
		return view('front.account.accountpayment_add_edit', ['id' => $id, 'customer' => $customer,'card' => $card,'current_year' => $current_year])->with(['cartItems'=> $cartItems, 'total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $this->default_minimum_order]);
			
	}
    
    /**
     * update card.
     *
    */
	public function savepaymentsaddedit(CustomerCardRequest $request, $id = null) {
        $details = $request->all();
		
		$name 			= $details['name'];
		$card_number 	= $details['number'];
		$number_filtered 	= Payment::cc_mask($card_number);
		$exp_month 		= sprintf('%02d', $details['exp_month']);
		$exp_year 		= substr($details['exp_year'],2,2);
		$expiryDate = $exp_month.$exp_year;
		
		//$cardDetails = Payment::getCardDetails($card_number);
		//$cardDetails = Payment::getCardDetailsFromServer($card_number);
		//$cardDetails = Payment::getCardDetailsFromServer('1000350000000007');
        //pr($cardDetails);die;
		
		if(empty($id)){
			$dataArr = [];
			$dataArr['customers_id'] = Auth()->User()->id;
			$dataArr['card_holder_name'] = $name;
			$dataArr['pay360_token'] = $card_number;
			$dataArr['number_filtered'] = $number_filtered;
			$dataArr['card_type'] = null;
			$dataArr['expiry_date'] = $expiryDate;
			$dataArr['date_added'] = date('Y-m-d H:i:s');
		
			DB::table('customers_pay360_tokens')->insert($dataArr);
			
			return redirect()->route('accounts.accountpayments', auth()->user()->id)->with('status', 'Added card details successful');
		}else{
			DB::table('customers_pay360_tokens')->where(['id' => $id])->update([
				'card_holder_name' => $name,
				'number_filtered' => $number_filtered,
				//'card_type' => $pay360_result['paymentMethod']['card']['cardType'],
				'expiry_date' => $expiryDate,
			]);
			return redirect()->route('accounts.accountpayments', auth()->user()->id)->with('status', 'Update card details successful');
		}
    }
    
    /**
     * delete card .
     * param card id
    */
    public function deleteCard($card_id, Request $request) {
       
        $card = DB::table('customers_pay360_tokens')->where(['id' => $card_id, 'customers_id' => Auth()->user()->id])->delete();
		
		if($card){
			return redirect()->route('accounts.accountpayments', Auth()->user()->id)->with('status', 'Card deleted successful');
		}else{
			return redirect()->route('accounts.accountpayments', Auth()->user()->id)->with('error', 'Card not deleted, please try again.');
		}
    }
    	
    /**
     * update newsletter.
     *
    */
	public function updatenewsletter($id) {
        
        $customer = $this->customerRepo->findCustomerById($id);
        
        //echo "<pre>"; print_r($customer); exit;

        $update = new CustomerRepository($customer);
        $data = request()->except('_method', '_token');
        
        if(!isset($data['newsletter']) && empty($data['newsletter'])) {
            
            $data['newsletter'] = 0;
        }
        
        $update->updateCustomer($data);

        return redirect()->route('accounts', ['tab' => 'email_notification'])
            ->with('message1', 'Update successful');
        
    }
    
    /**
     * update newsletter.
     *
    */
	public function updateproductnotification($id) {
        
        $customer = $this->customerRepo->findCustomerById($id);
        
        //echo "<pre>"; print_r($customer); exit;

        $update = new CustomerRepository($customer);
        $data = request()->except('_method', '_token');
        
        if(!isset($data['global_product_notifications']) && empty($data['global_product_notifications'])) {
            
            $data['global_product_notifications'] = 0;
        }
        
       // echo "ok"; print_r($data); exit;
        
        $update->updateCustomer($data);

        
        return redirect()->route('accounts', ['tab' => 'email_notification'])
            ->with('message2', 'Update successful');
        
    }
    
    /**
     * product list by category.
     * param catid
    */
 /*   public function productslist() {
        
        $parent_catid = '';
        $catid = '';
        $product_name = '';
        //$data = request()->all();
        
        $data = request()->except('_method', '_token', 'submit');
        
       // echo "<pre>";
        
        //print_r($data); exit;
        
        if(isset($data['parentcatid'])) {
            $parent_catid = $data['parentcatid'];
        }
       
        if(isset($data['catid'])) {
            $catid = $data['catid'];
        }
        
        if(isset($data['product_name'])) {
            $product_name = $data['product_name'];
        }
        
        
        if(!isset(Auth()->user()->id) && empty(Auth()->user()->id)) {
            
            return redirect()->route('login');
        }
        
        $customer = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
        
        $categories = $this->category->listMyaccoutcategories();
        
        $catname = DB::table('categories')->select('name')->where('id', $catid)->first();
        
        $updated_price_with_markup = array();
        
        if($catid != '') {
            
            $products = $this->category->listCategoryproducts($catid, $product_name);
            
            //update markup charges as per group
            foreach ($products as $product) {
            
                $updated_price_with_markup[$product->id] = $this->product_price_with_markup($product->type, $product->price, $catid, Auth()->user()->id);
            }
            
            
        
        } else {
            
            //search products if parent tcategory and subcategory not selected
            $parent_categories = DB::table('categories')->select('id','name')->where('parent_id', 0)->where('status', 1)->get();
            $products_list = array();
            if($parent_catid != '') {
            
            $parent_categories = DB::table('categories')->select('id','name')->where('id', $parent_catid)->get();
            
            }
            
            foreach($parent_categories as $key2=>$parent_cat) {

                    $subcategories = DB::table('categories')->select('id','name')->where('parent_id', $parent_cat->id)->where('status', 1)->get();
                    $cat_id = $subcategories[0]->id;
                  
                    $sub_categories = array();

                    foreach ($subcategories as $key=>$subcat) {

                              $products = $this->category->listCategoryproducts($subcat->id, $data['product_name']);

                               $sub_categories[$key]['name'] = $parent_cat->name." >> ".$subcat->name;
                               $sub_categories[$key]['cat_id'] = $subcat->id;


                              $sortlistprd_col = array();
                              if(!$products->isEmpty()) {

                                  foreach($products as $key1=>$product) {

                                      $sortlistprd_col[$key1]['productId'] = $product->id;
                                      $sortlistprd_col[$key1]['productName'] = $product->name;
                                      $sortlistprd_col[$key1]['productCode'] = $product->product_code;
                                      $sortlistprd_col[$key1]['productSize'] = $product->packet_size;
                                      $sortlistprd_col[$key1]['productPrice'] = $product->price;
                                      $sortlistprd_col[$key1]['productStatusFront'] = $product->products_status_2;
                                      $sortlistprd_col[$key1]['productStatusBackend'] = $product->products_status;
                                      $sortlistprd_col[$key1]['productType'] = $product->type;
                                      $sortlistprd_col[$key1]['catId'] = $product->catid;
                                  }

                              }
                             if(!empty($sortlistprd_col)){
                                  $sub_categories[$key]['products'] = $sortlistprd_col;
                                  $products_list[] = $sub_categories[$key];
                              }
                      }   
            
          }
          
            if(!empty($products_list)) {

              foreach ($products_list as $product) {

                foreach($product['products'] as $pr_data) {

                  $updated_price_with_markup[$pr_data['productId']] = $this->product_price_with_markup($pr_data['productType'], $pr_data['productPrice'], $pr_data['catId'], Auth()->user()->id);

                  }

                }

          }
            
      }
        
        
       $temp_basket_data = DB::table('customers_temp_basket')->where('customers_id', Auth()->user()->id)->orderBy('customers_basket_id')->get();
        
        $temp_product_data = array();
        $total_price = 0;
         
        foreach($temp_basket_data as $temp_data)
        {
            $product_details = DB::table('products')->where('id', $temp_data->products_id)->first();
            
            $cat_details = DB::table('categories')->select('parent_id','name')->where('id', $temp_data->catid)->first();
           
            $parent_cat_details = DB::table('categories')->select('id','name')->where('id', $cat_details->parent_id)->first();
            
            $catname_prd = $parent_cat_details->name." => ".$cat_details->name;
            
            
            $temp_product_data[$temp_data->products_id]["product_name"] = $product_details->name;
            $temp_product_data[$temp_data->products_id]["packet_size"] = $product_details->packet_size;
            $temp_product_data[$temp_data->products_id]["product_type"] = $product_details->type;
            $temp_product_data[$temp_data->products_id]["catname_prd"] = $catname_prd;
            $total_price += str_replace(",", "", $temp_data->price);
            
        }
        
       
        
        
       $default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
       if(isset(Auth()->User()->id)) {
           
        $customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
        
         if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {
             
              $default_minimum_order = $customer_minimum_order->minimum_order;
              
         } 
        
       }
       
      $cart_total = $this->cartRepo->getSubTotal();
      
      $total_products_price = str_replace(",", "", $cart_total)+str_replace(",", "", $total_price);
        
       // $cartItems = $this->cartRepo->getCartItemsTransformed();
        
       // $total_price = 0;
        
       //$total_price += $this->cartRepo->getSubTotal();
        
     //  echo "<pre>"; print_r($cartItems); exit;
        if($catid != '') {
            
            return view('front.productslist', [
                'products' => $products,
                'updated_price_with_markup' => $updated_price_with_markup,
                'customer' => $customer,
                'temp_basket_data' => $temp_basket_data,
                'default_minimum_order' => $default_minimum_order,
               // 'cartItems' => $cartItems,
                'total_price' => $total_price,
                'total_products_price' => $total_products_price,
                'product_data' => $temp_product_data,
                'categories' => $categories
            ])->with(["category_name" => $catname->name, "cat_id" => $catid, 'total_price' => number_format($total_price, 2)]);
        
            
        } else {
            
                return view('front.productssearchlist', [
                'products_data' => $products_list,
                'updated_price_with_markup' => $updated_price_with_markup,
                'customer' => $customer,
                'default_minimum_order' => $default_minimum_order,
                'cat_id' => $cat_id,
                'temp_basket_data' => $temp_basket_data,
               // 'cartItems' => $cartItems,
                'total_price' => $total_price,
                'total_products_price' => $total_products_price,
                'product_data' => $temp_product_data,
                'categories' => $categories
            ])->with(['total_price' => number_format($total_price, 2)]);
        }
    }
    */
    /**
	* product list by category and product name.
    */
	/*  
    
	public function productssearch($catid) {
        
        if(!isset(Auth()->user()->id) && empty(Auth()->user()->id)) {
            
            return redirect()->route('login');
        }
        
        $customer = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
        
        $categories = $this->category->listMyaccoutcategories();
        
        $catname = DB::table('categories')->select('name')->where('id', $catid)->first();
        
        $products = $this->category->listCategoryproducts($catid);
        
        $updated_price_with_markup = array();
        
        
        
        foreach ($products as $product) {
            
            $updated_price_with_markup[$product->id] = $this->product_price_with_markup($product->type, $product->price, $catid, Auth()->user()->id);
        }
        
        $temp_basket_data = DB::table('customers_temp_basket')->where('customers_id', Auth()->user()->id)->orderBy('customers_basket_id')->get();
        
        $temp_product_data = array();
        $total_price = 0;
         
        foreach($temp_basket_data as $temp_data)
        {
            $product_details = DB::table('products')->where('id', $temp_data->products_id)->first();
            
            $cat_details = DB::table('categories')->select('parent_id','name')->where('id', $temp_data->catid)->first();
           
            $parent_cat_details = DB::table('categories')->select('id','name')->where('id', $cat_details->parent_id)->first();
            
            $catname_prd = $parent_cat_details->name." => ".$cat_details->name;
            
            
            $temp_product_data[$temp_data->products_id]["product_name"] = $product_details->name;
            $temp_product_data[$temp_data->products_id]["packet_size"] = $product_details->packet_size;
            $temp_product_data[$temp_data->products_id]["product_type"] = $product_details->type;
            $temp_product_data[$temp_data->products_id]["catname_prd"] = $catname_prd;
            $total_price += $temp_data->price;
            
        }
        
        $cartItems = $this->cartRepo->getCartItemsTransformed();
        
       // $total_price = 0;
        
       //$total_price += $this->cartRepo->getSubTotal();
        
     //  echo "<pre>"; print_r($cartItems); exit;
        return view('front.productslist', [
            'products' => $products,
            'updated_price_with_markup' => $updated_price_with_markup,
            'customer' => $customer,
            'temp_basket_data' => $temp_basket_data,
            'cartItems' => $cartItems,
            'total_price' => $total_price,
            'product_data' => $temp_product_data,
            'categories' => $categories
        ])->with(["category_name" => $catname->name, "cat_id" => $catid, 'total_price' => number_format($total_price, 2)]);
        
    }
    
    */
    
    /**
     * update shopping list.
     * param catid
    */
    public function updateshoppinglist(Request $request) {
        
        $requested_data = $request->all();
		
		//get main category id
        //$product_main_category_id = DB::table("categories")->select('parent_id')->where("id", $requested_data['catid'])->first();
		
        //$pricewith_markup = $this->product_price_with_markup($requested_data['prdtype'], $requested_data['price'], $requested_data['catid'], Auth()->user()->id);
        
        //$requested_data['price'] = str_replace(",", "", $pricewith_markup);
       
        //add product to cart
		$product = $this->productRepo->findProductById($requested_data['pid']);
		$product->price = $requested_data['price'];
		$cartItems = $this->cartRepo->getCartItemsTransformed();
		$itemexists = 0;
			
			
     
		foreach($cartItems as $cartItem) {
			//pr($cartItem); exit;
			if($cartItem->product_code == $product->product_code) {
				 $itemexists = 1;
				 $this->cartRepo->updateQuantityInCart($cartItem->rowId, $requested_data['pvalue']);
			}
		}
		
		if ($itemexists != 1) {
			
			$this->cartRepo->addToCart($product, $requested_data['pvalue']);
		}


	   $cartItems = $this->cartRepo->getCartItemsTransformed();
        
       $cart_total = $this->cartRepo->getSubTotal();
        
       //$total_products_price = str_replace(",", "", $cart_total)+str_replace(",", "", $total_price);
	   $total_products_price = str_replace(",", "", $cart_total);
	   $total_price = str_replace(",", "", $cart_total);
        
       $default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
       if(isset(Auth()->User()->id)) {
           
        $customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
        
         if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {
             
              $default_minimum_order = $customer_minimum_order->minimum_order;
              
         } 
        
       }
        
       
        
        return view('front.shared.tempbasketdata', compact('cartItems'))->with(['total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $default_minimum_order, 'cat_id' => $requested_data['catid']]);
        
       
    }
    
    /**
     * Copy order into cart.
     * param order_id
    */
    public function order_copy(Request $request,$order_id) {
        
		$order = Order::where('id', $order_id)->with(['orderDetail', 'orderproducts' => function($q){
			$q->leftjoin('category_product', 'category_product.product_id', '=', 'order_product.product_id');
			$q->leftjoin('products', 'products.id', '=', 'order_product.product_id');
			$q->select('order_product.id','order_product.order_id','order_product.product_id','order_product.quantity','order_product.quantity','category_product.category_id','products.price','products.type');
		}])->first();
		
		if(empty($order) || $order->count() < 1){
			return redirect()->route('accounts.orders')->with(['error' => 'Order not exist.']);
		}else if($order->customer->id != Auth()->User()->id){
			return redirect()->route('accounts.orders')->with(['error' => 'This order not valid for you.']);
		}else{
			$this->cartRepo->clearCart();
			
			if(isset(Auth()->User()->id)) {
				$customer = DB::table('customers')->select('minimum_order','default_address_id')->where('id', Auth()->user()->id)->first();
			}else{
				$customer = [];
			}
			
			foreach($order->orderproducts as $product){
				$requested_data['catid'] = $product->category_id;
				$requested_data['pid'] = $product->product_id;
				$requested_data['pvalue'] = $product->quantity;
				$requested_data['uid'] = $order->customer_id;
				$requested_data['price'] = $product->price;
				$requested_data['prdtype'] = $product->type;
				
				//get main category id
				
				$pricewith_markup = $this->product_price_with_markup($requested_data['prdtype'], $requested_data['price'], $requested_data['catid'], Auth()->user()->id);
				
				$requested_data['price'] = str_replace(",", "", $pricewith_markup);
				
				//add product to cart
				$product = $this->productRepo->findProductById($requested_data['pid']);
				$product->price = $requested_data['price'];
				
				$cartItems = $this->cartRepo->getCartItemsTransformed();
				$itemexists = 0;
				
				foreach($cartItems as $cartItem) {
					if($cartItem->product_code == $product->product_code) {
						 $itemexists = 1;
						 $this->cartRepo->updateQuantityInCart($cartItem->rowId, $requested_data['pvalue']);
					}
				}
				
				if ($itemexists != 1) {
					$this->cartRepo->addToCart($product, $requested_data['pvalue']);
				}

				$cartItems = $this->cartRepo->getCartItemsTransformed();
				
				$cart_total = $this->cartRepo->getSubTotal();
				
				$total_products_price = str_replace(",", "", $cart_total);
				$total_price = str_replace(",", "", $cart_total);
				
				$default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
			   
				if(!empty($customer) && isset($customer->minimum_order) && !empty($customer->minimum_order)) {
					$default_minimum_order = $customer->minimum_order;
				}
			}
			
			/**** Set order delivery address
			
			$deliveryAdd = [];
			
			if(!empty($order->address_id)){
				$deliveryAdd['address'] = $order->address_id;
			}else{
				if(!empty($customer))
					$deliveryAdd['address'] = $customer->default_address_id;
				else
					$deliveryAdd['address'] = null;
			}
			
			$deliveryAdd['delivery_window'] = $order->orderDetail->Access_Time;
			$deliveryAdd['delivery_notes'] = $order->orderDetail->delivery_procedure;
			$deliveryAdd['delivery_date'] = date("Y-m-d");
			
			session(['checkout.step1' => $deliveryAdd]);
			
			End set order delivery address ***********/
			
			session()->forget('coupon_id');
			session()->forget('coupon_code');
			session()->forget('discount_coupon_amount');
			
			return redirect()->route('checkout.index');
		}
    }
    
    /**
     * delete basket product .
     * param product id
    */
    public function deletebasketproduct($product_id, Request $request) {
       
         //delete product from basket
        $request_data = $request->all();
        $cat_id = $request_data['catid'];
        $tempid = $request_data['tempid'];
        
        
        if(isset($product_id) && !empty($product_id)) {
            
            DB::table('customers_temp_basket')->where(['products_id' => $product_id, 'customers_id' => Auth()->user()->id, 'customers_basket_id' => $tempid ])->delete();
        }
        
       
       
        
         return redirect()->route('accounts.productslist', 'catid='.$cat_id)
            ->with('message', 'Deleted successful');
        
    }
    
}
