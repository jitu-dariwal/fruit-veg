<?php

namespace App\Http\Controllers\Admin\CustomersPostcodeListReport;

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
use Maatwebsite\Excel\Facades\Excel;
use App\Shop\Orders\Order;
use App\Shop\Customers\Customer;
use App\Shop\OrdersTotals\OrdersTotal;
use App\Shop\Anomolies\Anomolie;
use App\Shop\Logs\IssuelogAdmin;
use App\Shop\Addresses\Address;
use App\Mail\SendWeeklyInoviceMail;
use Illuminate\Support\Facades\Mail;
use App\Helper\Finder;
use Carbon\Carbon;
use Auth;
use DB;

class CustomersPostcodeListReportController extends Controller
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
			$searchDate =  date('Y-m-d');
			$searchData = '';
			if(!empty($request->postcode))
			{
			$searchData = 	$request->postcode;
			}
			 	
		
			 $customers = Customer::join('addresses', 'customers.default_address_id', '=', 'addresses.id')
			 ->selectRaw("
			 customers.id,
			 customers.first_name,
			 customers.last_name,
			 addresses.tel_num,
			 addresses.company_name,
			 addresses.post_code as invoice_postcode,
			 customers.created_at
			 ")
			->where(function($where) use ($searchData){
			 
				if (!empty($searchData)) {
					$where->where('addresses.post_code', '=', $searchData);
				}
			  
			})
			->orderBy('id','desc') 
			->paginate($_RECORDS_PER_PAGE);
			  
			  
			  $timestamp=mktime(0, 0, 0, date("m"), date("d")-14,   date("Y"));
			  $date_from=date("Y-m-d", $timestamp);
			
			  $orderPluck = Order::where(function($where) use ($date_from){
				  $where->whereDate('created_at', '>', strtotime($date_from));
			  })->pluck('customer_id','customer_id');
			
		return view('admin.customers_postcode_list_report.index',compact('customers','orderPluck','searchDate','date_from'));

		}
		
		
		public function exportReport(Request $request)
		{
			
			$orderBy    =  'id desc';
			$searchDate =  date('Y-m-d');
			$searchData = '';
			if(!empty($request->postcode))
			{
			$searchData = 	$request->postcode;
			}
			 	
		
			 $customers = Customer::join('addresses', 'customers.default_address_id', '=', 'addresses.id')
			 ->selectRaw("
			 customers.id,
			 customers.first_name,
			 customers.last_name,
			 addresses.tel_num,
			 addresses.company_name,
			 addresses.post_code as invoice_postcode,
			 customers.created_at
			 ")
			->where(function($where) use ($searchData){
			 
				if (!empty($searchData)) {
					$where->where('addresses.post_code', '=', $searchData);
				}
			  
			})
			->orderBy('id','desc') 
			->get();
			  
			  
			  $timestamp=mktime(0, 0, 0, date("m"), date("d")-14,   date("Y"));
			  $date_from=date("Y-m-d", $timestamp);
			
			  $orderPluck = Order::where(function($where) use ($date_from){
				  $where->whereDate('created_at', '>', strtotime($date_from));
			  })->pluck('customer_id','customer_id');
			  
			$export_file_name = "PostcodeReport".date('d.m.Y');
			$xl= Excel::create($export_file_name, function($excel) use ($customers,$date_from,$orderPluck,$export_file_name) {
            $excel->sheet($export_file_name, function($sheet) use ($customers,$date_from,$orderPluck)
            {	
				$sheet->cell('A1', function($cell) {$cell->setValue('Client ID');   });
                $sheet->cell('B1', function($cell) {$cell->setValue('First Name');   });
                $sheet->cell('C1', function($cell) {$cell->setValue('Last Name');   });
                $sheet->cell('D1', function($cell) {$cell->setValue('Contact');   });
                $sheet->cell('E1', function($cell) {$cell->setValue('Company');   });
				$sheet->cell('F1', function($cell) {$cell->setValue('Postcode');   });
				$sheet->cell('G1', function($cell) {$cell->setValue('Date Registration');   });
				$sheet->cell('H1', function($cell) {$cell->setValue('Placed orders in the last two weeks');   });
				$sheet->cell('I1', function($cell) {$cell->setValue('The activity in the last two weeks');   });
                if (count($customers)>0) {
					$i=1;
                    foreach ($customers as $customer) {
						$i++;
                        $sheet->cell('A'.$i, $customer->id); 
                        $sheet->cell('B'.$i, ucfirst($customer->first_name)); 
                        $sheet->cell('C'.$i, ucfirst($customer->last_name)); 
                        $sheet->cell('D'.$i, $customer->tel_num);
                        $sheet->cell('E'.$i, $customer->company_name);
						$sheet->cell('F'.$i, $customer->invoice_postcode);
						$sheet->cell('G'.$i, $customer->created_at);
						$set = "No";
						$set2 = "No";
						if(!empty($orderPluck[$customer->id]))
						{
							$set = "Yes";
						}
						
						if(date('Y-m-d',strtotime($customer->created_at)) > $date_from)
						{
							$set2 = "Yes";
						}
						$sheet->cell('H'.$i, $set);
						$sheet->cell('I'.$i, $set2);
						
                    }
                }
            });
        });
		return $xl->download('xlsx');

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
