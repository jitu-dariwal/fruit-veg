<?php

namespace App\Helper;

use Auth;
use DateTime;
use DB;
use App\Shop\Customers\CustomerFavouriteProduct;

/**
 *------------------------------------------------------------------
 *  Class Finder
 *------------------------------------------------------------------
 *  Description: This class is used for defining some common functions
 *  used in the project.
 *
 *  @author <>
 */
class Finder
{
    /**
     * Formate Number Upto two Decimals
     *
     * @param  decimal $number
     * @return float number
     */
    public static function getDashboardSectionsData()
    {
        $dashboard_sections[config('constants.dashboard_sections.top_section')] = [];
		$dashboard_sections[config('constants.dashboard_sections.quality')] = [];
		$dashboard_sections[config('constants.dashboard_sections.easy_order')] = [];
		$dashboard_sections[config('constants.dashboard_sections.happy_customer')] = [];
		$dashboard_sections[config('constants.dashboard_sections.deliver_to')] = [];
		
		$data = DB::table('pages')->where(['type' => 'dashboard_section'])->get();
		
		foreach($data as $k=>$v){
			if(!empty($v) && array_key_exists($v->id,$dashboard_sections)){
				$dashboard_sections[$v->id] = $v;
			}
		}
		
		return $dashboard_sections;
    }
	
	/**
     * Formate Number Upto two Decimals
     *
     * @param  decimal $number
     * @return float number
     */
    public static function getFormatted($num)
    {
        return number_format((float) $num, 2, '.', '');
    }

    /**
     * Get Payment Methods
     *
     *
     * @return array of payments
     */
    public static function getPaymentMethods()
    {
        $paymentMethods = collect(explode(',', config('payees.name')))->transform(function ($name) {
            return config($name);
        })->all();
        $paymenttypes = [];
        foreach ($paymentMethods as $payment_type) {

            $paymenttypes[$payment_type['value']] = $payment_type['name'];

        }
		
		$frontPaymentMethods = DB::table('payment_methods')->get();
		foreach($frontPaymentMethods as $val){
			$paymenttypes[$val->value] = $val->name;
		}
		
        return $paymenttypes;
    }
    /**
     * Get Orders Count By Date
     *
     * @param  int $date
     * @return Order Count as per date
     */
    public static function getOrderCountByDate($date)
    {
        return DB::table('order_details')->whereDate('shipdate', $date)->count();
    }

    /**
     * params @start_date Date
     * params @end_date Date
     * params @show_top int
     * params @status int
     * params @sort int
     * params @export int
     *
     * @return Sales Reports by Dates of orders
     */
    public static function getSalesReportbyDate($start_date, $end_date, $show_top = 0, $status = 0, $sort = 0, $export = 0)
    {
        $start_date = date('Y-m-d', strtotime($start_date));
        $to_date    = date('Y-m-d', strtotime($end_date));
        $sales      = DB::table('orders')->join('order_details', 'orders.id', '=', 'order_details.order_id')->selectRaw('orders.id,count(orders.id) as total_order, sum(orders.total) as revenue, sum(orders.shipping_charges) as total_shipping, sum(orders.customer_discount) as total_discount,  MONTH(order_details.shipdate) as month')
            ->whereDate('orders.created_at', '>=', $start_date)
            ->whereDate('orders.created_at', '<=', $to_date);
        if ($status != 0) {
            $sales = $sales->where('orders.order_status_id', $status);
        }
        switch ($sort) {
            case '0':
                $sales = $sales->orderBy('total_order', 'desc');
                break;
            case '1':
                $sales = $sales->orderBy('total_order', 'desc');
                break;
            case '2':
                $sales = $sales->orderBy('total_order', 'desc');
                break;
            case '3':
                $sales = $sales->orderBy('total_order', 'asc');
                break;
            case '4':
                $sales = $sales->orderBy('total_order', 'desc');
                break;
            case '5':
                $sales = $sales->orderBy('revenue', 'asc');
                break;
            case '6':
                $sales = $sales->orderBy('revenue', 'desc');
                break;
        }
        $sales = $sales->get();
        return $sales;
    }

