<?php

namespace App\Helper;

use App\Shop\Categories\Category;

use DB;
use Session;
use Auth;
/**
 *------------------------------------------------------------------
 *  Class Generalfnv
 *------------------------------------------------------------------
 *  Description: This class is used for defining some common functions
 *  used in the project.
 *
 *  @author <>
 */
class Generalfnv
{
	
	 
    /**
     * Get constants settings
     *
     * @return settings array
     */
	 public static function getConstantsfnv()
	 {
		 return DB::table('settings')->first();
	 }
	 
	 /**
     * Get parent categories
     *
     * @return settings array
     */
	 public static function getParentCategories()
	 {
		 return Category::where(['parent_id' => 0])->get();
	 }
	 
	 /**
     * Get footer pages links
     *
     * @return settings array
     */
	 public static function getPagesLinks()
	 {
		 return DB::table('pages')->select('title','slug')->wherein('id', array(13,14,15,16,17,18,19,20,21,22,23,24,25,26))->get();
	 }
	 
	 
	 /**
     * Get delivery dates
     *
     * @return holiday details array
     */
	 public static function getDeliverydates()
	 {
		$delivery_date_total_days = config('constants.TOTAL_DELIVERY_DAYS'); 
		 for ($i = 1; $i < $delivery_date_total_days+1; $i++) {
				$date_of_delivery = date('Y-m-d', strtotime(date('Y-m-d') . ' +'.$i.' day'));
				$timestamp_val = strtotime($date_of_delivery);
				$day_name = date("l", $timestamp_val);
				
				$is_holiday = DB::table('bankholidays')
											->where('holiday_date', $date_of_delivery)->count();
				
				if($day_name == 'Sunday' || $is_holiday > 0) {
					$delivery_date_total_days = $delivery_date_total_days+1;
				}
			}
		
			$holiday_dates = DB::table('bankholidays')->select('holiday_date')->get();
			
			$dates_holiday = '';
			foreach($holiday_dates as $holiday_date) {
				$dates_holiday .= date('d-m-Y', strtotime($holiday_date->holiday_date)).",";
			}
			
			$all_holiday_dates = rtrim($dates_holiday, ",");
			
			$holiday_details = array("all_holiday_dates" => $all_holiday_dates, "delivery_date_total_days" => $delivery_date_total_days);
			
		return $holiday_details;
	 }
	 
	 /**
     * Verify coupon code
     *
     * @return coupon status
     */
	 public static function verifyCouponcode($coupon_code, $category_ids = '', $product_ids = '', $cart_total)
	 {
             //echo $category_ids."<br>";
            // echo $product_ids."<br>";
            // exit;
           $coupon_is_invalid = 0;
           $coupon_details = DB::table('coupons')->where('coupon_code', $coupon_code)->first();
          
           if(isset($coupon_details->coupon_code) && !empty($coupon_details->coupon_code)) {
                
                $currDate = date('Y-m-d');
                $currDate=date('Y-m-d', strtotime($currDate));
                //echo $paymentDate; // echos today! 
                $coupon_start_date = date('Y-m-d', strtotime($coupon_details->coupon_start_date));
                $coupon_end_date = date('Y-m-d', strtotime($coupon_details->coupon_expire_date));

                //check coupon code expired or not.
                if (($currDate < $coupon_start_date) || ($currDate > $coupon_end_date) || (isset($coupon_details->coupon_minimum_order) && !empty($coupon_details->coupon_minimum_order) && $coupon_details->coupon_minimum_order > $cart_total)){
					 $coupon_is_invalid++;
                }
                
                //track coupon if total_coupon_uses added for coupon.
				if(isset($coupon_details->uses_per_coupon) && !empty($coupon_details->uses_per_coupon)) {
					$total_coupon_uses = DB::table('coupon_redeem_track')
											->where('coupon_id', $coupon_details->id)->count();
						if($total_coupon_uses >= $coupon_details->uses_per_coupon) {
								$coupon_is_invalid++;
						}
				}
                
                //track coupon if total_coupon_uses_per_customer added for coupon.
				if(isset($coupon_details->uses_per_user) && !empty($coupon_details->uses_per_user)) {
						$total_coupon_uses_byme = DB::table('coupon_redeem_track')
													->where(['coupon_id' => $coupon_details->id, 'customer_id' => Auth()->user()->id])->count();
													
							if($total_coupon_uses_byme >= $coupon_details->uses_per_user) {
								$coupon_is_invalid++;
							}
					}
               
            //track coupon if coupon restrict to products
               $coupon_allowed = 0;
               if($product_ids != '') {
                   //echo "<pre>"; print_r(explode(",",$coupon_details->restrict_to_products)); 
                   $product_ids_arr = explode(",",$product_ids);
                   foreach(explode(",",$coupon_details->restrict_to_products) as $restrict_product) {
                      if(in_array($restrict_product, $product_ids_arr)) {
                          $coupon_allowed = 1;
                      }
                   }
               }
                
                
            //track coupon if coupon restrict to category
               if($category_ids != '') {
                   //echo "<pre>"; print_r(explode(",",$coupon_details->restrict_to_products)); 
                   $cat_ids_arr = explode(",",$category_ids);
                   foreach(explode(",",$coupon_details->restrict_to_categories) as $restrict_cat) {
                      if(in_array($restrict_cat, $cat_ids_arr)) {
                          $coupon_allowed = 1;
                      }
                   }
               }
               
               //track coupon if coupon restrict to products or category
               if(($product_ids != '' || $category_ids != '') && $coupon_allowed == 0) {
                   
                   $coupon_is_invalid++;
               }
               
               
               if($coupon_is_invalid>0) {

                    Session::put('discount_coupon_amount', '');
                    Session::put('coupon_code', '');
                    Session::put('coupon_id', '');
                    return FALSE;
					
                } else {
                        //success
                        Session::put('coupon_code', $coupon_details->coupon_code);
                        Session::put('coupon_id', $coupon_details->id);
                        
                        
                        if($coupon_details->coupon_amount_type == 'percentage') {
                            
                            $coupon_dis_amount = $cart_total*$coupon_details->coupon_amount/100;
                            $coupon_amount_type = '%';
                        } else {
                            $coupon_dis_amount = $coupon_details->coupon_amount;
                            $coupon_amount_type = '';
                        }
                        session(['discount_coupon_amount' => $coupon_dis_amount]);
                        //Session::put('discount_coupon_amount', $coupon_dis_amount);
						
                        return true;
               }
            
           } else {
               
			   return FALSE;
               
           }
	 }
	 
