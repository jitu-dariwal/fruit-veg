<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Helper\Finder;
use App\Http\Controllers\Controller;
use App\Mail\SendMonthlyInoviceMail;
use App\Shop\Customers\Customer;
use App\Shop\Invoices\Invoice;
use App\Shop\OrderStatuses\OrderStatus;
use App\Shop\Orders\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class MonthlyReportController extends Controller
{

    /**
     * Display customer paid unpaid monthly Statements.
     *
     * @return \Illuminate\Http\Response
     */
    public function paidunpaidMonthlyTotal(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $month_no     = date("m");
        $year         = date("Y");
        $order_status = 5;
        $invoice_type = '';
        $check_paid   = 0;
        if ($request->month) {
            $month_no = $request->month;
        }
        if ($request->year) {
            $year = $request->year;
        }
        if ($request->delivered) {
            $order_status = 3;
        }
        if ($request->monthly_invoice) {
            $invoice_type = 'monthly-invoice';
        }
        if ($request->check_paid) {
            $check_paid = $request->check_paid;
        }

        $m_start                = date('01-' . $month_no . '-' . $year);
        $m_end                  = date('t-' . $month_no . '-' . $year, strtotime($m_start));
        $start_d                = date('Y-m-d', strtotime($m_start));
        $end_d                  = date('Y-m-d', strtotime($m_end));
        $customers_invoice_type = \Config::get('constants.REQUIRE_INVOICE_TYPE');
        $getInvoices            = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('orders.id as order_id, customers.id, customers.first_name, customers.last_name, customers.default_address_id, sum(orders.total) as inovice_total, orders.payment_method')
            ->where('customers.customers_require_invoice_type', '=', $customers_invoice_type)
            ->whereDate('order_details.shipdate', '>=', $start_d)
            ->whereDate('order_details.shipdate', '<=', $end_d);
        if ($order_status == 3) {
            $getInvoices = $getInvoices->where('orders.order_status_id', '=', $order_status);
        } else {
            $getInvoices = $getInvoices->where('orders.order_status_id', '!=', $order_status);
        }
        if (!empty($invoice_type)) {
            $getInvoices = $getInvoices->where('orders.payment_method', '=', $invoice_type);
        }
        $getInvoices = $getInvoices->groupBy('customers.id')
            ->paginate($_RECORDS_PER_PAGE);

        return view('admin.reports.monthly.paid_unpaid_monthly_total', compact('getInvoices', 'month_no', 'year', 'start_d', 'end_d'));
    }

    /**
     * Display Monthly Invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function monthlyInovice(Request $request)
    { 
        $year = date('Y');
        $month_no = date('m');
        if ($request->month) {
            $month_no = $request->month;
        }
        if ($request->year) {
            $year = $request->year;
        }
        if ($request->customer) {
            $customer_id = $request->customer;
        }
        if (!empty($month_no) && !empty($year) && !empty($customer_id)) {
            $m_start                = date('01-' . $month_no . '-'.$year);
            $m_end                  = date('t-m-Y', strtotime($m_start));
            $start_d                = date('Y-m-d', strtotime($m_start));
            $end_d                  = date('Y-m-d', strtotime($m_end));
            $customers_invoice_type = \Config::get('constants.REQUIRE_INVOICE_TYPE');
            $customer               = Customer::findOrfail($customer_id);
            $orders                 = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->select('orders.*')
                ->where('orders.customer_id', $customer_id)
                ->where('orders.order_status_id', '=', 3)
                ->where('orders.payment_method', '=', 'monthly-invoice')
                ->whereDate('order_details.shipdate', '>=', $start_d)
                ->whereDate('order_details.shipdate', '<=', $end_d)
                ->get();
            $invoiceId    = Finder::getMonthlyInvoiceId($month_no, $year, $customer_id);
            $checkInvoice = Invoice::where('invoiceid', $invoiceId)->first();

            $data = [
                'customer_id'    => $customer_id,
                'invoiceid'      => $invoiceId,
                'status'         => 1,
                'month'          => $month_no,
                'year'           => $year,
                'start_date'     => $start_d,
                'end_date'       => $end_d,
                'type'           => 'monthly',
                'payment_method' => '',
                'paid_date'      => null,
                'remittance'     => '',
            ];
            if ($checkInvoice && !empty($checkInvoice)) {
            } else {
                Invoice::create($data);
            }
            return view('admin.reports.monthly.monthly_invoice', compact('orders', 'customer', 'm_start', 'm_end', 'month_no', 'year', 'customer_id'));
        }

        return back()->with('error', 'Sorry! Something is going wrong.');
    }

    /**
     * Email Monthly Invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function emailMonthlyInovice(Request $request)
    {
        if ($request->month) {
            $month_no = $request->month;
        }
        if ($request->year) {
            $year = $request->year;
        }
        if ($request->customer_id) {
            $customer_id = $request->customer_id;
        }
        if (!empty($month_no) && !empty($year) && !empty($customer_id)) {
            $m_start                = date('01-' . $month_no . '-Y');
            $m_end                  = date('t-m-Y', strtotime($m_start));
            $start_d                = date('Y-m-d', strtotime($m_start));
            $end_d                  = date('Y-m-d', strtotime($m_end));
            $customers_invoice_type = \Config::get('constants.REQUIRE_INVOICE_TYPE');
            $customer               = Customer::findOrfail($customer_id);
            $orders                 = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->select('orders.*')
                ->where('orders.customer_id', $customer_id)
                ->where('orders.order_status_id', '=', 3)
                ->where('orders.payment_method', '=', 'monthly-invoice')
                ->whereDate('order_details.shipdate', '>=', $start_d)
                ->whereDate('order_details.shipdate', '<=', $end_d)
                ->get();
            $invoiceId = Finder::getMonthlyInvoiceId($month_no, $year, $customer_id);
            $data      = [
                'month_no'   => $month_no,
                'year'       => $year,
                'customer'   => $customer,
                'm_start'    => $m_start,
                'm_end'      => $m_end,
                'orders'     => $orders,
                'invoice_id' => $invoiceId,
                'notes'      => $request->Notes,
            ];
            $sendmail = Mail::to($customer->email);
            if ($request->emailadd && !empty($request->emailadd)) {
                $sendmail->cc($request->emailadd);
            }
            $sendmail->send(new SendMonthlyInoviceMail($data));
            return back()->with('message', 'Invoice successfully sent.');
        }

        return back()->with('error', 'Sorry! Something is going wrong.');
        return view('emails.invoices.monthly_invoice');
    }

    /**
     * Display Monthly Invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function customerMonthlyStatement(Request $request)
    {
        $data                   = array();
        $customers_invoice_type = \Config::get('constants.REQUIRE_INVOICE_TYPE');
        $year                   = date('Y');
        $cId                    = 0;
        $cust_Id                = Customer::select('id', 'default_address_id')
                                //->where('customers_require_invoice_type', $customers_invoice_type)
                                ->orderBy('id', 'desc')
                                ->first();

        if ($cust_Id) {
            $cId = $cust_Id->id;
        } else {
            return back()->with('error', 'No user found for monthly invoice statement.');
        }
        if ($request->cId) {
            $cId = $request->cId;
        }
        if ($request->year) {
            $year = $request->year;
        }

        $customers = Customer::select('id', 'first_name', 'last_name', 'default_address_id')->orderBy('first_name', 'asc')->get();
        $customer  = Customer::where('id', $cId)->first();
        if (!empty($year) && !empty($cId)) {
            foreach (\Config::get('constants.MONTHS') as $key => $val) {
                $m_start = date('01-' . $key . '-'.$year);
                $m_end   = date('t-m-'.$year, strtotime($m_start));
                $start_d = date('Y-m-d', strtotime($m_start));
                $end_d   = date('Y-m-d', strtotime($m_end));

                $orders = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->selectRaw('sum(orders.total) as total_amt')
                    ->where('orders.customer_id', $cId)
                    ->where('orders.order_status_id', '=', 3)
                    ->where('orders.payment_method', '=', 'monthly-invoice')
                    ->whereDate('order_details.shipdate', '>=', $start_d)
                    ->whereDate('order_details.shipdate', '<=', $end_d)
                    ->first();
                $order_total = '0.00';
                if ($orders && !empty($orders->total_amt)) {
                    $order_total = $orders->total_amt;

                    $data[$key] = [
                        'month'      => $val,
                        'invoice_id' => Finder::getMonthlyInvoiceId($key, $year, $cId),
                        'amount'     => $order_total,
                    ];
                }
            }
            return view('admin.reports.monthly.customer_monthly_statement', compact('data', 'customer', 'customers', 'year', 'cId'));
        }
        return back()->with('error', 'Sorry! Something is going wrong.');
    }

    /**
     * Display Monthly Sales/Tax
     *
     * @return \Illuminate\Http\Response
     */
    public function monthlySalesTax(Request $request)
    {
        $data       = array();
        $mdata      = array();
        $year       = date('Y');
        $months_arr = \Config::get('constants.MONTHS');
        $status     = 0;
        $statusName = 'Delivered';
        $invert     = 0;
        if ($request->status) {
            $status     = $request->status;
            $statusName = OrderStatus::where('id', $status)->first();
            $statusName = $statusName->name;
        }
        if ($request->invert) {
            $invert = $request->invert;
        }
        if ($invert == 0) {
            krsort($months_arr);
        }

        if ($invert == 1) {
            for ($y = 2010; $y <= date('Y'); $y++) {
                foreach ($months_arr as $key => $val) {
                    $m_start     = date('01-' . $key . '-' . $y);
                    $m_end       = date('t-' . $key . '-' . $y, strtotime($m_start));
                    $start_d     = date('Y-m-d', strtotime($m_start));
                    $end_d       = date('Y-m-d', strtotime($m_end));
                    $statusLists = OrderStatus::get();
                    $orders      = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->selectRaw('sum(orders.total) as total_amt, sum(orders.tax) as total_tax, sum(orders.customer_discount) as total_discount, sum(orders.shipping_charges) as total_shipping');
                        if(!empty($status)){
                        $orders = $orders->where('orders.order_status_id', '=', $status);
                        } 
                        $orders = $orders->whereDate('order_details.shipdate', '>=', $start_d)
                        ->whereDate('order_details.shipdate', '<=', $end_d)
                        ->first();
                    $order_total    = number_format(0.00);
                    $order_tax      = number_format(0.00);
                    $order_shipping = number_format(0.00);
                    $order_discount = number_format(0.00);
                    if ($orders && !empty($orders->total_amt)) {
                        $order_total    = $orders->total_amt;
                        $order_tax      = $orders->total_tax;
                        $order_shipping = $orders->total_shipping;
                        $order_discount = $orders->total_discount;
                    }

                    $mdata[$key] = [
                        'year'           => $y,
                        'month'          => $val,
                        'gross_income'   => $order_total,
                        'gross_tax'      => $order_tax,
                        'gross_shipping' => $order_shipping,
                        'gross_discount' => $order_discount,
                    ];

                }
                $data[$y] = $mdata;

            }
        } else {
            for ($y = date('Y'); $y >= 2010; $y--) {
                foreach ($months_arr as $key => $val) {
                    $m_start     = date('01-' . $key . '-' . $y);
                    $m_end       = date('t-' . $key . '-' . $y, strtotime($m_start));
                    $start_d     = date('Y-m-d', strtotime($m_start));
                    $end_d       = date('Y-m-d', strtotime($m_end));
                    $statusLists = OrderStatus::get();
                    $orders      = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->selectRaw('sum(orders.total) as total_amt, sum(orders.tax) as total_tax, sum(orders.customer_discount) as total_discount, sum(orders.shipping_charges) as total_shipping');
                        if(!empty($status)){
                        $orders = $orders->where('orders.order_status_id', '=', $status);
                        } 
                        $orders = $orders->whereDate('orders.created_at', '>=', $start_d)
                        ->whereDate('orders.created_at', '<=', $end_d)
                        ->first();
                    $order_total    = number_format(0.00);
                    $order_tax      = number_format(0.00);
                    $order_shipping = number_format(0.00);
                    $order_discount = number_format(0.00);
                    if ($orders && !empty($orders->total_amt)) {
                        $order_total    = $orders->total_amt;
                        $order_tax      = $orders->total_tax;
                        $order_shipping = $orders->total_shipping;
                        $order_discount = $orders->total_discount;
                    }

                    $mdata[$key] = [
                        'year'           => $y,
                        'month'          => $val,
                        'gross_income'   => $order_total,
                        'gross_tax'      => $order_tax,
                        'gross_shipping' => $order_shipping,
                        'gross_discount' => $order_discount,
                    ];

                }
                $data[$y] = $mdata;

            }
        }

        if ($request->print && $request->print == 'yes') {
            $print = "yes";
            return view('admin.reports.monthly.monthly_sales_tax_xl', compact('data', 'status', 'statusLists', 'invert', 'print', 'statusName'));
        }
        return view('admin.reports.monthly.monthly_sales_tax', compact('data', 'status', 'statusLists', 'invert'));
    }

    /**
     * Export Monthly Sales/Tax
     *
     * @return \Illuminate\Http\Response
     */
    public function exportMonthlySalesTax(Request $request)
    {
        $data       = array();
        $mdata      = array();
        $year       = date('Y');
        $months_arr = \Config::get('constants.MONTHS');
        $status     = '';
        $invert     = 0;
        if ($request->status) {
            $status = $request->status;
        }
        if ($request->invert) {
            $invert = $request->invert;
        }
        if ($invert == 0) {
            krsort($months_arr);
        }

        if ($invert == 1) {
            for ($y = 2010; $y <= date('Y'); $y++) {
                foreach ($months_arr as $key => $val) {
                    $m_start     = date('01-' . $key . '-' . $y);
                    $m_end       = date('t-' . $key . '-' . $y, strtotime($m_start));
                    $start_d     = date('Y-m-d', strtotime($m_start));
                    $end_d       = date('Y-m-d', strtotime($m_end));
                    $statusLists = OrderStatus::get();
                    $orders      = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->selectRaw('sum(orders.total) as total_amt, sum(orders.tax) as total_tax, sum(orders.customer_discount) as total_discount, sum(orders.shipping_charges) as total_shipping');
                        if(!empty($status)){
                        $orders = $orders->where('orders.order_status_id', '=', $status);
                        } 
                        $orders = $orders->whereDate('orders.created_at', '>=', $start_d)
                        ->whereDate('orders.created_at', '<=', $end_d)
                        ->first();
                    $order_total    = number_format(0.00);
                    $order_tax      = number_format(0.00);
                    $order_shipping = number_format(0.00);
                    $order_discount = number_format(0.00);
                    if ($orders && !empty($orders->total_amt)) {
                        $order_total    = $orders->total_amt;
                        $order_tax      = $orders->total_tax;
                        $order_shipping = $orders->total_shipping;
                        $order_discount = $orders->total_discount;
                    }

                    $mdata[$key] = [
                        'year'           => $y,
                        'month'          => $val,
                        'gross_income'   => $order_total,
                        'gross_tax'      => $order_tax,
                        'gross_shipping' => $order_shipping,
                        'gross_discount' => $order_discount,
                    ];

                }
                $data[$y] = $mdata;

            }
        } else {
            for ($y = date('Y'); $y >= 2010; $y--) {
                foreach ($months_arr as $key => $val) {
                    $m_start     = date('01-' . $key . '-' . $y);
                    $m_end       = date('t-' . $key . '-' . $y, strtotime($m_start));
                    $start_d     = date('Y-m-d', strtotime($m_start));
                    $end_d       = date('Y-m-d', strtotime($m_end));
                    $statusLists = OrderStatus::get();
                    $orders      = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->selectRaw('sum(orders.total) as total_amt, sum(orders.tax) as total_tax, sum(orders.customer_discount) as total_discount, sum(orders.shipping_charges) as total_shipping');
                         if(!empty($status)){
                        $orders = $orders->where('orders.order_status_id', '=', $status);
                        } 
                        $orders = $orders->whereDate('orders.created_at', '>=', $start_d)
                        ->whereDate('orders.created_at', '<=', $end_d)
                        ->first();
                    $order_total    = number_format(0.00);
                    $order_tax      = number_format(0.00);
                    $order_shipping = number_format(0.00);
                    $order_discount = number_format(0.00);
                    if ($orders && !empty($orders->total_amt)) {
                        $order_total    = $orders->total_amt;
                        $order_tax      = $orders->total_tax;
                        $order_shipping = $orders->total_shipping;
                        $order_discount = $orders->total_discount;
                    }

                    $mdata[$key] = [
                        'year'           => $y,
                        'month'          => $val,
                        'gross_income'   => $order_total,
                        'gross_tax'      => $order_tax,
                        'gross_shipping' => $order_shipping,
                        'gross_discount' => $order_discount,
                    ];

                }
                $data[$y] = $mdata;

            }
        }
        $print     = "no";
        $sheetname = 'monthlySalesandTax' . date('d-m-Y');
        return Excel::create($sheetname, function ($excel) use ($data, $sheetname, $print) {
            $excel->sheet($sheetname, function ($sheet) use ($data, $print) {
                $sheet->loadView('admin.reports.monthly.monthly_sales_tax_xl', compact('data', 'print'));
            });
        })->download('xlsx');

    }
}
