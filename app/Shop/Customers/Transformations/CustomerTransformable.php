<?php

namespace App\Shop\Customers\Transformations;

use App\Shop\Customers\Customer;
use DB;

trait CustomerTransformable
{
    protected function transformCustomer(Customer $customer)
    {
       
       
       
       $prop = new Customer;
       $prop->id = (int) $customer->id;
       
       $address_details = DB::table('addresses')->select('company_name', 'post_code')->where('customer_id', $prop->id)->first();
       
       if(isset($address_details->company_name) && !empty($address_details->company_name)) {
        $prop->company_name = $address_details->company_name;
       } else {
           $prop->company_name = '';
       }
       $prop->first_name = $customer->first_name;
       $prop->last_name = $customer->last_name;
       
       if(isset($address_details->post_code) && !empty($address_details->post_code)) {
        $prop->post_code = $address_details->post_code;
       } else {
           $prop->post_code = '';
       }
       
       $prop->chase = $customer->chase;
       $prop->current_spend_month = $customer->current_spend_month;
       $prop->created_at = $customer->created_at;
       $prop->status = (int) $customer->status;

        return $prop;
    }
}