    /**
     * params @start_date Date
     * params @end_date Date
     * params @show_top int
     * params @status int
     * params @sort int
     * params @export int
     *
     * @return Sales Reports of Items
     */
    public static function getSalesReportofItems($start_date, $end_date, $show_top = 0, $status = 0, $sort = 0, $export = 0)
    {
        $start_date  = date('Y-m-d', strtotime($start_date));
        $to_date     = date('Y-m-d', strtotime($end_date));
        $order_items = DB::table('orders')
            ->join('order_product', 'orders.id', '=', 'order_product.order_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('orders.id,sum(order_product.quantity) as product_qty, order_product.product_id,order_product.product_name,order_product.type,sum(order_product.final_price) as prd_revenue')
            ->whereDate('orders.created_at', '>=', $start_date)
            ->whereDate('orders.created_at', '<=', $to_date)
            ->groupBy('order_product.product_id');

        switch ($sort) {
            case '0':
                $order_items = $order_items->orderBy('product_qty', 'desc');
                break;
            case '1':
                $order_items = $order_items->orderBy('order_product.product_name', 'asc');
                break;
            case '2':
                $order_items = $order_items->orderBy('order_product.product_name', 'desc');
                break;
            case '3':
                $order_items = $order_items->orderBy('product_qty', 'asc');
                break;
            case '4':
                $order_items = $order_items->orderBy('product_qty', 'desc');
                break;
            case '5':
                $order_items = $order_items->orderBy('prd_revenue', 'asc');
                break;
            case '6':
                $order_items = $order_items->orderBy('prd_revenue', 'desc');
                break;
        }
        if ($show_top != 0) {
            $order_items = $order_items->take($show_top);
        }
        if ($status != 0) {
            $order_items = $order_items->where('orders.order_status_id', $status);
        }

        $order_items = $order_items->get();
        return $order_items;
    }

    /**
     * params @year
     *
     * @return List of weeks and number of week in year
     */
    public static function getIsoWeeksInYear($year)
    {
        $date = new DateTime();
        $date->setISODate($year, 53);
        return ($date->format("W") === "53" ? 53 : 52);
    }

    /**
     * params @year
     * params @week
     *
     * @return Start and End Date of Week
     */
    public static function getStartAndEndDate($week, $year)
    {
        $dto               = new DateTime();
        $ret['week_start'] = $dto->setISODate($year, $week)->format('d-m-Y');
        $ret['week_end']   = $dto->modify('+6 days')->format('d-m-Y');
        return $ret;
    }

    /**
     * params @invoiceid
     *
     * @return Invoice Status
     */
    public static function getInvoiceStatus($invoice_id)
    {
        $status             = 0;
        $checkInvoiceStatus = DB::table('invoices')->where('invoiceid', '=', $invoice_id)->first();

        return $checkInvoiceStatus;
    }

    /**
     * Get Invoice Id.
     *
     * @param  int $week_number Week No. of Year
     * @param  int $year Year
     * @param  int $customer_id Customer Id
     * @return Invoice Id
     */
    public static function getInvoiceId($week, $year, $customer_id)
    {
        if ($week < 10) {
            $invoice_id = '0' . $week . $year . 'cID' . $customer_id;
        } else {
            $invoice_id = $week . $year . 'cID' . $customer_id;
        }

        return $invoice_id;
    }

    /**
     * Get Invoice Id.
     *
     * @param  int $month Month No. of Year
     * @param  int $year Year
     * @param  int $customer_id Customer Id
     * @return Invoice Id
     */
    public static function getMonthlyInvoiceId($month, $year, $customer_id)
    {
        if ($month < 10) {
            $invoice_id = '0M' . '0' . $month . $year . 'cID' . $customer_id;
        } else {
            $invoice_id = '0M' . $month . $year . 'cID' . $customer_id;
        }

        return $invoice_id;
    }

    /**
     * Get Invoice Part Payments.
     *
     * @param  str $invoiceid
     * @return Part Payment Total
     */
    public static function getPartPayment($invoiceid)
    {
        $partpayment = 0.00;
        $payments    = DB::table('invoice_part_payments')->selectRaw('sum(partpayment) as total_payment')->where('invoiceid', $invoiceid)->first();
        if ($payments) {
            if(!empty($payments->total_payment)){
            $partpayment = $payments->total_payment;
        }
        }
        return $partpayment;
    }

    /**
     * Get Driver Rounds by date
     *
     * @param  date $date
     * @return Driver rounds for the day
     */
    public static function getDriverRounds($date)
    {
        $rounds = DB::table('driver_rounds')->whereDate('round_date', $date)->get();
        return $rounds;
    }

    /**
     * Get Driver Rounds Count by date
     *
     * @param  date $date
     * @param  str $driver
     * @return Driver rounds for the day
     */
    public static function getDriverTotalRounds($date, $driver)
    {
        $rounds = DB::table('orders')
                     ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                     ->selectRaw('count(orders.id) as totalrounds')
                     ->whereDate('order_details.shipdate', $date)
                     ->where('orders.driver', '=', $driver)
                     ->where('orders.order_status_id', '!=', 5)
                     ->first();
        return $rounds->totalrounds;
    }

    /**
     * Get Not availabel products
     *
     * @param  order $order_id
     * @return NA Product
     */
    public static function getNAProducts($order_id)
    {
        $products = DB::table('order_product')->where('order_id', '=', $order_id)->where('is_available', '=', 0)->get();
        return $products;
    }

    /**
     * Get Order Lock Date
     *
     * @param  order $startDate
     * @return Order Lock Date
     */
    public static function getOrderLockDate($startDate)
    {
        $wDays = 3;
        $holidays = DB::table('bankholidays')->select('holiday_date')->get();
        $day = date('w', strtotime($startDate));
        if ($day == 4) {
            $wDays = $wDays + 1;
        } elseif ($day == 5) {
            $wDays = $wDays + 1;
        } elseif ($day == 6) {
            $wDays = $wDays + 1;
        } else {
            $wDays = $wDays;
        }

        $wDays_in_timestamp = $wDays * 24 * 3600;

        $startdateIntimestamp = strtotime($startDate);
        $newdate              = $startdateIntimestamp + $wDays_in_timestamp;

        // using + weekdays excludes weekends
        $new_date   = date('Y-m-d', $newdate);
        //$holiday_ts_new = strtotime($holidays);
        $extradays = 0;
        foreach ($holidays as $holidaydate) {
            if (strtotime($holidaydate->holiday_date) >= strtotime($startDate) && strtotime($holidaydate->holiday_date) <= strtotime($new_date)) {
                $h = date('w', strtotime($holidaydate->holiday_date));
                if ($h != 0) {
                    $extradays++;
                    // holiday falls on a working day, add an extra working day
                }
            }

        }

        if ($extradays > 0) {

            $newdate  = $newdate + $extradays * 24 * 3600;
            $new_date = date('Y-m-d', $newdate);
        }

        return $new_date;
    }

    /**
     * Get Ids array of Milk category products
     *
     * 
     * @return Products array of milk category
     */
    public static function getMilkProducts()
    {
        $prduct = array();
        $milkProducts = DB::table('category_product')
                       ->join('products', 'products.id', '=', 'category_product.product_id')
                       ->select('category_product.product_id')
                       ->whereIn('category_id', \Config::get('constants.MILK_PRODUCT_CATEGORIES'))
                       ->whereNotIn('products.id', [1777])
                       ->where('products.products_status_2','=',1)
                       ->groupBy('category_product.product_id')
                       ->orderBy('category_product.product_id','ASC')
                       ->get();
        $i=0;
        foreach($milkProducts as $milkProduct){
           $i++;
           $prducts[$i] = $milkProduct->product_id;
        }
        return $prducts;
    }

    /**
     * Get Parent Category Id
     *
     * @var category id
     * @return Parent Category of category
     */
    public static function getParentCategory($categoryid)
    {
        $Cat = DB::table('categories')->select('id', 'parent_id')->whereId($categoryid)->first();
        $pCat = $Cat->parent_id;
        $categoryid = $Cat->id;
        if($pCat!=0){
            $Cat = DB::table('categories')->select('id', 'parent_id')->whereId($pCat)->first();
            $pCat = $Cat->parent_id;
            $categoryid = $Cat->id;
            if($pCat!=0){
            $Cat = DB::table('categories')->select('id', 'parent_id')->whereId($pCat)->first();
            $pCat = $Cat->parent_id;
            $categoryid = $Cat->id;
            if($pCat!=0){
            $Cat = DB::table('categories')->select('id', 'parent_id')->whereId($pCat)->first();
            $pCat = $Cat->parent_id;
            $categoryid = $Cat->id;
            if($pCat!=0){
            $Cat = DB::table('categories')->select('id', 'parent_id')->whereId($pCat)->first();
            $pCat = $Cat->parent_id;
            $categoryid = $Cat->id;
            }
            }
            }
        }
        return $categoryid;
    }

    /**
     * Get Products Category Ids
     *
     * @var product id
     * @return Product category array
     */
    public static function getProductsParentCategory($productid)
    {
        $categoryArr = array();
        $categories = DB::table('category_product')->select('category_id')->whereProductId($productid)->get();
        $a = 0 ;
        if($categories){
           foreach($categories as $cat){
            $categoryArr[$a]=self::getParentCategory($cat->category_id);
            $a++;
           } 
        }
        return array_unique($categoryArr);
    }

    /**
     * Get Fav-Products ids list array
     *
     * @var customer_id
     * @return Product ids array
     */
    public static function getFavProducts($customer_id)
    {
        $customerFavProducts = CustomerFavouriteProduct::where('customer_id', \Auth::user()->id)->pluck('product_id')->all();
        return array_unique($customerFavProducts);
    }

}
