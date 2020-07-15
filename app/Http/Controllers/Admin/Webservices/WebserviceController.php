<?php

namespace App\Http\Controllers\Admin\Webservices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class WebserviceController extends Controller
{
   /**
     * Get the request from api.
     *
     * @return api response
     */
	
    public function index(Request $request) {

       $req_data = $request->all();

            $json	=	$req_data['jsonRequest'];
            $josonConverted	=	 json_decode(stripslashes($json));
            $mehodName	=	$josonConverted->method_name;

            if($mehodName	== 'userLogin'){
                    $data	=	$josonConverted->data;
                    $email_id	=	 $data->email_id;
                    $password	= 	 $data->password;
                    echo $returnData	=	$this->checkUserLogin($email_id, $password);
                    exit;

            }elseif($mehodName	== 'paswordRecovery'){

                    $data	=	$josonConverted->data;
                    $email_id	=	 $data->email_id;
                    echo $returnData	=	$this->passwordRecovery($email_id);
            }
            elseif($mehodName	== 'getCategorieslist'){

                    echo $returnData	=	$this->getCategorieslist();

            }elseif($mehodName	== 'getSubCategorieslist'){

                    echo $returnData	=	$this->getSubCategorieslist();

            }elseif($mehodName	== 'getProductsList'){

                    echo $returnData	=	$this->getProductsList($order_id);

            }elseif($mehodName	== 'getSupplayersList'){

                    echo $returnData	=	$this->getSupplayersList();

            }elseif($mehodName	== 'getCountryList'){

                    echo $returnData	=	$this->getCountryList();

            }elseif($mehodName	== 'getPurchasedProductsLists'){

                    $data_ordered_products	=	$josonConverted->data;
                    $userID	=	$josonConverted->userID;
                    $cartID	=	$josonConverted->cartID;
                    $orders_total = $josonConverted->orders_total;
                    $payment_mode = $josonConverted->payment_mode;
                    echo $returnData	=	$this->getPurchasedProductsLists($data_ordered_products,$userID,$cartID='',$orders_total,$payment_mode);

            }elseif($mehodName	== 'getOrderHistoryData'){

                    $maxorder_id	=	$josonConverted->maxorder_id;	
                    echo $returnData	=	$this->getOrderHistoryData($maxorder_id);

            }elseif($mehodName	== 'makeprodcutoutofstock'){

                    $data	=	$josonConverted->data;	
                    $productID	= 	 $data->productID; // for bulk
                    $split_product_id	= 	 $data->split_product_id; // for split
                    $products_status_bulk	= 	 $data->product_status; // for bulk product status
                    $split_product_status	= 	 $data->split_product_status; // for bulk product status

                    echo $returnData	=	$this->makeprodcutoutofstock($productID,$split_product_id,$products_status_bulk,$split_product_status);

            }elseif($mehodName	== 'addNewProduct'){

                    $data_add_products	=	$josonConverted->data;	
                    $userID	=	$josonConverted->userID;
                    echo $returnData	=	$this->addNewProduct($data_add_products,$userID);

            }elseif($mehodName	== 'update_ordered_products'){

                    $data_add_products	=	$josonConverted->data;	
                    $userID	=	$josonConverted->userID;
                    $ordersID	=	$josonConverted->ordersID;	
                    echo $returnData	=	$this->update_ordered_products($data_add_products,$userID,$ordersID);

            }

    }

