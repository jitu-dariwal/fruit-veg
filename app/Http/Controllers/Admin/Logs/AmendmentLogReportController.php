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
use App\Shop\Addresses\Address;
use App\Shop\Logs\AmendmentLogAdmin;
use App\Mail\SendWeeklyInoviceMail;
use Illuminate\Support\Facades\Mail;
use App\Helper\Finder;
use Carbon\Carbon;
use DB;
use App\Helper\Generalfnv;

class AmendmentLogReportController extends Controller
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
                        
			$orderBy = 'ID desc';
			
			$AmendmentLogAdmins = AmendmentLogAdmin::where(function($where) use ($request){
			
				/* if (!empty($request->fr) && $request->fr) {
					$where->whereDate('created_at', '>=', date('Y-m-d', strtotime($request->fr)));
				}

				if (!empty($request->to) && $request->to) {
					$where->whereDate('created_at', '<=', date('Y-m-d', strtotime($request->to)));
				}
				if (!empty($request->com) && $request->com != '') {
					$search = '%' . $request->com . '%';
					$where->where('CompanyName_search', 'LIKE', $search);
				} */
			  
			  
			})
			->orderByRaw($orderBy)
			->paginate(10);
	 

		return view('admin.logs.amendment_log_admin_list',compact('AmendmentLogAdmins'));

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

    return view('admin.logs.amendment_log_admin_create',compact('companyNames'));
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
    
	//$getIdData = Customer::find($request->CompanyName);
	
	//if(isset($getIdData->id)) { $CompanyName_search = $getIdData->first_name; } else { $CompanyName_search = ''; } 
	
    $AmendmentLogAdmin 						    = new AmendmentLogAdmin;
    $AmendmentLogAdmin->CompanyName 			= $request->CompanyName;
    $AmendmentLogAdmin->CompanyContact 		    = $request->CompanyContact;
    $AmendmentLogAdmin->AdminClerk 		        = $request->AdminClerk;
    $AmendmentLogAdmin->OriginalOrderDate 	    = date("Y-m-d");
	if($request->NewOrderDate)
    $AmendmentLogAdmin->NewOrderDate 			= date("Y-m-d",strtotime($request->NewOrderDate));
	if($request->NewOrderDate2)
    $AmendmentLogAdmin->NewOrderDate2 			= date("Y-m-d",strtotime($request->NewOrderDate2));
	if($request->NewOrderDate3)
    $AmendmentLogAdmin->NewOrderDate3 		    = date("Y-m-d",strtotime($request->NewOrderDate3));
	if($request->NewOrderDate4)
    $AmendmentLogAdmin->NewOrderDate4 			= date("Y-m-d",strtotime($request->NewOrderDate4));
	if($request->NewOrderDate5)
    $AmendmentLogAdmin->NewOrderDate5 			= date("Y-m-d",strtotime($request->NewOrderDate5));
    $AmendmentLogAdmin->Cancellation 			= $request->Cancellation;
    $AmendmentLogAdmin->AmendedOrderDetails     = $request->AmendedOrderDetails;
    $AmendmentLogAdmin->save();

    return redirect()->route('admin.amendmentlogreport.index')->with('success', 'Amendment log has been created successful!');
  }
	
	public function edit($id) {
	
    $AmendmentLogAdmin = AmendmentLogAdmin::find($id);
	
	if($AmendmentLogAdmin->NewOrderDate != "0000-00-00") 
	{
	$AmendmentLogAdmin->NewOrderDate = date('d-m-Y',strtotime($AmendmentLogAdmin->NewOrderDate));	
	} else { $AmendmentLogAdmin->NewOrderDate = '';}
	
	if($AmendmentLogAdmin->NewOrderDate2 != "0000-00-00") 
	{
	$AmendmentLogAdmin->NewOrderDate2 = date('d-m-Y',strtotime($AmendmentLogAdmin->NewOrderDate2));	
	} else { $AmendmentLogAdmin->NewOrderDate2 = '';}
	
	if($AmendmentLogAdmin->NewOrderDate3 != "0000-00-00") 
	{
	$AmendmentLogAdmin->NewOrderDate3 = date('d-m-Y',strtotime($AmendmentLogAdmin->NewOrderDate3));	
	} else { $AmendmentLogAdmin->NewOrderDate3 = '';}
	
	if($AmendmentLogAdmin->NewOrderDate4 != "0000-00-00") 
	{
	$AmendmentLogAdmin->NewOrderDate4 = date('d-m-Y',strtotime($AmendmentLogAdmin->NewOrderDate4));	
	} else { $AmendmentLogAdmin->NewOrderDate4 = '';}
	
	if($AmendmentLogAdmin->NewOrderDate5 != "0000-00-00") 
	{
	$AmendmentLogAdmin->NewOrderDate5 = date('d-m-Y',strtotime($AmendmentLogAdmin->NewOrderDate5));	
	} else { $AmendmentLogAdmin->NewOrderDate5 = '';}
	
	$companyNames = Customer::leftJoin('addresses as ab', function($join){
		//$join->on('customers.id','=','ab.customers_id');
		$join->on('customers.default_address_id','=','ab.id');
	})
	->selectRaw('customers.id as customers_id, ab.company_name')
	->orderBy('ab.company_name','desc')
	->get();  

	
    return view('admin.logs.amendment_log_admin_edit', compact('AmendmentLogAdmin','companyNames'));
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
    
	//$getIdData = Customer::find($request->CompanyName);
	
	//if(isset($getIdData->id)) { $CompanyName_search = $getIdData->first_name; } else { $CompanyName_search = ''; } 
	
    $AmendmentLogAdmin 						    = AmendmentLogAdmin::find($id);
    $AmendmentLogAdmin->CompanyName 			= $request->CompanyName;
    $AmendmentLogAdmin->CompanyContact 		    = $request->CompanyContact;
    $AmendmentLogAdmin->AdminClerk 		        = $request->AdminClerk;
    $AmendmentLogAdmin->OriginalOrderDate 	    = date("Y-m-d");
	if($request->NewOrderDate)
    $AmendmentLogAdmin->NewOrderDate 			= date("Y-m-d",strtotime($request->NewOrderDate));
	if($request->NewOrderDate2)
    $AmendmentLogAdmin->NewOrderDate2 			= date("Y-m-d",strtotime($request->NewOrderDate2));
	if($request->NewOrderDate3)
    $AmendmentLogAdmin->NewOrderDate3 		    = date("Y-m-d",strtotime($request->NewOrderDate3));
	if($request->NewOrderDate4)
    $AmendmentLogAdmin->NewOrderDate4 			= date("Y-m-d",strtotime($request->NewOrderDate4));
	if($request->NewOrderDate5)
    $AmendmentLogAdmin->NewOrderDate5 			= date("Y-m-d",strtotime($request->NewOrderDate5));
    $AmendmentLogAdmin->Cancellation 			= $request->Cancellation;
    $AmendmentLogAdmin->AmendedOrderDetails     = $request->AmendedOrderDetails;
    $AmendmentLogAdmin->save();

    return redirect()->route('admin.amendmentlogreport.index')->with('success', 'Amendment log has been created successful!');
  }
	
	public function show($id) {
	
    $AmendmentLogAdmin = AmendmentLogAdmin::find($id);
	$companyNames = Customer::leftJoin('addresses as ab', function($join){
		//$join->on('customers.id','=','ab.customers_id');
		$join->on('customers.default_address_id','=','ab.id');
	})
	->selectRaw('customers.id as customers_id, ab.company_name')
	->orderBy('ab.company_name','desc')
	->get();  

	
    return view('admin.logs.amendment_log_admin_view', compact('AmendmentLogAdmin','companyNames'));
	}
	
	public function destroy($id) {
		
		$AmendmentLogAdmin = AmendmentLogAdmin::find($id);
		$AmendmentLogAdmin->delete(); 
		
		//DB::query("DELETE FROM `amendment_log_admins` WHERE `amendment_log_admins`.`ID` = $id");
		
		return redirect()->route('admin.amendmentlogreport.index')->with('success', 'Amendment log has been deleted successful!');
	}
	
	public function printLog(Request $request) {
		
		if(!empty($request->printdate))
		{
		 
		
		$AmendmentLogAdmins = AmendmentLogAdmin::where(function($where) use ($request){
			
				if (!empty($request->printdate) && $request->printdate != '') {
					$search = date('Y-m-d',strtotime($request->printdate));
					$where->orWhere('NewOrderDate', '=', $search);
					$where->orWhere('NewOrderDate2', '=', $search);
					$where->orWhere('NewOrderDate3', '=', $search);
					$where->orWhere('NewOrderDate4', '=', $search);
					$where->orWhere('NewOrderDate5', '=', $search);
				}
			  
			  
			})
			->orderByRaw('id','desc')
			->get();
			
		return view('admin.logs.amendment_log_admin_print',compact('AmendmentLogAdmins'));
		}
		else
		{
		return redirect()->route('admin.amendmentlogreport.index')->with('error', 'Amendmentlog log date can not be null!');	
		}
	}
	
}
