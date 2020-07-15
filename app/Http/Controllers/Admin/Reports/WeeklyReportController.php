<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Helper\Finder;
use App\Http\Controllers\Controller;
use App\Mail\SendWeeklyInoviceMail;
use App\Shop\Customers\Customer;
use App\Shop\Invoices\Invoice;
use App\Shop\Orders\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class WeeklyReportController extends Controller
{

    /**
     * Display customer paid unpaid weekly Statements.
     *
     * @return \Illuminate\Http\Response
     */
    public function paidunpaidWeeklyTotal(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $week_no           = date("W");
        $year              = date("Y");
        $order_status      = 5;
        $invoice_type      = '';
        $check_paid        = 0;
        if ($request->week_number) {
            $week_no = $request->week_number;
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
        $dates                  = Finder::getStartAndEndDate($week_no, $year);
        $w_start                = $dates['week_start'];
        $w_end                  = $dates['week_end'];
        $start_d                = date('Y-m-d', strtotime($w_start));
        $end_d                  = date('Y-m-d', strtotime($w_end));
        $customers_invoice_type = \Config::get('constants.REQUIRE_INVOICE_TYPE');
        $getInvoices            = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('orders.id as order_id, customers.id, customers.first_name, customers.sage_ref, customers.last_name, customers.default_address_id, sum(orders.total) as inovice_total, orders.payment_method')
            ->where(function ($query) use ($customers_invoice_type) {
                $query->whereNull('customers.customers_require_invoice_type')
                    ->orWhere('customers.customers_require_invoice_type', '!=', $customers_invoice_type);
            })
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
        $getInvoices = $getInvoices->groupBy('customers.id')->orderBy('order_details.company_name', 'asc')
            ->paginate($_RECORDS_PER_PAGE);

        return view('admin.reports.weekly.paid_unpaid_weekly_total', compact('getInvoices', 'week_no', 'year', 'start_d', 'end_d'));
    }

    /**
     * Export customer paid unpaid weekly Statements.
     *
     * @return \Illuminate\Http\Response
     */
    public function paidunpaidWeeklyTotalExport(Request $request)
    {
        $week_no      = date("W");
        $year         = date("Y");
        $order_status = 5;
        $invoice_type = '';
        $check_paid   = 0;
        if ($request->week_number) {
            $week_no = $request->week_number;
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
        $paymenttypes           = Finder::getPaymentMethods();
        $dates                  = Finder::getStartAndEndDate($week_no, $year);
        $w_start                = $dates['week_start'];
        $w_end                  = $dates['week_end'];
        $start_d                = date('Y-m-d', strtotime($w_start));
        $end_d                  = date('Y-m-d', strtotime($w_end));
        $customers_invoice_type = \Config::get('constants.REQUIRE_INVOICE_TYPE');
        $getInvoices            = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('orders.id as order_id, customers.id, customers.first_name, customers.last_name, customers.default_address_id, sum(orders.total) as inovice_total, orders.payment_method')
            ->where(function ($query) use ($customers_invoice_type) {
                $query->whereNull('customers.customers_require_invoice_type')
                    ->orWhere('customers.customers_require_invoice_type', '!=', $customers_invoice_type);
            })
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
            ->get();

        $export_file_name = "Paid_unpaid_total" . $start_d . "_to_" . $end_d;
        $xl               = Excel::create($export_file_name, function ($excel) use ($getInvoices, $week_no, $year, $paymenttypes) {
            $excel->sheet('mySheet', function ($sheet) use ($getInvoices, $week_no, $year, $paymenttypes) {
                $sheet->cell('A1', function ($cell) {$cell->setValue('Customer Name');});
                $sheet->cell('B1', function ($cell) {$cell->setValue('Company');});
                $sheet->cell('C1', function ($cell) {$cell->setValue('Payment Type');});
                $sheet->cell('D1', function ($cell) {$cell->setValue('Total');});
                $sheet->cell('E1', function ($cell) {$cell->setValue('Payment Method');});
                $sheet->cell('F1', function ($cell) {$cell->setValue('REMITTANCE');});
                $sheet->cell('G1', function ($cell) {$cell->setValue('Paid/Unpaid');});
                $sheet->cell('H1', function ($cell) {$cell->setValue('Paid Date');});
                $sheet->cell('I1', function ($cell) {$cell->setValue('InvoiceID');});
                if (count($getInvoices) > 0) {

                    foreach ($getInvoices as $key => $ex_data) {
                        $invoiceStatus = 0;
                        $is_locked     = 0;
                        $paid_method   = 0;
                        $is_remittance = 0;
                        $paid_date     = 0;
                        $invoiceId     = Finder::getInvoiceId($week_no, $year, $ex_data->id);
                        $getInvoice    = Finder::getInvoiceStatus($invoiceId);
                        if ($getInvoice) {
                            $invoiceStatus = $getInvoice->status;
                            $is_locked     = $getInvoice->is_confirm;
                            $paid_method   = $getInvoice->payment_method;
                            $is_remittance = $getInvoice->remittance;
                            $paid_date     = $getInvoice->paid_date;
                        }
                        $i = $key + 2;
                        $sheet->cell('A' . $i, ucfirst($ex_data->first_name . ' ' . $ex_data->last_name));
                        $sheet->cell('B' . $i, $ex_data->defaultaddress->company_name);
                        $sheet->cell('C' . $i, $paymenttypes[$ex_data->payment_method]);
                        $sheet->cell('D' . $i, env('CURRENCY_SYMBOL') . ' ' . $ex_data->inovice_total);
                        $sheet->cell('E' . $i, (!empty($paid_method)) ? $paymenttypes[$paid_method] : '');
                        $sheet->cell('F' . $i, $is_remittance);
                        $sheet->cell('G' . $i, (!empty($invoiceStatus)) ? \Config::get('constants.INVOICE_STATUS')[$invoiceStatus] : '');
                        $sheet->cell('H' . $i, (!empty($paid_date)) ? Carbon::parse($paid_date)->format('jS M Y') : 'N/A');
                        $sheet->cell('I' . $i, $invoiceId);
                    }
                }
            });
        });
        return $xl->download('xlsx');
    }

    /**
     * Display customer paid unpaid weekly Statements.
     *
     * @return \Illuminate\Http\Response
     */
    public function paidunpaidCustomerWeekly(Request $request)
    {
        $cId        = 0;
        $getinvoice = array();
        if ($request->cId) {
            $cId = $request->cId;
        }
        $week_no      = date("W");
        $year         = date("Y");
        $order_status = 5;
        $invoice_type = 'monthly-invoice';
        $check_paid   = 0;
        if ($request->week_number) {
            $week_no = $request->week_number;
        }
        if ($request->year) {
            $year = $request->year;
        }
        if ($request->orders_type) {
            $order_status = $request->orders_type;
        }
        if ($request->orders_method_type) {
            $invoice_type = $request->orders_method_type;
        }
        if ($request->check_paid) {
            $check_paid = $request->check_paid;
        }
        $weeks = Finder::getIsoWeeksInYear($year);
        for ($week_no = 1; $week_no <= $weeks; $week_no++) {
            $dates                  = Finder::getStartAndEndDate($week_no, $year);
            $w_start                = $dates['week_start'];
            $w_end                  = $dates['week_end'];
            $start_d                = date('Y-m-d', strtotime($w_start));
            $end_d                  = date('Y-m-d', strtotime($w_end));
            $customers_invoice_type = \Config::get('constants.REQUIRE_INVOICE_TYPE');
            $getinvoices            = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->selectRaw('orders.id as order_id, sum(orders.total) as inovice_total, orders.payment_method')
                ->where('orders.customer_id', '=', $cId)
                ->whereDate('order_details.shipdate', '>=', $start_d)
                ->whereDate('order_details.shipdate', '<=', $end_d);
            if ($order_status != 5) {
                $getinvoices = $getinvoices->where('orders.order_status_id', '=', $order_status);
            } else {
                $getinvoices = $getinvoices->where('orders.order_status_id', '!=', $order_status);
            }
            if (!empty($invoice_type)) {
                $getinvoices = $getinvoices->where('orders.payment_method', '=', $invoice_type);
            }
            $getinvoices = $getinvoices->first();

            if (!empty($getinvoices->order_id)) {
                $getinvoice[$week_no] = [
                    'invoice' => $getinvoices,
                    'week'    => $week_no,
                    'year'    => $year,
                ];
            }
        }
        //dd($getinvoice);
        $customers = Customer::select('id', 'first_name', 'last_name', 'default_address_id')
            ->where(function ($query) use ($customers_invoice_type) {
                $query->whereNull('customers_require_invoice_type')
                    ->orWhere('customers_require_invoice_type', '!=', $customers_invoice_type);
            })
            ->orderBy('first_name', 'asc')->get();
        $customer = Customer::where('id', $cId)
            ->where(function ($query) use ($customers_invoice_type) {
                $query->whereNull('customers_require_invoice_type')
                    ->orWhere('customers_require_invoice_type', '!=', $customers_invoice_type);
            })->first();
        return view('admin.reports.weekly.paid_unpaid_yearly_weekly', compact('getinvoice', 'customers', 'week_no', 'year', 'start_d', 'end_d', 'cId', 'customer', 'invoice_type', 'order_status'));
    }

    /**
     * Display Weekly Invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function weeklyInovice(Request $request)
    {
        if ($request->week_number) {
            $week_no = $request->week_number;
        }
        if ($request->year) {
            $year = $request->year;
        }
        if ($request->customer) {
            $customer_id = $request->customer;
        }
        if (!empty($week_no) && !empty($year) && !empty($customer_id)) {
            $dates                  = Finder::getStartAndEndDate($week_no, $year);
            $w_start                = $dates['week_start'];
            $w_end                  = $dates['week_end'];
            $start_d                = date('Y-m-d', strtotime($w_start));
            $end_d                  = date('Y-m-d', strtotime($w_end));
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
            $invoiceId    = Finder::getInvoiceId($week_no, $year, $customer_id);
            $checkInvoice = Invoice::where('invoiceid', $invoiceId)->first();

            $data = [
                'customer_id'    => $customer_id,
                'invoiceid'      => $invoiceId,
                'status'         => 1,
                'week_no'        => $week_no,
                'year'           => $year,
                'start_date'     => $start_d,
                'end_date'       => $end_d,
                'type'           => 'weekly',
                'payment_method' => '',
                'paid_date'      => null,
                'remittance'     => '',
            ];
            if ($checkInvoice && !empty($checkInvoice)) {
            } else {
                Invoice::create($data);
            }
            return view('admin.reports.weekly.weekly_invoice', compact('orders', 'customer', 'w_start', 'w_end', 'week_no', 'year', 'customer_id'));
        }

        return back()->with('error', 'Sorry! Something is going wrong.');
    }

    /**
     * Email Weekly Invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function emailWeeklyInovice(Request $request)
    {
        if ($request->week_number) {
            $week_no = $request->week_number;
        }
        if ($request->year) {
            $year = $request->year;
        }
        if ($request->customer_id) {
            $customer_id = $request->customer_id;
        }
        if (!empty($week_no) && !empty($year) && !empty($customer_id)) {
            $dates                  = Finder::getStartAndEndDate($week_no, $year);
            $w_start                = $dates['week_start'];
            $w_end                  = $dates['week_end'];
            $start_d                = date('Y-m-d', strtotime($w_start));
            $end_d                  = date('Y-m-d', strtotime($w_end));
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
            $invoiceId = Finder::getInvoiceId($week_no, $year, $customer_id);
            $data      = [
                'week_no'    => $week_no,
                'year'       => $year,
                'customer'   => $customer,
                'w_start'    => $w_start,
                'w_end'      => $w_end,
                'orders'     => $orders,
                'invoice_id' => $invoiceId,
                'notes'      => $request->Notes,
            ];
            $sendmail = Mail::to($customer->email);
            if ($request->emailadd && !empty($request->emailadd)) {
                $sendmail->cc($request->emailadd);
            }
            $sendmail->send(new SendWeeklyInoviceMail($data));
            return back()->with('message', 'Invoice successfully sent.');
        }

        return back()->with('error', 'Sorry! Something is going wrong.');
        return view('emails.invoices.weekly_invoice');
    }

    /**
     * Display Weekly Invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function customerWeeklyStatement(Request $request)
    {
        $customers_invoice_type = \Config::get('constants.REQUIRE_INVOICE_TYPE');
        $data                   = array();
        $year                   = date('Y');
        $cId                    = 0;
        $cust_Id                = Customer::select('id', 'default_address_id')
            ->where(function ($query) use ($customers_invoice_type) {
                $query->whereNull('customers_require_invoice_type')
                    ->orWhere('customers_require_invoice_type', '!=', $customers_invoice_type);
            })->orderBy('id', 'desc')->first();

        if ($cust_Id) {
            $cId = $cust_Id->id;
        } else {
            return back()->with('error', 'No user found for weekly invoice statement.');
        }
        if ($request->cId) {
            $cId = $request->cId;
        }
        if ($request->year) {
            $year = $request->year;
        }

        $customers = Customer::select('id', 'first_name', 'last_name', 'default_address_id')
            ->where(function ($query) use ($customers_invoice_type) {
                $query->whereNull('customers_require_invoice_type')
                    ->orWhere('customers_require_invoice_type', '!=', $customers_invoice_type);
            })
            ->orderBy('first_name', 'asc')->get();
        $customer = Customer::where('id', $cId)
            ->where(function ($query) use ($customers_invoice_type) {
                $query->whereNull('customers_require_invoice_type')
                    ->orWhere('customers_require_invoice_type', '!=', $customers_invoice_type);
            })->first();
        if (!empty($year) && !empty($cId)) {
            $weeks = Finder::getIsoWeeksInYear($year);
            for ($x = 1; $x <= $weeks; $x++) {
                $dates   = Finder::getStartAndEndDate($x, $year);
                $w_start = $dates['week_start'];
                $w_end   = $dates['week_end'];
                $start_d = date('Y-m-d', strtotime($w_start));
                $end_d   = date('Y-m-d', strtotime($w_end));

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

                    $data[$x] = [
                        'week'       => 'Week Number ' . $x . '<br> ' . $w_start . ' - ' . $w_end,
                        'invoice_id' => Finder::getInvoiceId($x, $year, $cId),
                        'amount'     => $order_total,
                    ];
                }
            }
            return view('admin.reports.weekly.customer_weekly_statement', compact('data', 'customer', 'customers', 'year', 'cId'));
        }
        return back()->with('error', 'Sorry! Something is going wrong.');

    }
}