	 /**
     * Verify coupon code of order
     *
     * @return coupon status
     */
	 public static function verifyOrderCouponCode($coupon_code, $category_ids = '', $product_ids = '', $cart_total){
		$coupon_is_invalid = 0;
		$coupon_details = DB::table('coupons')->where('coupon_code', $coupon_code)->first();
          
		if(isset($coupon_details->coupon_code) && !empty($coupon_details->coupon_code)) {
			$currDate = date('Y-m-d');
			$currDate=date('Y-m-d', strtotime($currDate));
			//echo $paymentDate; // echos today! 
			
			$coupon_start_date = date('Y-m-d', strtotime($coupon_details->coupon_start_date));
			$coupon_end_date = date('Y-m-d', strtotime($coupon_details->coupon_expire_date));

			//check coupon code expired or not.
			if (($currDate < $coupon_start_date) || ($currDate > $coupon_end_date) || (isset($coupon_details->coupon_minimum_order) && !empty($coupon_details->coupon_minimum_order) && $coupon_details->coupon_minimum_order > $cart_total)){
				$coupon_is_invalid++;
			}
			
			//track coupon if total_coupon_uses added for coupon.
			if(isset($coupon_details->uses_per_coupon) && !empty($coupon_details->uses_per_coupon)) {
				$total_coupon_uses = DB::table('coupon_redeem_track')->where('coupon_id', $coupon_details->id)->count();
				
				if($total_coupon_uses >= $coupon_details->uses_per_coupon) {
					$coupon_is_invalid++;
				}
			}
			
			//track coupon if total_coupon_uses_per_customer added for coupon.
			if(isset($coupon_details->uses_per_user) && !empty($coupon_details->uses_per_user)) {
				$total_coupon_uses_byme = DB::table('coupon_redeem_track')->where(['coupon_id' => $coupon_details->id, 'customer_id' => Auth()->user()->id])->count();
											
				if($total_coupon_uses_byme >= $coupon_details->uses_per_user) {
					$coupon_is_invalid++;
				}
			}
		   
			//track coupon if coupon restrict to products
			$coupon_allowed = 0;
			if($product_ids != '') {
				//echo "<pre>"; print_r(explode(",",$coupon_details->restrict_to_products)); 
				$product_ids_arr = explode(",",$product_ids);
				foreach(explode(",",$coupon_details->restrict_to_products) as $restrict_product) {
					if(in_array($restrict_product, $product_ids_arr)) {
						$coupon_allowed = 1;
					}
				}
			}
			
			
			//track coupon if coupon restrict to category
			if($category_ids != '') {
				//echo "<pre>"; print_r(explode(",",$coupon_details->restrict_to_products)); 
				$cat_ids_arr = explode(",",$category_ids);
				foreach(explode(",",$coupon_details->restrict_to_categories) as $restrict_cat) {
					if(in_array($restrict_cat, $cat_ids_arr)) {
						$coupon_allowed = 1;
					}
				}
			}
		   
			//track coupon if coupon restrict to products or category
			if(($product_ids != '' || $category_ids != '') && $coupon_allowed == 0) {
				$coupon_is_invalid++;
			}
		   
		   
			if($coupon_is_invalid>0) {
				return FALSE;
			} else {
				//success
				return true;
			}
		
		} else {               
			return FALSE;
		}
	}
	 
	 /* 
	 * check permission for employee user
	 */
	 public static function check_permission($permission = '') {
		
		$user = Auth::guard('employee')->user();
		
		$allow = 0;
                
		if((isset($permission) && $user->hasPermission($permission)) || $user->hasRole('superadmin')) {
			$allow = 1;
		}
		return $allow;
	 }
	 
	 
}
