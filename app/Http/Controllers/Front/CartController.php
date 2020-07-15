<?php

namespace App\Http\Controllers\Front;

use App\Shop\Carts\Requests\AddToCartRequest;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\Shop\ProductAttributes\Repositories\ProductAttributeRepositoryInterface;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Tools\MarkuppriceTrait;
use Gloudemans\Shoppingcart\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Shop\Categories\Repositories\CategoryRepository;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use DB;

class CartController extends Controller
{
    use ProductTransformable;
    use MarkuppriceTrait;
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
     * @var ProductAttributeRepositoryInterface
     */
    private $productAttributeRepo;
	
	/**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * CartController constructor.
     * @param CartRepositoryInterface $cartRepository
     * @param ProductRepositoryInterface $productRepository
     * @param CourierRepositoryInterface $courierRepository
     * @param ProductAttributeRepositoryInterface $productAttributeRepository
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        ProductRepositoryInterface $productRepository,
        CourierRepositoryInterface $courierRepository,
        ProductAttributeRepositoryInterface $productAttributeRepository,
		CategoryRepositoryInterface $categoryRepository
    ) {
        $this->cartRepo = $cartRepository;
        $this->productRepo = $productRepository;
        $this->courierRepo = $courierRepository;
        $this->productAttributeRepo = $productAttributeRepository;
		$this->categoryRepo = $categoryRepository;
        
        
    }
	
	/**
     * check guest user login.
     *
     * @return auth after login.
     */
    public function checkAuth(){
		if (\Auth::guest()) {
			session(['backUrl' => url()->previous()]);
			
			return redirect()->guest('login');
		}else{
			return \Session::get('backUrl') ? redirect()->intended(\Session::get('backUrl')) : redirect()->route('home');
		}
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courier = $this->courierRepo->findCourierById(request()->session()->get('courierId', 1));
        $shippingFee = $this->cartRepo->getShippingFee($courier);
		$parent_categories = $this->categoryRepo->listParentCategories();
		
		//$cart_items = $this->cartRepo->getCartItemsTransformed();
		//pr($cart_items); exit;

        return view('front.carts.cart', [
            'cartItems' => $this->cartRepo->getCartItemsTransformed(),
            'subtotal' => $this->cartRepo->getSubTotal(),
			'categories' => $parent_categories,
           // 'tax' => $this->cartRepo->getTax(),
           // 'shippingFee' => $shippingFee,
           // 'total' => $this->cartRepo->getTotal(2, $shippingFee)
        ]);
        
        
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AddToCartRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddToCartRequest $request)
    { 
       $requested_data = $request->all();
       
       $default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
       if(isset(Auth()->User()->id)) {
           
        $customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
        
         if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {
             
              $default_minimum_order = $customer_minimum_order->minimum_order;
              
         } 
        
       } 
       
      $cart_total = $this->cartRepo->getSubTotal();
      
      $total_products_price = str_replace(",", "", $cart_total)+str_replace(",", "", $requested_data['total']);
      
      
       
      if(isset($total_products_price) && !empty($total_products_price) && $total_products_price < $default_minimum_order) {
           
           return redirect()->route('accounts.productslist', 'catid='.$requested_data['catid']);
       }
       
      //Session::set('shop_catid', $requested_data['catid']);
       Session::put('shop_catid', $requested_data['catid']);
       
      $basketdata = DB::table('customers_temp_basket')->select('products_id', 'customers_basket_quantity', 'catid')->where('customers_id', Auth()->user()->id)->get();
      
  foreach($basketdata as $temp_data) {  
         //echo "<pre>"; print_r($temp_data); exit;
          $product = $this->productRepo->findProductById($temp_data->products_id);
          
        // $product_price = $this->product_price_with_markup($product->type, $product->price, $temp_data->catid, Auth()->user()->id);
          $product->price = $this->product_price_with_markup($product->type, $product->price, $temp_data->catid, Auth()->user()->id);
          
           $cartItems = $this->cartRepo->getCartItemsTransformed();
           
           $itemexists = 0;
     
            foreach($cartItems as $cartItem) {

                if($cartItem->product_code == $product->product_code) {
                     $itemexists = 1;
                     $this->cartRepo->updateQuantityInCart($cartItem->rowId, $temp_data->customers_basket_quantity);
                }
            }
            
            if ($itemexists != 1) {
                
                //product category
                //$cat_details = DB::table('categories')->select('parent_id','name')->where('id', $temp_data->catid)->first();
                //$parent_cat_details = DB::table('categories')->select('id','name')->where('id', $cat_details->parent_id)->first();

                //$product->catname = $parent_cat_details->name." => ".$cat_details->name;
                
                $this->cartRepo->addToCart($product, $temp_data->customers_basket_quantity);
            }
          
         }

        //DB::table('customers_temp_basket')->where('customers_id', Auth()->user()->id)->delete();

        return redirect()->route('cart.index')
            ->with('message', 'Add to cart successful');
    }

	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateshoppingcart(Request $request)
    {
        $requested_data = $request->all();
		
		$pricewith_markup = $this->product_price_with_markup($requested_data['prdtype'], $requested_data['price'], $requested_data['catid'], Auth()->user()->id);
        
        $requested_data['price'] = str_replace(",", "", $pricewith_markup);
       
        //add product to cart
		$product = $this->productRepo->findProductById($requested_data['pid']);
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
        
       
        
        return view('front.carts.cart_qty_update', compact('cartItems'))->with(['subtotal' => number_format($total_price, 2), 'cat_id' => $requested_data['catid']]);
        
		
		//print_r($requested_data);
    }
	
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->cartRepo->updateQuantityInCart($id, $request->input('quantity'));

        request()->session()->flash('message', 'Update cart successful');
        return redirect()->route('cart.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$this->cartRepo->removeToCart($id);
        
		$category = [];
		/** Set cart items **/
		$cartItems = $this->cartRepo->getCartItemsTransformed();
		$cart_total = $this->cartRepo->getSubTotal();
        
		$total_products_price = str_replace(",", "", $cart_total);
		$total_price = str_replace(",", "", $cart_total);
        
		$default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
		if(isset(Auth()->User()->id)){
			$customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();

			if(isset($customer_minimum_order->minimum_order) && $customer_minimum_order->minimum_order != 0) {
				$default_minimum_order = $customer_minimum_order->minimum_order;
			}
		}
		
		/** End cart items **/
		
		return json_encode([
			'status' => true,
			'msg' => 'Removed to cart successful',
			'view' => view('front.categories.right-cartinfo', compact('cartItems','category'))->with([
				'total_price' => number_format($total_price, 2),
				'total_products_price' => number_format($total_products_price, 2), 
				'default_minimum_order' => $default_minimum_order,
				'cartItems' => $cartItems,
				'cat_id' => (!empty($category)) ? $category->id : null,
			])->render(),
		]);
		

        //request()->session()->flash('message', 'Removed to cart successful');
        //return redirect()->route('cart.index');
    }
}