    function checkUserLogin($email_id, $password){

            $appUser_query = DB::table('app_user')->where(['email_address' => $email_id, 'password' => $password])->first();

            $totalRecord =  $appUser_query->count();

              $jsonArray = '';
              $statusValue =    '';
              if($totalRecord == 0){

                            $message	=	'Invalid user name or password';
                            $data	=	array('message'=>$message);
                            $statusValue	=	'error';

              }else{
                            //$message	=	'Login Successfully';
                            //$data[]	=	array('message'=>$message);
                            $statusValue	=	'success';

              }

               $data = array('app_user_id' => $app_user_id, 'first_name' => $first_name, 
                             'last_name' => $last_name, 'email_address' => $email_address, 
                             'create_account_date' => $create_account_date
                             );

                  /*
                    while ($app_userData = mysql_fetch_assoc($appUser_query)) {
                                    $app_user_id			=	$app_userData['app_user_id'];
                                    $first_name				=	$app_userData['first_name'];
                                    $last_name				=	$app_userData['last_name'];
                                    $email_address 			=	$app_userData['email_address'];
                                    $create_account_date  	=	$app_userData['create_account_date'];


                              $data[]	=	array('app_user_id'=>$app_user_id, 
                                                                            'first_name'=>$first_name,
                                                                            'last_name'	=>	$last_name, 
                                                                            'email_address'	=>	$email_address, 
                                                                            'create_account_date'	=>	$create_account_date
                             );

                    }
                    */

                    $jsonArray['data'] = $data;
                    //$jsonArray['is_mobile']	=	'iphone';
                    $jsonArray['method_name']	=	'userLogin';
                    $jsonArray['status']		=	$statusValue;


              return json_encode($jsonArray);


    }

    function passwordRecovery($email_id){

                $appUser_query = DB::table('app_user')->where('email_address', $email_id)->first();
                $totalRecord =   $appUser_query->count();
                
                $jsonArray = '';
                $statusValue = '';
                if($totalRecord == 0){		  
                             $message =	'Email address does not exist, Please enter correct email address.';
                             $data = array('message'=>$message);
                             $statusValue = 'error';

                } else {
                             $random = substr( md5(rand()), 0, 7);

                             $to = $email_id;
                             $subject = "Password Recovery";
                             $message = "This is your password '".$random."'";
                             $header = "From:rakesh.gupta@dotsquares.com \r\n";
                             $retval = mail ($to,$subject,$message,$header);
                             if( $retval == true )  
                             {
                                DB::table('app_user')->where('email_address', $email_id)->update(['password' => $random]);
                                $message = 'Your password has been sent on your email address.Please check email.';
                                $data = array('message'=>$message);
                                 $statusValue =	'success';
                             }
                             else
                             {
                                     $message =	'Error in sending email.';
                                     $data = array('message'=>$message);
                                     $statusValue = 'error';
                             }

                }


                /* while ($app_userData = mysql_fetch_assoc($appUser_query)) {
                             $app_user_id			=	$app_userData['app_user_id'];
                             $first_name				=	$app_userData['first_name'];
                             $last_name				=	$app_userData['last_name'];
                             $email_address 			=	$app_userData['email_address'];
                             $create_account_date  	=	$app_userData['create_account_date'];


                             $data[]	=	array('app_user_id'=>$app_user_id, 
                                                                     'first_name'=>$first_name,
                                                                     'last_name'	=>	$last_name, 
                                                                     'email_address'	=>	$email_address, 
                                                                     'create_account_date'	=>	$create_account_date
                      );

             }*/


                     $jsonArray['data'] = $data;
                     //$jsonArray['is_mobile']	=	'iphone';
                     $jsonArray['method_name']	= 'paswordRecovery';
                     $jsonArray['status'] = $statusValue;
                     
                     return json_encode($jsonArray);
    }

    //echo getCategorieslist();
    function getCategorieslist(){

                                //misc category should not include into the list
                             // $catrgoryQuery = mysql_query("select c.*,cd.categories_name  from categories  as c,categories_description as cd  where c.categories_id = cd.categories_id and c.parent_id='0' and cd.language_id='1' and c.categories_id!=43 order by c.sort_order asc ");
                              $catrgoryQuery = DB::table('categories')->where(['parent_id' => 0])->get();
                              $totalRecord =   $catrgoryQuery->count();
                              $data = array();
                              if($totalRecord > 0){
                                    //	$message	=	'categorylist';
                                            //$data[]	=	array('message'=>$message);
                                            $statusValue	=	'success';
                                      foreach($catrgoryQuery as $categoryData) {	

                                            $categories_id = $categoryData->id;
                                            $categories_name = $categoryData->name;
                                            $parent_id = $categoryData->parent_id;
                                            $sort_order = 1;
                                            $date_added = $categoryData->created_at;
                                            $last_modified = $categoryData->updated_at;



                                            $data[] = array('categories_id'=>$categories_id,
                                                            'categories_name'=>$categories_name,
                                                            'parent_id'=>$parent_id,
                                                            'sort_order' => $sort_order, 
                                                            'date_added' => $date_added, 
                                                            'last_modified' => $last_modified	
                                                        );

                              }

                            }else{
                                        $message = 'category does not exist';
                                        $data[]	= array('message'=>$message);
                                        $statusValue = 'error';

                            }


                      $jsonArray['data'] = $data;
                      //$jsonArray['is_mobile']	=	'iphone';
                      $jsonArray['method_name']	= 'getCategorieslist';
                      $jsonArray['status'] = $statusValue;

                      return json_encode($jsonArray);

    }
        
