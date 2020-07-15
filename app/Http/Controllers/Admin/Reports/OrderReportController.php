<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop\Orders\Order;
use App\Shop\OrderDetails\OrderDetail;
use App\Shop\OrderProducts\OrderProduct;
use App\Shop\Products\Product;
use App\Shop\Customers\Customer;
use App\Shop\OrderStatuses\OrderStatus;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Helper\Finder;
use DB;

class OrderReportController extends Controller
{
	/**
     * Display Sales Reports.
     *
     * @return \Illuminate\Http\Response
     */
    public function salesReport(Request $request)
	{
		$show_top=0;
		$status=0;
		$sort=0;
		$export=0;
		$start_date=date('Y-m-01');
		if($request->from_date){
			$start_date= date('Y-m-d', strtotime($request->from_date));
		}
		$to_date=date('Y-m-d');
		if($request->to_date){
			$to_date= date('Y-m-d', strtotime($request->to_date));
		}
		
		if($request->max && !empty($request->max)){
			$show_top= $request->max;
		}
		
		if($request->status && !empty($request->status)){
			$status= $request->status;
		}
		
		if($request->sort && !empty($request->sort)){
			$sort= $request->sort;
		}
		
		if($request->export && !empty($request->export)){
			$export= $request->export;
		}
		
		
		$statuses=OrderStatus::all();				
		$start_date= date('d-m-Y', strtotime($start_date));
		$to_date= date('d-m-Y', strtotime($to_date));
		$execlData=[
		    'start_date' => $start_date,
		    'to_date' => $to_date,
		    'show_top' => $show_top,
		    'status' => $status,
		    'sort' => $sort,
		    'export' => $export
		];
		if($export==1){
		return view('admin.reports.order.sales_reports_html', compact('start_date','to_date', 'show_top','statuses','status','sort','export'));
		}
		
		if($export==2){
			return Excel::create('salesReport', function($excel) use ($execlData){
				$excel->sheet('salesReport', function($sheet) use ($execlData){
					$sheet->loadView('admin.reports.order.sales_reports_xl', $execlData);
				});
			})->download('xlsx');
		}
		
		return view('admin.reports.order.sales_reports', compact('start_date','to_date', 'show_top','statuses','status','sort','export'));
	}


	/**
     * Display Sales Reports.
     *
     * @return \Illuminate\Http\Response
     */
    public function salesAsPerCatReport(Request $request)
	{
		$show_top=0;
		$status=0;
		$sort=0;
		$export=0;
		$start_date=date('Y-m-01');
		if($request->from_date){
			$start_date= date('Y-m-d', strtotime($request->from_date));
		}
		$to_date=date('Y-m-d');
		if($request->to_date){
			$to_date= date('Y-m-d', strtotime($request->to_date));
		}
		
		if($request->max && !empty($request->max)){
			$show_top= $request->max;
		}
		
		if($request->status && !empty($request->status)){
			$status= $request->status;
		}
		
		if($request->sort && !empty($request->sort)){
			$sort= $request->sort;
		}
		
		if($request->export && !empty($request->export)){
			$export= $request->export;
		}
		
		
		$statuses=OrderStatus::all();				
		$start_date= date('d-m-Y', strtotime($start_date));
		$to_date= date('d-m-Y', strtotime($to_date));
		$execlData=[
		    'start_date' => $start_date,
		    'to_date' => $to_date,
		    'show_top' => $show_top,
		    'status' => $status,
		    'sort' => $sort,
		    'export' => $export
		];
		if($export==1){
		return view('admin.reports.order.cat_sales_reports_html', compact('start_date','to_date', 'show_top','statuses','status','sort','export'));
		}
		
		if($export==2){
			return Excel::create('salesReport', function($excel) use ($execlData){
				$excel->sheet('salesReport', function($sheet) use ($execlData){
					$sheet->loadView('admin.reports.order.cat_sales_reports_xl', $execlData);
				});
			})->download('xlsx');
		}
		
		return view('admin.reports.order.cat_sales_reports', compact('start_date','to_date', 'show_top','statuses','status','sort','export'));
	}

	
	/**
     * Display Daily Product Sales Report.
     *
     * @return \Illuminate\Http\Response
     */
	public function dailyProductsalesReport(Request $request)
	{
		
		$for_date=date('Y-m-d');
		if($request->for_date){
			$for_date= date('Y-m-d', strtotime($request->for_date));
		}
				
		$_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
		 $order_items=OrderProduct::join('order_details', 'order_product.order_id', '=', 'order_details.order_id')
		    ->join('orders', 'order_product.order_id', '=', 'orders.id')
		    ->join('products', 'order_product.product_id', '=', 'products.id')
		    ->selectRaw('order_product.*,SUM(order_product.quantity) as orderProQty')
		    ->whereDate('order_details.shipdate','=',$for_date)
		    ->where('orders.order_status_id','!=',5)
		    ->where('order_product.quantity','>',0)
		    ->where('products.products_status','=',1)
		    ->orderBy('order_product.product_name', 'asc')
		    ->groupBy('order_product.product_id')		    
		    ->paginate($_RECORDS_PER_PAGE);
        $for_date= date('d-m-Y', strtotime($for_date));						
		return view('admin.reports.order.daily_product_sales_reports', compact('for_date','order_items'));
	}



