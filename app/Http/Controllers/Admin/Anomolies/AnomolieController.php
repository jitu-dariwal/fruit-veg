<?php

namespace App\Http\Controllers\Admin\Anomolies;

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
use App\Shop\Anomolies\Anomolie;
use App\Shop\Logs\IssuelogAdmin;
use App\Shop\Addresses\Address;
use App\Mail\SendWeeklyInoviceMail;
use Illuminate\Support\Facades\Mail;
use App\Helper\Finder;
use Carbon\Carbon;
use Auth;

class AnomolieController extends Controller
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
			
			if(!empty($request->fr))
			{
			$searchDate = 	$request->fr;
			}
			
			$Anomolies = Anomolie::where(function($where) use ($searchDate){
			 
				if (!empty($searchDate)) {
					$where->where('anomolies_date', '=', date('Y-m-d', strtotime($searchDate)));
				}
			  
			  
			})
			->orderByRaw($orderBy)
			->paginate($_RECORDS_PER_PAGE);
	 

		return view('admin.anomolies.index',compact('Anomolies','searchDate'));

		}


	public function create() {
	
    return view('admin.anomolies.create');
	}	


		/**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    
    
    $request->validate([
          'anomolies_date'      => 'required',
          'anomolies_points'      => 'required',
        ], 
        [
          'anomolies_date.required' => 'The anomolies date field is required.',
          'anomolies_points.required' => 'The anomolies point field is required.',
         
        ]);
  
	
    $Anomolie 						     = new Anomolie;
    $Anomolie->anomolies_points_reply 	 = $request->anomolies_points_reply;
    $Anomolie->anomolies_points 		 = $request->anomolies_points;
    $Anomolie->anomolies_date 			 = date("Y-m-d",strtotime($request->anomolies_date));
    $Anomolie->save();

    return redirect()->route('admin.anomolies.index')->with('success', 'Anomolie has been created successful!');
  }
	
	public function edit($id) {
	$Anomolie = Anomolie::find($id);
   
	
    return view('admin.anomolies.edit', compact('Anomolie'));
	}
	
		/**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update($id, Request $request) {
    
    
    $request->validate([
          'anomolies_date'      => 'required',
          'anomolies_points'    => 'required',
        ], 
        [
          'anomolies_date.required' => 'The anomolies date field is required.',
          'anomolies_points.required' => 'The anomolies point field is required.',
         
        ]);
  
	
    $Anomolie 						     = Anomolie::find($id);
    $Anomolie->anomolies_points_reply 	 = $request->anomolies_points_reply;
    $Anomolie->anomolies_points 		 = $request->anomolies_points;
    $Anomolie->anomolies_date 			 = date("Y-m-d",strtotime($request->anomolies_date));
    $Anomolie->save();

    return redirect()->route('admin.anomolies.index')->with('success', 'Anomolies has been created successful!');
  }
	
	/* public function show($id) {
	
    $issuelogAdmin = IssuelogAdmin::find($id);
	$companyNames = Customer::leftJoin('addresses as ab', function($join){
		//$join->on('customers.id','=','ab.customers_id');
		$join->on('customers.default_address_id','=','ab.id');
	})
	->selectRaw('customers.id as customers_id, ab.company_name')
	->orderBy('ab.company_name','desc')
	->get();

	
    return view('admin.anomolies.issuelog_admin_view', compact('issuelogAdmin','companyNames'));
	}
	
	public function destroy($id) {
		$issuelogAdmin = IssuelogAdmin::find($id);
		$issuelogAdmin->delete(); 
		return redirect()->route('admin.issuelog.index')->with('success', 'Issue log has been deleted successful!');
	} */
	
	public function printLog(Request $request) {
		if(!empty($request->printdate))
		{
			
		    $orderBy    =  'id desc';
			$searchDate =  $request->printdate;
			 
			
			$Anomolies = Anomolie::where(function($where) use ($searchDate){
			 
				if (!empty($searchDate)) {
					$where->orWhere('anomolies_date', '=', date('Y-m-d', strtotime($searchDate)));
					$where->orWhereDate('created_at', '=', date('Y-m-d', strtotime($searchDate)));
				}
			  
			  
			})
			->orderByRaw($orderBy)
			->get();
			
		
		return view('admin.anomolies.print',compact('Anomolies'));
		}
		else
		{
		return redirect()->route('admin.anomolies.index')->with('error', 'Date can not be null!');	
		}
	}
	
}
