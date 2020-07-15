<?php

namespace App\Http\Controllers\Admin\InvoicePartPayments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop\InvoicePartPayments\InvoicePartPayment;

class InvoicePartPaymentController extends Controller
{
    /**
     * Display Invoice Part Payments
     *
     * @return \Illuminate\Http\Response
     */
	public function index($customer_id,$invoiceid)
    {
		$record_per_page = config('constants.RECORDS_PER_PAGE');
        $invoicePayments = InvoicePartPayment::where('invoiceid',$invoiceid)->orderBy('id','desc')->paginate($record_per_page);
	    return view('admin.invoice-part-payments.list', compact('invoicePayments','invoiceid','customer_id'));
    }
	
	/**
     * Add Invoice PArt Payments
     *
     * @return \Illuminate\Http\Response
     */
	public function create($customer_id,$invoiceid)
    {
		return view('admin.invoice-part-payments.create', compact('invoiceid','customer_id'));
    }
	
	/**
     * Add Invoice PArt Payments
     *
     * @return \Illuminate\Http\Response
     */
	public function store(Request $request)
    {
		$data =[
		    'customer_id' =>  $request->customer_id,
		    'invoiceid'   =>  $request->invoice_id,
		    'partpayment' =>  $request->amount,
		];
		InvoicePartPayment::create($data);
		return redirect('admin/invoice-part-payments/view/'.$request->customer_id.'/'.$request->invoice_id)->with('message','Notes Added Successfully.');
    }
}