	/**
     * Display Daily Product Sales Report.
     *
     * @return \Illuminate\Http\Response
     */
	public function printDailyProductsalesReport(Request $request)
	{
		
		$for_date=date('Y-m-d');
		if($request->for_date){
			$for_date= date('Y-m-d', strtotime($request->for_date));
		}
				
		
		 $order_items=OrderProduct::join('order_details', 'order_product.order_id', '=', 'order_details.order_id')
		    ->join('orders', 'order_product.order_id', '=', 'orders.id')
		    ->join('products', 'order_product.product_id', '=', 'products.id')
		    ->selectRaw('order_product.*,SUM(order_product.quantity) as orderProQty')
		    ->whereDate('order_details.shipdate','=',$for_date)
		    ->where('orders.order_status_id','!=',5)
		    ->where('order_product.quantity','>',0)
		    ->where('products.products_status','=',1)
		    ->orderBy('order_product.product_name', 'asc')
		    ->groupBy('order_product.product_id')
		    ->get();
        $for_date= date('d-m-Y', strtotime($for_date));						
		return view('admin.reports.order.print_daily_product_sales_reports', compact('for_date','order_items'));
	}
    
    /**
     * Export Daily Product Sales Report.
     *
     * @return \Illuminate\Http\Response
     */
	public function exportDailyProductsales(Request $request)
	{
        $for_date=date('Y-m-d');
		if($request->for_date){
			$for_date= date('Y-m-d', strtotime($request->for_date));
		}
				
		
		 $order_items=OrderProduct::join('order_details', 'order_product.order_id', '=', 'order_details.order_id')
		    ->join('orders', 'order_product.order_id', '=', 'orders.id')
		    ->join('products', 'order_product.product_id', '=', 'products.id')
		    ->selectRaw('order_product.*,SUM(order_product.quantity) as orderProQty')
		    ->whereDate('order_details.shipdate','=',$for_date)
		    ->where('orders.order_status_id','!=',5)
		    ->where('order_product.quantity','>',0)		    
		    ->where('products.products_status','=',1)
		    ->orderBy('order_product.product_name', 'asc')
		    ->groupBy('order_product.product_id')
		    ->get();
		$export_file_name = "StockRequiredFor_".$for_date;						
		$xl= Excel::create($export_file_name, function($excel) use ($order_items,$export_file_name) {
            $excel->sheet($export_file_name, function($sheet) use ($order_items)
            {
                $sheet->cell('A1', function($cell) {$cell->setValue('Product');   });
                $sheet->cell('B1', function($cell) {$cell->setValue('Quantity');   });
                $sheet->cell('C1', function($cell) {$cell->setValue('Weight');   });
                $sheet->cell('D1', function($cell) {$cell->setValue('Packet Size');   });
				$sheet->cell('E1', function($cell) {$cell->setValue('Sale Price');   });
				$sheet->cell('F1', function($cell) {$cell->setValue('New Price');   });
				$sheet->cell('G1', function($cell) {$cell->setValue('In/Out');   });
                if (count($order_items)>0) {
					$i=1;
                    foreach ($order_items as $products) {
						$i++;
						$pType = '';
						if(!empty($products->type)){
							$pType = '('.$products->type.')';
						}
                        $sheet->cell('A'.$i, ucfirst($products->product_name).' '.$pType); 
                        $sheet->cell('B'.$i, $products->orderProQty); 
                        $sheet->cell('C'.$i, $products->actual_weight.' '.$products->weight_unit);
                        $sheet->cell('D'.$i, $products->packet_size);
						$sheet->cell('E'.$i, config('cart.currency_symbol_2').' '. $products->product_price);
						$sheet->cell('F'.$i, config('cart.currency_symbol_2').' '.$products->final_price);
						$sheet->cell('G'.$i, 'In');
                    }
                }
            });
        });
		return $xl->download('xlsx');
	}