    //echo getSubCategorieslist();
    function getSubCategorieslist(){

                               $catrgoryQuery = DB::table('categories')->where('parent_id', '!=', 0)->get();
                               $totalRecord =   $catrgoryQuery->count();
                               $data = array();
                               
                              if($totalRecord > 0){
                                            //$message	=	'Subcategorylist';
                                            //$data[]	=	array('message'=>$message);
                                            $statusValue	=	'success';
                                      foreach($catrgoryQuery as $categoryData) {	

                                            $categories_id = $categoryData->id;
                                            $categories_name = $categoryData->name;
                                            $parent_id = $categoryData->parent_id;
                                            $sort_order = 1;
                                            $date_added = $categoryData->created_at;
                                            $last_modified = $categoryData->updated_at;



                                            $data[] = array('categories_id'=>$categories_id,
                                                            'categories_name'=>$categories_name,
                                                            'parent_id'=>$parent_id,
                                                            'sort_order' => $sort_order, 
                                                            'date_added' => $date_added, 
                                                            'last_modified' => $last_modified	
                                                        );

                                        }

                                }else{
                                            $message	=	'Sub category does not exist';
                                            $data[]	=	array('message'=>$message);
                                            $statusValue	=	'error';
                                }


                      $jsonArray['data'] = $data;
                      //$jsonArray['is_mobile']	=	'iphone';
                      $jsonArray['method_name']	=	'getSubCategorieslist';
                      $jsonArray['status']		=	$statusValue;


              return json_encode($jsonArray);

    }


