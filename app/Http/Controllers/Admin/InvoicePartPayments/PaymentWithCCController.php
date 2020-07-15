<?php

namespace App\Http\Controllers\Admin\InvoicePartPayments;

use App\Http\Controllers\Controller;
use App\Shop\Customers\Customer;
use App\Shop\Invoices\Invoice;
use App\Shop\Orders\Order;
use App\Shop\InvoicePartPayments\InvoicePartPayment;
use Illuminate\Http\Request;

class PaymentWithCCController extends Controller
{
    public function index(Request $request, $invoiceid)
    {
        $invoice_type = 'monthly-invoice';
        $invoice      = Invoice::where('invoiceid', $invoiceid)->firstOrfail();
        if($invoice->status==2){
            return back()->with('error', 'Invoice has been already paid.');
        }
        $invoicePartPayments = InvoicePartPayment::selectRaw('sum(partpayment) as totalpaid')->where('invoiceid',$invoiceid)->first();
        $customer     = Customer::select('id', 'first_name', 'last_name', 'email', 'default_address_id')->where('id', $invoice->customer_id)->firstOrfail();
        $getinvoices  = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('orders.id as order_id, sum(orders.total) as inovice_total, orders.payment_method')
            ->where('orders.customer_id', '=', $invoice->customer_id)
            ->whereDate('order_details.shipdate', '>=', $invoice->start_date)
            ->whereDate('order_details.shipdate', '<=', $invoice->end_date)
            ->where('orders.order_status_id', '!=', 5)
            ->first();
        $dueAmt = $getinvoices->inovice_total;
        $dueAmt = $dueAmt-$invoicePartPayments->totalpaid;
        if($dueAmt<=0){
        	return back()->with('error', 'Error! The amount should be greater than 0.');
        }
        //dd($getinvoices);
        return view('admin.invoice-part-payments.paymentWithCC', compact('getinvoices', 'customer', 'invoiceid', 'dueAmt'));
    }

    public function updateStatus(Request $request, $invoiceid)
    {
    	$isPaymentSuccess = 0;
        if ($request->valid == 'true') {
        	
        	$paid_amount = $request->amount;
        	$trnsId = $request->trans_id;
        	$ip = $request->ip;
        	$invoice_type = 'monthly-invoice';
        	$invoiceinfo = Invoice::where('invoiceid', $invoiceid);
            $invoice = $invoiceinfo->first();
            $paidStatus = $invoice->status;
            $getinvoices  = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('orders.id as order_id, sum(orders.total) as inovice_total, orders.payment_method')
            ->where('orders.customer_id', '=', $invoice->customer_id)
            ->whereDate('order_details.shipdate', '>=', $invoice->start_date)
            ->whereDate('order_details.shipdate', '<=', $invoice->end_date)
            ->where('orders.order_status_id', '!=', 5)
            ->first();
            $invoicePartPayments = InvoicePartPayment::selectRaw('sum(partpayment) as totalpaid')->where('invoiceid',$invoiceid)->first();
            $dueAmt = $getinvoices->inovice_total;
            $dueAmt = $dueAmt-$invoicePartPayments->totalpaid;
            if($paid_amount>=$dueAmt){
            	$paidStatus = 2;
            }
	        $invoice->update([
	            'status' => $paidStatus,
	            'payment_method' => 'credit-card',
	            'paid_date' => date('Y-m-d'),
	            'is_confirm' => 1,
	            'trans_id' => $trnsId,
	        ]);

		    $data =[
			    'customer_id' =>  $invoice->customer_id,
			    'invoiceid'   =>  $invoice->invoiceid,
			    'partpayment' =>  $paid_amount,
			];
			InvoicePartPayment::create($data);
            $isPaymentSuccess = 1;
            return view('admin.invoice-part-payments.paymentStatus', compact('isPaymentSuccess'));
	        }
            return view('admin.invoice-part-payments.paymentStatus', compact('isPaymentSuccess'));
    }
}