	/**
     * Display Daily Milk Product Sales Report.
     *
     * @return \Illuminate\Http\Response
     */
	public function dailyMilkProductsalesReport(Request $request)
	{
		
		$for_date=date('Y-m-d');
		if($request->for_date){
			$for_date= date('Y-m-d', strtotime($request->for_date));
		}
		$_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
		$milkProducts = Finder::getMilkProducts();
		//dd($milkProducts);
		 $order_items=OrderProduct::join('order_details', 'order_product.order_id', '=', 'order_details.order_id')
		    ->join('orders', 'order_product.order_id', '=', 'orders.id')
		    ->join('products', 'order_product.product_id', '=', 'products.id')
		    ->selectRaw('order_product.*,SUM(order_product.quantity) as orderProQty')
		    ->whereDate('order_details.shipdate','=',$for_date)
		    ->whereIn('order_product.product_id', $milkProducts)
		    ->where('orders.order_status_id','!=',5)
		    ->where('order_product.quantity','>',0)		
		    ->orderBy('order_product.product_name', 'asc')
		    ->groupBy('order_product.product_id')
		    ->paginate($_RECORDS_PER_PAGE);

        $for_date= date('d-m-Y', strtotime($for_date));						
		return view('admin.reports.order.daily_milkproduct_sales_reports', compact('for_date','order_items','milkProducts'));
	}

	/**
     * Print Daily Milk Product Sales Report.
     *
     * @return \Illuminate\Http\Response
     */
	public function printMilkDailyProductsalesReport(Request $request)
	{
		
		$for_date=date('Y-m-d');
		if($request->for_date){
			$for_date= date('Y-m-d', strtotime($request->for_date));
		}
				
		 $milkProducts = Finder::getMilkProducts();
		 $order_items=OrderProduct::join('order_details', 'order_product.order_id', '=', 'order_details.order_id')
		    ->join('orders', 'order_product.order_id', '=', 'orders.id')
		    ->join('products', 'order_product.product_id', '=', 'products.id')
		    ->selectRaw('order_product.*,SUM(order_product.quantity) as orderProQty')
		    ->whereDate('order_details.shipdate','=',$for_date)
		    ->whereIn('order_product.product_id', $milkProducts)
		    ->where('orders.order_status_id','!=',5)
		    ->where('order_product.quantity','>',0)	
		    ->orderBy('order_product.product_name', 'asc')
		    ->groupBy('order_product.product_id')
		    ->get();
        $for_date= date('d-m-Y', strtotime($for_date));						
		return view('admin.reports.order.print_daily_milkproduct_sales_reports', compact('for_date','order_items'));
	}
    