    function getProductsList($maxProductID = 0){
                                    if($maxProductID > 0){
                                            $condition = " AND p.products_id > '".$maxProductID."'";
                                    }else{
                                            $condition	=	'';
                                    }

                                    if($maxProductID > 0){
                                        $condition = "['products.products_id', '>', '".$maxProductID."'],";
                                    }else{
                                            $condition	=	'';
                                    }
									
                    $productsDatas = DB::table('products')
                                       ->leftJoin('category_product', 'category_product.product_id', '=', 'products.id')
                                       ->select('products.packet_brand', 'products.id', 'category_product.category_id', 'products.name', 'products.products_status'
                                           , 'products.price', 'products.created_at', 'products.updated_at', 'products.product_code', 'products.packet_size', 'products.type'
                                           , 'products.quantity', 'products.price', 'products.split_price', 'products.parent_id')
                                       ->where([
                                                   ['products.id', '=', 'category_product.products_id'],
                                                   $condition,
                                                   ['category_product.products_id', '=', 'p.products_id']               
                                               ])
                                       ->get();
 
                $totalRecord = $productsDatas->count();

                    if($totalRecord > 0){
                                            //$message	=	'Subcategorylist';
                                            //$data[]	=	array('message'=>$message);
                                            $statusValue	=	'success';
                                foreach($productsDatas as $productsData) {	

                                            $data[] = array(  
                                                                'products_id'			=>	$productsData->id,
                                                                'categories_id'			=>	$productsData->category_id,
                                                                'products_name'			=>	$productsData->name,
                                                                'products_status'               =>	$productsData->products_status,
                                                                'products_price'                =>	$productsData->price,
                                                                'products_date_added'           =>	$productsData->created_at,
                                                                'products_last_modified'        =>	$productsData->updated_at,
                                                                'product_code'			=>	$productsData->product_code,
                                                                'packet_size'			=>	$productsData->packet_size,
                                                                'type'				=>	$productsData->type,
                                                                'bulk_quantity'			=>	$productsData->quantity,
                                                                'bulk_price'			=>	$productsData->price,
                                                                'split_price'			=>	$productsData->split_price,
                                                                'parent_id'			=>	$productsData->parent_id,
                                                                'packet_brand'			=>	$productsData->packet_brand
                                            );

                              }

                      }else{
                                            $message	=	'Products does not exist';
                                            $data[]	=	array('message'=>$message);
                                            $statusValue	=	'error';

                      }


                      $jsonArray['data'] = $data;
                     /* if($order_id > 0){
                             $jsonArray['order_id']	=	$order_id;
                      }
                      */
                      $jsonArray['method_name']	=	'getProductsList';
                      $jsonArray['status']	=	$statusValue;


              return json_encode($jsonArray);



    }
        
    
    function getSupplayersList(){

                             //$manufacturersQuery = mysql_query("SELECT  *FROM  manufacturers");	
                             
                             $manufacturersDatas = DB::table('manufacturers')->get();
                             $totalRecord	=	$manufacturersDatas->count();

                              if($totalRecord > 0){
                                            //$message	=	'Subcategorylist';
                                            //$data[]	=	array('message'=>$message);
                                            $statusValue	=	'success';
                                      foreach($manufacturersDatas as $manufacturersData) {	




                                      $data[]	=	array(  
                                                                        'manufacturers_id'	=>	$manufacturersData->id,
                                                                        'manufacturers_name'	=>	$manufacturersData->name,
                                                                        'date_added'		=>	$manufacturersData->created_at,
                                                                        'last_modified'		=>	$manufacturersData->updted_at
                                     );

                              }

                      }else{
                                            $message	=	'Manufacturers does not exist';
                                            $data[]	=	array('message'=>$message);
                                            $statusValue	=	'error';

                      }


                      $jsonArray['data'] = $data;
                      //$jsonArray['is_mobile']	=	'iphone';
                      $jsonArray['method_name']	=	'getSupplayersList';
                      $jsonArray['status']		=	$statusValue;


              return json_encode($jsonArray);



    }
        
    
    function getCountryList(){

                               $countryDatas 	= 	DB::table('countries')->select('id', 'name')
                                                        ->orderBy('name', 'ASC')
                                                        ->get();						
                               $totalRecord     =	$countryDatas->count();

                              if($totalRecord > 0){
                                            //$message	=	'Subcategorylist';
                                            //$data[]	=	array('message'=>$message);
                                            $statusValue	=	'success';
                                      foreach($countryDatas as $countryData) {	

                                        $data[] =	array('countries_id' =>	$countryData['id'],
                                                        'countries_name' => $countryData['name']
                                                  );

                                        }

                                }else{
                                                      $message	=	'Country does not exist';
                                                      $data[]	=	array('message'=>$message);
                                                      $statusValue	=	'error';

                                }


                      $jsonArray['data'] = $data;
                      //$jsonArray['is_mobile']	=	'iphone';
                      $jsonArray['method_name']	= 'getCountryList';
                      $jsonArray['status']	= $statusValue;

                       return json_encode($jsonArray);



    }

