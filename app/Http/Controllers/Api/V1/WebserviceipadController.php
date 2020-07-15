<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Access\User\User;
use Validator;
use DB;

class WebserviceipadController extends APIController
{
   

    /**
     * __construct.
     *
     * @param $repository
     */
    public function __construct()
    {
       
    }
	
	public function ipadapis() {
		return view('front.apiinterface', compact('page_details'));
	}

    /**
     * All ipad webservices.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ipadwebservice(Request $request)
    {
        $req_data = $request->all();
		
		/* $jsonRequest = '{"data":{"email_id":"admin@admin.com","password":"1234"},"method_name":"userLogin"}';
		
		$json_data	=	$jsonRequest;
		
		$josonConverted	=	 json_decode(stripslashes($req_data));
		
		$data	=	$josonConverted->data;
		$mehodName	=	$josonConverted->method_name; */
		
		$req_data	=	 json_decode(stripslashes($req_data['jsonRequest']), true);
		//pr($req_data);die;
		
		$methodName	=	$req_data['method_name'];
		
		$data = [];
		if(array_key_exists("data", $req_data))
			$data	=	$req_data['data'];
		
		if($methodName	== 'userLogin'){
			$validator = Validator::make($data, [
				'email_id' => 'required|string|email',
				'password' => 'required|string',
			]);
			
			if ($validator->fails()) {
				$errors = $validator->errors();
				
				$errorMsg = '';
				if($errors->first('email_id'))
					$errorMsg .= 'Email : '.$errors->first('email_id')."<br/>";
				
				if($errors->first('password'))
					$errorMsg .= 'Password : '.$errors->first('password');
				
				$res = [];
				$res['method_name']	=	'userLogin';
				$res['status']		=	'error';
				$res['data'] = ['message' => $errorMsg];
				
				$returnData = json_encode($res);
			}else{
				$returnData	=	$this->checkUserLogin($data['email_id'], $data['password']);
			}
			
			echo $returnData;
			
		}
		else if($methodName	== 'paswordRecovery'){
			$validator = Validator::make($data, [
				'email_id' => 'required|string|email'
			]);
			
			if ($validator->fails()) {
				$errors = $validator->errors();
				
				$errorMsg = '';
				if($errors->first('email_id'))
					$errorMsg .= 'Email : '.$errors->first('email_id')."<br/>";
				
				$res = [];
				$res['method_name']	=	'paswordRecovery';
				$res['status']		=	'error';
				$res['data'] = ['message' => $errorMsg];
				
				$returnData = json_encode($res);
			}else{
				$returnData	=	$this->passwordRecovery($data['email_id']);
			}
			
			echo $returnData;
			
		}
		else if($methodName	== 'getCategorieslist'){
			
			echo $this->getCategorieslist();
			
		}
		else if($methodName	== 'getSubCategorieslist'){
			
			echo $this->getSubCategorieslist();

		}
		else if($methodName	== 'getProductsList'){
			$product_id = 0;
			
			if(array_key_exists('product_id', $data))
				$product_id = $data['product_id'];
			
			echo $this->getProductsList($product_id);
			
		}
		else if($methodName	== 'getSupplayersList'){
			
			echo $this->getSupplayersList();
			
		}
		else if($methodName	== 'getCountryList'){
			
			echo $this->getCountryList();
			
		}
		else if($methodName	== 'getPurchasedProductsLists'){

			$userID	=	$req_data['userID'];
			$cartID	=	$req_data['cartID'];
			$orders_total = $req_data['orders_total'];
			$payment_mode = $req_data['payment_mode'];
			echo $this->getPurchasedProductsLists($data,$userID,$cartID='',$orders_total,$payment_mode);
			
		}
		else if($methodName	== 'getOrderHistoryData'){
			if(array_key_exists('maxorder_id', $req_data) && !empty($req_data['maxorder_id'])){
				return $this->getOrderHistoryData($req_data['maxorder_id']);
			}else{
				$returnData['method_name'] = 'getOrderHistoryData';
				$returnData['status'] = 'error';
				$returnData['data'] = ['message' => 'Please give max order id.'];
				
				echo json_encode($returnData);
			}			
		}
		else if($methodName	== 'makeprodcutoutofstock'){
			if(array_key_exists('productID', $data) && !empty($data['productID'])){
				$productID	= 	 $data['productID']; // for bulk
				$split_product_id	= 	 $data['split_product_id']; // for split
				$products_status_bulk	= 	 $data['product_status']; // for bulk product status
				$split_product_status	= 	 $data['split_product_status']; // for bulk product status
				
				return $this->makeprodcutoutofstock($productID,$split_product_id,$products_status_bulk,$split_product_status);
			}else{
				$returnData['method_name'] = 'makeprodcutoutofstock';
				$returnData['status'] = 'error';
				$returnData['data'] = ['message' => 'Please give product id.'];
				
				echo json_encode($returnData);
			}
		}
		else if($methodName	== 'addNewProduct'){
			return $this->addNewProduct($data, $req_data['userID']);
		}
		else if($methodName	== 'update_ordered_products'){
			return $this->update_ordered_products($data,$req_data['userID'],$req_data['ordersID']);
			
		}
    }
	
	/**** Below functions are helper function for fatching the data ***/
	
	public function checkUserLogin($email_id, $password) {
		$statusValue = '';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'userLogin';
		$jsonArray['status'] = $statusValue;
		
		$user = DB::table('app_user')->where(['email_address' => $email_id, 'password' => $password])->first();
		
		if($user){
			$statusValue = 'success';
			
			$data = [
				'app_user_id'=>$user->app_user_id, 
				'first_name'=>$user->first_name,
				'last_name'	=>	$user->last_name, 
				'email_address'	=>	$user->email_address, 
				'create_account_date'	=>	$user->create_account_date
			];
		}else{
			$statusValue = 'error';
			$data = ['message' => 'Invalid username or password'];
		}
		
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
	  
		return json_encode($jsonArray);
	}

	public function passwordRecovery($email_id){
		$statusValue = '';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'paswordRecovery';
		$jsonArray['status'] = $statusValue;
		
		$user = DB::table('app_user')->where(['email_address' => $email_id])->first();
		
		if($user){
			$random = substr( md5(rand()), 0, 7);
			
			$to = $email_id;
			$subject = "Password Recovery";
			$message = "This is your password '".$random."'";
			
			\Mail::raw($message, function ($message) use($email_id, $subject){
				$message->from('rakesh.gupta@dotsquares.com', 'FruitAndVeg');
				$message->to($email_id);
				$message->subject($subject);
			});
			
			DB::table('app_user')->where('email_address', $email_id)->update(['password' => $random]);
			
			$statusValue = 'success';
			$data = ['message' => 'Your password has been sent on your email address.Please check email.'];
			
		}else{
			$statusValue = 'error';
			$data = ['message' => 'Email address does not exist, Please enter correct email address.'];
		}
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
	  
		return json_encode($jsonArray);
	}
	
	public function getCategorieslist(){
		$statusValue = '';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'getCategorieslist';
		$jsonArray['status'] = $statusValue;
		
		$categories = DB::table('categories')->where(['parent_id' => 0,'status' => 1])->get();
		
		if($categories->count() > 0){
			$statusValue = 'success';
			$data = [];
			
			foreach($categories as $key=>$category){
				$data[$key] = [
					'categories_id' => $category->id,
					'categories_name' => $category->name,
					'parent_id' => $category->parent_id,
					'sort_order' => '',
					'date_added' => $category->created_at,
					'last_modified' => $category->updated_at
				];
			}
		}else{
			$statusValue = 'error';
			$data = ['message' => 'categories does not exist.'];
		}
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
	  
		return json_encode($jsonArray);
	}
	
	public function getSubCategorieslist(){
		$statusValue = '';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'getSubCategorieslist';
		$jsonArray['status'] = $statusValue;
		
		$categories = DB::table('categories')->where('parent_id', '!=', 0)->where(['status' => 1])->get();
		
		if($categories->count() > 0){
			$statusValue = 'success';
			$data = [];
			
			foreach($categories as $key=>$category){
				$data[$key] = [
					'categories_id' => $category->id,
					'categories_name' => $category->name,
					'parent_id' => $category->parent_id,
					'sort_order' => '',
					'date_added' => $category->created_at,
					'last_modified' => $category->updated_at
				];
			}
		}else{
			$statusValue = 'error';
			$data = ['message' => 'Sub category does not exist.'];
		}
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
	  
		return json_encode($jsonArray);
	}

	public function getProductsList($product_id = 0){
		$statusValue = '';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'getProductsList';
		$jsonArray['status'] = $statusValue;
		
		$products = DB::table('products');
		
		if($product_id > 0){
			$products->where('products.id', '>', $product_id);
		}
		
		$products->join('category_product as p2c', 'p2c.product_id', '=', 'products.id');
		
		$products->select('products.*','p2c.category_id');
		
		if($products->count() > 0){		
			$statusValue = 'success';
			$data = [];
			
			foreach($products->get() as $key=>$product){
				$data[$key] = [
					'products_id' => $product->id,
					'categories_id' => $product->category_id,
					'products_name' => $product->name,
					'products_status' => $product->status,
					'products_price' => $product->price,
					'products_date_added' => $product->created_at,
					'products_last_modified' => $product->updated_at,
					'product_code' => $product->product_code,
					'packet_size' => $product->packet_size,
					'type' => $product->type,
					'bulk_quantity' => $product->packvalue_quantity,
					'bulk_price' => '',
					'split_price' => $product->split_price,
					'parent_id' => $product->parent_id,
					'vat_include' => '',
					'packet_brand' => $product->packet_brand,
				];
			}
		}else{
			$statusValue = 'error';
			$data = ['message' => 'Products does not exist.'];
		}
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
	  
		return json_encode($jsonArray);
	}
	
	public function getSupplayersList(){
		$statusValue = '';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'getSupplayersList';
		$jsonArray['status'] = $statusValue;
		
		$manufacturers = DB::table('manufacturers');
		
		if($manufacturers->count() > 0){		
			$statusValue = 'success';
			$data = [];
			
			foreach($manufacturers->get() as $key=>$manufactur){
				$data[$key] = [
					'manufacturers_id' => $manufactur->id,
					'manufacturers_name' => $manufactur->name,
					'date_added' => $manufactur->created_at,
					'last_modified' => $manufactur->updated_at,
				];
			}
		}else{
			$statusValue = 'error';
			$data = ['message' => 'Manufacturers does not exist.'];
		}
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
	  
		return json_encode($jsonArray);
	}
	
	public function getCountryList(){
		$statusValue = '';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'getCountryList';
		$jsonArray['status'] = $statusValue;
		
		$countries = DB::table('countries');
		
		if($countries->count() > 0){		
			$statusValue = 'success';
			$data = [];
			
			foreach($countries->get() as $key=>$country){
				$data[$key] = [
					'countries_id' => $country->id,
					'countries_name' => $country->name,
				];
			}
		}else{
			$statusValue = 'error';
			$data = ['message' => 'Country does not exist.'];
		}
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
	  
		return json_encode($jsonArray);
	}

	public function getPurchasedProductsLists($data, $userID, $cartID, $orders_total, $payment_mode = ''){
		$statusValue = 'error';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'getPurchasedProductsLists';
		$jsonArray['status'] = $statusValue;
		
		$updatedProducts = [];
		
		if(count($data) > 0){
			$insert_orders_id = DB::table('app_user_orders')->insertGetId(['app_user' => $userID, 'order_create_date' => now(), 'orders_total' => $orders_total, 'payment_mode' => $payment_mode]);
			
			foreach($data as $key => $val){
				if($val['IsMakeProductOutOfStock'] == 1){
					$updatedProducts[] = $val['product_id'];
					
					if($val['split_product_id'] > 0){
						$updatedProducts[] = $val['split_product_id'];
						
						DB::table('products')->where('id', $val['split_product_id'])->update(['products_status' => $val['split_product_status'], 'products_status_2' => $val['split_product_status']]);
					}else{
						DB::table('products')->where('id', $val['product_id'])->update(['products_status' => $val['product_status'], 'products_status_2' => $val['product_status']]);
					}
				}else{
					if(DB::table('products')->where('id', $val['product_id'])->exists()){
						$bulk_price = $val['product_price'];
						$split_price = $val['split_price'];
						$updatedProducts[] = $val['product_id'];
						
						if($val['split_product_id'] > 0){
							$updatedProducts[] = $val['split_product_id'];
							
							DB::table('products')->where('id', $val['product_id'])->update([
								'name' => $val['product_name'],
								'split_price' => $split_price,
								'price' => $split_price,
								'status' => $val['split_product_status'],
								'products_status' => $val['split_product_status'],
								'products_status_2' => $val['split_product_status'],
								'packvalue_quantity' => $val['split_quantity'],
								'packet_brand' => $val['packet_brand'],
								'packet_size' => $val['split_packet_size'],
							]);
						}else{
							DB::table('products')->where('id', $val['product_id'])->update([
								'name' => $val['product_name'],
								'split_price' => $bulk_price,
								'price' => $bulk_price,
								'status' => $val['product_status'],
								'products_status' => $val['product_status'],
								'products_status_2' => $val['product_status'],
								'packvalue_quantity' => $val['bulk_quantity'],
								'packet_brand' => $val['packet_brand'],
								'packet_size' => $val['packet_size'],
							]);
						}
					}
					
					if($val['supplier'] == "Other"){
						if(!DB::table('manufacturers')->where('manufacturers_name', trim($val['new_supplier']))->exists()){
							DB::table('manufacturers')->insert([
								'manufacturers_name' => $val['new_supplier'],
								'date_added' => now(),
								'last_modified' => now(),
							]);
						}
					}
					
					$purchased_products = DB::table('app_user_purchased_products')->insert([
						'orders_id' => $insert_orders_id,
						'product_id' => $val['product_id'],
						'product_name' => $val['product_name'],
						'product_code' => $val['product_code'],
						'bulk_quantity' => $val['bulk_quantity'],
						'packet_size' => $val['packet_size'],
						'total_basket_price' => $val['total_basket_price'],
						'type' => $val['type'],
						'splitPacketSize' => $val['split_packet_size'],
						'category_id' => $val['category_id'],
						'number_purchase' => $val['number_purchase'],
						'product_date_added' => $val['product_date_added'],
						'product_modified_date' => $val['product_modified_date'],
						'product_status' => $val['product_status'],
						'supplier' => $val['supplier'],
						'bulk_price' => $val['bulk_price'],
						'parent_id' => $val['parent_id'],
						'split_price' => $val['split_price'],
						'product_price' => $val['product_price'],
						'origin' => $val['origin'],
						'purchased_by_user' => $userID,
						'split_product_id' => $val['split_product_id'],
						'split_product_status' => $val['split_product_status'],
						'new_supplier' => $val['new_supplier'],
						'split_quantity' => $val['split_quantity'],
						'vat_include' => $val['vat_include'],
						'packet_brand' => $val['packet_brand'],
						'purchased_date' => now(),
					]);
				}
			}
			
			return $this->getUpdatedProductsList($insert_orders_id, $updatedProducts);
		}else{
			$statusValue = 'error';
			$data = ['message' => 'Please give at least one product details.'];
		}
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
	  
		return json_encode($jsonArray);
	}
	
	public function getUpdatedProductsList($order_id = 0, $updatedProducts){
		$statusValue = 'error';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'getPurchasedProductsLists';
		$jsonArray['status'] = $statusValue;
		
		$products = DB::table('products')->whereIn('products.id', $updatedProducts)->join('category_product as p2c', 'p2c.product_id', '=', 'products.id')->select('products.*','p2c.category_id');
		
		if($products->count() > 0){
			$statusValue = 'success';
			$data = [];
			
			foreach($products->get() as $key=>$product){
				$data[$key] = [
					'products_id' => $product->id,
					'categories_id' => $product->category_id,
					'products_name' => $product->name,
					'products_status' => $product->status,
					'products_price' => $product->price,
					'bulk_quantity' => $product->packvalue_quantity,
					'packet_size' => $product->packet_size,
					'vat_include' => '',
					'packet_brand' => $product->packet_brand,
					'parent_id' => $product->parent_id,
				];
			}
		}
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
		
		if($order_id > 0){
			$jsonArray['order_id']	=	$order_id;
		}
	  
		return json_encode($jsonArray);
	}

	public function getOrderHistoryData($maxorder_id){
		$statusValue = '';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'getOrderHistoryData';
		$jsonArray['status'] = $statusValue;
		
		$OrderHistoryQuery = DB::table('app_user_orders as auo')->where('auo.order_id', '>', $maxorder_id)->leftJoin('app_user_purchased_products as aupp', 'aupp.orders_id', '=', 'auo.order_id')->select('auo.order_id','auo.app_user','auo.order_create_date','auo.orders_total','auo.payment_mode','aupp.*');
		
		if($OrderHistoryQuery->count() > 0){
			$statusValue = 'success';
			$data = [];
			
			foreach($OrderHistoryQuery->get() as $key=>$OrderHistoryData){
				$app_customers_data = DB::table('app_user')->where('app_user_id', $OrderHistoryData->app_user)->select(DB::raw("CONCAT(first_name,' ',last_name) AS app_username"))->first();
				
				$data[$key] = [
					'order_id'		=>	$OrderHistoryData->order_id,
					'app_user'	=>	$app_customers_data->app_username,
					'order_create_date' => $OrderHistoryData->order_create_date,
					'orders_total' => $OrderHistoryData->orders_total,
					'payment_mode' => $OrderHistoryData->payment_mode,
					
					//Data from Purchased products table
					'purchased_products_id' => $OrderHistoryData->purchased_products_id,
					'bulk_price' 		=>	$OrderHistoryData->product_price,
					'origin' 			=>	$OrderHistoryData->origin,
					'number_purchase' 	=>	$OrderHistoryData->number_purchase,
					'product_date_added'=>	$OrderHistoryData->product_date_added,
					'product_id' 		=>	$OrderHistoryData->product_id,
					'type'				=>	$OrderHistoryData->type,
					'split_product_id' 	=>	$OrderHistoryData->split_product_id,
					'product_status'	=>	$OrderHistoryData->product_status,
					'supplier' 			=>	$OrderHistoryData->supplier,
					'split_packet_size' =>	$OrderHistoryData->splitPacketSize,
					'product_name'		=>	$OrderHistoryData->product_name,
					'new_supplier' 		=>	'',
					'bulk_quantity' 	=>	$OrderHistoryData->bulk_quantity,
					'product_modified_date' =>	$OrderHistoryData->product_modified_date,
					'category_id'		=>	$OrderHistoryData->category_id,
					'total_basket_price'=>	$OrderHistoryData->total_basket_price,
					'product_code' 		=>	$OrderHistoryData->product_code,
					'split_price' 		=>	$OrderHistoryData->split_price,
					'product_price'		=>	$OrderHistoryData->product_price,
					'packet_size' 		=>	$OrderHistoryData->packet_size,
					'parent_id' 		=>	$OrderHistoryData->parent_id,
					'vat_include' 		=>	$OrderHistoryData->vat_include,
					'packet_brand' 		=>	$OrderHistoryData->packet_brand,
					'split_product_status'=>$OrderHistoryData->split_product_status,
					'split_quantity' 	=>	$OrderHistoryData->split_quantity
				];
			}
		}else{
			$statusValue = 'error';
			$data = ['message' => 'New orders does not exist.'];
		}
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
		
		return json_encode($jsonArray);
	}

	public function makeprodcutoutofstock($productID,$split_product_id,$products_status_bulk,$split_product_status){
		$statusValue = '';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'makeprodcutoutofstock';
		$jsonArray['status'] = $statusValue;
		
		$productIDBoth[] = $productID;
		if($split_product_id != "" && is_numeric($split_product_id) && $split_product_id > 0) {
			
			DB::table('products')->where('id', $split_product_id)->update(['status' => $split_product_status, 'products_status' => $split_product_status, 'products_status_2' => $split_product_status]);
			
			$productIDBoth[] = $split_product_id;
		}else{
			DB::table('products')->where('id', $productID)->update(['status' => $products_status_bulk, 'products_status' => $products_status_bulk, 'products_status_2' => $products_status_bulk]);
		}
		
		$products = DB::table('products')->whereIn('products.id', $productIDBoth)->join('category_product as p2c', 'p2c.product_id', '=', 'products.id')->select('products.*','p2c.category_id');
		
		if($products->count() > 0){
			$statusValue = 'success';
			$data = [];
			
			foreach($products->get() as $key=>$product){
				$data[$key] = [
					'products_id' => $product->id,
					'categories_id' => $product->category_id,
					'products_name' => $product->name,
					'products_status' => $product->status,
					'products_price' => $product->price,
					'bulk_quantity' => $product->packvalue_quantity,
					'packet_size' => $product->packet_size,
					'vat_include' => '',
					'packet_brand' => $product->packet_brand,
					'parent_id' => $product->parent_id,
				];
			}
		}
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
		
		return json_encode($jsonArray);
	}

	public function addNewProduct($data, $userID){
		$statusValue = '';
		
		$jsonArray = [];
		$jsonArray['data'] = [];
		$jsonArray['method_name'] =	'addNewProduct';
		$jsonArray['status'] = $statusValue;
		
		if(count($data) > 0){
			$LastRecordID_BeforeAddNewProduct = DB::table('products')->latest('id')->select('id')->first()->id;
			
			foreach($data as $key => $val ){
				$insert_id = DB::table('products')->insertGetId([
					'product_code' => $val['bulk_product_code'],
					'name' => $val['product_name'],
					'packet_size' => $val['bulk_packet_size'],
					'type' => 'Bulk',
					'packvalue_quantity' => $val['bulk_quantity'],
					'parent_id' => '0',
					//'vat_include' => $val['vat_include'],
					'status' => $val['bulk_product_status'],
					'products_status' => $val['bulk_product_status'],
					'products_status_2' => $val['bulk_product_status'],
					'price' => $val['bulk_price'],
					'addedby' => 'appuser',
					'app_user_id' => $userID,
					'created_at' => now(),
					'updated_at' => now(),
				]);
				
				DB::table('category_product')->insert(['product_id' => $insert_id, 'category_id' => $val['subcategory_id']]);
				
				//we need to add split product of above products then below code will use Start
				
				$insert_id_split_product = DB::table('products')->insertGetId([
					'product_code' => $val['split_product_code'],
					'name' => $val['product_name'],
					'packet_size' => $val['split_packet_size'],
					'type' => 'Split',
					'packvalue_quantity' => $val['split_quantity'],
					'parent_id' => $insert_id,
					//'vat_include' => $val['vat_include'],
					'status' => $val['split_product_status'],
					'products_status' => $val['split_product_status'],
					'products_status_2' => $val['split_product_status'],
					'price' => $val['split_price'],
					'addedby' => 'appuser',
					'app_user_id' => $userID,
					'created_at' => now(),
					'updated_at' => now(),
				]);
				
				DB::table('category_product')->insert(['product_id' => $insert_id_split_product, 'category_id' => $val['subcategory_id']]);
				
				//we need to add split product of above products then below code will use End
			}
			
			return $this->getProductsList($LastRecordID_BeforeAddNewProduct);
		}else{
			$statusValue = 'error';
			$data = ['message' => 'Please give products details for add.'];
		}
		
		$jsonArray['data'] = $data;
		$jsonArray['status'] = $statusValue;
	  
		return json_encode($jsonArray);
	}

	public function update_ordered_products($data,$userID,$orderID){
		$statusValue = 'success';
		
		$jsonArray = [];
		$jsonArray['message'] = '';
		$jsonArray['method_name'] =	'update_ordered_products';
		$jsonArray['status'] = $statusValue;
		
		if(DB::table('app_user_purchased_products')->where(['product_id' => $data['product_id'], 'orders_id' => $orderID])->update([
			'number_purchase' => $data['products_Quantity'],
			'product_price' => $data['products_price'],
		])){
			$statusvalue = 'success';
			$msg = 'Record updated successfully';
		}else{
			$statusValue = 'error';
			$msg = 'Error updating record:';
		}
		
		$jsonArray['message'] = $msg;
		$jsonArray['status'] = $statusValue;
	  
		return json_encode($jsonArray);
	}
}
