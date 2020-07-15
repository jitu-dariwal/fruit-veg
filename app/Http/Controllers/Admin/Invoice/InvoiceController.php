<?php

namespace App\Http\Controllers\Admin\Invoice;

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
use App\Mail\SendWeeklyInoviceMail;
use Illuminate\Support\Facades\Mail;
use App\Helper\Finder;
use Carbon\Carbon;

class InvoiceController extends Controller
{
	/**
     * @var BrandRepositoryInterface
     */
    private $invoiceRepo;
    
    /**
        * @var CategoryRepositoryInterface
    */
    private $invoice;

    /**
     * BrandController constructor.
     *
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(InvoiceRepositoryInterface $invoiceRepo, Invoice $invoice)
    {
        $this->invoiceRepo = $invoiceRepo;
        $this->invoice = $invoice;
    }
	
	
	/**
     * Update/Add Inovice with payment Status
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateInvoiceRequest $request)
	{
		
		$checkInvoice = Invoice::where('invoiceid',$request->invoiceid)->first();
		
		$data=[
		      'customer_id' => $request->customer_id,
		      'invoiceid' => $request->invoiceid,
		      'status' => $request->status,
		      'week_no' => $request->week,
		      'month' => $request->month,
		      'year' => $request->year,
		      'start_date' => $request->start_date,
		      'end_date' => $request->end_date,
		      'type' => $request->type,
		      'payment_method' => '',
		      'paid_date' => null,
		      'remittance' => '',
		];
		if($checkInvoice){
			$update = new InvoiceRepository($checkInvoice);
			$update->updateInvoice($data);
	    }else{
		    $this->invoiceRepo->createInvoice($data);
		}
		return \Response::json([
				'success' => true,
				'msg' => 'Invoice Status Updated Successfully.'
			]); 
	}
	
	
	/**
     * Update/Add Mutli Inovice with payment Status 
     *
     * @return \Illuminate\Http\Response
     */
    public function createMultiInvoice(Request $request)
	{
		
		if(isset($request->b_invoiceid) && count($request->b_invoiceid)>0){
			
		$invoiceids=$request->b_invoiceid;
		foreach($invoiceids as $key=>$val){
			$customer_id = $request->b_customer_id[$key];
			$invoiceid = $request->b_invoiceid[$key];
			$month = $request->b_month[$key];
			$week_no = $request->b_week[$key];
			$year = $request->b_year[$key];
			$start_date = $request->b_start_date[$key];
			$end_date = $request->b_end_date[$key];
			$type = $request->b_type[$key];
		if($request->paid_status_bulk==1){
		$checkInvoice = Invoice::where('invoiceid',$val)->first();
		$data=[
		      'customer_id' => $customer_id,
		      'invoiceid' => $invoiceid,
		      'status' => 1,
		      'week_no' => $week_no,
		      'month' => $month,
		      'year' => $year,
		      'start_date' => $start_date,
		      'end_date' => $end_date,
		      'type' => $type,
		      'payment_method' => '',
		      'paid_date' => null,
		      'remittance' => '',
		];
		if($checkInvoice){
			//$update = new InvoiceRepository($checkInvoice);
			//$update->updateInvoice($data);
	    }else{
		    $this->invoiceRepo->createInvoice($data);
		}
		
		return back()->with('message','Invoice Status Updated Successfully.');
		}elseif($request->paid_status_bulk==2)
		{
		/**************Send Maas Emails*******************/
         if(!empty($week_no) && !empty($year) && !empty($customer_id)){
		 $dates   = Finder::getStartAndEndDate($week_no, $year);
		 $w_start =$dates['week_start'];
		 $w_end   =$dates['week_end'];
		 $start_d =date('Y-m-d', strtotime($w_start));
		 $end_d   = date('Y-m-d', strtotime($w_end));
		 $customers_invoice_type =\Config::get('constants.REQUIRE_INVOICE_TYPE');
		 $customer=Customer::findOrfail($customer_id);
	     $orders=Order::join('order_details','orders.id','=','order_details.order_id')
		                    ->select('orders.*')
		                    ->where('orders.customer_id',$customer_id)
		                    ->where('orders.order_status_id','=',3)
		                    ->where('orders.payment_method','=','monthly-invoice')
		                    ->whereDate('order_details.shipdate','>=',$start_d)
							->whereDate('order_details.shipdate','<=',$end_d)
							->get();
		$invoiceId=Finder::getInvoiceId($week_no,$year,$customer_id);					
		$data=[
		    'week_no'    => $week_no,
		    'year'       => $year,
		    'customer'   => $customer,
		    'w_start'    => $w_start,
		    'w_end'      => $w_end,
		    'orders'     => $orders,			
		    'invoice_id' => $invoiceId,
            'notes'		 => ''
		];
	        $sendmail=Mail::to($customer->email)->queue(new SendWeeklyInoviceMail($data));
			return back()->with('message','Invoice mass email successfully sent.');
		}
		/**************End Send Maas Emails*******************/
		}
		}
		}
		return back()->with('error','Please Select Invoice Which one Status You Want To Change.');
	}
	/**
     * Lock Inovice if payment is paid
     *
     * @return \Illuminate\Http\Response
     */
	public function lockInvoice($inoviceid)
	{
		$checkInvoice = Invoice::where('invoiceid',$inoviceid)->firstOrFail();
		if($checkInvoice->status==2){
			$data=[
		      'is_confirm' => 1,
		    ];
		$update = new InvoiceRepository($checkInvoice);
		$update->updateInvoice($data);
		return back()->with('message','Inovice #'.$inoviceid.' has been locked successfully.');
		}else{
		return back()->with('error',\Config::get('messages.INVOICE_NOT_PAID'));	
		}
	}
	