    function getPurchasedProductsLists($data_ordered_products,$userID,$cartID,$orders_total,$payment_mode=''){

                    if(count($data_ordered_products) > 0){

                            $insert_orders_id = DB::table('app_user_orders')->insertGetId(
                                        ['app_user' => $userID, 'order_create_date' => now(), 'orders_total' => $orders_total, 'payment_mode' => $payment_mode]
                                    );

                                  foreach($data_ordered_products as $key => $val ){

                                 if($val->IsMakeProductOutOfStock == 1){
                                                $updatedProducts[]=$val->product_id;
                                                
                                                DB::table('products')
                                                ->where('id', $val->product_id)
                                                ->update(['products_status' => $val->product_status, 'products_status_2' => $val->product_status]);
                                                
                                 }else{

                                   $products_data_count = DB::table('products')->select('products_id')->where('products_id', $val->product_id)->count();
                                   
                                   if($products_data_count > 0){			     	
                                                $bulk_price = $val->product_price;
                                                $split_price = $val->split_price;
                                                $updatedProducts[]=$val->product_id;
                                                
                                    DB::table('products')
                                                ->where('id', $val->product_id)
                                                ->update(['name' => $val->product_name, 'split_price' => $bulk_price, 'products_price' => $bulk_price, 'products_status' => $val->product_status
                                                        , 'products_status_2' => $val->product_status, 'bulk_quantity' => $val->bulk_quantity, 'packet_brand' => $val->packet_brand
                                                        , 'packet_size' => $val->packet_size]);             
                                                
                                    if($val->split_product_id > 0){
                                                $updatedProducts[]=$val->split_product_id;
                                                
                                                DB::table('products')
                                                ->where('id', $val->split_product_id)
                                                ->update(['name' => $val->product_name, 'split_price' => $split_price, 'products_price' => $split_price, 'products_status' => $val->split_product_status
                                                        , 'products_status_2' => $val->split_product_status, 'bulk_quantity' => $val->split_quantity, 'packet_brand' => $val->packet_brand
                                                        , 'packet_size' => $val->split_packet_size]);
                                                
                                               
                                                }

                                   }

                                        if($val->supplier == "Other"){ 

                                            $totalRecord     =	DB::table('manufacturers')->where('name', trim($val->new_supplier))->count();
                                            
                                              if($totalRecord == 0){

                                                                    DB::table('manufacturers')->insert(['name' => trim($val->new_supplier), 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                                                }
                                        }

                                    DB::table('app_user_purchased_products')->insert(['product_id' => $val->product_id, 'orders_id' => $insert_orders_id, 'product_name' => $val->product_name, 'product_code' => $val->product_code, 'bulk_quantity' => $val->bulk_quantity, 'packet_size' => $val->packet_size, 'total_basket_price' => $val->total_basket_price, 'type' => $val->type,
                                    'splitPacketSize' => $val->split_packet_size, 'category_id' => $val->category_id, 'number_purchase' => $val->number_purchase, 'product_date_added' => $val->product_date_added, 'product_modified_date' => $val->product_modified_date, 'product_status' => $val->product_status, 'supplier' => $val->supplier, 'bulk_price' => $val->bulk_price,
                                    'parent_id' => $val->parent_id, 'split_price' => $val->split_price, 'product_price' => $val->product_price, 'origin' => $val->origin, 'purchased_by_user' => $userID, 'split_product_id' => $val->split_product_id, 'split_product_status' => $val->split_product_status,
                                    'new_supplier' => $val->new_supplier, 'split_quantity' => $val->split_quantity, 'packet_brand' => $val->packet_brand, 'purchased_date' => date('Y-m-d H:i:s')]);

                                    }


                                }

                                }
                                /*$data[]	=	array('message'=>"Purchased products has been added in server");
                                $jsonArray['data'] = $data;
                                //$jsonArray['is_mobile']	=	'iphone';
                                $jsonArray['method_name']	=	'getProductsList';
                                $jsonArray['status']		=	'success';*/

                                $updatedproductslist  =   getUpdatedProductsList($insert_orders_id,$updatedProducts);

                           return $updatedproductslist;



        }

        function getUpdatedProductsList($order_id=0,$updatedProducts){


                        foreach($updatedProducts as $key => $value){
                            
                        
                            
                       $productsData = DB::table('products')
                                    ->leftJoin('category_product', 'category_product.product_id', '=', 'products.id')
                                    ->select('products.packet_brand', 'products.id', 'category_product.category_id', 'products.name', 'products.products_status'
                                                , 'products.price', 'products.quantity', 'products.packet_size'
                                                , 'products.parent_id')
                                    ->where([
                                                ['products.id', '=', 'category_product.products_id'],
                                                ['category_product.products_id', '=', 'products.id'],
                                                ['products.id', '=', $value]
                                            ])
                                    ->first();
                       
                       $totalRecord	=	$productsDatas->count();
                       
                        if($totalRecord > 0){
                                                
                                    $statusValue	=	'success';
                                          	  
                                    $data[]	=	array(  
                                                                'products_id'			=>	$productsData->id,
                                                                'categories_id'			=>	$productsData->category_id,										
                                                                'products_status'		=>	$productsData->products_status,
                                                                'products_price'		=>	$productsData->price,
                                                                'bulk_quantity'			=>	$productsData->quantity,
                                                                'packet_size'			=>	$productsData->packet_size,
                                                                'products_name'			=>	$productsData->name,
                                                                //'vat_include'			=>	$productsData['vat_include'],
                                                                'packet_brand'			=>	$productsData->packet_brand,
                                                                //'split_price'			=>	$split_products_price,
                                                                //'split_product_id'		=>	$split_products_id,
                                                                'parent_id'			=>	$productsData->parent_id

                                         );



                          }

                        }


                          $jsonArray['data'] = $data;
                          if($order_id > 0){
                                 $jsonArray['order_id']	=	$order_id;
                          }
                          $jsonArray['method_name']	=	'getUpdatedProductsList';
                          $jsonArray['status']		=	$statusValue;


                  return json_encode($jsonArray);



        }

        function getOrderHistoryData($maxorder_id){

                                //$OrderHistoryQuery  = mysql_query("Select auo.order_id,auo.app_user,auo.order_create_date,auo.orders_total,auo.payment_mode,aupp.* From app_user_orders as auo,app_user_purchased_products as aupp where auo.order_id = aupp.orders_id and auo.order_id > $maxorder_id");
                                //$totalRecord		=	mysql_num_rows($OrderHistoryQuery);
                                
                                $OrderHistoryDatas = DB::table('app_user_orders')
                                                    ->leftJoin('app_user_purchased_products', 'app_user_purchased_products.orders_id', '=', 'app_user_orders.order_id')
                                                   ->select('app_user_orders.order_id', 'app_user_orders.app_user', 'app_user_orders.order_create_date', 'app_user_orders.orders_total', 'app_user_orders.payment_mode', 'app_user_purchased_products.*')
                                                   ->where([
                                                               ['app_user_orders.order_id', '=', 'app_user_purchased_products.orders_id'],
                                                               ['app_user_orders.order_id', '>', $maxorder_id]               
                                                           ])
                                                   ->get();
                                $totalRecord = $OrderHistoryDatas->count();
                                
                                if($totalRecord > 0){

                                                $statusValue	=	'success';
                                                foreach($OrderHistoryDatas as $OrderHistoryData) {
                                                               
                                                    $app_customers_data=mysql_fetch_assoc(mysql_query("select CONCAT(first_name,' ',last_name) as app_username from app_user where app_user_id 	='".$OrderHistoryData['app_user']."'"));
                                                    $app_username = $app_customers_data['app_username'];	


                                                    $data[] =	array(  
                                                                        'order_id'      =>	$OrderHistoryData->order_id,
                                                                        'app_user'	=>	$app_username,
                                                                        'order_create_date' => $OrderHistoryData->order_create_date,
                                                                        'orders_total' => $OrderHistoryData->orders_total,
                                                                        'payment_mode' => $OrderHistoryData->payment_mode,
                                                                        //Data from Purchased products table
                                                                        'purchased_products_id' => $OrderHistoryData->purchased_products_id,												
                                                                        'bulk_price' 		=>	$OrderHistoryData->product_price,
                                                                        'origin' 		=>	$OrderHistoryData->origin,
                                                                        'number_purchase' 	=>	$OrderHistoryData->number_purchase,
                                                                        'product_date_added'    =>	$OrderHistoryData->product_date_added,
                                                                        'product_id' 		=>	$OrderHistoryData->product_id,
                                                                        'type'			=>	$OrderHistoryData->type,
                                                                        'split_product_id' 	=>	$OrderHistoryData->split_product_id,
                                                                        'product_status'	=>	$OrderHistoryData->product_status,
                                                                        'supplier' 		=>	$OrderHistoryData->supplier,
                                                                        'split_packet_size'     =>	$OrderHistoryData->splitPacketSize,
                                                                        'product_name'		=>	$OrderHistoryData->product_name,
                                                                        'new_supplier' 		=>	$OrderHistoryData->countries_id,
                                                                        'bulk_quantity' 	=>	$OrderHistoryData->bulk_quantity,
                                                                        'product_modified_date' =>	$OrderHistoryData->product_modified_date,
                                                                        'category_id'		=>	$OrderHistoryData->category_id,
                                                                        'total_basket_price'    =>	$OrderHistoryData->total_basket_price,
                                                                        'product_code' 		=>	$OrderHistoryData->product_code,
                                                                        'split_price' 		=>	$OrderHistoryData->split_price,
                                                                        'product_price'		=>	$OrderHistoryData->product_price,
                                                                        'packet_size' 		=>	$OrderHistoryData->packet_size,
                                                                        'parent_id' 		=>	$OrderHistoryData->parent_id,
                                                                        'vat_include' 		=>	$OrderHistoryData->vat_include,
                                                                        'packet_brand' 		=>	$OrderHistoryData->packet_brand,
                                                                        'split_product_status'  =>      $OrderHistoryData->split_product_status,
                                                                        'split_quantity' 	=>	$OrderHistoryData->split_quantity

                                                                );

                                                }

                                }else{
                                                $message	=	'New orders does not exist';
                                                $data[]	=	array('message'=>$message);
                                                $statusValue	=	'error';

                          }


                          $jsonArray['data'] = $data;
                          //$jsonArray['is_mobile']	=	'iphone';
                          $jsonArray['method_name']	=	'getOrderHistoryData';
                          $jsonArray['status']		=	$statusValue;


                  return json_encode($jsonArray);

        }

        function makeprodcutoutofstock($productID,$split_product_id,$products_status_bulk,$split_product_status){  
                 
                 $statusValue =	'success';			
                // mysql_query("UPDATE products set  products_status ='".$products_status_bulk."',products_status_2='".$products_status_bulk."'  where products_id = '".$productID."'");	  
                 
                 DB::table('products')->where('id', $productID)
                                      ->update(['products_status' => $products_status_bulk, 'products_status_2' => $products_status_bulk]); 
                 
                 
                 $productIDBoth = $productID;
                 if($split_product_id != "" && is_numeric($split_product_id) && $split_product_id > 0) {	  
                        
                     //mysql_query("UPDATE products set  products_status ='".$split_product_status."',products_status_2='".$split_product_status."' where products_id = '".$split_product_id."'");	  
                  
                    
                    DB::table('products')->where('id', $split_product_id)
                                      ->update(['products_status' => $split_product_status, 'products_status_2' => $split_product_status]);
                  
                    $productIDBoth .= ','.$split_product_id;
                  }

               $productsDatas = DB::table('products')
                                    ->leftJoin('category_product', 'category_product.product_id', '=', 'products.id')
                                    ->select('products.packet_brand', 'products.id', 'category_product.category_id', 'products.name', 'products.products_status'
                                                , 'products.price', 'products.quantity', 'products.packet_size'
                                                , 'products.parent_id')
                                    ->where([
                                                ['products.id', '=', 'category_product.products_id'],
                                                ['category_product.products_id', '=', 'products.id'],
                                                ['products.id', 'IN', $productIDBoth]
                                            ])
                                    ->get();
                
                $totalRecord	=	$productsDatas->count();
                
                
                if($totalRecord > 0){
                                        //$message	=	'Subcategorylist';
                                        //$data[]	=	array('message'=>$message);
                                 $statusValue	=	'success';
                                 foreach($productsDatas as $productsData){		  


                                 $data[] = array(  
                                                    'products_id'               =>	$productsData->id,
                                                    'categories_id'		=>	$productsData->category_id,										
                                                    'products_status'           =>	$productsData->products_status,
                                                    'products_price'            =>	$productsData->products_price,
                                                    'bulk_quantity'		=>	$productsData->bulk_quantity,
                                                    'packet_size'		=>	$productsData->packet_size,
                                                    'products_name'		=>	$productsData->products_name,
                                                    //'vat_include'		=>	$productsData['vat_include'],
                                                    'packet_brand'		=>	$productsData->packet_brand,
                                                    //'split_price'		=>	$split_products_price,
                                                    //'split_product_id'	=>	$split_products_id,
                                                    'parent_id'			=>	$productsData->parent_id
                                        );


                                }



                }



                $jsonArray['data'] = $data;
                //$jsonArray['is_mobile']	=	'iphone';
                $jsonArray['method_name']	=	'makeprodcutoutofstock';
                $jsonArray['status']		=	$statusValue;


                  return json_encode($jsonArray);


        }

        function addNewProduct($data_add_products, $userID){
         
            //$maxproductid = mysql_fetch_assoc(mysql_query("SELECT MAX( products_id ) as maxid FROM products"));
        
            $maxproductid = DB::table('products')->select('id')->orderBy('id', 'DESC')->first();
        
            $LastRecordID_BeforeAddNewProduct = $maxproductid->id;

                if(count($data_add_products) > 0){		
                    foreach($data_add_products as $key => $val ){
                             
                        $insert_id = DB::table('products')->insertGetId(
                                        ['product_code' => $val->bulk_product_code, 'name' => $val->product_name, 'packet_size' => $val->bulk_packet_size, 'type' => 'Bulk', 'quantity' => $val->bulk_quantity
                                        , 'parent_id' => 0, 'products_status' => $val->bulk_product_status, 'products_status_2' => $val->bulk_product_status, 'price' => $val->bulk_price
                                        , 'addedby' => 'appuser', 'app_user_id' => $userID, 'created_at' => date('Y-m-d H:i:s')]
                                    );
                        
                        DB::table('category_product')->insert(
                                        ['category_id' => $val->subcategory_id, 'product_id' => $insert_id]
                                    );
                        
                        
                        //we need to add split product of above products then below code will use Start
                        
                        $insert_id_split_product = DB::table('products')->insertGetId(
                                        ['product_code' => $val->split_product_code, 'name' => $val->product_name, 'packet_size' => $val->split_packet_size, 'type' => 'Split', 'quantity' => $val->split_quantity
                                        , 'parent_id' => $insert_id, 'products_status' => $val->split_product_status, 'products_status_2' => $val->split_product_status, 'price' => $val->split_price
                                        , 'addedby' => 'appuser', 'app_user_id' => $userID, 'created_at' => date('Y-m-d H:i:s')]
                                    );
                                     
                         DB::table('category_product')->insert(
                                        ['category_id' => $val->subcategory_id, 'product_id' => $insert_id_split_product]
                                    ); 

                         //we need to add split product of above products then below code will use End

                        }

                       }

                        //$addedNewProductsListing  =  getNewAddedProductsList($LastRecordID_BeforeAddNewProduct);
                          $addedNewProductsListing  =  getProductsList($LastRecordID_BeforeAddNewProduct);
                          return $addedNewProductsListing;



        }

        function update_ordered_products($data_add_products,$userID,$orderID){

         
            $Sql_purchased_products_updated = DB::table('app_user_purchased_products')
                                   ->where(['product_id' => $data_add_products->product_id, 'orders_id' => $orderID])
                                   ->update(['number_purchase' => $data_add_products->products_Quantity, 'product_price' => $data_add_products->products_price]); 

           if (isset($Sql_purchased_products_updated->number_purchase)) {
                   $messgaeValue = "Record updated successfully";
                   $statusvalue ="success";
           } else {
                   $messgaeValue =  "Error updating record:";
                   $statusvalue ="fail";
           }


                        $jsonArray['message']		=	$messgaeValue;			
                        $jsonArray['status']		=	$statusvalue;


                  return json_encode($jsonArray);

        }
}
