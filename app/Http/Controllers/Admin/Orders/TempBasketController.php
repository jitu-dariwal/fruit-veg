<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Requests\UpdateCustomerRequest;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Orders\Order;
use App\Shop\Orders\Transformers\OrderTransformable;
use Illuminate\Support\Facades\Hash;
use App\Shop\Categories\Category;
use App\Http\Controllers\Admin\Products;
use App\Shop\Products\Product;
use App\Shop\OrderProducts\OrderProduct;
use App\Shop\Tools\MarkuppriceTrait;
use Illuminate\Http\Request;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use DB;

class TempBasketController extends Controller
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
     * @var CourierRepositoryInterface
     */
    private $courierRepo;
    
	 /**
	 * @var CategoryRepositoryInterface
	 */
	private $category;
	
	/**
	 * @var OrderRepositoryInterface
	 */
    private $order_rep;

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
        CustomerRepositoryInterface $customerRepository,
        Category $category,
		Order $order_rep
    ) {
        $this->customerRepo = $customerRepository;
        $this->cartRepo = $cartRepository;
        $this->courierRepo = $courierRepository;
        $this->category = $category;
		$this->order_rep = $order_rep;
    }

    /**
     * Show tailor made Products
     *
     * @param  int $order_id
     * @param  int $cat_id
     * @return \Illuminate\Http\Response
     */
	 public function index($order_id,$cat_id=null){
		$categories = $this->category->listMyaccoutcategories();
		$catname = DB::table('categories')->select('name','id')->first();
		$order=Order::findOrFail($order_id);
		$catid=$catname->id;
		if(!empty($cat_id)){
			$catid=$cat_id;
			$catname = DB::table('categories')->select('name','id')->where('id',$cat_id)->first();
		}
		 
		$products = $this->category->listCategoryproducts($catid);
		 
		$product_data = array();
        $total_price = 0;
        
        $temp_basket_data = DB::table('customers_temp_basket')->where('order_id', $order->id)->where('customers_id', $order->customer_id)->orderBy('customers_basket_id')->get();
        
        foreach($temp_basket_data as $temp_data)
        {
            $product_details = DB::table('products')->where('id', $temp_data->products_id)->first();
            
            $cat_details = DB::table('categories')->select('parent_id','name')->where('id', $temp_data->catid)->first();
            $parent_cat_details = DB::table('categories')->select('id','name')->where('id', $cat_details->parent_id)->first();
            
            $catname_prd = $parent_cat_details->name." => ".$cat_details->name;
            
            $product_data[$temp_data->products_id]["product_name"] = $product_details->name;
            $product_data[$temp_data->products_id]["packet_size"] = $product_details->packet_size;
            $product_data[$temp_data->products_id]["product_type"] = $product_details->type;
            $product_data[$temp_data->products_id]["product_qty"] = $temp_data->customers_basket_quantity;
            $product_data[$temp_data->products_id]["catname_prd"] = $catname_prd;
            $total_price += $temp_data->price;
            
        }
		
		$updated_price_with_markup = array();
        
        foreach ($products as $product) {
            $updated_price_with_markup[$product->id] = $this->product_price_with_markup($product->type, $product->price, $catid, $order->customer_id);
        }
		
		$default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
		   
		if(isset($order->customer_id)) {
			$customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', $order->customer_id)->first();
		
			if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {	 
				$default_minimum_order = $customer_minimum_order->minimum_order;
			}
		}
		
		if($order->total_products > $default_minimum_order)
			$default_minimum_order = 0;
		
		return view('admin.orders.add_multiple_product',compact('temp_basket_data', 'product_data', 'total_price', 'categories','catname','products','order_id','order','updated_price_with_markup','cat_id','default_minimum_order'));
	}
    
    /**
     * update shopping list.
     * param catid
    */
    public function updateshoppinglist(Request $request) {
        
        $requested_data = $request->all();
		$cust_id=$requested_data['uid'];
		$order_id = $requested_data['order_id'];
		
        $pricewith_markup = $this->product_price_with_markup($requested_data['prdtype'], $requested_data['price'], $requested_data['catid'], $cust_id);
        
        $requested_data['price'] = $pricewith_markup;
        
        $this->category->creatTempbasket($requested_data);
        
        $product_data = array();
        $total_price = 0;
        
        $temp_basket_data = DB::table('customers_temp_basket')->where('customers_id', $cust_id)->where('order_id', $order_id)->orderBy('customers_basket_id')->get();
        
        foreach($temp_basket_data as $temp_data)
        {
            $product_details = DB::table('products')->where('id', $temp_data->products_id)->first();
            
            $cat_details = DB::table('categories')->select('parent_id','name')->where('id', $temp_data->catid)->first();
            $parent_cat_details = DB::table('categories')->select('id','name')->where('id', $cat_details->parent_id)->first();
            
            $catname_prd = $parent_cat_details->name." => ".$cat_details->name;
            
            $product_data[$temp_data->products_id]["product_name"] = $product_details->name;
            $product_data[$temp_data->products_id]["packet_size"] = $product_details->packet_size;
            $product_data[$temp_data->products_id]["product_type"] = $product_details->type;
            $product_data[$temp_data->products_id]["catname_prd"] = $catname_prd;
			
            $total_price += $temp_data->price;
        }
        
		// $total_price += $this->cartRepo->getSubTotal();
		
        return view('admin.orders.partials.tempbasketdata', compact('temp_basket_data', 'product_data'))->with(['total_price' => number_format($total_price, 2), 'cat_id' => $requested_data['catid']]);
        
		// DB::table('category_product')->insert(['category_id' => $catid, 'product_id' => $split_product_id]);
    }
    
    /**
     * delete basket product .
     * param product id
    */
    public function deletebasketproduct($product_id, Request $request) {
       
         //delete product from basket
        $request_data = $request->all();
        $tempid = $request_data['tempid'];
        
        
        if(isset($product_id) && !empty($product_id)) {
            
            DB::table('customers_temp_basket')->where(['products_id' => $product_id, 'customers_basket_id' => $tempid])->delete();
        }
        
       
       
        
         return back();
        
    }
	
	/**
     * Add Order Products .
     * param product id
    */
    public function add_order_product($order_id, Request $request) {
		if(empty($request->order_id) || $request->order_id!=$order_id || empty($request->customer_id)){
			return back()->with('error','Sorry! Something is going wrong. Please try again...');
		}
		
		$order_products=DB::table('customers_temp_basket')->where('order_id',$request->order_id)->where('customers_id',$request->customer_id)->get();
		
		foreach($order_products as $product){
			$productinfo=Product::select()->where('id',$product->products_id)->firstOrfail();
			
			$actual_weight= $productinfo->weight*$product->customers_basket_quantity;
			
			$final_price=$this->order_rep->getProductFinalPrice($product->price,$productinfo->weight,$actual_weight,$product->customers_basket_quantity);
			
			if(OrderProduct::where('order_id',$order_id)->where('product_id',$product->products_id)->count()==0){
				OrderProduct::create([ 
					'order_id' => $request->order_id,
					'product_id' => $product->products_id,
					'quantity' => $product->customers_basket_quantity,
					'weight' => $productinfo->weight,
					'actual_weight' => $actual_weight,
					'weight_unit' => $productinfo->mass_unit,
					'product_name' => $productinfo->name,
					'product_code' => $productinfo->product_code,
					'product_description' => $productinfo->description,
					'packet_size' => $productinfo->packet_size,
					'type' => $productinfo->type,
					'product_price' => $product->price,
					'final_price' => $final_price
				]);
			}
			
			DB::table('customers_temp_basket')->where('customers_basket_id',$product->customers_basket_id)->delete();
		}
		
		$this->order_rep->updateOrderTotal($order_id);
		
		return redirect()->route('admin.orders.addproducts', $order_id);        
    }
}
