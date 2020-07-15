<?php

namespace App\Http\Controllers\Admin\Appusers;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\Helper\Generalfnv;

class AppuserController extends Controller
{
    
    public function __construct(
       Generalfnv $per_check
    ) {
       
        $this->permission = $per_check;
        
    }
   
    /**
     * Display a listing of the app users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
                /*
                * check permission
                */ 
                $is_allow = $this->permission->check_permission('view-appuser');

                if(isset($is_allow) && $is_allow == 0) {

                    return view('admin.permissions.permission_denied');
                    exit;
                }
               // end permission
        
		$record_per_page = config('constants.RECORDS_PER_PAGE');
		$app_users = DB::table('app_user')->orderBy("app_user_id", "DESC")
					  ->paginate($record_per_page);
					 // $this->categoryRepo
		 return view('admin.appusers.list', ['app_users' => $app_users]);
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
                $is_allow = $this->permission->check_permission('create-appuser');

                if(isset($is_allow) && $is_allow == 0) {

                    return view('admin.permissions.permission_denied');
                    exit;
                }
               // end permission
                
		return view('admin.appusers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
       $data = request()->all();
		//echo "<pre>"; print_r($data); exit;
		DB::table('app_user')->insert(
			['first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email_address' => $data['email'], 'password' => $data['password'], 'create_account_date' => date('Y-m-d H:i:s')]
		);
		
		return redirect()->route('admin.appusers.index')->with('message', 'Appuser created successfully');
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
     * Show the form for editing the specified app user.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
            /*
                * check permission
                */ 
                $is_allow = $this->permission->check_permission('update-appuser');

                if(isset($is_allow) && $is_allow == 0) {

                    return view('admin.permissions.permission_denied');
                    exit;
                }
               // end permission
                
		$app_users = DB::table('app_user')
                                                ->where('app_user_id', $id)
                                                ->first();
		
		return view('admin.appusers.edit', ['app_users' => $app_users]);
    }
	
	/**
     * Show the form for editing the specified app order.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editapporder($id)
    {
		
		$app_ordered_products = DB::table('app_user_purchased_products')
							->join('app_user_orders', 'app_user_orders.order_id', '=', 'app_user_purchased_products.orders_id')
							->join('app_user', 'app_user_orders.app_user', '=', 'app_user.app_user_id')
							->select('app_user_purchased_products.*', 'app_user.first_name', 'app_user.last_name')
							->where('orders_id', $id)
							->get();
							
							$order_total = 0;
							foreach($app_ordered_products as $app_ordered_product) {
								
								$order_total += $app_ordered_product->product_price*$app_ordered_product->number_purchase;
							
							}
							
							$app_order_details = DB::table('app_user_orders')
							->where('order_id', $id)
							->first();
							
							
							//echo $app_ordered_products[0]->deduct_price; exit;
							//$final_total = $order_total - $app_ordered_products[0]->deduct_price;
							$final_total = $order_total - $app_order_details->deduct_price;
							//echo "<pre>"; print_r($app_ordered_products); exit;
							
							/*
							
							$app_users_orders = DB::table('app_user_orders')->orderBy('order_id', 'desc')
								->join('app_user', 'app_user_orders.app_user', '=', 'app_user.app_user_id')
								->select('app_user_orders.*', 'app_user.first_name', 'app_user.last_name')
								->paginate($record_per_page);
								*/
		
		return view('admin.appusers.editorderedproducts', ['app_ordered_products' => $app_ordered_products, 'order_details' => $app_order_details, 'final_total' => $final_total, 'order_total' => $order_total]);
    }
	
	
	
	
