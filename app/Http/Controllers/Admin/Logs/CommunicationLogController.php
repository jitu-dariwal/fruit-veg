<?php

namespace App\Http\Controllers\Admin\Logs;

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
use App\Shop\Logs\IssuelogAdmin;
use App\Shop\Logs\CommunicationLogAdmin;
use App\Shop\Addresses\Address;
use App\Shop\Logs\AmendmentLogAdmin;
use App\Mail\SendWeeklyInoviceMail;
use Illuminate\Support\Facades\Mail;
use App\Helper\Finder;
use Carbon\Carbon;
use DB;
use App\Helper\Generalfnv;

class CommunicationLogController extends Controller
{
	

    /**
     * BrandController constructor.
     *
     * @list admin issue log
     */

		public function index(Request $request)
		{
                    /*
                    * check permission
                    */ 
                        $is_allow = Generalfnv::check_permission('view-logs');

                        if(isset($is_allow) && $is_allow == 0) {

                            return view('admin.permissions.permission_denied');
                            exit;
                        }
                    // end permission
                        
			$orderBy = 'id desc';
			
			$CommunicationLogAdmins = CommunicationLogAdmin::where(function($where) use ($request){
			
				
				if (!empty($request->com) && $request->com != '') {
					$search = '%' . $request->com . '%';
					$where->where('CompanyName_search', 'LIKE', $search);
				} 
			  
			  
			})
			->orderByRaw($orderBy)
			->paginate(10);
	 

		return view('admin.logs.communication_log_list',compact('CommunicationLogAdmins'));

		}


	public function create() {
		 
	
	$companyNames = Customer::leftJoin('addresses as ab', function($join){
		//$join->on('customers.id','=','ab.customers_id');
		$join->on('customers.default_address_id','=','ab.id');
	})
	->selectRaw('customers.id as customers_id, ab.company_name')
	->orderBy('ab.company_name','desc')
	->get(); 
	
 //echo "CK<pre>"; print_r($companyName); echo "</pre>CK"; exit;

    return view('admin.logs.communication_log_create',compact('companyNames'));
	}	


		/**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    
    
    $request->validate([
          'CompanyName'      => 'required',
        ], 
        [
          'CompanyName.required' => 'The Company Name field is required.',
        ]);
    
	$getIdData = Customer::find($request->CompanyName)->defaultaddress;
	

	if(isset($getIdData->id)) { $CompanyName_search = $getIdData->company_name; } else { $CompanyName_search = ''; }  
	
    $CommunicationLogAdmin 						    = new CommunicationLogAdmin;
    $CommunicationLogAdmin->CompanyName 			= $request->CompanyName;
    $CommunicationLogAdmin->CompanyContact 		    = $request->CompanyContact;
    $CommunicationLogAdmin->AdminClerk 		        = $request->AdminClerk;
    $CommunicationLogAdmin->CompanyName_search      = $CompanyName_search;
    $CommunicationLogAdmin->AmendedOrderDetails     = $request->AmendedOrderDetails;
    $CommunicationLogAdmin->save();

    return redirect()->route('admin.communicationlog.index')->with('success', 'Communication log has been created successful!');
  }
	
	public function edit($id) {
	
    $CommunicationLogAdmin = CommunicationLogAdmin::find($id);
	
	
	
	$companyNames = Customer::leftJoin('addresses as ab', function($join){
		//$join->on('customers.id','=','ab.customers_id');
		$join->on('customers.default_address_id','=','ab.id');
	})
	->selectRaw('customers.id as customers_id, ab.company_name')
	->orderBy('ab.company_name','desc')
	->get(); 
 
	
    return view('admin.logs.communication_log_edit', compact('CommunicationLogAdmin','companyNames'));
	}
	
		/**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update($id, Request $request) {
   
    $request->validate([
          'CompanyName'      => 'required',
        ], 
        [
          'CompanyName.required' => 'The Company Name field is required.',
        ]);
    
	
   $getIdData = Customer::find($request->CompanyName)->defaultaddress;
	

	if(isset($getIdData->id)) { $CompanyName_search = $getIdData->company_name; } else { $CompanyName_search = ''; }  
	
    $CommunicationLogAdmin 						    = CommunicationLogAdmin::find($id);
    $CommunicationLogAdmin->CompanyName 			= $request->CompanyName;
    $CommunicationLogAdmin->CompanyContact 		    = $request->CompanyContact;
    $CommunicationLogAdmin->AdminClerk 		        = $request->AdminClerk;
    $CommunicationLogAdmin->CompanyName_search      = $CompanyName_search;
    $CommunicationLogAdmin->AmendedOrderDetails     = $request->AmendedOrderDetails;
    $CommunicationLogAdmin->save();

    return redirect()->route('admin.communicationlog.index')->with('success', 'Communication log has been created successful!');
  }
	
	public function show($id) {
	
    $CommunicationLogAdmin = CommunicationLogAdmin::find($id);
	$companyNames = Customer::leftJoin('addresses as ab', function($join){
		//$join->on('customers.id','=','ab.customers_id');
		$join->on('customers.default_address_id','=','ab.id');
	})
	->selectRaw('customers.id as customers_id, ab.company_name')
	->orderBy('ab.company_name','desc')
	->get();

	
    return view('admin.logs.communication_log_view', compact('CommunicationLogAdmin','companyNames'));
	}
	
	public function destroy($id) {
		
		$CommunicationLogAdmin = CommunicationLogAdmin::find($id);
		$CommunicationLogAdmin->delete(); 
		
		//DB::query("DELETE FROM `amendment_log_admins` WHERE `amendment_log_admins`.`ID` = $id");
		
		return redirect()->route('admin.communicationlog.index')->with('success', 'Communication log has been deleted successful!');
	}
	
	
		public function printLog(Request $request) {
		
		if(!empty($request->printdate))
		{
			
		$CommunicationLogAdmins = CommunicationLogAdmin::whereDate('created_at', '=', date('Y-m-d',strtotime($request->printdate)))->orderByRaw('id','desc')
			->get();
		return view('admin.logs.communication_print',compact('CommunicationLogAdmins'));
		}
		else
		{
		return redirect()->route('admin.communicationlog.index')->with('error', 'Communication  log date can not be null!');	
		}
	}
	
}
