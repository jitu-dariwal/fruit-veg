<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop\Orders\Order;
use App\Shop\OrderDetails\OrderDetail;
use App\Shop\OrderProducts\OrderProduct;
use App\Shop\Products\Product;
use App\Shop\Customers\Customer;
use DB;

class ProductReportController extends Controller
{
	
	
    public function productsPurchased(Request $request)
	{
		$_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
		$sort_by= '';
		if($request->sort_by)
		{
			$sort_by=$request->sort_by;
		}
		$products= OrderProduct::join('products', 'products.id','=','order_product.product_id')
		      ->join('orders', 'orders.id','=','order_product.order_id')
		      ->selectRaw('order_product.product_id,count(order_product.product_id) purchased_count')
			  ->where('orders.order_status_id','=',3);
			  if(request()->has('q')){
			  $products=$products->where('products.name', 'like', '%'.request('q').'%');	
			  }
              if(!empty($sort_by) && $sort_by=='psort_asc'){
                $products=$products->orderBy('products.name','asc');
              }elseif(!empty($sort_by) && $sort_by=='psort_desc'){
                $products=$products->orderBy('products.name','desc');
              }elseif(!empty($sort_by) && $sort_by=='osort_asc'){
                $products=$products->orderBy('purchased_count','asc');
              }elseif(!empty($sort_by) && $sort_by=='osort_desc'){
                $products=$products->orderBy('purchased_count','desc');
              }else{
              	$products=$products->orderBy('purchased_count','desc');
              }
			 $products=$products->groupBy('order_product.product_id')
			  ->paginate($_RECORDS_PER_PAGE);
			  
		return view('admin.reports.product.products_purchased',compact('products'));
	}
}
