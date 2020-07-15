<?php

namespace App\Http\Controllers\Admin\Coupons;

use App\Http\Controllers\Controller;
use App\Shop\Coupons\Repositories\CouponRepository;
use App\Shop\Coupons\Repositories\CouponRepositoryInterface;
use App\Shop\Coupons\Requests\CreateCouponRequest;
use App\Shop\Coupons\Requests\UpdateCouponRequest;
use Illuminate\Http\Request;
use App\Shop\Categories\Category;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Mail\sendEmailtoCustomers;
use Illuminate\Support\Facades\Mail;
use DB;
use Auth;
use App\Helper\Generalfnv;

class CouponController extends Controller
{
    /**
     * @var BrandRepositoryInterface
     */
    private $couponRepo;
    
    private $customerRepo;
    
    /**
        * @var CategoryRepositoryInterface
        */
       private $category;
       private $permission;
    /**
     * BrandController constructor.
     *
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(CouponRepositoryInterface $couponRepository, Category $category, CustomerRepositoryInterface $customerRepository, Generalfnv $per_check)
    {
        $this->couponRepo = $couponRepository;
        $this->category = $category;
        $this->customerRepo = $customerRepository;
        $this->permission = $per_check;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        
        /*
            * check permission
            */ 
            $is_allow = $this->permission->check_permission('view-coupon');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
                
         if (request()->has('q') && request()->input('q') != '') {
            
            $searchValue = request()->input('q');
            $coupons_data = DB::select("CALL `SearchCoupons`('$searchValue')");
        } else {
            
            $coupons_data = DB::select("CALL `getAllCoupons`");
        }
        
        //list coupons using procedure getcoupons
        
        $record_per_page = config('constants.RECORDS_PER_PAGE');
        $data = $this->couponRepo->paginateArrayResults($coupons_data, $record_per_page);
        //echo "<pre>";
        //print_r($data); exit;
        
	return view('admin.coupons.list', ['coupons' => $data]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        /*
            * check permission
            */ 
            $is_allow = $this->permission->check_permission('create-coupon');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $categories = $this->category->listMyaccoutcategories(1);
        
        return view('admin.coupons.create', ['categories' => $categories]);
    }

