<?php

namespace App\Http\Controllers\Admin\Products;

use App\Shop\Manufacturers\Repositories\ManufacturerRepositoryInterface;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Products\Exceptions\ProductInvalidArgumentException;
use App\Shop\Products\Exceptions\ProductNotFoundException;
use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Products\Requests\CreateProductRequest;
use App\Shop\Products\Requests\UpdateProductRequest;
use App\Http\Controllers\Controller;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Tools\UploadableTrait;
use App\Shop\Tools\MarkuppriceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use PDF;
use Auth;
use App\Helper\Generalfnv;

class ProductController extends Controller
{
    use ProductTransformable, UploadableTrait, MarkuppriceTrait;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * @var ManufacturerRepositoryInterface
     */
    private $manufacturerRepo;
    private $permission;
    /**
     * ProductController constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param AttributeRepositoryInterface $attributeRepository
     * @param AttributeValueRepositoryInterface $attributeValueRepository
     * @param ProductAttribute $productAttribute
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        ManufacturerRepositoryInterface $manufacturerRepository,
        Generalfnv $per_check
    ) {
        $this->productRepo = $productRepository;
        $this->categoryRepo = $categoryRepository;
        $this->manufacturerRepo = $manufacturerRepository;
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
            $is_allow = $this->permission->check_permission('view-product');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $requested_data = request()->all();
        
        if(isset($requested_data['active_status']) && !empty($requested_data['active_status'])) {
            
            $list = $this->productRepo->listProducts('id')->where('status', '=', '1')->where('type','=','Bulk');
        } else {
            
            $list = $this->productRepo->listProducts('id')->where('type','=','Bulk');
        }
            
        if (request()->has('q') && request()->input('q') != '') {
            $list = $this->productRepo->searchProduct(request()->input('q'))->where('type','=','Bulk');
        }

        $products = $list->map(function (Product $item) {
            return $this->transformProduct($item);
        })->all();
		$record_per_page = config('constants.RECORDS_PER_PAGE');
        return view('admin.products.list', [
            'products' => $this->productRepo->paginateArrayResults($products, $record_per_page)
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
            $is_allow = $this->permission->check_permission('create-product');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        //$categories = $this->categoryRepo->listCategories('name', 'asc')->where('parent_id', 0);
		
        $catObj = new \App\Shop\Categories\Category();
        $categories = $catObj->listMyaccoutcategories();

        return view('admin.products.create', [
            'categories' => $categories,
            'brands' => $this->manufacturerRepo->listManufacturers(['*'], 'name', 'asc'),
            'default_weight' => env('SHOP_WEIGHT'),
            'weight_units' => Product::MASS_UNIT,
            'product' => new Product
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateProductRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $data = $request->except('_token', '_method');
		
        $data['slug'] = str_slug($request->input('name'));

        if ($request->hasFile('cover') && $request->file('cover') instanceof UploadedFile) {
            $data['cover'] = $this->productRepo->saveCoverImage($request->file('cover'));
        }
        
        if(!empty($data['product_code_split']) && !empty($data['packet_size_split'])){
            
            $data['is_split'] = 1;
        } 
        
       // echo "<pre>"; print_r($data); exit;

        $product = $this->productRepo->createProduct($data);
        
        

        $productRepo = new ProductRepository($product);

        if ($request->hasFile('image')) {
            $productRepo->saveProductImages(collect($request->file('image')));
        }

        $productRepo = new ProductRepository($product);
        if ($request->hasFile('image')) {
            $productRepo->saveProductImages(collect($request->file('image')));
        }

        if ($request->has('categories')) {
            $productRepo->syncCategories($request->input('categories'));
        } else {
            $productRepo->detachCategories();
        }	
	
		if(!empty($data['product_code_split']) && !empty($data['packet_size_split'])){
				$price_for_split_product 	= 	$data['real_split_price'];
				
				$sql_data_array_split 	= 	[
									'name'			=>	$data['name'],
									'description'           =>	$data['description'],
									'quantity'		=>	$data['quantity'],
									'brand_id'		=>	$data['brand_id'],
									'mass_unit'		=>	$data['mass_unit'],	                            								
									'price'			=> 	$price_for_split_product,
                                                                        'status'		=> 	$data['status'],
									'products_status'=> $data['products_status_split'],
                                                                        'products_status_2'=> $data['products_status_split_2'],
									'product_code'	=>  $data['product_code_split'],
									'packet_size'	=> 	$data['packet_size_split'],
									'type' 			=> 	'Split',									 
									'packvalue_quantity' => 	$data['split_quantity'],									 
									'split_price' 	=> 	$price_for_split_product,									  
									'packet_brand' 	=> 	$data['packet_brand'],
									'parent_id'		=>	$product->id,
									//'is_split' 		=> 1,
									'meta_title'	=>	$data['meta_title'],
									'meta_description'=>$data['meta_description'],
									'meta_keyword'	=>	$data['meta_keyword'],
									'slug'			=>	$data['slug'],
									'created_at'	=> 	now(),
									'updated_at'	=> 	now()
								];	
						
				$split_product_id = DB::table('products')->insertGetId( $sql_data_array_split);
                                
                                if ($request->has('categories')) {
                                    foreach($request->input('categories') as $catid) {
                                        
                                        DB::table('category_product')->insert(['category_id' => $catid, 'product_id' => $split_product_id]);
                                    }
                                }
		
		}
		
		
		/* */
		
	
     $list = $this->productRepo->listProducts('id')->where('type','=','Bulk');

