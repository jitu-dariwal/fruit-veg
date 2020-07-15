<?php

namespace App\Http\Controllers\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop\Orders\Order;
use App\Shop\DriverRounds\DriverRound;
use App\Shop\OrderDetails\OrderDetail;
use App\Shop\OrderProducts\OrderProduct;
use App\Shop\Products\Product;
use App\Shop\Invoices\Invoice;
use App\Shop\Customers\Customer;
use App\Shop\OrderStatuses\OrderStatus;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Helper\Finder;
use DB;

class DeliveryReportController extends Controller
{
	
	/**
     * Display Delivery Report Summary
     *
     * @return \Illuminate\Http\Response
     */
	 public function newDeliveryReport(Request $request)
	 {
	 	  $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
          $delivery_date = date('Y-m-d');		 
	      if($request->delivery_date){
			$delivery_date= $request->delivery_date;
		  }
		  $delivery_date = date('Y-m-d', strtotime($delivery_date));
	      $orders=Order::join('order_details','orders.id','=','order_details.order_id')
		                    ->select('orders.*','order_details.shipping_add_name','order_details.shipping_add_company','order_details.shipping_street_address','order_details.shipping_address_line2','order_details.shipping_city','order_details.shipping_state','order_details.shipping_post_code','order_details.shipping_country','order_details.shipping_tel_num','order_details.earliest_delivery','order_details.delivery_procedure')
		                    ->whereDate('order_details.shipdate','=',$delivery_date)
							->where('orders.order_status_id','!=',5)
		                    ->orderBy('orders.id','desc')
							->paginate($_RECORDS_PER_PAGE);
		  $delivery_date = date('d-m-Y', strtotime($delivery_date));
	     return view('admin.reports.delivery.new_delivery',compact('delivery_date','orders'));
	 }
	 
	 /**
     * Assign Drivers To Orders For Delivery
     *
     * @return \Illuminate\Http\Response
     */
	 public function deliveryReportSummary(Request $request)
	 {
	 	  $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
          $delivery_date = date('Y-m-d');		 
	      if($request->delivery_date){
			$delivery_date= $request->delivery_date;
		  }
		  $delivery_date = date('Y-m-d', strtotime($delivery_date));
		  $driver_rounds = $this->allocateDriverToRounds($delivery_date);
	      $orders=Order::join('order_details','orders.id','=','order_details.order_id')
		                    ->select('orders.*','order_details.shipping_add_name','order_details.shipping_add_company','order_details.shipping_street_address','order_details.shipping_address_line2','order_details.shipping_city','order_details.shipping_state','order_details.shipping_post_code','order_details.shipping_country','order_details.shipping_tel_num','order_details.earliest_delivery','order_details.delivery_procedure')
		                    ->whereDate('order_details.shipdate','like', $delivery_date. '%')
		                    ->where('orders.order_status_id','!=',5)
		                    ->orderBy('order_details.shipping_add_name','asc')
							->paginate($_RECORDS_PER_PAGE);
		  $delivery_date = date('d-m-Y', strtotime($delivery_date));
	     return view('admin.reports.delivery.delivery_report_summary',compact('delivery_date','orders','driver_rounds'));
	 }
	 
	 
	 
	/**
     * Assign Drivers To Orders For Delivery
     *
     * @return \Illuminate\Http\Response
     */
	public function assignDriverToOrder(Request $request)
	{
		$orders=$request->order;
		if(count($orders)<=0)
		{
			return back()->with('error','Please Select Order Which You want to allocat driver.');
		}
		foreach($orders as $key=>$val)
		{
			$driver = request('driver_'.$val);
			Order::where('id',$val)->update([
			     'driver' => $driver,
			]);
		}
		return back()->with('message','Driver Round allocated successfully.');
	}
	
	/**
     * Show Form To set Driver Name
     *
     * @return \Illuminate\Http\Response
     */
	public function getsetDriver(Request $request)
	{
		$delivery_date = date('Y-m-d');		 
	      if($request->delivery_date){
			$delivery_date= $request->delivery_date;
		  }
		  $driver_rounds = $this->allocateDriverToRounds($delivery_date);
		  $delivery_date = date('Y-m-d', strtotime($delivery_date));
		  $delivery_date		  = date('d-m-Y', strtotime($delivery_date));
		  return view('admin.reports.delivery.set_driver_for_rounds',compact('delivery_date','driver_rounds'));
	}
	