    /**
     * @param CreateBrandRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateCouponRequest $request)
    {
        $data = $request->all();
        
        $name = $data['name'];
        $description = $data['description'];
        $coupon_amount = $data['coupon_amount'];
        $coupon_amount_type = $data['coupon_amount_type'];
        $coupon_minimum_order = $data['coupon_minimum_order'];
        $free_shipping = 0;
        $coupon_code = $data['coupon_code'];
        $uses_per_coupon = $data['uses_per_coupon'];
        $uses_per_user = $data['uses_per_user'];
        $restrict_to_products = $data['restrict_to_products'];
        $restrict_to_categories = $data['restrict_to_categories'];
        $coupon_start_date = date('Y-m-d H:i:s', strtotime($data['coupon_start_date']));
        $coupon_expire_date = date('Y-m-d H:i:s', strtotime($data['coupon_expire_date']));
       
        
        DB::statement("CALL `createCoupon`('".$name."','".$description."','".$coupon_amount."','".$coupon_amount_type."','".$coupon_minimum_order."','".$free_shipping."',"
                . "'".$coupon_code."','".$uses_per_coupon."','".$uses_per_user."','".$restrict_to_products."','".$restrict_to_categories."','".$coupon_start_date."','".$coupon_expire_date."')");
       
        //$this->couponRepo->createCoupon($request->all());

        return redirect()->route('admin.coupons.index')->with('message', 'Create coupon successful!');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        /*
            * check permission
            */ 
            $is_allow = $this->permission->check_permission('update-coupon');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $categories = $this->category->listMyaccoutcategories(1);
        $cu_data = DB::select("CALL `GetCouponById`('$id')");
        //echo "<pre>";
        //print_r($cu_data);
        //exit;
        return view('admin.coupons.edit', ['coupon' => $cu_data[0], 'categories' => $categories]);
    }

    /**
     * @param UpdateBrandRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Shop\Brands\Exceptions\UpdateBrandErrorException
     */
    public function update(UpdateCouponRequest $request, $id)
    {
        //$coupon = $this->couponRepo->findCouponById($id);

        //$couponRepo = new CouponRepository($coupon);
       // $couponRepo->updateCoupon($request->all());
        $data = $request->all();
        
        $name = $data['name'];
        $description = $data['description'];
        $coupon_amount = $data['coupon_amount'];
        $coupon_amount_type = $data['coupon_amount_type'];
        $coupon_minimum_order = $data['coupon_minimum_order'];
        $free_shipping = 0;
        $coupon_code = $data['coupon_code'];
        $uses_per_coupon = $data['uses_per_coupon'];
        $uses_per_user = $data['uses_per_user'];
        $restrict_to_products = $data['restrict_to_products'];
        $restrict_to_categories = $data['restrict_to_categories'];
        $coupon_start_date = date('Y-m-d H:i:s', strtotime($data['coupon_start_date']));
        $coupon_expire_date = date('Y-m-d H:i:s', strtotime($data['coupon_expire_date']));
       
        
        DB::statement("CALL `updateCoupon`('".$id."','".$name."','".$description."','".$coupon_amount."','".$coupon_amount_type."','".$coupon_minimum_order."','".$free_shipping."',"
                . "'".$coupon_code."','".$uses_per_coupon."','".$uses_per_user."','".$restrict_to_products."','".$restrict_to_categories."','".$coupon_start_date."','".$coupon_expire_date."')");

        return redirect()->route('admin.coupons.edit', $id)->with('message', 'Update successful!');
    }
    
     /**
     * Show the email form.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function email($id)
    {
            $cu_data = DB::select("CALL `GetCouponById`('$id')");
            
            //echo "<pre>";
           // print_r($cu_data); exit;
            
            $customers = DB::table('customers')->select('id', 'first_name', 'last_name', 'email')->get();
        return view('admin.coupons.email', ['customers' => $customers, 'coupon_id' => $id, 'coupon_code' => $cu_data[0]->coupon_code]);
    }
    
    /**
     * Send mail to customers.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function sendmail()
    { 
                $user = Auth::guard('employee')->user();
               
		$data = request()->all();
		
		if ($data['customer_email'] == "all_customers") {
		
			$customers = DB::table('customers')->select('id', 'first_name', 'last_name', 'email')->get();
			//echo "<pre>"; print_r($customers); exit;
			if(isset($customers) && !empty($customers)) {
				
				foreach($customers as $customer) {
					
					$data['customer_name'] = $customer->first_name;
					$data['customer_email'] = $customer->email;
					Mail::to($data['customer_email'],$data['subject'])->send(new sendEmailtoCustomers($data));
                                        
                                        $email_data = array('customer_id_sent' => $customer->id, 'sender_id' => $user->id, 'coupon_id' => $data['coupon_id'], 'sent_firstname' => $customer->first_name, 'sent_lastname' => $customer->last_name, 'emailed_to' => $data['customer_email'], 'date_sent' => date('Y-m-d H:i:s'));
                                        DB::table('coupon_email_track')->insert($email_data);
                                }		 
						 
			}		 
		
		} else if($data['customer_email'] == "all_subscriber") {
		
			$customers = DB::table('customers')->where('newsletter', 1)->select('id','first_name', 'last_name', 'email')->get();
			//echo "<pre>"; print_r($customers); exit;
			if(isset($customers) && !empty($customers)) {
				
				foreach($customers as $customer) {
					
					$data['customer_name'] = $customer->first_name;
					$data['customer_email'] = $customer->email;
					Mail::to($data['customer_email'],$data['subject'])->send(new sendEmailtoCustomers($data));
				
                                        $email_data = array('customer_id_sent' => $customer->id, 'sender_id' => $user->id, 'coupon_id' => $data['coupon_id'], 'sent_firstname' => $customer->first_name, 'sent_lastname' => $customer->last_name, 'emailed_to' => $data['customer_email'], 'date_sent' => date('Y-m-d H:i:s'));
                                        DB::table('coupon_email_track')->insert($email_data);
                                }		 
						 
			}	
		
		} else {
			$customers_id = DB::table('customers')->where('email', $data['customer_email'])->select('id')->first();
			$customer = $this->customerRepo->findCustomerById($customers_id->id);
			$data['customer_name'] = $customer->first_name;
			Mail::to($data['customer_email'],$data['subject'])->send(new sendEmailtoCustomers($data));
                        
                        $email_data = array('customer_id_sent' => $customer->id, 'sender_id' => $user->id, 'coupon_id' => $data['coupon_id'], 'sent_firstname' => $customer->first_name, 'sent_lastname' => $customer->last_name, 'emailed_to' => $data['customer_email'], 'date_sent' => date('Y-m-d H:i:s'));
                        DB::table('coupon_email_track')->insert($email_data);
		}
		
		return redirect()->route('admin.coupons.index')->with('message', 'Email Sent Successfully!');
		
	}
    
    /**
     * select products
     * 
     * return product ids
     */
    public function selectproduct(Request $request) {
        
        $product_data = $request->except('_token');
        $productids = '';
        foreach($product_data['product_ids'] as $product_id) {
            $productids .= $product_id.",";
        }
        
        echo rtrim($productids, ",");
        exit;
        
    }
    
    /**
     * select categories
     * 
     * return category ids
     */
    public function selectcategory(Request $request) {
        
        $category_data = $request->except('_token');
        $catids = '';
        foreach($category_data['catlist'] as $cat_id) {
            $catids .= $cat_id.",";
        }
        
        echo rtrim($catids, ",");
        exit;
    }
    
     /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reports($coupon_id)
    {
        //list coupons users using procedure getCouponsuses
        $coupons_data = DB::select("CALL `getCouponsuses`('$coupon_id')");
        $total_uses = count($coupons_data);
       // echo "<pre>";
      //  print_r($coupons_data); exit;
        
        $record_per_page = config('constants.RECORDS_PER_PAGE');
        $data = $this->couponRepo->paginateArrayResults($coupons_data, $record_per_page);
        //echo "<pre>";
        //print_r($data); exit;
        
	return view('admin.coupons.report', ['coupons_customer' => $data, 'total_uses' => $total_uses]);
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sentcoupon()
    {
       /*
            * check permission
            */ 
            $is_allow = $this->permission->check_permission('view-coupon');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
       $sentcoupons = DB::select("CALL `getSentcoupons`");
       
	return view('admin.coupons.sentcouponlist', compact('sentcoupons'));
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($coupon_id)
    { 
       $customerid = $_REQUEST['customerid'];
       $customer_coupon_user = DB::select("CALL `getCouponusesbycid`($coupon_id, $customerid)");
       //echo "<pre>"; print_r($customer_coupon_user); exit;
       $customer_coupon_user_data = array();
       if(isset($customer_coupon_user[0]) && !empty($customer_coupon_user[0])) {
        $customer_coupon_user_data = $customer_coupon_user[0];
       }
       return view('admin.coupons.customercoupon', compact('customer_coupon_user_data'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        /*
            * check permission
            */ 
            $is_allow = $this->permission->check_permission('delete-coupon');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $coupon = $this->couponRepo->findCouponById($id);
        $couponRepo = new CouponRepository($coupon);
        $couponRepo->deleteCoupon();
        return redirect()->route('admin.coupons.index')->with('message', 'Delete successful!');
    }
}
