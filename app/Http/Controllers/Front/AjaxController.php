<?php

namespace App\Http\Controllers\Front;

use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Categories\Category;
use App\Shop\Tools\MarkuppriceTrait;
use Illuminate\Http\Request;
use App\Shop\Customers\CustomerFavouriteProduct;
use DB;
use App\Helper\Generalfnv;
use App\Shop\Orders\Order;

class AjaxController extends Controller
{
    use ProductTransformable;
    use MarkuppriceTrait;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;
    
     /**
     * @var CategoryRepositoryInterface
     */
    private $category;
    
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * ProductController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository, Category $category, CategoryRepositoryInterface $categoryRepository)
    {
        $this->productRepo = $productRepository;
        $this->category = $category;
        $this->categoryRepo = $categoryRepository;
    }

    /**
	 * Add product into favourites list of customer
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add_fav(Request $request){
        if($request->isMethod('post')){
			$data = $request->all();
			if(isset($data['id'])){
				if(isset($data['action']) && $data['action'] == 'add'){
					$productCatId = DB::table('category_product')->where('product_id', $data['id'])->first();
					
					$obj = CustomerFavouriteProduct::where(['customer_id' => \Auth::user()->id,
					'product_id' => $data['id']])->first();
					
					if(!$obj){
						$obj = new CustomerFavouriteProduct();
					}
					$obj->customer_id = \Auth::user()->id;
					$obj->category_id = $productCatId->category_id;
					$obj->product_id = $data['id'];
					
					if($obj->save()){
						return json_encode(['status' => true, 'msg' => 'Product addedd successfully into favourites list.']);
					}else{
						return json_encode(['status' => false, 'msg' => 'Product not addedd successfully into favourites list.']);
					}
				}else{
					CustomerFavouriteProduct::where(['customer_id' => \Auth::user()->id,
					'product_id' => $data['id']])->delete();
					return json_encode(['status' => true, 'msg' => 'Product removed successfully from favourites list.']);
				}
			}
		}else{
			return json_encode(['status' => false, 'msg' => 'This method required post request.']);
		}
    }
    
	public function checkOrderCouponCodeStatus(Request $request){
		if($request->get('type') == 'checkOrderCouponCode'){
			$order = Order::where('id', $request->get('order_id'))->with(['orderDetail','customer','orderproducts'])->first();
			
			$product_ids = '';
			$category_ids = '';
			
			$coupon_details = DB::table('coupons')->where('coupon_code', $order->coupon_code)->first();
			
			if((isset($coupon_details->restrict_to_products) && $coupon_details->restrict_to_products != '') || (isset($coupon_details->restrict_to_categories) && $coupon_details->restrict_to_categories != '')) {
			
				foreach($order->orderproducts as $product){
				   $category_obj = DB::table('category_product')->select('category_id')->where('product_id', $product->product_id)->first();
				   $category_ids .= $category_obj->category_id.",";
				   $product_ids .= $product->product_id.",";
				}
			}

			$category_ids = rtrim($category_ids, ",");
			$product_ids = rtrim($product_ids, ",");
			
			$check_coupon = Generalfnv::verifyOrderCouponCode($order->coupon_code, $category_ids, $product_ids, $order->sub_total);
			
			$res = [];
			if($check_coupon){
				$res['status'] = true;
				$res['msg'] = '';
			}else{
				$res['status'] = false;
				$res['msg'] = "Your order has a applied <b>coupon code:$order->coupon_code</b> which is not valid now for your order, so now your order total amount is <b>$order->sub_total</b> without any discount.";
			}
		}else{
			$order = Order::where('id', $request->get('order_id'))->first();
			
			$order->coupon_code = null;
			$order->customer_discount = 0;
			
			$total = $order->sub_total + $order->shipping_charges;
			
			$order->total = $total;
			$order->total_paid = $total;
			
			if($order->update()){
				$res['status'] = true;
				$res['msg'] = '';
			}else{
				$res['status'] = false;
				$res['msg'] = "something went wrong, please try again.";
			}
		}
		return json_encode($res);
	}
}
