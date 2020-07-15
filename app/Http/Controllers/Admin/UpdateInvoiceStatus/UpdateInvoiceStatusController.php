<?php

namespace App\Http\Controllers\Admin\UpdateInvoiceStatus;

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
use Session;

class UpdateInvoiceStatusController extends Controller
{
	

    /**
     * BrandController constructor.
     *
     * @list admin issue log
     */

		public function index(Request $request)
		{
			
		$searchDate  = '';

		return view('admin.update_invoice_status.index',compact('searchDate'));

		}
		
		public function lock(Request $request)
		{
			
		Session::put('invoiceStatus');
		 return redirect()->route('admin.update_invoice_status.index')->with('success', 'Your page locked  successfully!');

		}
		
		public function login(Request $request)
		{
			
		$request->validate([
          'password'      					=> 'required',
        ] );
		
		if($request->password == 'ffto_victoria')
		{
		Session::put('invoiceStatus','ffto_victoria');
		 return redirect()->route('admin.update_invoice_status.index')->with('success', 'You are Unlocked  successfully!');	
		}
		else
		{
		Session::put('invoiceStatus');
		 return redirect()->route('admin.update_invoice_status.index')->with('error', 'Please enter corect password to unlock  update  invoice!');
		}
		
		

		}
		
		
		public function update_po_unmber(Request $request)
		{
			
		$request->validate([
          'po_number'      				    	=> 'required',
          'invoiceid'      					    => 'required',
        ],[
				'po_number.required' => 'The Current PO Number field is required.',
				'invoiceid.required' => 'The Invoice ID field is required.'
			]);
			
			
			$invoice = Invoice::where([ 'invoiceid' => $request->invoiceid, 'po_number' => $request->po_number])->first();
			
			if(isset($invoice->id))
			{
			$invoice->is_confirm = 0;
			$invoice->save();
			}
			else
			{
			return redirect()->route('admin.update_invoice_status.index')->withInput($request->input())->with('error', 'PO Number or Invoice ID does not exist!');		
			}
		
		 return redirect()->route('admin.update_invoice_status.index')->with('success', 'Status has been updated successful!');		

		}
		
		
		public function update_payment_status(Request $request)
		{
			
		$request->validate([
          'status'      				    	=> 'required',
          'payinvoiceid'      					=> 'required',
        ],[
				'status.required' => 'The Paid / Unpaid Status field is required.',
				'payinvoiceid.required' => 'The Invoice ID field is required.'
			]);
		
		
			$invoice = Invoice::where([ 'invoiceid' => $request->payinvoiceid])->first();
			
			if(isset($invoice->id))
			{
			$invoice->status = $request->status;
			$invoice->save();
			}
			else
			{
			return redirect()->route('admin.update_invoice_status.index')->withInput($request->input())->with('error', 'PO Number or Invoice ID does not exist!');		
			}
		
		 return redirect()->route('admin.update_invoice_status.index')->with('success', 'Status has been updated successful!');			

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
