<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop\Orders\Order;
use App\Shop\Products\Product;
use App\Shop\Customers\Customer;
use Carbon\Carbon;
use DB;

class IssuesReportController extends Controller
{
    public function index()
    {
    	return view('admin.reports.issues.index');
    }


    /**
     * Show discounts which not appear on issue log
     *
     * @return \Illuminate\Http\Response
     */	 
    public function discountNotApperar(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
    	$from_date=date('Y-m-d');
		if($request->from_date){
			$from_date= date('Y-m-d', strtotime($request->from_date));
		}
		$to_date=date('Y-m-d');
		if($request->to_date){
			$to_date= date('Y-m-d', strtotime($request->to_date));
		}
        $orders = DB::table('issuelog_admins')->select('OrderNumber')->where('OrderNumber','!=',0)->get()->implode('OrderNumber',',');
        $orders = explode(',', $orders);
        //dd($orders);
        $discounts=Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
					->select('orders.id', 'orders.customer_id', 'order_details.shipdate', 'orders.customer_discount')
					->whereDate('order_details.shipdate','>=',$from_date)
					->whereDate('order_details.shipdate','<=',$to_date)
					->whereNotNull('orders.customer_discount')
					->where('orders.customer_discount','!=','0.00')
					->where('orders.order_status_id','!=',5)
					->whereNotIn('orders.id',$orders)
					->orderBy('orders.id','desc')->paginate($_RECORDS_PER_PAGE);
					
		$from_date = date('d-m-Y', strtotime($from_date));
		$to_date   = date('d-m-Y', strtotime($to_date));
        return view('admin.reports.issues.discount_not_appear_on_logs',compact('from_date', 'to_date', 'discounts'));
    }

    /**
     * Show discounts which not appear on customer discounts reports
     *
     * @return \Illuminate\Http\Response
     */	 
    public function discountOnlyInIssueslogs(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
         $from_date=date('Y-m-d');
		if($request->from_date){
			$from_date= date('Y-m-d', strtotime($request->from_date));
		}
		$to_date=date('Y-m-d');
		if($request->to_date){
			$to_date= date('Y-m-d', strtotime($request->to_date));
		}
       
        $discounts= Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->join('issuelog_admins', 'orders.id', '=', 'issuelog_admins.OrderNumber')
					->select('orders.id', 'orders.customer_id', 'order_details.shipdate', 'orders.customer_discount', 'issuelog_admins.*')
					->whereDate('order_details.shipdate','>=',$from_date)
					->whereDate('order_details.shipdate','<=',$to_date)
					->where(function($query){
                            $query->whereNull('orders.customer_discount')
                                  ->orWhere('orders.customer_discount', '=','0.00')
                                  ->orWhere('orders.customer_discount', '=','');
                    })
                    ->where(function($query){
                            $query->whereNotNull('issuelog_admins.FinancialImplication')
                                  ->orWhere('issuelog_admins.FinancialImplication', '!=','0.00')
                                  ->orWhere('issuelog_admins.FinancialImplication', '!=','');
                    })
					->where('orders.order_status_id','!=',5)
					->orderBy('orders.id','desc')->paginate($_RECORDS_PER_PAGE);
					
		$from_date = date('d-m-Y', strtotime($from_date));
		$to_date   = date('d-m-Y', strtotime($to_date));
        return view('admin.reports.issues.discount_not_appear_on_discounts',compact('from_date', 'to_date', 'discounts'));
    }

    /**
     * Once order numbers match on both reports, check to see that ‘financial implication’ on issue 
     * log and discount on order number are the same totals – if different alert us
     *
     * @return \Illuminate\Http\Response
     */	 
    public function discountmisMatchInIssueslogs(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $from_date=date('Y-m-d');
		if($request->from_date){
			$from_date= date('Y-m-d', strtotime($request->from_date));
		}
		$to_date=date('Y-m-d');
		if($request->to_date){
			$to_date= date('Y-m-d', strtotime($request->to_date));
		}
        $discounts= Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->join('issuelog_admins', 'orders.id', '=', 'issuelog_admins.OrderNumber')
					->select('orders.id', 'orders.customer_id', 'order_details.shipdate', 'orders.customer_discount', 'issuelog_admins.*')
					->whereDate('order_details.shipdate','>=',$from_date)
					->whereDate('order_details.shipdate','<=',$to_date)
                    ->whereRaw('issuelog_admins.FinancialImplication!=ABS(orders.customer_discount)')
					->where('orders.order_status_id','!=',5)
                    ->whereNotNull('orders.customer_discount')
                    ->where('orders.customer_discount', '!=','0.00')
                    ->where('orders.customer_discount', '!=','')
					->orderBy('orders.id','desc')->paginate($_RECORDS_PER_PAGE);
		$from_date = date('d-m-Y', strtotime($from_date));
		$to_date   = date('d-m-Y', strtotime($to_date));
        return view('admin.reports.issues.financial_implication_report',compact('from_date', 'to_date', 'discounts'));
    }
}
