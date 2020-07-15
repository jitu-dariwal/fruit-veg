<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Mail\sendEmailtoNewuser;
use App\Shop\Customers\Customer;
use App\Shop\OrderStatuses\OrderStatus;
use App\Shop\Orders\Order;
use DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerReportController extends Controller
{

    /**
     * Display customerOrderTotal
     *
     * @return \Illuminate\Http\Response
     */
    public function customerOrderTotal(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $month = date('m');
        if ($request->month) {
            $month = $request->month;
        }
        $year = date('Y');
        if ($request->year) {
            $year = $request->year;
        }
        $monthly_invoice = 0;
        if ($request->monthly_invoice && $request->monthly_invoice == 1) {
            $monthly_invoice = 'monthly-invoice';
        }
        $delivered = 0;
        if ($request->delivered && $request->delivered == 1) {
            $delivered = 3;
        }
        $customers = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('orders.customer_id,sum(orders.total) total_amt')
            ->whereMonth('order_details.shipdate', $month)
            ->whereYear('order_details.shipdate', $year);
        if (!empty($delivered)) {
            $customers = $customers->where('orders.order_status_id', $delivered);
        }
        if (!empty($monthly_invoice)) {
            $customers = $customers->where('orders.payment_method', $monthly_invoice);
        }
        $customers = $customers->groupBy('orders.customer_id')
            ->orderBy('total_amt', 'desc')
            ->paginate($_RECORDS_PER_PAGE);
        return view('admin.reports.customer.customer_order_total', compact('customers'));
    }

    /**
     * Display Not ValidCustomers
     *
     * @return \Illuminate\Http\Response
     */
    public function notValidCustomers(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $from_date = '';
        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        $to_date = '';
        if ($request->to_date) {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        $customers = Customer::where(function ($where) use ($from_date, $to_date) {
            $where = $where->where('customers_emailvalidated', 0);
            $where = $where->orWhereNull('customers_emailvalidated');
        });
        if (!empty($from_date)) {
            $customers = $customers->whereDate('created_at', '>=', $from_date);
        }
        if (!empty($to_date)) {
            $customers = $customers->whereDate('created_at', '<=', $to_date);
        }
        $customers = $customers->orderBy('id', 'desc')
            ->paginate($_RECORDS_PER_PAGE);
        return view('admin.reports.customer.not_valid_customers', compact('customers'));
    }

    /**
     * Send Email Verfication mail to Customer
     * @var $customer int
     * @return \Illuminate\Http\Response
     */
    public function sendValidationEmail($customerid)
    {

        $customer = Customer::select('id', 'first_name', 'last_name', 'email', 'customers_emailvalidated', 'token', 'activation_mail_send')->where('id', $customerid)->firstOrfail();
        if ($customer->customers_emailvalidated == 1) {
            return back()->with('error', 'Customer Email Id Already Verified by User.');
        }
        Customer::where('id', $customerid)->update([
            'activation_mail_send' => 'yes',
        ]);
        $complete_data = array(
            'first_name' => $customer->first_name,
            'last_name'  => $customer->last_name,
            'email'      => $customer->email,
            'token'      => $customer->token,
        );
        \Mail::to($complete_data['email'], "Registration Successful")->send(new sendEmailtoNewuser($complete_data));
        return back()->with('message', 'Mail has been sent successfully for email verification.');
    }

    /**
     * Export Not ValidCustomers
     *
     * @return \Illuminate\Http\Response
     */
    public function exportNotValidCustomers(Request $request)
    {
        $from_date = '';
        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        $to_date = '';
        if ($request->to_date) {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        $customers = Customer::where(function ($where) use ($from_date, $to_date) {
            $where = $where->where('customers_emailvalidated', 0);
            $where = $where->orWhereNull('customers_emailvalidated');
        });
        if (!empty($from_date)) {
            $customers = $customers->whereDate('created_at', '>=', $from_date);
        }
        if (!empty($to_date)) {
            $customers = $customers->whereDate('created_at', '<=', $to_date);
        }
        $customers = $customers->orderBy('id', 'desc')
            ->get();
        $export_file_name = "notValidateCustomers" . date('d.m.Y');
        $xl               = Excel::create($export_file_name, function ($excel) use ($customers, $export_file_name) {
            $excel->sheet($export_file_name, function ($sheet) use ($customers) {
                $sheet->cell('A1', function ($cell) {$cell->setValue('Customer Name');});
                $sheet->cell('B1', function ($cell) {$cell->setValue('Company');});
                $sheet->cell('C1', function ($cell) {$cell->setValue('Email');});
                $sheet->cell('D1', function ($cell) {$cell->setValue('Mobile');});
                $sheet->cell('E1', function ($cell) {$cell->setValue('Address');});
                $sheet->cell('F1', function ($cell) {$cell->setValue('City');});
                $sheet->cell('G1', function ($cell) {$cell->setValue('Postcode');});
                $sheet->cell('H1', function ($cell) {$cell->setValue('Mail Sent');});
                $sheet->cell('I1', function ($cell) {$cell->setValue('Created At');});
                if (count($customers) > 0) {
                    $i = 1;
                    foreach ($customers as $customer) {
                        $i++;
                        $sheet->cell('A' . $i, ucfirst($customer->first_name . ' ' . $customer->last_name));
                        $sheet->cell('B' . $i, $customer->defaultaddress->company_name);
                        $sheet->cell('C' . $i, $customer->email);
                        $sheet->cell('D' . $i, $customer->tel_num);
                        $sheet->cell('E' . $i, $customer->defaultaddress->street_address . ' ' . $customer->defaultaddress->address_line_2 . ' ' . $customer->defaultaddress->city . ' ' . $customer->defaultaddress->county_state . ' ' . $customer->defaultaddress->post_code);

                        $sheet->cell('F' . $i, $customer->defaultaddress->city);
                        $sheet->cell('G' . $i, $customer->defaultaddress->post_code);
                        $sheet->cell('H' . $i, ucfirst($customer->activation_mail_send));
                        $sheet->cell('I' . $i, (!empty($customer->created_at)) ? $customer->created_at->format('d-m-Y') : 'N/A');
                    }
                }
            });
        });
        return $xl->download('xlsx');
    }

    /**
     * Remove Not ValidCustomers
     *
     * @return \Illuminate\Http\Response
     */
    public function removeNotValidCustomers(Request $request)
    {
        $customer = Customer::findOrfail($request->customer_id);
        $customer->forceDelete();
        return back();
    }

    /**
     * Display customer orders statics.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerStatics(Request $request)
    {
        $year = date('Y');
        if ($request->year) {
            $year = $request->year;
        }
        $month = date('m');
        if ($request->month) {
            $month = $request->month;
        }
        $no_status = 0;
        if ($request->no_status) {
            $no_status = $request->no_status;
        }
        $status = 0;
        if ($request->status) {
            $status = $request->status;
        }
        $mini_ordered = 0;
        if ($request->mini_ordered) {
            $mini_ordered = $request->mini_ordered;
        }
        $data                  = [];
        $data['new_customers'] = Customer::selectRaw('id,count(id) as new_customer')->whereMonth('created_at', $month)->whereYear('created_at', $year)->groupBy(DB::raw("MONTH(created_at)"))->first();

        $customers_having_bought = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('count(orders.id) as bought_order,sum(orders.total) as total_sale, count(orders.customer_id) as totalcustbought')
            ->whereMonth('orders.created_at', $month)
            ->whereYear('orders.created_at', $year);
        if ($no_status != 0) {
            $customers_having_bought = $customers_having_bought->where('orders.order_status_id', '!=', $no_status);
        }
        if ($status != 0) {
            $customers_having_bought = $customers_having_bought->where('orders.order_status_id', '=', $status);
        }
        if ($mini_ordered != 0) {
            $customers_having_bought = $customers_having_bought->havingRaw('count(orders.id) >= ?', [$mini_ordered]);
        }

        $customers_having_bought = $customers_having_bought->groupBy('orders.customer_id')
            ->first();
        $data['customers_having_bought'] = $customers_having_bought;
        $custbuy                         = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->select('orders.id')
            ->whereMonth('orders.created_at', $month)
            ->whereYear('orders.created_at', $year);
        if ($no_status != 0) {
            $custbuy = $custbuy->where('orders.order_status_id', '!=', $no_status);
        }
        if ($status != 0) {
            $custbuy = $custbuy->where('orders.order_status_id', '=', $status);
        }
        $custbuy                      = $custbuy->groupBy('orders.customer_id')->get();
        $data['customersHavingBough'] = $custbuy;
        $new_customers_having_bought  = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->selectRaw('count(customers.id) as t_order')
            ->whereMonth('customers.created_at', $month)
            ->whereYear('customers.created_at', $year)
            ->whereMonth('orders.created_at', $month)
            ->whereYear('orders.created_at', $year);
        if ($no_status != 0) {
            $new_customers_having_bought = $new_customers_having_bought->where('orders.order_status_id', '!=', $no_status);
        }
        if ($status != 0) {
            $new_customers_having_bought = $new_customers_having_bought->where('orders.order_status_id', '=', $status);
        }

        $new_customers_having_bought = $new_customers_having_bought->groupBy('orders.customer_id')
            ->first();
        $data['new_customers_having_bought']   = $new_customers_having_bought;
        $new_customers_having_bought_one_order = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->selectRaw('count(orders.customer_id) as one_order')
            ->whereMonth('orders.created_at', $month)
            ->whereYear('orders.created_at', $year);
        if ($no_status != 0) {
            $new_customers_having_bought_one_order = $new_customers_having_bought_one_order->where('orders.order_status_id', '!=', $no_status);
        }
        if ($status != 0) {
            $new_customers_having_bought_one_order = $new_customers_having_bought_one_order->where('orders.order_status_id', '=', $status);
        }
        if ($mini_ordered != 0) {
            $new_customers_having_bought_one_order = $new_customers_having_bought_one_order->havingRaw('count(orders.customer_id) >= ?', [$mini_ordered]);
        }
        $new_customers_having_bought_one_order = $new_customers_having_bought_one_order->groupBy('orders.customer_id')
            ->get();
        $data['new_customers_having_bought_one_order'] = $new_customers_having_bought_one_order;

        $order_static = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('count(orders.id) as bought_order, sum(orders.total) as total_sale, sum(orders.shipping_charges) as total_shipping, sum(orders.tax) as total_tax')
            ->whereMonth('orders.created_at', $month)
            ->whereYear('orders.created_at', $year);
        if ($no_status != 0) {
            $order_static = $order_static->where('orders.order_status_id', '!=', $no_status);
        }
        if ($status != 0) {
            $order_static = $order_static->where('orders.order_status_id', '=', $status);
        }
        if ($mini_ordered != 0) {
            $order_static = $order_static->havingRaw('count(orders.id) >= ?', [$mini_ordered]);
        }
        $order_static         = $order_static->first();
        $data['order_static'] = $order_static;
        $data['statuses']     = OrderStatus::all();

        return view('admin.reports.customer.customer_statics', $data);
    }

    /**
     * Display Inactive customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function inactiveClients(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $to_date   = date('Y-m-d');
        $from_date = date('Y-m-d', strtotime('-14 days', strtotime($to_date)));
        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if ($request->to_date) {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        $customers = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->select('customers.id', 'customers.first_name', 'customers.last_name', 'customers.email', 'customers.tel_num', 'customers.default_address_id', 'customers.created_at' , 'customers.Access_Time')
        //->whereDate('order_details.shipdate','>=', $days_ago)
            ->whereNotBetween('order_details.shipdate', [$from_date, $to_date])
        //->whereDate('order_details.shipdate', '>=', $from_date)
        //->whereDate('order_details.shipdate', '<=', $to_date)
            ->groupBy('customers.id')
            ->paginate($_RECORDS_PER_PAGE);
        $from_date = date('d-m-Y', strtotime($from_date));
        $to_date   = date('d-m-Y', strtotime($to_date));
        return view('admin.reports.customer.inactive_customers', compact('customers', 'from_date', 'to_date'));
    }

    /**
     * Export Inactive customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportInactiveClients(Request $request)
    {

        $to_date   = date('Y-m-d');
        $from_date = date('Y-m-d', strtotime('-14 days', strtotime($to_date)));
        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if ($request->to_date) {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        $customers = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->select('customers.id', 'customers.first_name', 'customers.last_name', 'customers.email', 'customers.tel_num', 'customers.default_address_id', 'customers.created_at', 'customers.Access_Time')
        //->whereDate('order_details.shipdate','>=', $days_ago)
            ->whereNotBetween('order_details.shipdate', [$from_date, $to_date])
            ->groupBy('customers.id')
            ->orderBy('customers.id', 'desc');
        $export_file_name = "InActiveClients" . date('d.m.Y');
        $xl               = Excel::create($export_file_name, function ($excel) use ($customers, $export_file_name) {
            $excel->sheet($export_file_name, function ($sheet) use ($customers) {
                $sheet->cell('A1', function ($cell) {$cell->setValue('Customer Name');});
                $sheet->cell('B1', function ($cell) {$cell->setValue('Company');});
                $sheet->cell('C1', function ($cell) {$cell->setValue('Email');});
                $sheet->cell('D1', function ($cell) {$cell->setValue('Mobile');});
                $sheet->cell('E1', function ($cell) {$cell->setValue('Address');});
                $sheet->cell('F1', function ($cell) {$cell->setValue('City');});
                $sheet->cell('G1', function ($cell) {$cell->setValue('Postcode');});
                $sheet->cell('H1', function ($cell) {$cell->setValue('Earliest delivery time');});
                $sheet->cell('I1', function ($cell) {$cell->setValue('Total Orders');});
                $sheet->cell('J1', function ($cell) {$cell->setValue('Created At');});
                $customers->chunk(500, function($rows) use ($sheet)
                {
                if (count($rows) > 0) {
                    $i = 1;
                    foreach ($rows as $customer) {
                        $earlyDelTime = 'N/A';
                        if(!empty($customer->Access_Time)){
                            $iHour2 = floor($customer->Access_Time/3600);
                            $iMinute2 = ($customer->Access_Time-$iHour2*3600)/60;
                            $earlyDelTime = sprintf('%02d:%02d',$iHour2,$iMinute2);
                        }
                        $i++;
                        $sheet->cell('A' . $i, ucfirst($customer->first_name . ' ' . $customer->last_name));
                        $sheet->cell('B' . $i, $customer->defaultaddress->company_name);
                        $sheet->cell('C' . $i, $customer->email);
                        $sheet->cell('D' . $i, $customer->tel_num);
                        $sheet->cell('E' . $i, $customer->defaultaddress->street_address . ' ' . $customer->defaultaddress->address_line_2 . ' ' . $customer->defaultaddress->city . ' ' . $customer->defaultaddress->county_state . ' ' . $customer->defaultaddress->post_code);

                        $sheet->cell('F' . $i, $customer->defaultaddress->city);
                        $sheet->cell('G' . $i, $customer->defaultaddress->post_code);
                        $sheet->cell('H' . $i, $earlyDelTime);
                        $sheet->cell('I' . $i, count($customer->orders));
                        $sheet->cell('J' . $i, (!empty($customer->created_at)) ? $customer->created_at->format('d-m-Y') : 'N/A');
                    } 
                } //
                });
            });
        });
        return $xl->download('xlsx');
    }

    /**
     * Display Active customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeClients(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $to_date   = date('Y-m-d');
        $from_date = date('Y-m-d', strtotime('-14 days', strtotime($to_date)));
        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if ($request->to_date) {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        $customers = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->select('customers.id', 'customers.first_name', 'customers.last_name', 'customers.email', 'customers.tel_num', 'customers.default_address_id', 'customers.created_at', 'customers.Access_Time')
            ->whereDate('order_details.shipdate', '>=', $from_date)
            ->whereDate('order_details.shipdate', '<', $to_date)
            ->groupBy('customers.id')
            ->orderBy('customers.id', 'desc')->paginate($_RECORDS_PER_PAGE);
        $from_date = date('d-m-Y', strtotime($from_date));
        $to_date   = date('d-m-Y', strtotime($to_date));
        return view('admin.reports.customer.active_customers', compact('customers', 'from_date', 'to_date'));
    }

    /**
     * Export Active customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportActiveClients(Request $request)
    {
        $to_date   = date('Y-m-d');
        $from_date = date('Y-m-d', strtotime('-14 days', strtotime($to_date)));
        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if ($request->to_date) {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }

        $customers = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->select('customers.id', 'customers.first_name', 'customers.last_name', 'customers.email', 'customers.tel_num', 'customers.default_address_id', 'customers.created_at', 'customers.Access_Time')
            ->whereDate('order_details.shipdate', '>=', $from_date)
            ->whereDate('order_details.shipdate', '<', $to_date)
            ->groupBy('customers.id')
            ->orderBy('customers.id', 'desc')->get();
        $export_file_name = "ActiveClients" . date('d.m.Y');
        $xl               = Excel::create($export_file_name, function ($excel) use ($customers, $export_file_name) {
            $excel->sheet($export_file_name, function ($sheet) use ($customers) {
                $sheet->cell('A1', function ($cell) {$cell->setValue('Customer Name');});
                $sheet->cell('B1', function ($cell) {$cell->setValue('Company');});
                $sheet->cell('C1', function ($cell) {$cell->setValue('Email');});
                $sheet->cell('D1', function ($cell) {$cell->setValue('Mobile');});
                $sheet->cell('E1', function ($cell) {$cell->setValue('Address');});
                $sheet->cell('F1', function ($cell) {$cell->setValue('City');});
                $sheet->cell('G1', function ($cell) {$cell->setValue('Postcode');});
                $sheet->cell('H1', function ($cell) {$cell->setValue('Earliest delivery time');});
                $sheet->cell('I1', function ($cell) {$cell->setValue('Created At');});
                if (count($customers) > 0) {
                    $i = 1;
                    foreach ($customers as $customer) {
                        $i++;
                        $earlyDelTime = 'N/A';
                        if(!empty($customer->Access_Time)){
                            $iHour2 = floor($customer->Access_Time/3600);
                            $iMinute2 = ($customer->Access_Time-$iHour2*3600)/60;
                            $earlyDelTime = sprintf('%02d:%02d',$iHour2,$iMinute2);
                        }
                        $sheet->cell('A' . $i, ucfirst($customer->first_name . ' ' . $customer->last_name));
                        $sheet->cell('B' . $i, $customer->defaultaddress->company_name);
                        $sheet->cell('C' . $i, $customer->email);
                        $sheet->cell('D' . $i, $customer->tel_num);
                        $sheet->cell('E' . $i, $customer->defaultaddress->street_address . ' ' . $customer->defaultaddress->address_line_2 . ' ' . $customer->defaultaddress->city . ' ' . $customer->defaultaddress->county_state . ' ' . $customer->defaultaddress->post_code);

                        $sheet->cell('F' . $i, $customer->defaultaddress->city);
                        $sheet->cell('G' . $i, $customer->defaultaddress->post_code);
                        $sheet->cell('H' . $i, $earlyDelTime);
                        $sheet->cell('I' . $i, (!empty($customer->created_at)) ? $customer->created_at->format('d-m-Y') : 'N/A');
                    }
                }
            });
        });
        return $xl->download('xlsx');
    }

    /**
     * Display Never Ordered customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function neverOrderedClients(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $to_date   = '';
        $from_date = '';
        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if ($request->to_date) {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }

        $customers = Customer::leftJoin('orders', function ($join) {
            $join->on('customers.id', '=', 'orders.customer_id');
        })
            ->select('customers.id', 'customers.first_name', 'customers.last_name', 'customers.email', 'customers.tel_num', 'customers.default_address_id', 'customers.created_at')
            ->whereNull('orders.customer_id')
            ->where(function ($where) use ($from_date, $to_date) {
                if (!empty($from_date)) {

                    $where->whereDate('customers.created_at', '>=', $from_date);
                }
                if (!empty($to_date)) {
                    $where->whereDate('customers.created_at', '<=', $to_date);
                }
            })
            ->groupBy('customers.id')
            ->orderBy('customers.id', 'desc')->paginate($_RECORDS_PER_PAGE);
        $from_date = date('d-m-Y', strtotime($from_date));
        $to_date   = date('d-m-Y', strtotime($to_date));
        return view('admin.reports.customer.never_ordered_customers', compact('customers'));
    }

    /**
     * Export Never Ordered customers.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportNeverOrderedClients(Request $request)
    {
        $to_date   = '';
        $from_date = '';
        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        if ($request->to_date) {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }

        $customers = Customer::leftJoin('orders', function ($join) {
            $join->on('customers.id', '=', 'orders.customer_id');
        })
            ->select('customers.id', 'customers.first_name', 'customers.last_name', 'customers.email', 'customers.tel_num', 'customers.default_address_id', 'customers.created_at')
            ->whereNull('orders.customer_id')
            ->where(function ($where) use ($from_date, $to_date) {
                if (!empty($from_date)) {
                    $where->whereDate('customers.created_at', '>=', $from_date);
                }
                if (!empty($to_date)) {
                    $where->whereDate('customers.created_at', '<=', $to_date);
                }
            })
            ->groupBy('customers.id')
            ->orderBy('customers.id', 'desc')->get();

        $export_file_name = "NeverOrderedClients" . date('d.m.Y');
        $xl               = Excel::create($export_file_name, function ($excel) use ($customers, $export_file_name) {
            $excel->sheet($export_file_name, function ($sheet) use ($customers) {
                $sheet->cell('A1', function ($cell) {$cell->setValue('Customer Name');});
                $sheet->cell('B1', function ($cell) {$cell->setValue('Company');});
                $sheet->cell('C1', function ($cell) {$cell->setValue('Email');});
                $sheet->cell('D1', function ($cell) {$cell->setValue('Mobile');});
                $sheet->cell('E1', function ($cell) {$cell->setValue('Address');});
                $sheet->cell('F1', function ($cell) {$cell->setValue('City');});
                $sheet->cell('G1', function ($cell) {$cell->setValue('Postcode');});
                $sheet->cell('H1', function ($cell) {$cell->setValue('Created At');});
                if (count($customers) > 0) {
                    $i = 1;
                    foreach ($customers as $customer) {
                        $i++;
                        $sheet->cell('A' . $i, ucfirst($customer->first_name . ' ' . $customer->last_name));
                        $sheet->cell('B' . $i, $customer->defaultaddress->company_name);
                        $sheet->cell('C' . $i, $customer->email);
                        $sheet->cell('D' . $i, $customer->tel_num);
                        $sheet->cell('E' . $i, $customer->defaultaddress->street_address . ' ' . $customer->defaultaddress->address_line_2 . ' ' . $customer->defaultaddress->city . ' ' . $customer->defaultaddress->county_state . ' ' . $customer->defaultaddress->post_code);

                        $sheet->cell('F' . $i, $customer->defaultaddress->city);
                        $sheet->cell('G' . $i, $customer->defaultaddress->post_code);
                        $sheet->cell('H' . $i, (!empty($customer->created_at)) ? $customer->created_at->format('d-m-Y') : 'N/A');
                    }
                }
            });
        });
        return $xl->download('xlsx');
    }

    /**
     * Display Customers Discounts.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerDiscounts(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $from_date = date('Y-m-d');
        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        $to_date = date('Y-m-d');
        if ($request->to_date) {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        $customers = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('customers.id,customers.first_name, customers.last_name, customers.email,customers.default_address_id, customers.created_at,sum(orders.customer_discount) as total_discount')
            ->whereDate('order_details.shipdate', '>=', $from_date)
            ->whereDate('order_details.shipdate', '<=', $to_date)
            ->whereNotNull('orders.customer_discount')
            ->where('orders.customer_discount', '!=', '0.00')
            ->where('orders.order_status_id', '=', 3)
            ->groupBy('customers.id')
            ->orderBy('order_details.company_name', 'asc')->paginate($_RECORDS_PER_PAGE);
        $from_date = date('d-m-Y', strtotime($from_date));
        $to_date   = date('d-m-Y', strtotime($to_date));
        return view('admin.reports.customer.customer_discounts', compact('customers', 'from_date', 'to_date'));
    }

    /**
     * Export Customer Discount Reports.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportCustomerDiscounts(Request $request)
    {
        $from_date = date('Y-m-d');
        if ($request->from_date) {
            $from_date = date('Y-m-d', strtotime($request->from_date));
        }
        $to_date = date('Y-m-d');
        if ($request->to_date) {
            $to_date = date('Y-m-d', strtotime($request->to_date));
        }
        $customers = Customer::join('orders', 'customers.id', '=', 'orders.customer_id')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('customers.id,customers.first_name, customers.last_name, customers.email,customers.default_address_id, customers.created_at,sum(orders.customer_discount) as total_discount')
            ->whereDate('order_details.shipdate', '>=', $from_date)
            ->whereDate('order_details.shipdate', '<=', $to_date)
            ->whereNotNull('orders.customer_discount')
            ->where('orders.customer_discount', '!=', '0.00')
            ->where('orders.order_status_id', '=', 3)
            ->groupBy('customers.id')
            ->orderBy('orders.id', 'desc')->paginate(env('RECORDS_PER_PAGE'));
        $from_date = date('d-m-Y', strtotime($from_date));
        $to_date   = date('d-m-Y', strtotime($to_date));
        $sheetname = 'customerDiscounts' . date('d-m-Y');
        return Excel::create($sheetname, function ($excel) use ($customers, $from_date, $to_date, $sheetname) {
            $excel->sheet($sheetname, function ($sheet) use ($customers, $from_date, $to_date) {
                $sheet->loadView('admin.reports.customer.export_customer_discounts', compact('customers', 'from_date', 'to_date'));
            });
        })->download('xlsx');
    }

    /**
     * Display Customers Invoice Notes.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerInvoiceNotes(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $month_no = '';
        $year     = '';
        if ($request->month) {
            $month_no = $request->month;
        }
        if ($request->year) {
            $year = $request->year;
        }

        $notes = Customer::leftjoin('orders', 'customers.id', '=', 'orders.customer_id')
            ->leftjoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('customers.id,customers.first_name, customers.last_name, customers.email,customers.default_address_id, customers.customers_invoice_notes');
            if (!empty($month_no)) {
            $notes = $notes->whereMonth('order_details.shipdate', '=', $month_no);
            }
            if (!empty($year)) {
                $notes = $notes->whereYear('order_details.shipdate', '=', $year);
            }
            $notes= $notes->where('customers.customers_invoice_notes', '!=', '')
                          ->whereNotNull('customers_invoice_notes')
                          ->groupBy('customers.id')
                          ->orderBy('customers.id', 'asc')
                          ->paginate($_RECORDS_PER_PAGE);

       
        return view('admin.reports.customer.customers_invoice_notes', compact('notes', 'year', 'month_no'));
    }

    /**
     * Export Customers Invoice Notes.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportCustomerInvoiceNotes(Request $request)
    {
        $month_no = '';
        $year     = '';
        if ($request->month) {
            $month_no = $request->month;
        }
        if ($request->year) {
            $year = $request->year;
        }
        $notes = Customer::leftjoin('orders', 'customers.id', '=', 'orders.customer_id')
            ->leftjoin('order_details', 'orders.id', '=', 'order_details.order_id')
            ->selectRaw('customers.id,customers.first_name, customers.last_name, customers.email,customers.default_address_id, customers.customers_invoice_notes');
            if (!empty($month_no)) {
            $notes = $notes->whereMonth('order_details.shipdate', '=', $month_no);
            }
            if (!empty($year)) {
                $notes = $notes->whereYear('order_details.shipdate', '=', $year);
            }
            $notes= $notes->where('customers.customers_invoice_notes', '!=', '')
                          ->whereNotNull('customers_invoice_notes')
                          ->groupBy('customers.id')
                          ->orderBy('customers.id', 'asc')
                          ->get();

        $export_file_name = "CustInvoiceNotes" . date('d.m.Y');
        $xl               = Excel::create($export_file_name, function ($excel) use ($notes, $export_file_name) {
            $excel->sheet($export_file_name, function ($sheet) use ($notes) {
                $sheet->cell('A1', function ($cell) {$cell->setValue('S. No.');});
                $sheet->cell('B1', function ($cell) {$cell->setValue('Customers Name');});
                $sheet->cell('C1', function ($cell) {$cell->setValue('Company Name');});
                $sheet->cell('D1', function ($cell) {$cell->setValue('Company Address');});
                $sheet->cell('E1', function ($cell) {$cell->setValue('Notes');});
                if (count($notes) > 0) {
                    $i = 1;
                    foreach ($notes as $note) {
                        $i++;
                        $j = $i - 1;
                        $sheet->cell('A' . $i, $j);
                        $sheet->cell('B' . $i, ucfirst($note->first_name . ' ' . $note->last_name));
                        $sheet->cell('C' . $i, $note->defaultaddress->company_name);
                        $sheet->cell('D' . $i, $note->defaultaddress->street_address . ', ' . $note->defaultaddress->address_line_2 . ', ' . $note->defaultaddress->city . ', ' . $note->defaultaddress->county_state . ', ' . $note->defaultaddress->post_code);
                        $sheet->cell('E' . $i, $note->customers_invoice_notes);
                    }
                }
            });
        });
        return $xl->download('xlsx');
    }

    /**
     * Display Detailed Discounts.
     *
     * @return \Illuminate\Http\Response
     */
    public function detailDiscounts(Request $request)
    {
        $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
        $month_no = date("m");
        $year     = date("Y");
        if ($request->month) {
            $month_no = $request->month;
        }
        if ($request->year) {
            $year = $request->year;
        }
        $discount_details = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->select('orders.id', 'orders.customer_discount', 'orders.discount_type', 'orders.customer_id')
            ->whereMonth('order_details.shipdate', '=', $month_no)
            ->whereYear('order_details.shipdate', '=', $year)
            ->whereNotNull('orders.customer_discount')
            ->where('orders.customer_discount', '!=', '0.00')
            ->where('orders.discount_type', '!=', '0')
            ->where('orders.order_status_id', '=', 3)
            ->orderBy('orders.id', 'desc')->paginate($_RECORDS_PER_PAGE);
        return view('admin.reports.customer.discount_detailed_reports', compact('discount_details', 'year', 'month_no'));
    }

    /**
     * Export Detailed Discounts.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportDetailDiscounts(Request $request)
    {
        $month_no = date("m");
        $year     = date("Y");
        if ($request->month) {
            $month_no = $request->month;
        }
        if ($request->year) {
            $year = $request->year;
        }
        $discount_details = Order::join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->select('orders.id', 'orders.customer_discount', 'orders.discount_type', 'orders.customer_id')
            ->whereMonth('order_details.shipdate', '=', $month_no)
            ->whereYear('order_details.shipdate', '=', $year)
            ->whereNotNull('orders.customer_discount')
            ->where('orders.customer_discount', '!=', '0.00')
            ->where('orders.discount_type', '!=', '0')
            ->where('orders.order_status_id', '=', 3)
            ->orderBy('orders.id', 'desc')->get();
        $sheetname = 'discountDetails' . $month_no . '_' . $year;
        return Excel::create($sheetname, function ($excel) use ($discount_details, $month_no, $year, $sheetname) {
            $excel->sheet($sheetname, function ($sheet) use ($discount_details, $month_no, $year) {
                $month_no = \Config::get('constants.MONTHS')[$month_no];
                $sheet->loadView('admin.reports.customer.export_discount_detailed_reports', compact('discount_details', 'month_no', 'year'));
            });
        })->download('xlsx');
    }
}
