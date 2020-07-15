<?php

namespace App\Http\Controllers\Admin\Lead;

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
use App\Shop\Employees\Employee;
use App\Shop\Customers\Customer;
use App\Shop\SalesLeads\SalesLead;
use App\Shop\Logs\IssuelogAdmin;
use App\Shop\Addresses\Address;
use App\Shop\SetQueryOnetimes\SetQueryOnetime;
use App\Shop\CustomerNotesAgainstLeadReports\CustomerNotesAgainstLeadReport;
use App\Mail\SendWeeklyInoviceMail;
use Illuminate\Support\Facades\Mail;
use App\Helper\Finder;
use Carbon\Carbon;
use Auth;
use DB;
use App\Helper\Generalfnv;

class LeadController extends Controller
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
                        $is_allow = Generalfnv::check_permission('view-leads');

                        if(isset($is_allow) && $is_allow == 0) {

                            return view('admin.permissions.permission_denied');
                            exit;
                        }
                    // end permission
                        
			$orderBy = 'id desc';
			$date=date("Y-m-d");
			
			
			$SetQueryOnetime = SetQueryOnetime::where('createdate',$date)->first();
			
			if(isset($SetQueryOnetime->id) && $SetQueryOnetime->value > 0)
			{
			$SetQueryOnetime->value = $SetQueryOnetime->value+1;
			$SetQueryOnetime->save();
			
			}
			else
			{
				$SetQueryOnetime2              = new SetQueryOnetime;
				$SetQueryOnetime2->value       = 1;
				$SetQueryOnetime2->createdate  = $date;
				$SetQueryOnetime2->save();
				$SetQueryOnetime = $SetQueryOnetime2;
				
				$this->query_customers_14day();
				$this->query_customers_SignUp_NoOrder();
			}
			

			
			$employees = Employee::selectRaw('id,first_name,last_name')->get();
			
			$salesLeads = SalesLead::where(function($where) use ($request){
				
				if (!empty($request->status) && $request->status) {
					$where->where('status', '=', $request->status);
				}
				else
				{
					$where->where('status', '!=', 6);
				}
				
				if (!empty($request->lead) && $request->lead) {
					$where->where('SalesClerk_ID', '=', $request->lead);
				}
				
				if (!empty($request->com) && $request->com != '') {
					$search = '%' . $request->com . '%';
					$where->where('ClientName', 'LIKE', $search);
					$where->orWhere('Company', 'LIKE', $search);
				} 
			  
			  
			})
			->orderByRaw($orderBy)
			->paginate(50);
	 
			$employees = Employee::selectRaw('id,first_name,last_name')->get();
			//echo "CK<pre>"; print_r($employees); echo "</pre>CK"; exit;

		return view('admin.lead.lead_list',compact('salesLeads','employees'));

		}

	public function query_customers_14day()
	{
		
		$datas = DB::select("SELECT 
				c.id as customer_id,
				c.first_name,
				c.last_name,
				c.email,
				c.tel_num,
				ab.company_name as entry_company,
				ab.company_name as entry_street_address,
				ab.address_line_2 as entry_suburb,
				ab.post_code as entry_postcode,
				ab.city as entry_city,
				ab.county_state as entry_state 
				FROM `customers` as c , addresses as ab
				WHERE c.id
					IN (
					
					SELECT DISTINCT o.customer_id
					FROM orders as o
					WHERE o.created_at < DATE_SUB( curdate( ) , INTERVAL 14 DAY) 
				
				AND o.customer_id 
					NOT IN (
									
					SELECT DISTINCT os.`customer_id` 
					FROM orders as os WHERE os.created_at > DATE_SUB( curdate( ) , INTERVAL 14 DAY) 
					)
				) AND c.id=ab.customer_id and c.default_address_id=ab.id");
			
				foreach($datas as $data)
				{
				if(isset($data->customer_id) && SalesLead::where('customers_id',$data->customer_id)->count() == 0 && $data->entry_company != '' ){
				$SalesLeads 				= new SalesLead;
				$SalesLeads->customers_id  	= $data->customer_id;
				$SalesLeads->SalesClerk    	= 'ONLINE';
				$SalesLeads->ClientName 	= $data->first_name.' '.$data->last_name;
				$SalesLeads->Company 		= $data->entry_company;
				$SalesLeads->Tel_1 			= $data->tel_num;
				$SalesLeads->Tel_2 			= $data->tel_num;
				$SalesLeads->eMail 			= $data->email;
				$SalesLeads->Address1 		= $data->entry_street_address;
				$SalesLeads->Address2 		= $data->entry_suburb;
				$SalesLeads->Town 			= $data->entry_city;
				$SalesLeads->County 		= $data->entry_state;
				$SalesLeads->Postcode 		= $data->entry_postcode;
				$SalesLeads->status 		= 5;
				$SalesLeads->save();
				}
				}
				return true;
	}
	
	public function query_customers_SignUp_NoOrder()
	{
		
		$datas = DB::select(" SELECT 
		    c.id as customer_id,
			c.first_name,
			c.last_name,
			c.email,
			c.tel_num,
			ab.company_name as entry_company,
			ab.company_name as entry_street_address,
			ab.address_line_2 as entry_suburb,
			ab.post_code as entry_postcode,
			ab.city as entry_city,
			ab.county_state as entry_state 
			from customers as c 
			Left JOIN addresses as ab 
			on c.id=ab.customer_id 
			Left Join orders as o on c.id=o.customer_id 
			where c.default_address_id=ab.id  group by c.id having count(o.id)=0");
			

				foreach($datas as $data)
				{
				if(isset($data->customer_id) && SalesLead::where('customers_id',$data->customer_id)->count() == 0 && $data->entry_company != '' ){
				$SalesLeads 				= new SalesLead;
				$SalesLeads->customers_id  	= $data->customer_id;
				$SalesLeads->SalesClerk    	= 'ONLINE';
				$SalesLeads->ClientName 	= $data->first_name.' '.$data->last_name;
				$SalesLeads->Company 		= $data->entry_company;
				$SalesLeads->Tel_1 			= $data->tel_num;
				$SalesLeads->Tel_2 			= $data->tel_num;
				$SalesLeads->eMail 			= $data->email;
				$SalesLeads->Address1 		= $data->entry_street_address;
				$SalesLeads->Address2 		= $data->entry_suburb;
				$SalesLeads->Town 			= $data->entry_city;
				$SalesLeads->County 		= $data->entry_state;
				$SalesLeads->Postcode 		= $data->entry_postcode;
				$SalesLeads->status 		= 4;
				$SalesLeads->save();
				}
				}
				return true;
	}
	
        
    public function create() {
		 
                /*
                    * check permission
                    */ 
                        $is_allow = Generalfnv::check_permission('create-lead');

                        if(isset($is_allow) && $is_allow == 0) {

                            return view('admin.permissions.permission_denied');
                            exit;
                        }
                    // end permission
                    
            //echo "CK<pre>"; print_r($companyName); echo "</pre>CK"; exit;

               return view('admin.lead.lead_create');
	}	


		/**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    
    
    $request->validate([
          'Tel_1'      					=> 'required',
          'status'     					=> 'required',
          'ArrangeCallBackAlertDate'    => 'required|max:180',
          'ArrangeCallBackAlertTime'    => 'required|max:180'
        ], 
        [
          'Tel_1.required'                    => 'The Tel 1 field is required.',
          'ArrangeCallBackAlertDate.required' => 'The Arrange Call Back Alert Date field is required.',
          'ArrangeCallBackAlertTime.required' => 'The Arrange Call Back Alert Time field is required.'
		 
         
        ]);
    
	
    $SalesLead 						     = new SalesLead;
    $SalesLead->SalesClerk_ID 			 = Auth::guard('employee')->user()->id;
    $SalesLead->SalesClerk 		         = Auth::guard('employee')->user()->first_name.' '.Auth::guard('employee')->user()->last_name;
    $SalesLead->ClientName 		         = $request->ClientName;
    $SalesLead->Company 				 = $request->Company;
    $SalesLead->Tel_1 			         = $request->Tel_1;
    $SalesLead->Tel_2 	                 = $request->Tel_2;
    $SalesLead->eMail 					 = $request->eMail;
    $SalesLead->Address1 				 = $request->Address1;
    $SalesLead->Address2 				 = $request->Address2;
    $SalesLead->Town 					 = $request->Town;
    $SalesLead->County 					 = $request->County;
    $SalesLead->Postcode 				 = $request->Postcode;
    $SalesLead->status 					 = $request->status;
    $SalesLead->Hear_About_Us 			 = $request->Hear_About_Us;
    $SalesLead->ArrangeCallBackAlertDate = date('Y-m-d',strtotime($request->ArrangeCallBackAlertDate));
    $SalesLead->ArrangeCallBackAlertTime = $request->ArrangeCallBackAlertTime;
    $SalesLead->Enquiry 			     = $request->Enquiry;
    $SalesLead->save();

    return redirect()->route('admin.lead.index')->with('success', 'Lead report has been created successful!');
  }
	

	
	public function show($id) {
	
    $SalesLead = SalesLead::find($id);
    $CustomerNotesAgainstLeadReports = CustomerNotesAgainstLeadReport::where('lead_id',$SalesLead->id)->get();
	  
    return view('admin.lead.lead_view', compact('SalesLead','CustomerNotesAgainstLeadReports'));
	}
	
	public function destroy($id) {
		$SalesLead = SalesLead::find($id);
		$SalesLead->status = 6; 
		$SalesLead->save();
		return redirect()->route('admin.lead.index')->with('success', 'Lead report has been closed successful!');
	}

	
}
