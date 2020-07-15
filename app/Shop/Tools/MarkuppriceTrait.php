<?php

namespace App\Shop\Tools;
use DB;

//use Illuminate\Http\UploadedFile;

trait MarkuppriceTrait
{
    /**
     * create a markup price of product
     *
     * @param UploadedFile $file
     * @param null $folder
     * @param string $disk
     * @param null $filename
     * @return updated price integer value
     */
    public function product_price_with_markup($product_type, $product_price, $product_category_id, $customersID = 0, $group_ID = 0){ 
		
		//get main category
        $product_main_category = DB::table("categories")->select('id', 'parent_id')->where("id", $product_category_id)->first();
		
		if($product_main_category->parent_id != 0){
			$product_main_category = DB::table("categories")->select('id', 'parent_id')->where("id", $product_main_category->parent_id)->first();
			
			if($product_main_category->parent_id != 0)
				$product_main_category = DB::table("categories")->select('id', 'parent_id')->where("id", $product_main_category->parent_id)->first();
		}
		
        if(env('CUSTOMERS_GROUP_PRICES_SETTING') == "true"){
		         
			$customers_groupID =0;
			
			if(isset($customersID) && $customersID != "" && $customersID >0 ){
				$check_customer_query_val = DB::table("customers")->select('customers_group_id')->where("id", $customersID)->first();
				
				// $check_customer_query_val = tep_db_query("select  customers_group_id from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($customersID) . "'");
				//$new_query_for_val = tep_db_fetch_array($check_customer_query_val);	
				
				$customers_groupID = $check_customer_query_val->customers_group_id;	
			}

			if($group_ID > 0 && $customersID == 0){
				$customers_groupID = $group_ID;
			}
			
			if(isset($customers_groupID) && $customers_groupID != "" && $customers_groupID > 0 ){
				$check_charges_exist = DB::table("customers_groups_charges")->where(["group_id" => $customers_groupID, "category_id" => $product_main_category->id])->first();
				// $product_main_category_id = mysql_fetch_assoc(mysql_query( "select parent_id from categories where categories_id='".$product_category_id."'"));
				// $check_charges_exist      = mysql_query( "select * from  customers_custom_groups_charges where group_id ='".$customers_groupID."' and category_id = '".$product_main_category->id."'");
			}else{
				$check_charges_exist = DB::table("customers_groups_charges")->where(["group_id" => 1, "category_id" => $product_main_category->id])->first();
			   // $product_main_category_id = mysql_fetch_assoc(mysql_query( "select parent_id from categories where categories_id='".$product_category_id."'"));
				//$check_charges_exist = mysql_query( "select * from  customers_custom_groups_charges where group_id =1 and category_id = '".$product_main_category->id."'");
			}
		
		}else{
			$check_charges_exist = DB::table("customers_groups_charges")->where(["group_id" => 1, "category_id" => $product_main_category->id])->first();
		
			//$product_main_category_id = mysql_fetch_assoc(mysql_query( "select parent_id from categories where categories_id='".$product_category_id."'"));
			//$check_charges_exist 	= mysql_query( "select * from  customers_custom_groups_charges where group_id =1 and category_id = '".$product_main_category->id."'");
		}
	
		if( empty($check_charges_exist) ){
			$bulk_charges 	= 0;
			$split_charges	= 0;                        
        }else{
			//$get_charges 	= mysql_fetch_assoc($check_charges_exist);
			$bulk_charges	= $check_charges_exist->bulk_value;
			$split_charges	= $check_charges_exist->split_value;
        }
				
		if($product_type == 'Bulk') {
			$product_price = number_format($product_price + ($product_price*$bulk_charges/100),2);      
        }elseif($product_type == 'Split'){
			$product_price = number_format($product_price + ($product_price*$split_charges/100),2);    
        }
	
		return str_replace(",", "", $product_price);
    }
	
}