	/**
     * Set Driver Name
     *
     * @return \Illuminate\Http\Response
     */
	public function setDriverName(Request $request)
	{
		$delivery_date= $request->delivery_date;
		$rounds= $request->round_name;
		$drivers= $request->driver_name;
		$delivery_date = date('Y-m-d', strtotime($delivery_date));
		$checkDeliveryNames = DriverRound::whereDate('round_date',$delivery_date)->first();
		if($checkDeliveryNames && !empty($checkDeliveryNames)){
			DriverRound::whereDate('round_date',$delivery_date)->delete();
		}
		foreach($rounds as $key=>$val){
		DriverRound::create([
		       'round_date' => $delivery_date,
		       'round_name'    => $val,
			   'driver_name'    => $drivers[$key],
		]);
		}
		    return back()->with('message','Drivers name set successfully.');
	}
	
	/**
     * Set Driver Name
     *
     * @return \Illuminate\Http\Response
     */
	 public function allocateDriverToRounds($date)
	 {
		 $delivery_date = date('Y-m-d', strtotime($date));
		 $driver_rounds=DriverRound::whereDate('round_date',$delivery_date)->count();
		  if($driver_rounds<=0){
			  $getOldData = DriverRound::select('round_date')->latest()->first();
			  if(!empty($getOldData)){
			  $oldDate=$getOldData->round_date;
			  $rounds = DriverRound::select('id')->whereDate('round_date',$oldDate)->get();
			  foreach($rounds as $round){
				  $create = DriverRound::find($round->id)->replicate();
				  $create->round_date = $delivery_date;
				  $create->save();
			  }
			  }
		  }
		  return DriverRound::whereDate('round_date',$delivery_date)->get();
		  
	 }
	 
	 /**
     * Show Free delivery invoice
     *
     * @return \Illuminate\Http\Response
     */
	 public function freeDeliveryInvoice(Request $request,$driver=null)
	 {
		 		 
	      if($request->delivery_date){
			$delivery_date= $request->delivery_date;
		  }else{
			  return back()->with('error','Sorry! Something is going worng.');
		  }
		  $delivery_date = date('Y-m-d', strtotime($delivery_date));
	      $orders=Order::join('order_details','orders.id','=','order_details.order_id')
		                    ->select('orders.*','order_details.shipping_add_name','order_details.shipping_add_company','order_details.shipping_street_address','order_details.shipping_address_line2','order_details.shipping_city','order_details.shipping_state','order_details.shipping_post_code','order_details.shipping_country','order_details.shipping_tel_num','order_details.shipdate')
							->where('orders.order_status_id','!=',5)
							->whereDate('order_details.shipdate','=',$delivery_date);
		          if(!empty($driver)){
				  $orders = $orders->where('orders.driver','=',$driver);
				  }
		          $orders = $orders->orderBy('orders.id','desc')
							->get();
		  $delivery_date = date('d-m-Y', strtotime($delivery_date));
		 return view('admin.reports.delivery.free_delivery_invoices',compact('orders','delivery_date'));
		  
	 }
	 
	 /**
     * Show Free Packing Slip
     *
     * @return \Illuminate\Http\Response
     */
	 public function freeDeliveryPackingslip(Request $request,$driver=null)
	 {
		 		 
	      if($request->delivery_date){
			$delivery_date= $request->delivery_date;
		  }else{
			  return back()->with('error','Sorry! Something is going worng.');
		  }
		  $delivery_date = date('Y-m-d', strtotime($delivery_date));
	      $orders=Order::join('order_details','orders.id','=','order_details.order_id')
		                    ->select('orders.*','order_details.shipping_add_name','order_details.shipping_add_company','order_details.shipping_street_address','order_details.shipping_address_line2','order_details.shipping_city','order_details.shipping_state','order_details.shipping_post_code','order_details.shipping_country','order_details.shipping_tel_num','order_details.shipdate','order_details.earliest_delivery')
							->where('orders.order_status_id','!=',5)
							->whereDate('order_details.shipdate','=',$delivery_date);
		          if(!empty($driver)){
				  $orders = $orders->where('orders.driver','=',$driver);
				  }
		          $orders = $orders->orderBy('orders.id','desc')
							->get();
		  $delivery_date = date('d-m-Y', strtotime($delivery_date));
		 return view('admin.reports.delivery.free_delivery_packing_slip',compact('orders','delivery_date'));
		  
	 }
	 