	/**
     * Lock PO Number if payment status added
     *
     * @return \Illuminate\Http\Response
     */
	public function lockPONumber($inoviceid)
	{
		$checkInvoice = Invoice::where('invoiceid',$inoviceid)->firstOrFail();
		if(!empty($checkInvoice->po_number)){
			$data=[
		      'po_number_confirm' => 1,
		    ];
		$update = new InvoiceRepository($checkInvoice);
		$update->updateInvoice($data);
		return back()->with('message','Inovice #'.$inoviceid.' PO Number has been locked successfully.');
		}else{
		return back()->with('error',\Config::get('messages.INVOICE_NOT_PAID'));	
		}
	}
	
	/**
     * Update Invoice payment method
     *
     * @return \Illuminate\Http\Response
     */
	public function updatePaymentMethod(UpdatePaymentMethodRequest $request)
	{
		$inoviceid = $request->invoice_id;
		$paid_method = $request->paid_method;
		$checkInvoice = Invoice::where('invoiceid',$inoviceid)->first();
		if($checkInvoice){
			if($checkInvoice->status!=2)
			{
			return \Response::json([
				'success' => false,
				'msg' => 'You need to set the payment status as PAID first then you can apply the payment method'
			]);
			}
			$data=[
		      'payment_method' => $paid_method,
		    ];
			$update = new InvoiceRepository($checkInvoice);
		    $update->updateInvoice($data);
			return \Response::json([
				'success' => true,
				'msg' => 'Payment method Added successfully to invoice '.$inoviceid
			]);
		}
		return \Response::json([
				'success' => false,
				'msg' => 'You need to set the payment status first then you can apply the payment method.'
		]);
	}
	
	/**
     * Update remittance
     *
     * @return \Illuminate\Http\Response
     */
	public function updateRemittance(UpdateRemittanceRequest $request)
	{
		$inoviceid = $request->invoice_id;
		$remittance = $request->remittance;
		$checkInvoice = Invoice::where('invoiceid',$inoviceid)->first();
		if($checkInvoice){
			if($checkInvoice->status!=2)
			{
			return \Response::json([
				'success' => false,
				'msg' => 'You need to set the payment status as PAID first then you can apply the remittance'
			]);
			}
			$data=[
		      'remittance' => $remittance,
		    ];
			$update = new InvoiceRepository($checkInvoice);
		    $update->updateInvoice($data);
			return \Response::json([
				'success' => true,
				'msg' => 'Remittance Added successfully to invoice '.$inoviceid
			]);
		}
		return \Response::json([
				'success' => false,
				'msg' => 'You need to set the payment status first then you can apply the remittance.'
		]);
	}
	
	/**
     * Update Paid Date
     *
     * @return \Illuminate\Http\Response
     */
	public function updatePaidDate(UpdatePaidDateRequest $request)
	{
		$inoviceid = $request->invoice_id;
		$paid_date = $request->paid_date;
		$paid_date = Carbon::parse($paid_date)->format('Y-m-d');
		$checkInvoice = Invoice::where('invoiceid',$inoviceid)->first();
		if($checkInvoice){
			if($checkInvoice->status!=2)
			{
			return \Response::json([
				'success' => false,
				'msg' => 'You need to set the payment status as PAID first then you can apply the paid date'
			]);
			}
			$data=[
		      'paid_date' => $paid_date,
		    ];
			$update = new InvoiceRepository($checkInvoice);
		    $update->updateInvoice($data);
			return \Response::json([
				'success' => true,
				'msg' => 'Paid Date Added successfully to invoice '.$inoviceid
			]);
		}
		    return \Response::json([
				'success' => false,
				'msg' => 'You need to set the payment status first then you can apply the paid date.'
		    ]);
	}
	
	/**
     * Update Invoice PO Number
     *
     * @return \Illuminate\Http\Response
     */
	public function updateInvoicePONumber(UpdatePONumberRequest $request)
	{
		$inoviceid = $request->invoice_id;
		$po_number = $request->po_number;
		$checkInvoice = Invoice::where('invoiceid',$inoviceid)->first();
		if($checkInvoice){
			if(empty($checkInvoice))
			{
			return \Response::json([
				'success' => false,
				'msg' => 'You need to set the payment status as Paid/Unpaid first then you can apply the PO number'
			]);
			}
			$data=[
		      'po_number' => $po_number,
		    ];
			$update = new InvoiceRepository($checkInvoice);
		    $update->updateInvoice($data);
			return \Response::json([
				'success' => true,
				'msg' => 'PO Number Added successfully to invoice '.$inoviceid
			]);
		}
		return \Response::json([
				'success' => false,
				'msg' => 'You need to set the payment status Paid/Unpaid first then you can apply the payment method.'
		]);
	}
	
	/**
     * Get Week Drop Down by Year
     *
     * @return \Illuminate\Http\Response
     */
	public function getweek($year)
	{   
	    $week_no=0;
		$weeks = Finder::getIsoWeeksInYear($year);
        return view('admin.reports.partials.week_number_dropdown',compact('weeks','year','week_no'));		
	}
	
	
}