     $products = $list->map(function (Product $item) {
            return $this->transformProduct($item);
        })->all();
	
     $record_per_page = config('constants.RECORDS_PER_PAGE');
     
     return redirect()->route('admin.products.index')->with('message', 'Product Create successful');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        /*
        * check permission
        */ 
            $is_allow = $this->permission->check_permission('view-product');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $product = $this->productRepo->findProductById($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        /*
        * check permission
        */ 
            $is_allow = $this->permission->check_permission('update-product');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $product = $this->productRepo->findProductById($id);
       
        //$categories = $this->categoryRepo->listCategories('name', 'asc')->where('parent_id', 0);
		
        $catObj = new \App\Shop\Categories\Category();
        $categories = $catObj->listMyaccoutcategories();
		
		if($product->is_split== 1){
				$splitproduct = Product::where('parent_id', '=',$product->id)->first();	
		}else{
		         $splitproduct = array();
		}
		
	return view('admin.products.edit', [
            'product' => $product,
            'images' => $product->images()->get(['src','id','alt_text']),
            'categories' => $categories,
            'selectedIds' => $product->categories()->pluck('category_id')->all(),
           // 'attributes' => $this->attributeRepo->listAttributes(),
           // 'productAttributes' => $productAttributes,
           // 'qty' => $qty,
            'brands' => $this->manufacturerRepo->listManufacturers(['*'], 'name', 'asc'),
            'weight' => $product->weight,
            'default_weight' => $product->mass_unit,
            'weight_units' => Product::MASS_UNIT,
            'split_product' => $splitproduct
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProductRequest $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Shop\Products\Exceptions\ProductUpdateErrorException
     */
    public function update(UpdateProductRequest $request, int $id)
    {
	
        $product = $this->productRepo->findProductById($id);
        $productRepo = new ProductRepository($product);

     $data = $request->except(
				'categories',
				'_token',
				'_method',
				'default',
				'image',
				'products_status_split',
                                'products_status_split_2',
				'product_code_split',
				'packet_size_split',
				'split_quantity',
				'gross_price_bulk',
				'real_split_price',
				'image_alt_text'
        );
        
        $split_data = $request->except(
				'categories',
				'_token',
				'_method',
				'default',
				'image'
				
        );
        
        if(!empty($request->input('product_code_split')) && !empty($request->input('packet_size_split'))){
            
            $data['is_split'] = 1;
        }

        $data['slug'] = str_slug($request->input('name'));

        if ($request->hasFile('cover')) {
            $data['cover'] = $productRepo->saveCoverImage($request->file('cover'));
        }

        if ($request->hasFile('image')) {
            $productRepo->saveProductImages(collect($request->file('image')));
		}
		
			if(!empty($request->input('image_alt_text'))) {
				
				foreach($request->input('image_alt_text') as $key=>$val){
					$data_images_alt = ['alt_text'	=>	$val];
					DB::table('product_images')->where('id', $key)->update($data_images_alt);
				}
			}
			
			

        if ($request->has('categories')) {
            $productRepo->syncCategories($request->input('categories'));
        } else {
            $productRepo->detachCategories();
        }
		
		

        $productRepo->updateProduct($data);
		
		
	if(!empty($request->input('product_code_split')) && !empty($request->input('packet_size_split'))){
                    
                            $splitproducts = Product::where('parent_id', '=',$product->id)->first();
                            
                            if(!empty($splitproducts)) {
				$price_for_split_product = 	$request->input('real_split_price');
				$products_status_split 	= 	$request->input('products_status_split');
                                $products_status_split_2 = 	$request->input('products_status_split_2');
				$product_code_split 	= 	$request->input('product_code_split');
				$packet_size_split 	= 	$request->input('packet_size_split');
				$split_quantity 	= 	$request->input('split_quantity');
				$gross_price_bulk 	= 	$request->input('gross_price_bulk');	
                                
                              /*  if(empty($products_status_split)) {
                                    
                                    $products_status_split = 1;
                                }
                               * 
                               */
                                
                                
				
				$sql_data_array_split 	= [
								'name'              =>  $data['name'],
								'description'       =>	$data['description'],
								'quantity'          =>	$data['quantity'],
								'brand_id'          =>	$data['brand_id'],
								'mass_unit'         =>	$data['mass_unit'],	                            								
								'price'             => 	$price_for_split_product,
                                                                'status'            => 	$data['status'],
								'products_status'   =>  $products_status_split,
                                                                'products_status_2' =>  $products_status_split_2,
								'product_code'      =>  $product_code_split,
								'packet_size'       => 	$packet_size_split,
								'type' 		    => 	'Split',									 
								'packvalue_quantity'     => 	$split_quantity,									 
								'split_price'       => 	$price_for_split_product,									  
								'packet_brand'      => 	$data['packet_brand'],
								'parent_id'	    =>	$product->id,
								'meta_title'        =>	$data['meta_title'],
								'meta_description'  =>  $data['meta_description'],
								'meta_keyword'      =>	$data['meta_keyword'],
								'slug'              =>	$data['slug'],													
								'updated_at'        => 	now()
							];	
					//echo "<pre>"; print_r($sql_data_array_split); exit;	
				
				DB::table('products')->where('id', $splitproducts->id)->update($sql_data_array_split);
                                
                                DB::table('category_product')->where('product_id', '=', $splitproducts->id)->delete();
                                if ($request->has('categories')) {
                                    foreach($request->input('categories') as $catid) {
                                        DB::table('category_product')->insert(['category_id' => $catid, 'product_id' => $splitproducts->id]);
                                    }
                                }
                                
                            } else {
                                
                                
                                $price_for_split_product = 	$split_data['real_split_price'];
                                
                                $sql_data_array_split 	= [
								'name'              =>	$split_data['name'],
								'description'       =>	$split_data['description'],
								'quantity'          =>	$split_data['quantity'],
                                                                'brand_id'          =>	$split_data['brand_id'],
                                                                'mass_unit'         =>	$split_data['mass_unit'],	                            								
                                                                'price'             => 	$price_for_split_product,
                                                                'status'            => 	$split_data['status'],
								'products_status'   =>  $split_data['products_status_split'],
                                                                'products_status_2' =>  $split_data['products_status_split_2'],
								'product_code'	    =>  $split_data['product_code_split'],
								'packet_size'       => 	$split_data['packet_size_split'],
								'type' 		    => 	'Split',									 
								'packvalue_quantity'     => 	$split_data['split_quantity'],									 
								'split_price'       => 	$price_for_split_product,									  
								'packet_brand'      => 	$split_data['packet_brand'],
								'parent_id'		=>	$product->id,
								//'is_split' 		=> 1,
								'meta_title'        =>	$split_data['meta_title'],
								'meta_description'  =>  $split_data['meta_description'],
								'meta_keyword'      =>	$split_data['meta_keyword'],
								'slug'		    =>	$data['slug'],
								'created_at'        => 	now(),
								'updated_at'        => 	now()
							];
                                
                                
                                $split_product_id = DB::table('products')->insertGetId( $sql_data_array_split);
                                
                                if ($request->has('categories')) {
                                    foreach($request->input('categories') as $catid) {
                                        
                                        DB::table('category_product')->insert(['category_id' => $catid, 'product_id' => $split_product_id]);
                                    }
                                }
						
			}
							
		
		}
		

        return redirect()->route('admin.products.edit', $id)->with('message', 'Update successful');
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
            $is_allow = $this->permission->check_permission('delete-product');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $req_data = request()->all();
        
        $product = $this->productRepo->findProductById($id);
        $product->categories()->sync([]);
       // $productAttr = $product->attributes();

      /*  $productAttr->each(function ($pa) {
            DB::table('attribute_value_product_attribute')->where('product_attribute_id', $pa->id)->delete();
        }); */

       // $productAttr->where('product_id', $product->id)->delete();
        
        //delete split product
        if (isset($product["is_split"]) && $product["is_split"] == 1) {
             $splitid = DB::table("products")->select('id')->where("parent_id",$id)->first();
             
             if(isset($splitid->id) && !empty($splitid->id)) {
                $split_product = $this->productRepo->findProductById($splitid->id);
             }
             
             if (!empty($split_product)) {
                $split_product->categories()->sync([]);
                $productRepo = new ProductRepository($split_product);
                $productRepo->removeProduct();
             }
        }
       
        
        $productRepo = new ProductRepository($product);
        $productRepo->removeProduct();
        
        if (isset($req_data['deletefrombulk']) && !empty($req_data['deletefrombulk'])) {
            
            return redirect()->route('admin.products.productsbulkupdate')->with('message', 'Delete successful');
            
        } else {
            
            return redirect()->route('admin.products.index')->with('message', 'Delete successful');
        }
        
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeImage(Request $request)
    {
        $this->productRepo->deleteFile($request->only('product', 'image'), 'uploads');
        return redirect()->back()->with('message', 'Image delete successful');
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeThumbnail(Request $request)
    {
        $this->productRepo->deleteThumb($request->input('src'));
        return redirect()->back()->with('message', 'Image delete successful');
    }
    
    /**
     * @param 
     *
     * @return view
     */
    public function productspdf()
    {
        /*
        * check permission
        */ 
            $is_allow = $this->permission->check_permission('product-pdf');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $groups = DB::table("customers_groups")->select('customers_group_id', 'customers_group_name')
                  ->get();
        
        $categories = $this->categoryRepo->listCategories('name', 'asc')->where('parent_id', 0);
        
        return view('admin.products.productpdf', compact('groups', 'categories'));
    }
    
     /**
     * @param catid
     *
     * @return view
     */
    public function subcategories(int $parent_cat)
    {
        $subcategories = $this->categoryRepo->listCategories('name', 'asc')->where('parent_id', $parent_cat);
       
        return view('admin.products.subcategories', compact('subcategories'));
    }
    
    /**
     * @param Request $request
     *
     * generate pdf
     */
    public function generateprdpdf(Request $request)
    {
        /*
        * check permission
        */ 
            $is_allow = $this->permission->check_permission('product-pdf');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $product_data = $request->except('submit','_token');
        
		// echo "<pre>"; print_r($product_data); exit;
        
        $user = Auth::guard('employee')->user();
        
        $pdf = PDF::loadView('admin.products.productpdfgenerate', ['product_data' => $product_data, 'current_uemail' => $user->email]);
        $pdf_file = str_replace(" ", "_", $product_data['customer_name'])."_".time()."_productlist";
        
        return $pdf->download($pdf_file.'.pdf');
    }
    
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendpdfemail(Request $request)
    { 
        $customer_data = $request->except(
				'submit',
				'_token'
	);
        
       
        
        
      
        Mail::send('emails.admin.CustomerProductpriceemailTpl', $customer_data, function ($message) use ($customer_data) {
               
               $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
               $message->to($customer_data['email_address']);
               $message->subject("Products List");
               $filename = explode(".", $customer_data['pdf_file']->getClientOriginalName());
              
            if (isset($customer_data['pdf_file']) && ($customer_data['pdf_file'] instanceof UploadedFile)) {
                $pdf_file = $this->uploadOne($customer_data['pdf_file'], 'productpricespdf', 'uploads', $filename[0]);
				
            }
               
               
              $message->attach("uploads/".$pdf_file);
              
               
            });
            
            $uploaded_file_name = $customer_data['pdf_file']->getClientOriginalName();
            
            if (file_exists("uploads/productpricespdf/".$uploaded_file_name)) {
                @unlink("uploads/productpricespdf/".$uploaded_file_name);
            }
            
           return redirect()->route('admin.products.productspdf')->with('message', 'PDF Sent Successfully.');
        
        
    }
    
    /**
     * @param Request $request
     *
     * @return products list
     */
    public function filterproducts(Request $request)
    {
         $filter_data = $request->except(
				'_token'
             );
         
        
         
        //parent categories
            $cat_qry = DB::table('categories');
            $cat_qry = $cat_qry->where('parent_id', '=', 0);
            
            if (isset($filter_data['category']) && !empty($filter_data['category']) && $filter_data['category'] != -1) {
                 
                $cat_qry = $cat_qry->where('id', '=', $filter_data['category']);
            } 
            
            
            $parent_categories = $cat_qry->get();

            
           // $catproducts = array();
            $products_list = array();
            foreach ($parent_categories as $key2=>$parent_cat) {

                //sub categories
                $subcat_qry = DB::table('categories');
                $subcat_qry = $subcat_qry->where('parent_id', '=', $parent_cat->id);
                
                if (isset($filter_data['subcategory']) && !empty($filter_data['subcategory'])) {
                 
                 $subcat_qry = $subcat_qry->where('id', '=', $filter_data['subcategory']);
                 
                  }
                  
                $subcats = $subcat_qry->select("id", "name")->get();
                
                $sub_categories = array();
                foreach ($subcats as $key=>$subcat) {
                    
                    $sub_categories[$key]['name'] = $parent_cat->name." >> ".$subcat->name;
                    $sub_categories[$key]['cat_id'] = $subcat->id;
                    
                    $products_qry = DB::table('products')
                                    ->leftjoin('category_product', 'category_product.product_id', '=', 'products.id')
                                    ->leftjoin('categories', 'categories.id', '=', 'category_product.category_id')
                                    ->select('products.id', 'products.name', 'products.products_status', 'products.products_status_2', 'products.packet_size', 
                                             'products.type', 'products.price', 'categories.id as catid', 'categories.name as catname');
                    
                    $products_qry = $products_qry->where('category_product.category_id', '=', $subcat->id);
                    
                    if (isset($filter_data['product_name']) && !empty($filter_data['product_name'])) {

                            $products_qry = $products_qry->where('products.name', 'like', '%'.$filter_data['product_name'].'%');
                            
                        }
                        
                    $products = $products_qry->get();
                    $sortlistprd_col = array();
                    if(!empty($products)) {
                        
                        foreach ($products as $key1=>$product) {
                            
                            $product->price = $this->product_price_with_markup($product->type, $product->price, $subcat->id, 0, $filter_data['group']);
                            $sortlistprd_col[$key1]['productId'] = $product->id;
                            $sortlistprd_col[$key1]['productName'] = $product->name;
                            $sortlistprd_col[$key1]['productSize'] = $product->packet_size;
                            $sortlistprd_col[$key1]['productPrice'] = $product->price;
                            $sortlistprd_col[$key1]['productStatusFront'] = $product->products_status_2;
                            $sortlistprd_col[$key1]['productStatusBackend'] = $product->products_status;
                            $sortlistprd_col[$key1]['productType'] = $product->type;
                        }
                        
                        
                    }                 
                    if(!empty($sortlistprd_col)){
                        $sub_categories[$key]['products'] = $sortlistprd_col;
                        $products_list[] = $sub_categories[$key];
                    }
                }
               // $catproducts[$key2] = $sub_categories;

            }

            //return view('admin.products.productpdfexport', compact('products_list'));	
            return view('admin.products.filteredproducts', compact('products_list'));
         
    }
    
   /**
     * @param Request $request
     *
     * @return products list
     */
    public function sortpdfproducts(Request $request)
    {
         $selected_products = $request->except(
				'_token'
             );
         
         $product_selected_list = $selected_products['products'];
         
         
        return view('admin.products.sortpdfproducts', compact('product_selected_list'));
        // echo "<pre>"; print_r($selected_products); exit;
         
    }
    
    
   /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function productsbulkupdate()
    { 
        
        /*
        * check permission
        */ 
            $is_allow = $this->permission->check_permission('product-bulk-update');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
        
        //parent categories
            $cat_qry = DB::table('categories');
            $cat_qry = $cat_qry->where('parent_id', '=', 0);
            
           
            
            
            $parent_categories = $cat_qry->get();

            
           // $catproducts = array();
            $products_list = array();
            foreach ($parent_categories as $key2=>$parent_cat) {

                //sub categories
                $subcat_qry = DB::table('categories');
                $subcat_qry = $subcat_qry->where('parent_id', '=', $parent_cat->id);
                
                
                  
                $subcats = $subcat_qry->select("id", "name")->get();
                
                $sub_categories = array();
                foreach ($subcats as $key=>$subcat) {
                    
                    $sub_categories[$key]['name'] = $parent_cat->name." >> ".$subcat->name;
                    $sub_categories[$key]['cat_id'] = $subcat->id;
                    
                    $products_qry = DB::table('products')
                                    ->leftjoin('category_product', 'category_product.product_id', '=', 'products.id')
                                    ->leftjoin('categories', 'categories.id', '=', 'category_product.category_id')
                                    ->select('products.id', 'products.name', 'products.products_status', 'products.products_status_2', 'products.packet_size', 
                                             'products.type', 'products.price', 'categories.id as catid', 'categories.name as catname');
                    
                    $products_qry = $products_qry->where('category_product.category_id', '=', $subcat->id);
                    
                    $products_qry = $products_qry->where('products.type', '=', 'Bulk');
                        
                    $products = $products_qry->get();
                    $sortlistprd_col = array();
                    if(!empty($products)) {
                        
                        foreach ($products as $key1=>$product) {
                            
                            $sortlistprd_col[$key1]['productId'] = $product->id;
                            $sortlistprd_col[$key1]['productName'] = $product->name;
                           $sortlistprd_col[$key1]['productSize'] = $product->packet_size;
                            $sortlistprd_col[$key1]['productPrice'] = $product->price;
                            $sortlistprd_col[$key1]['productStatusFront'] = $product->products_status_2;
                            $sortlistprd_col[$key1]['productStatusBackend'] = $product->products_status;
                            $sortlistprd_col[$key1]['productType'] = $product->type;
                        }
                        
                        
                    }                 
                    if(!empty($sortlistprd_col)){
                        $sub_categories[$key]['products'] = $sortlistprd_col;
                        $products_list[] = $sub_categories[$key];
                    }
                }
               // $catproducts[$key2] = $sub_categories;

            }
            
            $categories = $this->categoryRepo->listCategories('name', 'asc')->where('parent_id', 0);

            //return view('admin.products.productpdfexport', compact('products_list'));	
            
            return view('admin.products.productbulkupdate', compact('products_list', 'categories'));
           
}
    
    
     /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function filterbulkproducts(Request $request)
    { 
        $filter_data = $request->except(
				'_token'
             );
         
       
         
        //parent categories
            $cat_qry = DB::table('categories');
            $cat_qry = $cat_qry->where('parent_id', '=', 0);
            
            if (isset($filter_data['category']) && !empty($filter_data['category']) && $filter_data['category'] != -1) {
                 
                $cat_qry = $cat_qry->where('id', '=', $filter_data['category']);
            } 
            
            
            $parent_categories = $cat_qry->get();

            
           // $catproducts = array();
            $products_list = array();
            foreach ($parent_categories as $key2=>$parent_cat) {

                //sub categories
                $subcat_qry = DB::table('categories');
                $subcat_qry = $subcat_qry->where('parent_id', '=', $parent_cat->id);
                
                if (isset($filter_data['subcategory']) && !empty($filter_data['subcategory'])) {
                 
                 $subcat_qry = $subcat_qry->where('id', '=', $filter_data['subcategory']);
                 
                  }
                  
                $subcats = $subcat_qry->select("id", "name")->get();
                
                $sub_categories = array();
                foreach ($subcats as $key=>$subcat) {
                    
                    $sub_categories[$key]['name'] = $parent_cat->name." >> ".$subcat->name;
                    $sub_categories[$key]['cat_id'] = $subcat->id;
                    
                    $products_qry = DB::table('products')
                                    ->leftjoin('category_product', 'category_product.product_id', '=', 'products.id')
                                    ->leftjoin('categories', 'categories.id', '=', 'category_product.category_id')
                                    ->select('products.id', 'products.name', 'products.products_status', 'products.products_status_2', 'products.packet_size', 
                                             'products.type', 'products.price', 'categories.id as catid', 'categories.name as catname');
                    
                    $products_qry = $products_qry->where('category_product.category_id', '=', $subcat->id);
                    
                    if (isset($filter_data['product_name']) && !empty($filter_data['product_name'])) {

                            $products_qry = $products_qry->where('products.name', 'like', '%'.$filter_data['product_name'].'%');
                            
                        }
                        
                    $products_qry = $products_qry->where('products.type', '=', 'Bulk');
                        
                    $products = $products_qry->get();
                    $sortlistprd_col = array();
                    if(!empty($products)) {
                        
                        foreach ($products as $key1=>$product) {
                            
                            $sortlistprd_col[$key1]['productId'] = $product->id;
                            $sortlistprd_col[$key1]['productName'] = $product->name;
                           $sortlistprd_col[$key1]['productSize'] = $product->packet_size;
                            $sortlistprd_col[$key1]['productPrice'] = $product->price;
                            $sortlistprd_col[$key1]['productStatusFront'] = $product->products_status_2;
                            $sortlistprd_col[$key1]['productStatusBackend'] = $product->products_status;
                            $sortlistprd_col[$key1]['productType'] = $product->type;
                        }
                        
                        
                    }                 
                    if(!empty($sortlistprd_col)){
                        $sub_categories[$key]['products'] = $sortlistprd_col;
                        $products_list[] = $sub_categories[$key];
                    }
                }
               // $catproducts[$key2] = $sub_categories;

            }
            
            $categories = $this->categoryRepo->listCategories('name', 'asc')->where('parent_id', 0);

            //return view('admin.products.productpdfexport', compact('products_list'));	
            
            if (!empty($filter_data)) {
                
                return view('admin.products.filteredproductbulkupdate', compact('products_list', 'categories'));
                
            } else {
                
                return view('admin.products.productbulkupdate', compact('products_list', 'categories'));
            }
            
            
    }
    
  
 
    /**
    * @param Request $request
    * @product backend and frontend stock update
    * @return \Illuminate\Http\RedirectResponse
    */
   public function bulkprdstockupdate(Request $request) {
       
       $requested_data = $request->all();
       
        $current_status = $requested_data['status'];
        $status_to_updated = $current_status==0?'1':'0';
        
        //echo "<pre>"; print_r($requested_data); exit;
       
       if (isset($requested_data['frontend']) && $requested_data['frontend'] == "yes") {
          // echo $status_to_updated; exit;
          DB::table('products')
            ->where('id', $requested_data['pid'])
            ->update(['products_status' => $current_status]);
          
          if ($current_status == 1) 
              { 
              
                  $imgsrc = "/images/action_check.png"; 
              
              } else { 
                  
                  $imgsrc = "/images/action_delete.png"; 
              }
          ?>
            <img src="<?php echo $imgsrc; ?>" name="Image1_<?php echo $requested_data['i']; ?>" onClick="Stock_frontend(<?php echo $requested_data['pid']; ?>, <?php echo $status_to_updated; ?>, <?php echo $requested_data['i']; ?>)">
    <?php
           
       } else {
           
           
           DB::table('products')
            ->where('id', $requested_data['pid'])
            ->update(['products_status_2' => $current_status]);
           
           if ($current_status == 1) 
              { 
              
                  $imgsrc1 = "/images/action_check.png"; 
              
              } else { 
                  
                  $imgsrc1 = "/images/action_delete.png"; 
              }
           
           ?>
            <img src="<?php echo $imgsrc1; ?>" name="Image2_<?php echo $requested_data['i']; ?>" onClick="Stock_backend(<?php echo $requested_data['pid']; ?>, <?php echo $status_to_updated; ?>, <?php echo $requested_data['i']; ?>)">
    <?php
           
       }
       
       //echo "<pre>"; print_r($requested_data); exit;
       
   }


    /**
     * @param Request $request
     * @product price update
     * @return \Illuminate\Http\RedirectResponse
     */
   
   public function bulkprdpriceupdate(Request $request) {
        
        $requested_data = $request->all();
        $bulk_price = $requested_data['price'];
        
        DB::table('products')
            ->where('id', $requested_data['pid'])
            ->update(['price' => $bulk_price]);
        
        $product_bulk_details = DB::table('products')
                            ->select('packvalue_quantity', 'is_split')
                            ->where('id', $requested_data['pid'])
                            ->first();
        
        //echo "<pre>"; print_r($product_bulk_details); exit;
        
        //update split product price if this product have the split product
        if ($product_bulk_details->is_split == 1) {
            
            $product_split_details = DB::table('products')
                            ->select('id', 'packvalue_quantity')
                            ->where('parent_id', $requested_data['pid'])
                            ->first();
            
            $bulk_quanty = $product_bulk_details->packvalue_quantity;
            $split_quantity = $product_split_details->packvalue_quantity;
            
            $split_price = number_format($bulk_price*$split_quantity / $bulk_quanty, 2);
            
            DB::table('products')
            ->where('id', $product_split_details->id)
            ->update(['price' => $split_price, 'split_price' => $split_price]);
            
        }
        
    }
    
    public function test() {
        echo "ok"; exit;
    }
    
    
}

    