    /**
     * Export Daily Product Sales Report.
     *
     * @return \Illuminate\Http\Response
     */
	public function exportDailyMilkProductsales(Request $request)
	{
        $for_date=date('Y-m-d');
		if($request->for_date){
			$for_date= date('Y-m-d', strtotime($request->for_date));
		}
				
		 $milkProducts = Finder::getMilkProducts();
		 $order_items=OrderProduct::join('order_details', 'order_product.order_id', '=', 'order_details.order_id')
		    ->join('orders', 'order_product.order_id', '=', 'orders.id')
		    ->join('products', 'order_product.product_id', '=', 'products.id')
		    ->selectRaw('order_product.*,SUM(order_product.quantity) as orderProQty')
		    ->whereDate('order_details.shipdate','=',$for_date)
		    ->whereIn('order_product.product_id', $milkProducts)
		    ->where('orders.order_status_id','!=',5)
		    ->where('order_product.quantity','>',0)	
		    ->orderBy('order_product.product_name', 'asc')
		    ->groupBy('order_product.product_id')
		    ->get();
		$export_file_name = "MilkProductsSalesFor_".$for_date;						
		$xl= Excel::create($export_file_name, function($excel) use ($order_items,$export_file_name) {
            $excel->sheet($export_file_name, function($sheet) use ($order_items)
            {
                $sheet->cell('A1', function($cell) {$cell->setValue('Product ID');   });
                $sheet->cell('B1', function($cell) {$cell->setValue('Product');   });
                $sheet->cell('C1', function($cell) {$cell->setValue('Quantity');   });
                $sheet->cell('D1', function($cell) {$cell->setValue('Packet Size');   });
				$sheet->cell('E1', function($cell) {$cell->setValue('Sale Price');   });
				$sheet->cell('F1', function($cell) {$cell->setValue('New Price');   });
				$sheet->cell('G1', function($cell) {$cell->setValue('In/Out');   });
                if (count($order_items)>0) {
					$i=1;
                    foreach ($order_items as $products) {
						$i++;
                        $sheet->cell('A'.$i, ucfirst($products->product_id)); 
                        $sheet->cell('B'.$i, ucfirst($products->product_name)); 
                        $sheet->cell('C'.$i, $products->orderProQty);
                        $sheet->cell('D'.$i, $products->packet_size);
						$sheet->cell('E'.$i, config('cart.currency_symbol_2').' '. $products->product_price);
						$sheet->cell('F'.$i, config('cart.currency_symbol_2').' '.$products->final_price);
						$sheet->cell('G'.$i, 'In');
                    }
                }
            });
        });
		return $xl->download('xlsx');
	}
	
	/**
     * Display Order Difference Report.
     *
     * @return \Illuminate\Http\Response
     */
	public function orderDifference(Request $request)
	{
		
		$from_date=date('Y-m-d');
		if($request->from_date){
			$from_date= date('Y-m-d', strtotime($request->from_date));
		}
		$to_date=date('Y-m-d');
		if($request->to_date){
			$to_date= date('Y-m-d', strtotime($request->to_date));
		}
		$_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
		$order_items=Order::join('order_product', 'orders.id', '=', 'order_product.order_id')
		                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
		                ->select('orders.id','orders.customer_id','orders.payment_method')
						->whereDate('order_details.shipdate','>=',$from_date)
						->whereDate('order_details.shipdate','<=',$to_date)
						->where('order_product.is_available','=',0)
						->where('orders.order_status_id','=',3)
						->orderBy('orders.id','desc')
						->groupBy('orders.id')
						->paginate($_RECORDS_PER_PAGE);
        $from_date= date('d-m-Y', strtotime($from_date));
		$to_date= date('d-m-Y', strtotime($to_date));					
		return view('admin.reports.order.order_differences', compact('from_date','to_date','order_items'));
	}
	
	/**
     * Export Order Difference Report.
     *
     * @return \Illuminate\Http\Response
     */
	public function exportOrderDifference(Request $request)
	{
		
		$from_date=date('Y-m-d');
		if($request->from_date){
			$from_date= date('Y-m-d', strtotime($request->from_date));
		}
		$to_date=date('Y-m-d');
		if($request->to_date){
			$to_date= date('Y-m-d', strtotime($request->to_date));
		}
		$order_items=Order::join('order_product', 'orders.id', '=', 'order_product.order_id')
		                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
		                ->select('orders.id','orders.customer_id','orders.payment_method')
						->whereDate('order_details.shipdate','>=',$from_date)
						->whereDate('order_details.shipdate','<=',$to_date)
						->where('order_product.is_available','=',0)
						->where('orders.order_status_id','=',3)
						->orderBy('orders.id','desc')
						->groupBy('orders.id')
						->get();
        $from_date= date('d-m-Y', strtotime($from_date));
		$to_date= date('d-m-Y', strtotime($to_date));
		$sheetname='orderDeiff'.$from_date.'_'.$to_date;
	      return Excel::create($sheetname, function($excel) use ($order_items, $from_date, $to_date, $sheetname){
				$excel->sheet($sheetname, function($sheet) use ($order_items , $from_date, $to_date){
					$sheet->loadView('admin.reports.order.export_order_differences', compact('from_date','to_date','order_items'));
				});
			})->download('xlsx');
	}
}