/**
     * Update the specified resource in storage.
     *
     * @param  UpdateCustomerRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateorder()
    {
		$data = request()->all();
		
		$deduct_price = $data['deduct_price'];
		$deduct_comment = $data['deduct_comment'];
		$order_id = $data['order_id'];
		
		DB::table('app_user_orders')->where('order_id', $order_id)
				->update(['deduct_price' => $deduct_price, 'deduct_comment' => $deduct_comment]);
		
		
		return redirect()->route('admin.appusers.editapporder', ['id' => $order_id])->with('message', 'App Order updated successfully');
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
		DB::table('app_user')->where('app_user_id', $id)
				->update(['first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email_address' => $data['email'], 'password' => $data['password']]);
		
		
		return redirect()->route('admin.appusers.index')->with('message', 'App user updated successfully');
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
	
	/**
     * Display a listing of the app orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function apporders()
    {
                /*
                * check permission
                */ 
                $is_allow = $this->permission->check_permission('view-apporders');

                if(isset($is_allow) && $is_allow == 0) {

                    return view('admin.permissions.permission_denied');
                    exit;
                }
               // end permission
                
		$data = request()->all();
		
		$record_per_page = config('constants.RECORDS_PER_PAGE');
		
		
		$auo_qry = DB::table('app_user_orders')->orderBy('order_id', 'desc')
								->join('app_user', 'app_user_orders.app_user', '=', 'app_user.app_user_id')
								->select('app_user_orders.*', 'app_user.first_name', 'app_user.last_name');
		$app_user_name = '';
		$order_date = '';
		
		if(!empty($data)) {
		
			if(isset($data['user_id']) && !empty($data['user_id'])) {
				$user_id = $data['user_id'];
				$app_user_names = DB::table('app_user')->select('first_name', 'last_name')->where('app_user_id', $user_id)->first();
				$app_user_name = $app_user_names->first_name.' '.$app_user_names->last_name;
				$auo_qry = $auo_qry->where('app_user.app_user_id','=',$user_id);
			}
			
			if(isset($data['order_date']) && !empty($data['order_date'])) {
				$order_date = $data['order_date'];
				$auo_qry = $auo_qry->whereRaw("DATE_FORMAT(app_user_orders.order_create_date,'%Y-%m-%d') = '".date('Y-m-d', strtotime($order_date))."'");
			}			
		}
					  
			  
		$app_users_orders =$auo_qry->paginate($record_per_page);
		
				  
		$app_users = DB::table('app_user')->get();
		
		return view('admin.appusers.orderslist', ['app_users_orders' => $app_users_orders, 'app_users' => $app_users, 'search_user_name' => $app_user_name, 
													'search_order_date' => $order_date]);
													
    }
	
	/**
     * Display a listing of the app orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportapporders()
    {
		$data = request()->all();
		$from_date = date('Y-m-d', strtotime($data['from_date']));
		$to_date = date('Y-m-d', strtotime($data['to_date']));
		$export_file_name = "app_orders_list_".$from_date."_to_".$to_date;
		 
		$export_data = DB::table('app_user_orders')->orderBy('order_id', 'desc')
								->join('app_user_purchased_products', 'app_user_orders.order_id', '=', 'app_user_purchased_products.orders_id')
								->select('app_user_orders.*', 'app_user_purchased_products.*')
								->whereRaw("DATE_FORMAT(app_user_orders.order_create_date,'%Y-%m-%d') >= '".$from_date."' AND DATE_FORMAT(app_user_orders.order_create_date,'%Y-%m-%d') <= '".$to_date."'")
								->get();
		
		//echo "<pre>"; print_r($export_data); exit;
	
		//$data = User::get()->toArray();
        return Excel::create($export_file_name, function($excel) use ($export_data) {
            $excel->sheet('mySheet', function($sheet) use ($export_data)
            {
                $sheet->cell('A1', function($cell) {$cell->setValue('ProductsID');   });
                $sheet->cell('B1', function($cell) {$cell->setValue('Products Name');   });
                $sheet->cell('C1', function($cell) {$cell->setValue('Products Code');   });
                            $sheet->cell('D1', function($cell) {$cell->setValue('Packet Size');   });
                            $sheet->cell('E1', function($cell) {$cell->setValue('Brand');   });
                            $sheet->cell('F1', function($cell) {$cell->setValue('Type');   });
                            $sheet->cell('G1', function($cell) {$cell->setValue('Quantity');   });
                            $sheet->cell('H1', function($cell) {$cell->setValue('Supplier');   });
                            $sheet->cell('I1', function($cell) {$cell->setValue('Origin');   });
                            $sheet->cell('J1', function($cell) {$cell->setValue('Product Price');   });
                            $sheet->cell('K1', function($cell) {$cell->setValue('Total Price');   });
                            $sheet->cell('L1', function($cell) {$cell->setValue('Order ID');   });
                            $sheet->cell('M1', function($cell) {$cell->setValue('Create Date');   });
            if (!empty($export_data)) {
                    foreach ($export_data as $key=>$ex_data) {
                        $i= $key+2;
                        $sheet->cell('A'.$i, $ex_data->product_id); 
                        $sheet->cell('B'.$i, $ex_data->product_name); 
                        $sheet->cell('C'.$i, $ex_data->product_code);
						$sheet->cell('D'.$i, $ex_data->packet_size);
						$sheet->cell('E'.$i, $ex_data->packet_brand);
						$sheet->cell('F'.$i, $ex_data->type);
						$sheet->cell('G'.$i, $ex_data->number_purchase);
						$sheet->cell('H'.$i, $ex_data->supplier);
						$sheet->cell('I'.$i, $ex_data->origin);
						$sheet->cell('J'.$i, "£".$ex_data->product_price);
						
						$TotalPrice = ($ex_data->product_price*$ex_data->number_purchase);
						$TotalPriceFormated = number_format($TotalPrice,2);
						$sheet->cell('K'.$i, "£".$TotalPriceFormated);
						
						$sheet->cell('L'.$i, $ex_data->order_id);
						$sheet->cell('M'.$i, $ex_data->order_create_date);
                    }
                }
            });
        })->download('xlsx');
													
    }
}
