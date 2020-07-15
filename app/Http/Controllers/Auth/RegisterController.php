<?php

namespace App\Http\Controllers\Auth;

use App\Shop\Customers\Customer;
use App\Shop\Customers\CustomerTemp;
use App\Http\Controllers\Controller;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Customers\Requests\CreateCustomerRequest;
use App\Shop\Customers\Requests\RegisterCustomerRequest;
use App\Shop\Customers\Requests\RegisterCustomerStep1Request;
use App\Shop\Customers\Requests\RegisterCustomerStep2Request;
use App\Shop\Customers\Requests\RegisterCustomerStep3Request;
use App\Shop\Customers\Requests\RegisterCustomerStep4Request;
use App\Shop\Addresses\Address;
use App\Shop\Addresses\AddressTemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\sendEmailtoNewuser;
use App\Mail\sendEmailtoAdminOnNewuser;
use Illuminate\Support\Facades\Mail;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/accounts?tab=account';

    private $customerRepo;

    /**
     * Create a new controller instance.
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->middleware('guest');
        $this->customerRepo = $customerRepository;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return Customer
     */
    protected function create(array $data)
    {
       $data['account_created_by_admin'] = 0;
       return $this->customerRepo->createCustomer($data);
    }
	
    /**
     * @param RegisterCustomerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterCustomerStep4Request $request)
    {
		if(!isset($request['newsletter']) || empty($request['newsletter'])){
			$request['newsletter'] = 0;
		}
		
		$complete_data = $request->except('_method', '_token','password_confirmation');
		$complete_data['token'] = str_random(40);
		$complete_data['first_name'] = session('tempCustomer.first_name');
		$complete_data['last_name'] = session('tempCustomer.last_name');
		$complete_data['email'] = session('tempCustomer.email');
		$complete_data['tel_num'] = session('tempCustomer.tel_num');
		
		/* Set Invoice Billing address as billing address */
		$complete_data['invoice_street_address'] = session('tempCustomer.billing_add.street_address');
		$complete_data['invoice_suburb'] = session('tempCustomer.billing_add.address_line_2');
		$complete_data['invoice_city'] = session('tempCustomer.billing_add.city');
		$complete_data['invoice_state'] = session('tempCustomer.billing_add.county_state');
		$complete_data['invoice_postcode'] = session('tempCustomer.billing_add.post_code');
		
		$customer = $this->create($complete_data);
		
		$default_address_id = null;
		
		if(!empty(session('tempCustomer.deliveryAdd'))){
			foreach(session('tempCustomer.deliveryAdd') as $deliveryAdd){
				$addTemp  = $deliveryAdd;
				
				unset($addTemp['id']);
				unset($addTemp['primary_address']);
				unset($addTemp['delivery_window']);
				
				$addTemp['customer_id'] = $customer->id;
				$addTemp['created_at'] = date('Y-m-d h:i:s');
				$addTemp['updated_at'] = date('Y-m-d h:i:s');
				
				$address_book_id = DB::table('addresses')->insertGetId($addTemp);
				
				if($deliveryAdd['primary_address'] == 1)
					$default_address_id = $address_book_id;
			}
		}
        
        DB::table('customers')->where('id', $customer->id)->update(['default_address_id' => $default_address_id ,'activation_mail_send' => 'yes']);
        
        $admin_email = DB::table('settings')->select('admin_email')->where('id', 1)->first();
        
        Mail::to($admin_email->admin_email, "New registration on FNV from front end")->send(new sendEmailtoAdminOnNewuser($complete_data));
        
		DB::table('customer_temps')->where('id' , session('tempCustomer.id'))->delete();
		DB::table('address_temps')->where('customer_id' , session('tempCustomer.id'))->delete();
		
		session()->forget('tempCustomer');
		
        return redirect('/register-success/'.$customer->id)->with('status', 'We sent you an activation code. Check your email and click on the link to verify.');
    }
    
	/**
     * @param $id of new register user
     * @return \Illuminate\Http\RedirectResponse
     */
	public function success($id){
		$addresses = Address::where('customer_id',$id)->get();
		$postCodesDeliveries = [];
		
		if(!empty($addresses) && $addresses->count() > 0){
			foreach($addresses as $add){
				$tempDetails = DB::table('post_codes')->whereRaw('find_in_set("\''.$add->post_code.'\'",post_codes.post_codes)')->first();
				
				$postCodesDeliveries[$add->post_code] = (!empty($tempDetails) && isset($tempDetails->week_days)) ? $tempDetails->week_days : [];
			}
		}
		
		return view('auth.register_success', compact(['addresses','postCodesDeliveries']));
	}
	
    /**
     * @param RegisterCustomerStep1Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function step1(RegisterCustomerStep1Request $request)
    {
		if(!isset($request['newsletter']) || empty($request['newsletter'])){
			$request['newsletter'] = 0;
		}
		
		$request->request->add(['email' => $request->all()['register_email']]);
		
		$complete_data = $request->except('_method', '_token','register_email','register_email_confirmation');
		$request['token'] = str_random(40);
		
		$complete_data['token'] = $request['token'];
		
		$customer = $this->createTempCustomer($complete_data);
		
		$value = [];
		$value['id'] = $customer->id;
		$value['token'] = $customer->token;
		$value['first_name'] = $customer->first_name;
		$value['last_name'] = $customer->last_name;
		$value['email'] = $customer->email;
		$value['company_name'] = $customer->company_name;
		$value['tel_num'] = $customer->tel_num;
		
		session(['tempCustomer' => $value]);
		
        return redirect()->route('registerStep', 2);
    }
    
	/**
     * Create a new user temp instance after a valid registration setp1.
     *
     * @param  array  $data
     * @return Customer
     */
	public function createTempCustomer(array $data){
		$data['account_created_by_admin'] = 0;
		
		$customer = CustomerTemp::where(['email' => $data['email']])->first();
		
		if(empty($customer))
			$customer = new CustomerTemp($data);
		
		
		$customer->first_name = $data['first_name'];
		$customer->last_name = $data['last_name'];
		$customer->company_name = $data['company_name'];
		$customer->email = $data['email'];
		$customer->tel_num = $data['tel_num'];
		$customer->newsletter = $data['newsletter'];
		$customer->token = $data['token'];
		$customer->account_created_by_admin = $data['account_created_by_admin'];
		
		$customer->save();
		
		return $customer;
	}
	
	
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function step($num = null)
    {
		if(empty(session('tempCustomer'))){
			\Session::flash('warning', 'Please fill basic information firstly for create new account.');
			return redirect()->route('login');
		}
		
		if($num == 2){
			$billingAdd = [];
			if(!empty(session('tempCustomer'))){
				$billingAdd = AddressTemp::where(['type' => 'billing','customer_id' => session('tempCustomer.id')])->first();
			}
			
			return view('auth.register_step2', compact(['billingAdd']));
		}else if($num == 3){
			$deliveryAddresses = AddressTemp::where(['type' => 'delivery','customer_id' => session('tempCustomer.id')])->get();
			
			$value = session('tempCustomer');
			$value['deliveryAdd'] = [];
			if(!empty($deliveryAddresses) && $deliveryAddresses->count() > 0){
				foreach($deliveryAddresses as $deliveryAdd){
					$delivery['id'] = $deliveryAdd->id;
					$delivery['company_name'] = $deliveryAdd->company_name;
					$delivery['street_address'] = $deliveryAdd->street_address;
					$delivery['address_line_2'] = $deliveryAdd->address_line_2;
					$delivery['post_code'] = $deliveryAdd->post_code;
					$delivery['city'] = $deliveryAdd->city;
					$delivery['county_state'] = $deliveryAdd->county_state;
					$delivery['country_id'] = $deliveryAdd->country_id;
					$delivery['customer_id'] = $deliveryAdd->customer_id;
					$delivery['Access_Time'] = $deliveryAdd->Access_Time;
					$delivery['Access_Time_latest'] = $deliveryAdd->Access_Time_latest;
					$delivery['delivery_notes'] = $deliveryAdd->delivery_notes;
					$delivery['primary_address'] = $deliveryAdd->primary_address;
					$delivery['access_24_hours'] = $deliveryAdd->access_24_hours;
					
					$delivery['delivery_window'] = str_replace(' ','',date("g:i a", $deliveryAdd->Access_Time)).'__'.str_replace(' ','',date("g:i a", $deliveryAdd->Access_Time_latest));
					
					$value['deliveryAdd'][$delivery['id']] = $delivery;
				}
			}
			session(['tempCustomer' => $value]);
			
			return view('auth.register_step3', compact(['deliveryAddresses']));
		}else if($num == 4){
			return view('auth.register_step4', compact(['']));
		}
    }
    
    /**
     * @param RegisterCustomerStep2Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function step2(RegisterCustomerStep2Request $request)
    {
		$request['street_address'] = $request['line1'];
		$request['address_line_2'] = $request['line2'];
		$request['city'] = $request['town'];
		$request['county_state'] = $request['county'];
		$request['post_code'] = $request['postcode'];
		
		$complete_data = $request->except('_token','line1','line2','county','postcode','town');
		$complete_data['type'] = 'billing';
		
		$billingAdd = AddressTemp::where(['type' => $complete_data['type'],'customer_id' => $complete_data['customer_id']])->first();
		
		if(empty($billingAdd))
			$billingAdd = new AddressTemp($complete_data);
		
		$billingAdd->street_address = $complete_data['street_address'];
		$billingAdd->address_line_2 = $complete_data['address_line_2'];
		$billingAdd->city = $complete_data['city'];
		$billingAdd->county_state = $complete_data['county_state'];
		$billingAdd->post_code = $complete_data['post_code'];
		$billingAdd->country_id = $complete_data['country_id'];
		
		$billingAdd->save();
		
		$value = session('tempCustomer');
		
		$value['billing_add'] = [];
		$value['billing_add']['id'] = $billingAdd->id;
		$value['billing_add']['street_address'] = $billingAdd->street_address;
		$value['billing_add']['address_line_2'] = $billingAdd->address_line_2;
		$value['billing_add']['post_code'] = $billingAdd->post_code;
		$value['billing_add']['city'] = $billingAdd->city;
		$value['billing_add']['county_state'] = $billingAdd->county_state;
		$value['billing_add']['country_id'] = $billingAdd->country_id;
		$value['billing_add']['customer_id'] = $billingAdd->customer_id;
		
		session(['tempCustomer' => $value]);
		
        return redirect()->route('registerStep', 3);
    }
    
    /**
     * @param RegisterCustomerStep3Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function step3(RegisterCustomerStep3Request $request)
    {
		if(!isset($request['primary_address']))
			$request['primary_address'] = 0;
		
		if(!isset($request['access_24_hours']))
			$request['access_24_hours'] = 0;
		
		$request['street_address'] = $request['line1'];
		$request['address_line_2'] = $request['line2'];
		$request['city'] = $request['town'];
		$request['county_state'] = $request['county'];
		$request['post_code'] = $request['postcode'];
		
		$accessTime = explode('__' , $request['delivery_window']);
		
		$request['Access_Time'] = strtotime($accessTime[0]);
		$request['Access_Time_latest'] = strtotime($accessTime[1]);
		
		$complete_data = $request->except('_token','line1','line2','county','postcode','town','delivery_window');
		$complete_data['type'] = 'delivery';
		
		if(array_key_exists('id',$complete_data) && !empty($complete_data['id']) && $complete_data != 0)
			$deliveryAdd = AddressTemp::where(['id' => $complete_data['id']])->first();
		else
			$deliveryAdd = new AddressTemp($complete_data);
		
		$deliveryAdd->first_name = session('tempCustomer.first_name');
		$deliveryAdd->last_name = session('tempCustomer.last_name');
		$deliveryAdd->email = session('tempCustomer.email');
		$deliveryAdd->company_name = $complete_data['company_name'];
		$deliveryAdd->street_address = $complete_data['street_address'];
		$deliveryAdd->address_line_2 = $complete_data['address_line_2'];
		$deliveryAdd->city = $complete_data['city'];
		$deliveryAdd->county_state = $complete_data['county_state'];
		$deliveryAdd->post_code = $complete_data['post_code'];
		$deliveryAdd->country_id = $complete_data['country_id'];
		$deliveryAdd->Access_Time = $complete_data['Access_Time'];
		$deliveryAdd->Access_Time_latest = $complete_data['Access_Time_latest'];
		$deliveryAdd->delivery_notes = $complete_data['delivery_notes'];
		$deliveryAdd->primary_address = $complete_data['primary_address'];
		$deliveryAdd->access_24_hours = $complete_data['access_24_hours'];
		
		$deliveryAdd->save();
		
		$delivery['id'] = $deliveryAdd->id;
		$delivery['first_name'] = $deliveryAdd->first_name;
		$delivery['last_name'] = $deliveryAdd->last_name;
		$delivery['email'] = $deliveryAdd->email;
		$delivery['company_name'] = $deliveryAdd->company_name;
		$delivery['street_address'] = $deliveryAdd->street_address;
		$delivery['address_line_2'] = $deliveryAdd->address_line_2;
		$delivery['post_code'] = $deliveryAdd->post_code;
		$delivery['city'] = $deliveryAdd->city;
		$delivery['county_state'] = $deliveryAdd->county_state;
		$delivery['country_id'] = $deliveryAdd->country_id;
		$delivery['customer_id'] = $deliveryAdd->customer_id;
		$delivery['Access_Time'] = $deliveryAdd->Access_Time;
		$delivery['Access_Time_latest'] = $deliveryAdd->Access_Time_latest;
		$delivery['delivery_notes'] = $deliveryAdd->delivery_notes;
		$delivery['primary_address'] = $deliveryAdd->primary_address;
		$delivery['access_24_hours'] = $deliveryAdd->access_24_hours;
		
		$delivery['delivery_window'] = str_replace(' ','',date("g:i a", $deliveryAdd->Access_Time)).'__'.str_replace(' ','',date("g:i a", $deliveryAdd->Access_Time_latest));
		
		$value = session('tempCustomer');
		$value['deliveryAdd'][$delivery['id']] = $delivery;
		
		/* Remove primary_address flag from other addresses */
		if( $deliveryAdd->primary_address == 1){
			if(!empty($value['deliveryAdd'])){	
				foreach($value['deliveryAdd'] as $k=>$v){
					if($k != $delivery['id'] && $v['primary_address'] == 1){
						$value['deliveryAdd'][$v['id']]['primary_address'] = 0;
						
						AddressTemp::where('id',$v['id'])->update(['primary_address' => 0]);
					}
				}
			}
		}
		
		/* End remove primary_address flag from other addresses */
		
		session(['tempCustomer' => $value]);
		
		$res = [];
		$res['status'] = true;
		$res['carousel_add'] = '';
		$res['simple_add'] = '';
		
		if(!empty($value['deliveryAdd'])){
			foreach($value['deliveryAdd'] as $add){
				$res['carousel_add'] .= "<div class='item'><span>".$add['company_name']."</span><a href='javascript:void(0)' class='link editAdd' data-form='". json_encode($add) ."'>Edit</a></div>";
				
				$res['simple_add'] .= "<li class='mea-inner'><span>".$add['company_name']."</span>
				<a href='javascript:void(0)' class='link editAdd' data-form='". json_encode($add) ."'>Edit</a></li>";
			}
		}
		
		return json_encode($res);
    }
    
	/* verify user */
	public function verifyUser($token)
    {
		$verifyUser = DB::table('customers')->select('customers_emailvalidated')->where('token', $token)->first();
        
        if(isset($verifyUser) ){
			if(!$verifyUser->customers_emailvalidated) {
                DB::table('customers')->where('token', $token)
					->update(['customers_emailvalidated' => 1]);
                $status = "Your account is verified. You can now login.";
            }else{
                $status = "Your account is already verified. You can now login.";
            }
        }else{
            return redirect('/login')->with('warning', "Sorry your email cannot be identified.");
        }

        return redirect('/login')->with('status', $status);
    }
}