	 /**
     * Show Driver Rounds
     *
     * @return \Illuminate\Http\Response
     */
	 public function driverRoundReport($driver,Request $request)
	 {
		 if($request->delivery_date){
			$delivery_date= $request->delivery_date;
		  }else{
			  return back()->with('error','Sorry! Something is going worng.');
		  }
		  $delivery_date = date('Y-m-d', strtotime($delivery_date));
	      $orders=Order::join('order_details','orders.id','=','order_details.order_id')
		                    ->select('orders.*','order_details.shipping_add_name','order_details.shipping_add_company','order_details.shipping_street_address','order_details.shipping_address_line2','order_details.shipping_city','order_details.shipping_state','order_details.shipping_post_code','order_details.shipping_country','order_details.shipping_tel_num','order_details.shipdate','order_details.earliest_delivery','order_details.delivery_procedure')
							->where('orders.order_status_id','!=',5)
							->whereDate('order_details.shipdate','=',$delivery_date)
				            ->where('orders.driver','=',$driver)
							->orderBy('orders.id','desc')
							->get();
		  $driver_info = DriverRound::where('round_name',$driver)->whereDate('round_date',$delivery_date)->firstOrFail();
		  $delivery_date = date('d-m-Y', strtotime($delivery_date));
		 return view('admin.reports.delivery.driver_rounds_report',compact('orders','driver','delivery_date','driver_info'));
	 }

     /**
     * Total Driver Rounds Report
     *
     * @return \Illuminate\Http\Response
     */
	 public function totaldriverRoundsReport(Request $request)
	 {
         $month_no      = date("m");
	     $year         = date("Y");
	     if($request->month){
			$month_no= $request->month;
		 }
		 if($request->year){
			$year= $request->year;
		 }
		 $driverRounds = DriverRound::whereMonth('round_date',$month_no)->whereYear('round_date',$year)->groupBy('round_date')->get();
		 return view('admin.reports.delivery.total_drivers_rounds',compact('driverRounds', 'month_no', 'year'));
	 }
	 
	 /**
     * Export Total Driver Rounds Report
     *
     * @return \Illuminate\Http\Response
     */
	 public function exporttotaldriverRoundsReport(Request $request)
	 {
           $month_no      = date("M");
	     $year         = date("Y");
	     if($request->month){
			$month_no= $request->month;
		 }
		 if($request->year){
			$year= $request->year;
		 }
		 $driverRounds = DriverRound::whereMonth('round_date',$month_no)->whereYear('round_date',$year)->groupBy('round_date')->get();
		// return view('admin.reports.delivery.total_drivers_rounds_xl',compact('driverRounds', 'month_no', 'year'));
		 $sheetname = 'TotalRounds_'.$year.'_'.$month_no;
		 return Excel::create($sheetname, function($excel) use ($driverRounds, $month_no, $year , $sheetname){
				$excel->sheet($sheetname, function($sheet) use ($driverRounds, $month_no, $year){
					$sheet->loadView('admin.reports.delivery.total_drivers_rounds_xl',compact('driverRounds', 'month_no', 'year'));
				});
			})->download('xlsx');
	 }

	 /**
     * Delivery Report Summary Xls
     *
     * @return \Illuminate\Http\Response
     */
	 public function deliveryReportSummaryExport(Request $request, $driver=null)
	 {
		  $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
          $delivery_date = date('Y-m-d');		
          $driver_info = '';	 	  
	      if($request->delivery_date){
			$delivery_date= $request->delivery_date;
		  }
		  $delivery_date = date('Y-m-d', strtotime($delivery_date));
		  if(!empty($driver)){
			  $driver_info = DriverRound::where('round_name',$driver)->whereDate('round_date',$delivery_date)->firstOrFail();
		  }
		  
		  $driver_rounds = $this->allocateDriverToRounds($delivery_date);
	      $orders=Order::join('order_details','orders.id','=','order_details.order_id')
		                    ->select('orders.*','order_details.shipping_add_name','order_details.shipping_add_company','order_details.shipping_street_address','order_details.shipping_address_line2','order_details.shipping_city','order_details.shipping_state','order_details.shipping_post_code','order_details.shipping_country','order_details.shipping_tel_num','order_details.earliest_delivery','order_details.delivery_procedure','order_details.tel_num','order_details.shipping_tel_num')
		                    ->whereDate('order_details.shipdate','=',$delivery_date)
		                    ->where('orders.order_status_id','!=',5);
							if(!empty($driver)){
								$orders = $orders->where('orders.driver','=',$driver);
							}
		                    $orders = $orders->orderBy('orders.id','desc')
							->paginate($_RECORDS_PER_PAGE);
		  $delivery_date = date('d-m-Y', strtotime($delivery_date));
		  $sheetname='deliverylist'.$delivery_date;
		  if(!empty($driver)){
		  $sheetname='deliverylistRound_'.$driver.$delivery_date;
		  }
		  return Excel::create($sheetname, function($excel) use ($orders, $delivery_date, $driver_info , $sheetname){
				$excel->sheet($sheetname, function($sheet) use ($orders, $delivery_date, $driver_info){
					$sheet->loadView('admin.reports.delivery.delivery_report_summary_xl', compact('delivery_date','orders', 'driver_info'));
				});
			})->download('xlsx');
	 }
	 
