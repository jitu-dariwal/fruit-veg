<?php

namespace App\Http\Controllers\Admin\Customers;
use App\Http\Controllers\Controller;
use App\Shop\Customers\Requests\UpdateGroupRequest;
use App\Shop\Customers\Requests\CreateGroupRequest;
use DB;
use App\Helper\Generalfnv;

class CustomerGroupController extends Controller
{
    private $permission;
    
    public function __construct(Generalfnv $per_check)
    {	
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
            $is_allow = $this->permission->check_permission('view-customergroup');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
		$group_list = DB::table('customers_groups')
					  ->select('customers_group_id', 'customers_group_name', 'customers_group_description')
					  ->get();
		 return view('admin.customers.grouplist', ['customer_groups' => $group_list]);
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
            $is_allow = $this->permission->check_permission('create-customergroup');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
		return view('admin.customers.creategroup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGroupRequest $request)
    {
       $data = request()->all();
		
		DB::table('customers_groups')->insert(
			['customers_group_name' => $data['group_name'], 'customers_group_description' => $data['description']]
		);
		
		return redirect()->route('admin.customersgroup.index')->with('message', 'Group created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
  /*  public function show(int $id)
    {
        $customer = $this->customerRepo->findCustomerById($id);
        
        return view('admin.customers.show', [
            'customer' => $customer,
            'addresses' => $customer->addresses
        ]);
    }
	*/

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
            $is_allow = $this->permission->check_permission('update-customergroup');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
            // end permission
            
		$groups_records = DB::table('customers_groups')
							->select('customers_group_id', 'customers_group_name', 'customers_group_description')
							->where('customers_group_id', $id)
							->first();
			  
		$categories_records = DB::table('categories')
								->select('id', 'name')
								->where('parent_id', 0)
								->get();
		//echo "<pre>";
		//print_r($categories_records); exit;	
		$all_res_catid = array();	
		foreach ($categories_records as $res_cat_id) {
			$all_res_catid[] = $res_cat_id->id;
		}
		
		//$all_res_catid = explode(",", rtrim($all_res_catid, ","));
		
								
		$group_categories_record = DB::table('customers_groups_charges')
									->where('group_id', $id)
									->whereIn('category_id', $all_res_catid)
									->get();
								
								
								
								//dd($group_categories_record);
								//echo "<pre>";
		//print_r($group_categories_record); exit;
								
		$cat_value_asgroup = array();						
		foreach ($group_categories_record as $group_cat_value) {
			$cat_value_asgroup[$group_cat_value->category_id]['bulk_value'] = $group_cat_value->bulk_value;
			$cat_value_asgroup[$group_cat_value->category_id]['split_value'] = $group_cat_value->split_value;
		}
		//echo "<pre>";
		//print_r($cat_value_asgroup); exit;
					  
		return view('admin.customers.editgroup', ['customer_groups' => $groups_records, 'categories' => $categories_records, 'grp_cat_charges' => $cat_value_asgroup]);
    }
	
	 
	
	
	/**
     * Update the specified resource in storage.
     *
     * @param  UpdateCustomerRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
		$data = request()->all();
		
		//update group data
		DB::table('customers_groups')->where('customers_group_id', $id)
				->update(['customers_group_description' => $data['description']]);
		
		$categories_records = DB::table('categories')
								->select('id', 'name')
								->where('parent_id', 0)
								->get();
		foreach ($categories_records as $categories_record) {
		
			$cat_data = DB::table('customers_groups_charges')->where([
				['group_id', '=', $id],
				['category_id', '=', $categories_record->id],
			])->count();
			
			if ($cat_data > 0) {
			
				DB::table('customers_groups_charges')->where('category_id', $categories_record->id)
				->update(['bulk_value' => $data['bulk_'.$categories_record->id], 'split_value' => $data['split_'.$categories_record->id]]);
				
			} else {
			
				DB::table('customers_groups_charges')->insert(['group_id' => $id, 'category_id' => $categories_record->id, 'bulk_value' => $data['bulk_'.$categories_record->id], 'split_value' => $data['split_'.$categories_record->id]]);
			
			}
			
			
			
		}
		
		return redirect()->route('admin.customersgroup.index')->with('message', 'Group updated successfully');
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
        
    }
}
