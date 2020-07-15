<?php

namespace App\Http\Controllers\Admin\PrepProduceReport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop\Invoices\Invoice;
use App\Shop\Invoices\Repositories\InvoiceRepository;
use App\Shop\Invoices\Repositories\InvoiceRepositoryInterface;
use App\Shop\Invoices\Requests\CreateInvoiceRequest;
use App\Shop\Invoices\Requests\UpdateInvoiceRequest;
use App\Shop\Invoices\Requests\UpdatePaymentMethodRequest;
use App\Shop\Invoices\Requests\UpdateRemittanceRequest;
use App\Shop\Invoices\Requests\UpdatePaidDateRequest;
use App\Shop\Invoices\Requests\UpdatePONumberRequest;
use App\Shop\Orders\Order;
use App\Shop\Customers\Customer;
use App\Shop\OrdersTotals\OrdersTotal;
use App\Shop\OrderProducts\OrderProduct;
use App\Shop\Anomolies\Anomolie;
use App\Shop\Logs\IssuelogAdmin;
use App\Shop\Addresses\Address;
use App\Mail\SendWeeklyInoviceMail;
use Illuminate\Support\Facades\Mail;
use App\Helper\Finder;
use Carbon\Carbon;
use Auth;
use DB;

class PrepProduceReportController extends Controller
{
	

    /**
     * BrandController constructor.
     *
     * @list admin issue log
     */

		public function index(Request $request)
		{
			$_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
			$orderBy    =  'id desc';
			$for_date =  date('Y-m-d');
			if(!empty($request->fr))
			{
			$for_date = date('Y-m-d', strtotime($request->fr));
			}
			 	
			$OrdersTotal=OrderProduct::join('order_details', 'order_product.order_id', '=', 'order_details.order_id')
		    ->join('orders', 'order_product.order_id', '=', 'orders.id')
		    ->selectRaw("
						order_product.products_model,
						order_product.quantity,
						order_product.product_id,
						order_details.shipdate,
						orders.total,
						orders.id as order_ids,
						order_product.product_name,
						order_product.final_price as ticket_price,
						order_product.packet_size")
		    ->whereDate('order_details.shipdate','=',$for_date)
		    ->whereIn('order_product.product_id', \Config::get('constants.PREP_PRODUCE_PRODUCTS_ARRAY'))
		    ->where('orders.order_status_id','!=',5)
		    ->where('order_product.quantity','>',0)
		    ->orderBy('order_product.product_name', 'asc')
		    ->groupBy('order_product.product_id')		    
		    ->paginate($_RECORDS_PER_PAGE);
            $searchDate = date('d-m-Y', strtotime($for_date));	
	 
 
		return view('admin.prep-produce-report.index',compact('OrdersTotal','searchDate'));

		}
		
		
		public function printReport(Request $request)
		{
			$orderBy    =  'id desc';
			$for_date =  date('Y-m-d');
			if(!empty($request->printdate))
			{
			$for_date = date('Y-m-d', strtotime($request->printdate));
			}			
			
			$OrdersTotal=OrderProduct::join('order_details', 'order_product.order_id', '=', 'order_details.order_id')
		    ->join('orders', 'order_product.order_id', '=', 'orders.id')
		    ->selectRaw("
						order_product.products_model,
						order_product.quantity,
						order_product.product_id,
						order_details.shipdate,
						orders.total,
						orders.id as order_ids,
						order_product.product_name,
						order_product.final_price as ticket_price,
						order_product.packet_size")
		    ->whereDate('order_details.shipdate','=',$for_date)
		    ->whereIn('order_product.product_id', \Config::get('constants.PREP_PRODUCE_PRODUCTS_ARRAY'))
		    ->where('orders.order_status_id','!=',5)
		    ->where('order_product.quantity','>',0)
		    ->orderBy('order_product.product_name', 'asc')
		    ->groupBy('order_product.product_id')
			->get();
			$searchDate = date('d-m-Y', strtotime($for_date));	 
 
		return view('admin.prep-produce-report.print_report',compact('OrdersTotal','searchDate'));

		}


	
}


/* $products_query_raw = DB::select("SELECT 
									op.products_model,
									op.quantity,
									op.product_id,
									ot.value,
									o.id as order_id,
								
									op.product_name,
									op.final_price as ticket_price,
									op.packet_size
									from orders_totals as ot, orders as o, order_product as op 
									WHERE 
									FROM_UNIXTIME(o.shipdate) LIKE '$searchDate%' and o.id = op.order_id and ot.orders_id = op.order_id and o.order_status_id !=6 and ot.class='ot_total' and op.quantity>0  order by op.product_name Asc"); */
