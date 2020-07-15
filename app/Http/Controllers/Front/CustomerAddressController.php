<?php

namespace App\Http\Controllers\Front;

use App\Shop\Addresses\Repositories\AddressRepository;
use App\Shop\Addresses\Repositories\Interfaces\AddressRepositoryInterface;
use App\Shop\Addresses\Requests\CreateAddressRequest;
use App\Shop\Addresses\Requests\UpdateAddressRequest;
use App\Shop\Addresses\Requests\UpdateBillingAddressRequest;
use App\Shop\Cities\Repositories\Interfaces\CityRepositoryInterface;
use App\Shop\Countries\Repositories\Interfaces\CountryRepositoryInterface;
use App\Shop\Customers\Customer;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Shop\Provinces\Repositories\Interfaces\ProvinceRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class CustomerAddressController extends Controller
{
    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepo;
	
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepo;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepo;

    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepo;
	
	

    /**
     * @var CityRepositoryInterface
     */
    private $cityRepo;

    /**
     * @var ProvinceRepositoryInterface
     */
    private $provinceRepo;
	
	private $default_minimum_order;

    public function __construct(
        AddressRepositoryInterface $addressRepository,
		CartRepositoryInterface $cartRepository,
        CustomerRepositoryInterface $customerRepository,
        CountryRepositoryInterface $countryRepository,
        CityRepositoryInterface $cityRepository,
		ProvinceRepositoryInterface $provinceRepository
    ) {
        $this->addressRepo = $addressRepository;
		$this->cartRepo = $cartRepository;
        $this->customerRepo = $customerRepository;
		$this->cartRepo = $cartRepository;
        $this->countryRepo = $countryRepository;
        $this->provinceRepo = $provinceRepository;
		$this->cityRepo = $cityRepository;
		$this->default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       

       if(isset(Auth()->User()->id)) {
           
        $customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
        

         if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {

             $this->default_minimum_order = $customer_minimum_order->minimum_order;
              
         } 
        
       }
		

		
    }

    /**
     * @param int $customerId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($customerId)
    {
        $customer = $this->customerRepo->findCustomerById($customerId);

        return view('front.customers.addresses.list', [
            'customer' => $customer,
            'addresses' => $customer->addresses
        ]);
    }

    /**
     * @param int $customerId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($customerId)
    {
        $countries = $this->countryRepo->listCountries();
        $states = DB::table('states')->select('state')->get();
		
		//To show the cart items on right sidebar
		$total_price = 0;
		
		$cartItems = $this->cartRepo->getCartItemsTransformed();

		
		$cart_total = $this->cartRepo->getSubTotal();
        
        $total_products_price = str_replace(",", "", $cart_total);
	    $total_price = str_replace(",", "", $cart_total);

        
	
        
        $total_products_price = str_replace(",", "", $cart_total);
	    $total_price = str_replace(",", "", $cart_total);

        return view('front.customers.addresses.create', [
            'customer' => $this->customerRepo->findCustomerById($customerId),
            'countries' => $countries,
            'states' => $states,
            'cities' => $this->cityRepo->listCities(),

            'provinces' => $this->provinceRepo->listProvinces()
        ])->with(['cartItems'=> $cartItems, 'total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $this->default_minimum_order]);

     }

    /**
     * @param CreateAddressRequest $request
     * @param int $customerId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateAddressRequest $request, $customerId, $page = '')
    {
        $request['customer_id'] = $request->user()->id;
		
		$accessTime = explode('__' , $request['delivery_window']);
		
		$request['Access_Time'] = strtotime($accessTime[0]);
		$request['Access_Time_latest'] = strtotime($accessTime[1]);
		
		if(!isset($request['primary_address']))
			$request['primary_address'] = 0;
		
		if(!isset($request['access_24_hours']))
			$request['access_24_hours'] = 0;
		
        $address = $this->addressRepo->createAddress($request->except('_token', '_method'));
		
		if ($request['primary_address'] == 1) {
			DB::table('customers')->where('id', $customerId)->update(['first_name' => $address->first_name, 'last_name' => $address->last_name, 'default_address_id' => $address->id]);
        }

        if($page == 'checkout') {
            return redirect()->route('checkout.index')->with('status', 'Address creation successful');
        } else {
            return redirect()->route('accounts.addressbook')->with('status', 'Address creation successful');
        }

        
    }

    /**
     * @param $customerId
     * @param $addressId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($customerId, $addressId)
    {
        $countries = $this->countryRepo->listCountries();
        $states = DB::table('states')->select('state')->get();
		

		$customer = Customer::find(auth()->user()->id);
		

		//To show the cart items on right sidebar
		$total_price = 0;
		
		$cartItems = $this->cartRepo->getCartItemsTransformed();

		$cart_total = $this->cartRepo->getSubTotal();
        
        $total_products_price = str_replace(",", "", $cart_total);
	    $total_price = str_replace(",", "", $cart_total);

        return view('front.customers.addresses.edit', [
            'customer' => $this->customerRepo->findCustomerById($customerId),
            'address' => $this->addressRepo->findAddressById($addressId),
            'customer' => $customer,
            'countries' => $countries,
            'states' => $states,
            'cities' => $this->cityRepo->listCities(),

            'provinces' => $this->provinceRepo->listProvinces()
        ])->with(['cartItems'=> $cartItems, 'total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $this->default_minimum_order]);

    }

    /**
     * @param UpdateAddressRequest $request
     * @param $customerId
     * @param $addressId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateAddressRequest $request, $customerId, $addressId)
    {
		$address = $this->addressRepo->findAddressById($addressId);

        $addressRepo = new AddressRepository($address);
        $request['customer'] = $customerId;
        
		$accessTime = explode('__' , $request['delivery_window']);
		
		$request['Access_Time'] = strtotime($accessTime[0]);
		$request['Access_Time_latest'] = strtotime($accessTime[1]);
		
		if(!isset($request['primary_address']))
			$request['primary_address'] = 0;
		
		if(!isset($request['access_24_hours']))
			$request['access_24_hours'] = 0;
		
		$addressRepo->updateAddress($request->except('_token', '_method'));
        
        $data = $request->except('_token', '_method');
        
        if ($request['primary_address'] == 1) {
			DB::table('customers')->where('id', $customerId)->update(['first_name' => $address->first_name, 'last_name' => $address->last_name, 'default_address_id' => $addressId]);
        }

        return redirect()->route('customer.address.edit', [auth()->user()->id, $addressId])->with('status', 'Address update successful');
    }
    
    /**
     * @param Request $request
     * @param $customerId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editBilling($customerId)
    {
		$customer = Customer::select('id','invoice_street_address','invoice_suburb','invoice_city','invoice_state','invoice_postcode')->find($customerId);
		
		//To show the cart items on right sidebar
		$total_price = 0;
		
		$cartItems = $this->cartRepo->getCartItemsTransformed();

		$cart_total = $this->cartRepo->getSubTotal();
        
        $total_products_price = str_replace(",", "", $cart_total);
	    $total_price = str_replace(",", "", $cart_total);

		
        return view('front.customers.addresses.edit_billing', compact(['customer']))->with(['cartItems'=> $cartItems, 'total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $this->default_minimum_order]);

	}
    
    /**
     * @param UpdateBillingAddressRequest $request
     * @param $customerId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBilling(UpdateBillingAddressRequest $request, $customerId)
    {
		$data = $request->all();
		
		$customer = Customer::find($customerId);
		$customer->invoice_street_address = $data['street_address'];
		$customer->invoice_suburb = $data['address_line_2'];
		$customer->invoice_city = $data['city'];
		$customer->invoice_state = $data['county_state'];
		$customer->invoice_postcode = $data['post_code'];
		
		$customer->update();
		
		\Auth::user()->invoice_street_address = $data['street_address'];
		\Auth::user()->invoice_suburb = $data['address_line_2'];
		\Auth::user()->invoice_city = $data['city'];
		\Auth::user()->invoice_state = $data['county_state'];
		\Auth::user()->invoice_postcode = $data['post_code'];
		
		return redirect()->route('customer.address.billing', [auth()->user()->id])->with('status', 'Address update successful');
    }
    
     /**
     * 
     * @param $customerId
     * @param $addressId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateprimary($customerId, $addressId)
    {
        $address = DB::table('addresses')->select('first_name', 'last_name')->where('id', $addressId)->first();
        
        DB::table('customers')->where('id', $customerId)
					->update(['first_name' => $address->first_name, 'last_name' => $address->last_name, 'default_address_id' => $addressId]);
        
        return redirect()->route('accounts.addressbook')->with('status', 'Address added to primary successful');
    }

    /**
     * @param $customerId
     * @param $addressId
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($customerId, $addressId)
    { 
        
        $address = $this->addressRepo->findAddressById($addressId);
        $address->delete();
        
        return redirect()->route('accounts.addressbook')->with('status', 'Address delete successful');

    }
}
