<?php

namespace App\Shop\Webservices\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use DB;

class WebserviceRepository extends BaseRepository
{
    
		function checkUserLogin($email_id, $password){
			//$password = md5($password);
			 //echo "select * from app_user WHERE `email_address` = '".$email_id."' AND `password`	=	'".$password."' ";
			  $appUser_query = mysql_query("select * from app_user WHERE `email_address` = '".$email_id."' AND `password`	=	'".$password."' ");
			  $totalRecord	=	mysql_num_rows($appUser_query);
			  
			  $jsonArray	=	'';
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
			  
				$jsonArray['data'] = $data;
				//$jsonArray['is_mobile']	=	'iphone';
				$jsonArray['method_name']	=	'userLogin';
				$jsonArray['status']		=	$statusValue;
				 
			  
			  return json_encode($jsonArray);
			  

		}

		function passwordRecovery($email_id){
				 
				   $appUser_query = mysql_query("select * from app_user WHERE `email_address` = '".$email_id."'");
				   $totalRecord	=	mysql_num_rows($appUser_query);
				   
				   $jsonArray	=	'';
				   $statusValue =    '';
				   if($totalRecord == 0){		  
						$message	=	'Email address does not exist, Please enter correct email address.';
						$data	=	array('message'=>$message);
						$statusValue	=	'error';
						
				   }else{
						$random = substr( md5(rand()), 0, 7);
						
						$to = $email_id;
						$subject = "Password Recovery";
						$message = "This is your password '".$random."'";
						$header = "From:rakesh.gupta@dotsquares.com \r\n";
						$retval = mail ($to,$subject,$message,$header);
						if( $retval == true )  
						{
							mysql_query("UPDATE app_user SET `password` = '".$random."' WHERE `email_address` = '".$email_id."' ");
							
							$message	=	'Your password has been sent on your email address.Please check email.';
							$data	=	array('message'=>$message);
							$statusValue	=	'success';
						}
						else
						{
							$message	=	'Error in sending email.';
							$data	=	array('message'=>$message);
							$statusValue	=	'error';
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
					$jsonArray['method_name']	=	'paswordRecovery';
					$jsonArray['status']		=	$statusValue;
				 
			  
					return json_encode($jsonArray);
		}

		//echo getCategorieslist();
		function getCategorieslist(){
				
					  $catrgoryQuery = mysql_query("select c.*,cd.categories_name  from categories  as c,categories_description as cd  where c.categories_id = cd.categories_id and c.parent_id='0' and cd.language_id='1' and c.categories_id!=43 order by c.sort_order asc ");
					 
					  $totalRecord	=	mysql_num_rows($catrgoryQuery);
					  if($totalRecord > 0){
						//	$message	=	'categorylist';
							//$data[]	=	array('message'=>$message);
							$statusValue	=	'success';
						  while($categoryData = mysql_fetch_array($catrgoryQuery)) {	
						  
							$categories_id			=	$categoryData['categories_id'];
							$categories_name 		=	$categoryData['categories_name'];
							$parent_id				=	$categoryData['parent_id'];
							$sort_order				=	$categoryData['sort_order'];
							$date_added 			=	$categoryData['date_added'];
							$last_modified  		=	$categoryData['last_modified'];
						  
						  
					
						  $data[]	=	array('categories_id'=>$categories_id,
												'categories_name'=>$categories_name ,
												'parent_id'=>$parent_id,
												'sort_order'	=>	$sort_order, 
												'date_added'	=>	$date_added, 
												'last_modified'	=>	$last_modified	
						 );
					  
					  }
					  
				  }else{
							$message	=	'category does not exist';
							$data[]	=	array('message'=>$message);
							$statusValue	=	'error';
				  
				  }
				  
				  
				  $jsonArray['data'] = $data;
				  //$jsonArray['is_mobile']	=	'iphone';
				  $jsonArray['method_name']	=	'getCategorieslist';
				  $jsonArray['status']		=	$statusValue;
				 
			  
			  return json_encode($jsonArray);
				  
				  
				 
		}
		//echo getSubCategorieslist();
		function getSubCategorieslist(){
				
					   $catrgoryQuery = mysql_query("select c.*,cd.categories_name  from categories  as c,categories_description  as cd  where c.categories_id = cd.categories_id and c.parent_id !='0' and cd.language_id='1' order by c.sort_order asc ");
					  
					  $totalRecord	=	mysql_num_rows($catrgoryQuery);
					  if($totalRecord > 0){
							//$message	=	'Subcategorylist';
							//$data[]	=	array('message'=>$message);
							$statusValue	=	'success';
						  while($categoryData = mysql_fetch_array($catrgoryQuery)) {	
						  
							$categories_id			=	$categoryData['categories_id'];
							$categories_name 		=	$categoryData['categories_name'];
							$parent_id				=	$categoryData['parent_id'];
							$sort_order				=	$categoryData['sort_order'];
							$date_added 			=	$categoryData['date_added'];
							$last_modified  		=	$categoryData['last_modified'];
						  
					
						  $data[]	=	array('categories_id'=>$categories_id,
												'categories_name'=>$categories_name ,
												'parent_id'=>$parent_id,
												'sort_order'	=>	$sort_order, 
												'date_added'	=>	$date_added, 
												'last_modified'	=>	$last_modified	
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
				
						$productsQuery = mysql_query("SELECT  p.packet_brand,p.vat_include,p.products_id,p2c.categories_id,pd.products_name,p.products_status,
																		p.products_price,p.products_date_added,p.products_last_modified,
																		p.product_code,p.packet_size,p.type,p.bulk_quantity,p.bulk_price,
																		p.split_price,p.parent_id   
															FROM  products AS p, products_description AS pd , products_to_categories AS p2c
															WHERE 
																	p.products_id = p2c.products_id
															AND pd.products_id = p2c.products_id
															AND pd.language_id = 1
															".$condition."
															AND p2c.products_id = p.products_id");						
					  
					   $totalRecord	=	mysql_num_rows($productsQuery);
					 
					 
					 
					  if($totalRecord > 0){
							//$message	=	'Subcategorylist';
							//$data[]	=	array('message'=>$message);
							$statusValue	=	'success';
						  while($productsData = mysql_fetch_array($productsQuery)) {	
						  
							
						  
					
						  $data[]	=	array(  
												'products_id'			=>	$productsData['products_id'],
												'categories_id'			=>	$productsData['categories_id'],
												'products_name'			=>	$productsData['products_name'],
												'products_status'		=>	$productsData['products_status'],
												'products_price'		=>	$productsData['products_price'],
												'products_date_added'	=>	$productsData['products_date_added'],
												'products_last_modified'=>	$productsData['products_last_modified'],
												'product_code'			=>	$productsData['product_code'],
												'packet_size'			=>	$productsData['packet_size'],
												'type'					=>	$productsData['type'],
												'bulk_quantity'			=>	$productsData['bulk_quantity'],
												'bulk_price'			=>	$productsData['bulk_price'],
												'split_price'			=>	$productsData['split_price'],
												'parent_id'				=>	$productsData['parent_id'],
												'vat_include'			=>	$productsData['vat_include'],
												'packet_brand'			=>	$productsData['packet_brand']
												
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
				  $jsonArray['status']		=	$statusValue;
				 
			  
			  return json_encode($jsonArray);
				  
				  
				 
		}
		function getSupplayersList(){
				
								$manufacturersQuery = mysql_query("SELECT  *   FROM  manufacturers");						
					  
					   $totalRecord	=	mysql_num_rows($manufacturersQuery);
					 
					  if($totalRecord > 0){
							//$message	=	'Subcategorylist';
							//$data[]	=	array('message'=>$message);
							$statusValue	=	'success';
						  while($manufacturersData = mysql_fetch_array($manufacturersQuery)) {	
						  
							
						  
					
						  $data[]	=	array(  
												'manufacturers_id'		=>	$manufacturersData['manufacturers_id'],
												'manufacturers_name'	=>	$manufacturersData['manufacturers_name'],
												'date_added'			=>	$manufacturersData['date_added'],
												'last_modified'			=>	$manufacturersData['last_modified']
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
				
					   $countryQuery 	= 	mysql_query("SELECT  countries_id,countries_name   FROM  countries where allowed_app ='y' ORDER BY countries_name ASC");						
					   $totalRecord			=	mysql_num_rows($countryQuery);
					 
					  if($totalRecord > 0){
							//$message	=	'Subcategorylist';
							//$data[]	=	array('message'=>$message);
							$statusValue	=	'success';
						  while($countryData = mysql_fetch_array($countryQuery)) {	
					
						  $data[]	=	array(  
												'countries_id'		=>	$countryData['countries_id'],
												'countries_name'	=>	$countryData['countries_name']
												
						 );
					  
					  }
					  
				  }else{
							$message	=	'Country does not exist';
							$data[]	=	array('message'=>$message);
							$statusValue	=	'error';
				  
				  }
				  
				  
				  $jsonArray['data'] = $data;
				  //$jsonArray['is_mobile']	=	'iphone';
				  $jsonArray['method_name']	=	'getCountryList';
				  $jsonArray['status']		=	$statusValue;
				 
			  
			  return json_encode($jsonArray);
				  
				  
				 
		}

		function getPurchasedProductsLists($data_ordered_products,$userID,$cartID,$orders_total,$payment_mode=''){
			
					//if($cartID == '555'){
					
				//echo "<pre>";
			//	print_r($data_ordered_products);
				 if(count($data_ordered_products) > 0){
				 

					   mysql_query("INSERT into app_user_orders set app_user = '".$userID."', order_create_date=now(),orders_total='".$orders_total."',payment_mode='".$payment_mode."'");
					   $insert_orders_id = mysql_insert_id();

					  foreach($data_ordered_products as $key => $val ){
					  
					 if($val->IsMakeProductOutOfStock == 1){
							$updatedProducts[]=$val->product_id;
							mysql_query("UPDATE products set  products_status ='".$val->product_status."', products_status_2 ='".$val->product_status."'  where products_id = '".$val->product_id."'");

							if($val->split_product_id > 0){
								$updatedProducts[]=$val->split_product_id;
								mysql_query("UPDATE products set products_status ='".$val->split_product_status."', products_status_2 ='".$val->split_product_status."' where products_id = '".$val->split_product_id."'");
							}
					 }else{
					  
					   $products_query = mysql_query("select products_id  from  products where products_id = '".$val->product_id."'") ;
					   if(mysql_num_rows($products_query) > 0){			     	
							$bulk_price = $val->product_price;
							$split_price = $val->split_price;
							$updatedProducts[]=$val->product_id;
								mysql_query("UPDATE products set split_price 			=	'".$bulk_price."', 
																	products_price 		=	'".$bulk_price."' , 
																	products_status 	=	'".$val->product_status."', 
																	products_status_2 	=	'".$val->product_status."',
																	bulk_quantity		=	'".$val->bulk_quantity."',															
																	vat_include			=	'".$val->vat_include."',
																	packet_brand			=	'".$val->packet_brand."',
																	packet_size			=	'".$val->packet_size."'
																	where products_id	= 	'".$val->product_id."'");
								mysql_query("UPDATE products_description set products_name	=	'".$val->product_name."' where  products_id	= '".$val->product_id."' and language_id =1");
							if($val->split_product_id > 0){
							$updatedProducts[]=$val->split_product_id;
								mysql_query("UPDATE products set split_price 			=	'".$split_price."', 
																	products_price 		=	'".$split_price."', 
																	products_status 	=	'".$val->split_product_status."', 
																	products_status_2 	=	'".$val->split_product_status."',
																	bulk_quantity		=	'".$val->split_quantity."',
																	vat_include		=	'".$val->vat_include."',
																	packet_brand			=	'".$val->packet_brand."',															
																	packet_size			=	'".$val->split_packet_size."'
																	where products_id	= 	'".$val->split_product_id."'");
								mysql_query("UPDATE products_description set products_name	=	'".$val->product_name."' where  products_id	= 	'".$val->split_product_id."' and language_id =1");

							}
					   
					   }
					
						if($val->supplier == "Other"){ 
						
							$manufacturersQuery = mysql_query("SELECT  *   FROM  manufacturers where manufacturers_name = '".trim($val->new_supplier)."'");						
							$totalRecord	=	mysql_num_rows($manufacturersQuery);
										 
						  if($totalRecord == 0){
							
									mysql_query("INSERT into manufacturers set manufacturers_name = '".$val->new_supplier."', date_added= now(),last_modified=now()");
									
							
							}
						}
					 
					   
					   $Sql_purchased_products =  "INSERT into app_user_purchased_products set product_id 	= 	'".$val->product_id."',	
																					orders_id				= 	'".$insert_orders_id."',		   
																					product_name 			= 	'".$val->product_name."',
																					product_code 			= 	'".$val->product_code."',
																					bulk_quantity 			= 	'".$val->bulk_quantity."',
																					packet_size 			= 	'".$val->packet_size."',
																					total_basket_price 		= 	'".$val->total_basket_price."',
																					type 					= 	'".$val->type."',
																					splitPacketSize 		= 	'".$val->split_packet_size."',
																					category_id 			= 	'".$val->category_id."',
																					number_purchase 		= 	'".$val->number_purchase."',
																					product_date_added 		= 	'".$val->product_date_added."',
																					product_modified_date 	= 	'".$val->product_modified_date."',
																					product_status 			= 	'".$val->product_status."',
																					supplier 				= 	'".$val->supplier."',
																					bulk_price 				= 	'".$val->bulk_price."',
																					parent_id 				= 	'".$val->parent_id."',
																					split_price 			= 	'".$val->split_price."',
																					product_price 			= 	'".$val->product_price."',
																					origin 					= 	'".$val->origin."',
																					purchased_by_user 		= 	'".$userID."',
																					split_product_id 		= 	'".$val->split_product_id."',
																					split_product_status	=	'".$val->split_product_status."',
																					new_supplier 			= 	'".$val->new_supplier."',
																					split_quantity 			= 	'".$val->split_quantity."',
																					vat_include				=	'".$val->vat_include."',
																					packet_brand				=	'".$val->packet_brand."',
																					purchased_date = now()";
						mysql_query($Sql_purchased_products);
						
				 
					  
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
				
				$productsQuery = mysql_query("SELECT  p.packet_brand,p.vat_include,p.products_id,p2c.categories_id,
															p.products_price,p.parent_id,p.products_status,pd.products_name,p.bulk_quantity,p.packet_size    
															FROM  products AS p, products_description AS pd , products_to_categories AS p2c
															WHERE 
																p.products_id = p2c.products_id
															AND pd.products_id = p2c.products_id
															AND pd.language_id = 1
															AND p2c.products_id = p.products_id and p.products_id ='".$value."'");
				$totalRecord	=	mysql_num_rows($productsQuery);
				if($totalRecord > 0){
							//$message	=	'Subcategorylist';
							//$data[]	=	array('message'=>$message);
						  $statusValue	=	'success';
						  $productsData = mysql_fetch_array($productsQuery);
						  
						  // for the split products start
					/*$productsQuery_split = mysql_query("SELECT  p.products_id,p2c.categories_id,
															p.products_price,p.parent_id,p.products_status    
															FROM  products AS p, products_description AS pd , products_to_categories AS p2c
															WHERE 
																p.products_id = p2c.products_id
															AND pd.products_id = p2c.products_id
															AND pd.language_id = 1
															AND p2c.products_id = p.products_id and p.parent_id ='".$value."'"); 
					$totalRecord_split	=	mysql_num_rows($productsQuery_split);
					if($totalRecord_split > 0){
							//$message	=	'Subcategorylist';
							//$data[]	=	array('message'=>$message);
						  $statusValue	=	'success';
						  $productsData_split = mysql_fetch_array($productsQuery_split);
							$split_products_price = $productsData_split['products_price'];	
							$split_products_id = $productsData_split['products_id'];					
					}	*/  
						/// end		  
					
						 $data[]	=	array(  
												'products_id'			=>	$productsData['products_id'],
												'categories_id'			=>	$productsData['categories_id'],										
												'products_status'		=>	$productsData['products_status'],
												'products_price'		=>	$productsData['products_price'],
												'bulk_quantity'			=>	$productsData['bulk_quantity'],
												'packet_size'			=>	$productsData['packet_size'],
												'products_name'			=>	$productsData['products_name'],
												'vat_include'			=>	$productsData['vat_include'],
												'packet_brand'			=>	$productsData['packet_brand'],
												//'split_price'			=>	$split_products_price,
												//'split_product_id'		=>	$split_products_id,
												'parent_id'				=>	$productsData['parent_id']
												
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

					$OrderHistoryQuery  = mysql_query("Select auo.order_id,auo.app_user,auo.order_create_date,auo.orders_total,auo.payment_mode,aupp.* From app_user_orders as auo,app_user_purchased_products as aupp where auo.order_id = aupp.orders_id and auo.order_id > $maxorder_id");
					$totalRecord		=	mysql_num_rows($OrderHistoryQuery);
					 
					if($totalRecord > 0){
							
							$statusValue	=	'success';
							while($OrderHistoryData = mysql_fetch_array($OrderHistoryQuery)) {	
										$app_customers_data=mysql_fetch_assoc(mysql_query("select CONCAT(first_name,' ',last_name) as app_username from app_user where app_user_id 	='".$OrderHistoryData['app_user']."'"));
										$app_username = $app_customers_data['app_username'];	

					
									$data[]	=	array(  
														'order_id'		=>	$OrderHistoryData['order_id'],
														'app_user'	=>	$app_username,
														'order_create_date' => $OrderHistoryData['order_create_date'],
														'orders_total' => $OrderHistoryData['orders_total'],
														'payment_mode' => $OrderHistoryData['payment_mode'],
														//Data from Purchased products table
														'purchased_products_id' => $OrderHistoryData['purchased_products_id'],												
														'bulk_price' 		=>	$OrderHistoryData['product_price'],
														'origin' 			=>	$OrderHistoryData['origin'],
														'number_purchase' 	=>	$OrderHistoryData['number_purchase'],
														'product_date_added'=>	$OrderHistoryData['product_date_added'],
														'product_id' 		=>	$OrderHistoryData['product_id'],
														'type'				=>	$OrderHistoryData['type'],
														'split_product_id' 	=>	$OrderHistoryData['split_product_id'],
														'product_status'	=>	$OrderHistoryData['product_status'],
														'supplier' 			=>	$OrderHistoryData['supplier'],
														'split_packet_size' =>	$OrderHistoryData['splitPacketSize'],
														'product_name'		=>	$OrderHistoryData['product_name'],
														'new_supplier' 		=>	$OrderHistoryData['countries_id'],
														'bulk_quantity' 	=>	$OrderHistoryData['bulk_quantity'],
														'product_modified_date' =>	$OrderHistoryData['product_modified_date'],
														'category_id'		=>	$OrderHistoryData['category_id'],
														'total_basket_price'=>	$OrderHistoryData['total_basket_price'],
														'product_code' 		=>	$OrderHistoryData['product_code'],
														'split_price' 		=>	$OrderHistoryData['split_price'],
														'product_price'		=>	$OrderHistoryData['product_price'],
														'packet_size' 		=>	$OrderHistoryData['packet_size'],
														'parent_id' 		=>	$OrderHistoryData['parent_id'],
														'vat_include' 		=>	$OrderHistoryData['vat_include'],
														'packet_brand' 		=>	$OrderHistoryData['packet_brand'],
														'split_product_status'=>$OrderHistoryData['split_product_status'],
														'split_quantity' 	=>	$OrderHistoryData['split_quantity']
														
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
				$statusValue	=	'success';			
				mysql_query("UPDATE products set  products_status ='".$products_status_bulk."',products_status_2='".$products_status_bulk."'  where products_id = '".$productID."'");	  
			 $productIDBoth = $productID;
			 if($split_product_id != "" && is_numeric($split_product_id) && $split_product_id > 0) {	  
				mysql_query("UPDATE products set  products_status ='".$split_product_status."',products_status_2='".$split_product_status."' where products_id = '".$split_product_id."'");	  
			  $productIDBoth .= ','.$split_product_id;
			  }
				
				$productsQuery = mysql_query("SELECT  p.packet_brand,p.vat_include,p.products_id,p2c.categories_id,
															p.products_price,p.parent_id,p.products_status,pd.products_name,p.bulk_quantity,p.packet_size    
															FROM  products AS p, products_description AS pd , products_to_categories AS p2c
															WHERE 
																p.products_id = p2c.products_id
															AND pd.products_id = p2c.products_id
															AND pd.language_id = 1
															AND p2c.products_id = p.products_id and p.products_id IN (".$productIDBoth.")");
				$totalRecord	=	mysql_num_rows($productsQuery);
				if($totalRecord > 0){
							//$message	=	'Subcategorylist';
							//$data[]	=	array('message'=>$message);
						 $statusValue	=	'success';
						 while($productsData = mysql_fetch_array($productsQuery)){		  
							
					
						 $data[]	=	array(  
												'products_id'			=>	$productsData['products_id'],
												'categories_id'			=>	$productsData['categories_id'],										
												'products_status'		=>	$productsData['products_status'],
												'products_price'		=>	$productsData['products_price'],
												'bulk_quantity'			=>	$productsData['bulk_quantity'],
												'packet_size'			=>	$productsData['packet_size'],
												'products_name'			=>	$productsData['products_name'],
												'vat_include'			=>	$productsData['vat_include'],
												'packet_brand'			=>	$productsData['packet_brand'],
												//'split_price'			=>	$split_products_price,
												//'split_product_id'		=>	$split_products_id,
												'parent_id'				=>	$productsData['parent_id']
												
						 );
						 
						 
						}
					  
					  
					  
				}
				
				
			  
				$jsonArray['data'] = $data;
				//$jsonArray['is_mobile']	=	'iphone';
				$jsonArray['method_name']	=	'makeprodcutoutofstock';
				$jsonArray['status']		=	$statusValue;
				 
			  
			  return json_encode($jsonArray);
			  

		}

		function addNewProduct($data_add_products,$userID){
		$maxproductid = mysql_fetch_assoc(mysql_query("SELECT MAX( products_id ) as maxid FROM products"));
		$LastRecordID_BeforeAddNewProduct = $maxproductid['maxid'];

				if(count($data_add_products) > 0){		
					 foreach($data_add_products as $key => $val ){
					 
					$Sql_add_products =  "INSERT into products set product_code			= 	'".$val->bulk_product_code."',		   
																packet_size 	 		= 	'".$val->bulk_packet_size."',
																type 					= 	'Bulk',
																bulk_quantity 			= 	'".$val->bulk_quantity."',
																parent_id 				= 	'0',
																vat_include 			= 	'".$val->vat_include."',
																products_status 		= 	'".$val->bulk_product_status."',
																products_status_2 		= 	'".$val->bulk_product_status."',
																products_price			=   '".$val->bulk_price."',
																addedby					=	'appuser',
																app_user_id				=	'".$userID."',														
																products_date_added = now()";
					
					
					
					mysql_query($Sql_add_products);
					$insert_id =mysql_insert_id();
					
					$Sql_add_products_description1 =  "INSERT into products_description set products_id = '".$insert_id."',
																							products_name = '".$val->product_name."',	   
																							language_id = 1";
					mysql_query($Sql_add_products_description1);
					$Sql_add_products_description2 =  "INSERT into products_description set products_id = '".$insert_id."',																						   
																							language_id = 2";
					mysql_query($Sql_add_products_description2);
					$Sql_add_products_description3 =  "INSERT into products_description set products_id = '".$insert_id."',																						   
																							language_id = 3";
					mysql_query($Sql_add_products_description3);
					$Sql_add_products_description4 =  "INSERT into products_description set products_id = '".$insert_id."',																						   
																							language_id = 4";																					
					mysql_query($Sql_add_products_description4);
					
					$Sql_add_products_to_category =  "INSERT into products_to_categories set products_id = '".$insert_id."',
																							categories_id = '".$val->subcategory_id."'";
					mysql_query($Sql_add_products_to_category);
					
					//we need to add split product of above products then below code will use Start
					
					$Sql_add_products_split =  "INSERT into products set product_code		= 	'".$val->split_product_code."',		   
																	packet_size 	 		= 	'".$val->split_packet_size."',
																	type 					= 	'Split',
																	bulk_quantity 			= 	'".$val->split_quantity."',
																	parent_id 				= 	'".$insert_id."',
																	vat_include 			= 	'".$val->vat_include."',
																	products_status 		= 	'".$val->split_product_status."',
																	products_status_2 		= 	'".$val->split_product_status."',
																	products_price			=   '".$val->split_price."',
																	addedby					=	'appuser',
																	app_user_id			=	'".$userID."',															
																	products_date_added 	= 	now()";
					mysql_query($Sql_add_products_split);
					$insert_id_split_product =mysql_insert_id();
					
					$Sql_add_products_description1_split =  "INSERT into products_description set products_id = '".$insert_id_split_product."',
																							products_name = '".$val->product_name."',	   
																							language_id = 1";
					mysql_query($Sql_add_products_description1_split);
					$Sql_add_products_description2_split =  "INSERT into products_description set products_id = '".$insert_id_split_product."',																						   
																							language_id = 2";
					mysql_query($Sql_add_products_description2_split);
					$Sql_add_products_description3_split =  "INSERT into products_description set products_id = '".$insert_id_split_product."',																						   
																							language_id = 3";
					mysql_query($Sql_add_products_description3_split);
					$Sql_add_products_description4_split =  "INSERT into products_description set products_id = '".$insert_id_split_product."',																						   
																							language_id = 4";																					
					mysql_query($Sql_add_products_description4_split);
					
					$Sql_add_products_to_category_split =  "INSERT into products_to_categories set products_id = '".$insert_id_split_product."',
																							categories_id = '".$val->subcategory_id."'";
					mysql_query($Sql_add_products_to_category_split);
					
					//we need to add split product of above products then below code will use End
					
					}
				
				}
				
				
				 
				  
				  //$addedNewProductsListing  =  getNewAddedProductsList($LastRecordID_BeforeAddNewProduct);
				  $addedNewProductsListing  =  getProductsList($LastRecordID_BeforeAddNewProduct);
				  return $addedNewProductsListing;
				 
			  
			 
		}

		function update_ordered_products($data_add_products,$userID,$orderID){

		 $Sql_purchased_products_updated =  "UPDATE app_user_purchased_products SET 
																					number_purchase 	= 	'".$data_add_products->products_Quantity."',
																					product_price		=	   '".$data_add_products->products_price."'
																					where product_id 	= 	'".$data_add_products->product_id."' and orders_id='".$orderID."'";


		if (mysql_query($Sql_purchased_products_updated) === TRUE) {
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