	 /**
     * Delivery Report Summary Xls
     *
     * @return \Illuminate\Http\Response
     */
	 public function deliveryPOIAddressExport(Request $request, $driver=null)
	 {
		  $_RECORDS_PER_PAGE = config('constants.RECORDS_PER_PAGE');
          $delivery_date = date('Y-m-d');		
          $driver_info = '';	 	  
	      if($request->delivery_date){
			$delivery_date= $request->delivery_date;
		  }
		  $delivery_date = date('Y-m-d', strtotime($delivery_date));
		  if(!empty($driver)){
			  $driver_info = DriverRound::where('round_name',$driver)->whereDate('round_date',$delivery_date)->firstOrFail();
		  }
		  
		  $driver_rounds = $this->allocateDriverToRounds($delivery_date);
	      $orders=Order::join('order_details','orders.id','=','order_details.order_id')
		                    ->select('orders.*','order_details.shipping_add_name','order_details.shipping_add_company','order_details.shipping_street_address','order_details.shipping_address_line2','order_details.shipping_city','order_details.shipping_state','order_details.shipping_post_code','order_details.shipping_country','order_details.shipping_tel_num','order_details.earliest_delivery','order_details.delivery_procedure','order_details.tel_num','order_details.shipping_tel_num')
		                    ->whereDate('order_details.shipdate','=',$delivery_date)
		                    ->where('orders.order_status_id','!=',5);
							if(!empty($driver)){
								$orders = $orders->where('orders.driver','=',$driver);
							} 
		                    $orders = $orders->orderBy('orders.id','desc')
							->paginate($_RECORDS_PER_PAGE);
		  $delivery_date = date('d-m-Y', strtotime($delivery_date));
		  //return view('admin.reports.delivery.delivery_report_summay_xl', compact('delivery_date','orders'));
		  $sheetname='deliveryPOIAddress'.$delivery_date;
		  if(!empty($driver)){
		  $sheetname='deliveryPOIRound_'.$driver.$delivery_date;
		  }
		  return Excel::create($sheetname, function($excel) use ($orders, $delivery_date, $driver_info, $sheetname){
				$excel->sheet($sheetname, function($sheet) use ($orders, $delivery_date, $driver_info){
					$sheet->loadView('admin.reports.delivery.delivery_report_summary_fleetmatrics', compact('delivery_date','orders', 'driver_info'));
				});
			})->download('xlsx');
	 }


	 /**
     * Export Delivery Report Summary
     *
     * @return \Illuminate\Http\Response
     */
	 public function exportNewDeliveryReport(Request $request)
	 {
          $delivery_date = date('Y-m-d');		 
	      if($request->delivery_date){
			$delivery_date= $request->delivery_date;
		  }
		  $delivery_date = date('Y-m-d', strtotime($delivery_date));
	      $orders=Order::join('order_details','orders.id','=','order_details.order_id')
		                    ->select('orders.*','order_details.shipping_add_name','order_details.shipping_add_company','order_details.shipping_street_address','order_details.shipping_address_line2','order_details.shipping_city','order_details.shipping_state','order_details.shipping_post_code','order_details.shipping_country','order_details.shipping_tel_num','order_details.earliest_delivery','order_details.delivery_procedure')
		                    ->whereDate('order_details.shipdate','=',$delivery_date)
							->where('orders.order_status_id','!=',5)
		                    ->orderBy('orders.id','desc')
							->get();
		  $sheetname='deliveryReportFor'.$request->delivery_date;
	      return Excel::create($sheetname, function($excel) use ($orders, $delivery_date, $sheetname){
				$excel->sheet($sheetname, function($sheet) use ($orders, $delivery_date){
					$sheet->loadView('admin.reports.delivery.new_delivery_xl', compact('delivery_date','orders'));
				});
			})->download('xlsx');
	 }
}
