<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
//use App\Shop\Customers\Requests\UpdateGroupRequest;
use App\Shop\Customers\Requests\CreateCustomerRequest;
use App\Shop\Customers\Requests\UpdateCustomerRequest;
use App\Shop\Customers\Requests\UpdateChaseRequest;
use App\Shop\Customers\Transformations\CustomerTransformable;
use App\Http\Controllers\Controller;
use App\Mail\sendEmailtoCustomers;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Helper\Generalfnv;

class CustomerController extends Controller
{
    use CustomerTransformable;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;
    private $permission;
    /**
     * CustomerController constructor.
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(CustomerRepositoryInterface $customerRepository, Generalfnv $per_check)
    {	
        $this->customerRepo = $customerRepository;
        $this->permission = $per_check;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
            * check permission
            */ 
            $is_allow = $this->permission->check_permission('view-customer');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
            
        $list = $this->customerRepo->listCustomers('created_at', 'desc');
		//echo sizeof($list);
		$record_per_page = config('constants.RECORDS_PER_PAGE');
        if (request()->has('q')) {
            $list = $this->customerRepo->searchCustomer(request()->input('q'));
        }

        $customers = $list->map(function (Customer $customer) {
            return $this->transformCustomer($customer);
        })->all();


        return view('admin.customers.list', [
            'customers' => $this->customerRepo->paginateArrayResults($customers, $record_per_page)
        ])->with('i', (request()->input('page', 1) - 1) * $record_per_page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            /*
            * check permission
            */ 
            $is_allow = $this->permission->check_permission('create-customer');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
		$states = DB::table('states')->select('state')->get();
                return view('admin.customers.create', ['states'=>$states]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCustomerRequest $request)
    {
        $request['token'] = str_random(40);
        $data = $request->except('_token', '_method', 'company_name', 'street_address', 'address_line_2', 'post_code'
                                    , 'city', 'county_state', 'country_id');
       $complete_data = $request->except('_token', '_method');
       $customer =  $this->customerRepo->createCustomer($data);
        
       $customerid = $customer->id;
       
       $address_book_id = DB::table('addresses')->insertGetId(
                                                [ 
                                                  'first_name' => $complete_data['first_name'],
                                                  'last_name' => $complete_data['last_name'],
                                                  'company_name' => $complete_data['company_name'], 
                                                  'street_address' => $complete_data['street_address'],
                                                  'address_line_2' => $complete_data['address_line_2'],
                                                  'post_code' => $complete_data['post_code'],
                                                  'city' => $complete_data['city'],
                                                  'county_state' => $complete_data['county_state'],
                                                  'country_id' => $complete_data['country_id'],
                                                  'customer_id' => $customerid,
                                                  'status' => 1
                                                ]
                                            );
       
       
        DB::table('customers')->where('id', $customerid)
					->update(['default_address_id' => $address_book_id]);

        return redirect()->route('admin.customers.index')->with('message', "Customer Created Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        /*
            * check permission
            */ 
            $is_allow = $this->permission->check_permission('view-customer');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $customer = $this->customerRepo->findCustomerById($id);
        
        return view('admin.customers.show', [
            'customer' => $customer,
            'addresses' => $customer->addresses
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
            /*
            * check permission
            */ 
            $is_allow = $this->permission->check_permission('update-customer');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
		$states = DB::table('states')->select('state')->get();
                //$primary_address_details = DB::table('addresses')->where('')->get();
               
                 //$customer = DB::table('customers')->where('')->get();
                 
           $customer = DB::table('customers')
                                ->leftJoin('addresses', 'customers.default_address_id', '=', 'addresses.id')
                                ->select('customers.*','addresses.company_name','addresses.street_address','addresses.address_line_2'
                                        ,'addresses.post_code','addresses.city','addresses.county_state','addresses.country_id','addresses.customer_id')
                                ->where('customers.id', $id)
                                ->first();
           
          // echo "<pre>"; print_r($customer); exit;
		
		$payment_methods = DB::table('payment_methods')->get();
           
        return view('admin.customers.edit', ['states' => $states, 'customer' => $customer, 'payment_methods' => $payment_methods]);
    }
	
	 /**
     * Show the email form.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function email($id)
    {
		$customers = DB::table('customers')->select('id', 'first_name', 'last_name', 'email')->get();
        return view('admin.customers.email', ['customers' => $customers, 'customer_id' => $id]);
    }
	
	/**
     * Send mail to customers.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function sendmail()
    { 
		$data = request()->all();
		
		if ($data['customer_email'] == "all_customers") {
		
			$customers = DB::table('customers')->select('first_name', 'email')->get();
			//echo "<pre>"; print_r($customers); exit;
			if(isset($customers) && !empty($customers)) {
				
				foreach($customers as $customer) {
					
					$data['customer_name'] = $customer->first_name;
					$data['customer_email'] = $customer->email;
					Mail::to($data['customer_email'],$data['subject'])->send(new sendEmailtoCustomers($data));
				}		 
						 
			}		 
		
		} else if($data['customer_email'] == "all_subscriber") {
		
			$customers = DB::table('customers')->where('newsletter', 1)->select('first_name', 'email')->get();
			//echo "<pre>"; print_r($customers); exit;
			if(isset($customers) && !empty($customers)) {
				
				foreach($customers as $customer) {
					
					$data['customer_name'] = $customer->first_name;
					$data['customer_email'] = $customer->email;
					Mail::to($data['customer_email'],$data['subject'])->send(new sendEmailtoCustomers($data));
				}		 
						 
			}	
		
		} else {
			
			$customer = $this->customerRepo->findCustomerById($data['customer_id']);
			$data['customer_name'] = $customer->first_name;
			Mail::to($data['customer_email'],$data['subject'])->send(new sendEmailtoCustomers($data));
		}
		
		return redirect()->route('admin.customers.index')->with('message', 'Email Sent Successfully!');
		
	}
	
/**
     * update chase.
    * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function updatechase(UpdateChaseRequest $request)
    {
        $data = request()->all();
		
		foreach ($data['update_chase'] as $customer_id) {
		
			DB::table('customers')->where('id', $customer_id)
					->update(['chase' => $data['update_order_type']]);
		
		}
		
		if($data['update_order_type'] == "t") {
			$msg = "Chased Successfully!";
		} else {
			$msg = "UnChased Successfully!";
		}
		
		return redirect()->route('admin.customers.index')->with('message', $msg);
		
		
    }	
	
	
	/**
     * Show the customer create group form.
     *
     * 
     * @return \Illuminate\Http\Response
     */
 /*   public function groups()
    {
		$group_list = DB::table('customers_groups')
					  ->select('customers_group_id', 'customers_group_name', 'customers_group_description')
					  ->get();
		 return view('admin.customers.grouplist', ['customer_groups' => $group_list]);
    }
	*/
	/**
     * Show the customer create group form.
     *
     * 
     * @return \Illuminate\Http\Response
     */
 /*   public function creategroup()
    { 
		return view('admin.customers.creategroup');
    } */
	
	
	/**
     * save the group.
     *
     * 
     * @return \Illuminate\Http\Response
     */
 /*   public function storegroup()
    { 
		$data = request()->all();
		
		DB::table('customers_groups')->insert(
			['customers_group_name' => $data['group_name'], 'customers_group_description' => $data['description']]
		);
		
		return redirect()->route('admin.customers.groups')->with('message', 'Group created successfully');
		
	} */
	
	/**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
  /*  public function editgroup($id)
    { 
		$groups_records = DB::table('customers_groups')
							->select('customers_group_id', 'customers_group_name')
							->where('customers_group_id', $id)
							->first();
			  
		$categories_records = DB::table('categories')
								->select('id', 'name')
								->where('parent_id', 1)
								->get();
					  
		return view('admin.customers.editgroup', ['customer_groups' => $groups_records, 'categories' => $categories_records]);
		
    }
	*/
	
	
	/**
     * Update the specified resource in storage.
     *
     * @param  UpdateCustomerRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = $this->customerRepo->findCustomerById($id);

        $update = new CustomerRepository($customer);
       // $data = $request->except('_method', '_token', 'password');
		//$data = $request->except('_method', '_token');
                
            $data = $request->except('_token', '_method', 'company_name', 'street_address', 'address_line_2', 'post_code'
                               , 'city', 'county_state', 'country_id');
            $complete_data = $request->except('_token', '_method');
            
           $data['customers_email_address_check_for'] = '';
		if(null !== $request->input('customers_email_address_check_for')) {
			$check_for_1 = implode(",",$request->input('customers_email_address_check_for'));
			$data['customers_email_address_check_for'] = $check_for_1;
		}
		
                $data['customers_company_contact_email_extra_check_for'] = '';
		if(null !== $request->input('customers_company_contact_email_extra_check_for')) {
			$check_for_2 = implode(",",$request->input('customers_company_contact_email_extra_check_for'));
			$data['customers_company_contact_email_extra_check_for'] = $check_for_2;
		}
		
                $data['customers_account_contact_email_extra_check_for'] = '';
		if(null !== $request->input('customers_account_contact_email_extra_check_for')) {
			$check_for_3 = implode(",",$request->input('customers_account_contact_email_extra_check_for'));
			$data['customers_account_contact_email_extra_check_for'] = $check_for_3;
		}
		
                $data['customers_accountemail_check_for'] = '';
		if(null !== $request->input('customers_accountemail_check_for')) {
			$check_for_4 = implode(",",$request->input('customers_accountemail_check_for'));
			$data['customers_accountemail_check_for'] = $check_for_4;
		}
		
                $data['customers_payment_allowed'] = '';
		if(null !== $request->input('customers_payment_allowed')) {
			$check_for_5 = implode(",",$request->input('customers_payment_allowed'));
			$data['customers_payment_allowed'] = $check_for_5;
		}
		
                $data['delivery_status'] = '';
		if(null !== $request->input('delivery_status')) {
			$check_for_6 = implode(",",$request->input('delivery_status'));
			$data['delivery_status'] = $check_for_6;
		}
		
		$iEarliestTime = (intval($request->input('hour'))*60+intval($request->input('minute')))*60;
		$iEarliestTime_latest = (intval($request->input('hour_latest'))*60+intval($request->input('minute_latest')))*60;
		
		$data['Access_Time'] = $iEarliestTime;
		$data['Access_Time_latest'] = $iEarliestTime_latest;
		$data['shipping_disabled_dates'] = $complete_data['shipping_disabled_dates'];
		
		//echo "<pre>";
		//print_r($data); exit;
		
      /*  if ($request->has('password')) {
            $data['password'] = bcrypt($request->input('password'));
        } */
                
        $default_address_id = DB::table('customers')->select('default_address_id')->where('id', $id)->first();
       
        DB::table('addresses')->where('id', $default_address_id->default_address_id)->update([ 
			'first_name' => $complete_data['first_name'],
			'last_name' => $complete_data['last_name'],
			'company_name' => $complete_data['company_name'], 
			'street_address' => $complete_data['street_address'],
			'address_line_2' => $complete_data['address_line_2'],
			'post_code' => $complete_data['post_code'],
			'city' => $complete_data['city'],
			'county_state' => $complete_data['county_state'],
			'country_id' => $complete_data['country_id'],
			'customer_id' => $id
		]);

        $update->updateCustomer($data);
        
       

        $request->session()->flash('message', 'Update successful');
        return redirect()->route('admin.customers.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        /*
            * check permission
            */ 
            $is_allow = $this->permission->check_permission('delete-customer');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $customer = $this->customerRepo->findCustomerById($id);

        $customerRepo = new CustomerRepository($customer);
        $customerRepo->deleteCustomer();

        return redirect()->route('admin.customers.index')->with('message', 'Delete successful');
    }
	
}
