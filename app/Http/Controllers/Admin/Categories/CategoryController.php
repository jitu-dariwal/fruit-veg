<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Shop\Categories\Repositories\CategoryRepository;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Shop\Categories\Requests\CreateCategoryRequest;
use App\Shop\Categories\Requests\UpdateCategoryRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Helper\Generalfnv;

class CategoryController extends Controller
{
    private $categoryRepo;
    private $permission;

    public function __construct(CategoryRepositoryInterface $categoryRepository, Generalfnv $per_check)
    {
        $this->categoryRepo = $categoryRepository;
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
                $is_allow = $this->permission->check_permission('view-category');

                if(isset($is_allow) && $is_allow == 0) {

                    return view('admin.permissions.permission_denied');
                    exit;
                }
               // end permission
		
		$list = DB::table('categories')
			->select(['categories.*', DB::raw("(SELECT count(cat.id) from `categories` cat where cat.parent_id = categories.id) as `total_cat`")])
			->whereIn('categories.parent_id', [0])
			->orderBy('categories.created_at', 'DESC')
			->get();
			
		$total_products = array();
			
		if(!empty($list)) {
		
		foreach($list as $listproduct) {
		
			//echo "<pre>"; print_r($listproduct); exit;
			$parent_category = $listproduct->id;
			
			$total_products[$listproduct->id] = $this->totalProducts($listproduct->id);
			
		} 
		
		}
		
		//echo "<pre>"; print_r($total_products); exit;
		
		
		
		$record_per_page = config('constants.RECORDS_PER_PAGE');
                return view('admin.categories.list', [
                    'categories' => $this->categoryRepo->paginateArrayResults($list->all(), $record_per_page),
                                'total_products' => $total_products
                ]);
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
        $is_allow = $this->permission->check_permission('create-category');

        if(isset($is_allow) && $is_allow == 0) {

            return view('admin.permissions.permission_denied');
            exit;
        }
       // end permission
               
		//$cats = $this->categoryRepo->listCategories('name', 'asc');
		//echo "<pre>"; print_r($cats); exit;
        return view('admin.categories.create', [
            'categories' => $this->categoryRepo->listCategories('name', 'asc')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {

        $this->categoryRepo->createCategory($request->except('_token', '_method'));

        $request->session()->flash('message', 'Category created');
        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*
        * check permission
        */ 
        $is_allow = $this->permission->check_permission('view-category');

        if(isset($is_allow) && $is_allow == 0) {

            return view('admin.permissions.permission_denied');
            exit;
        }
       // end permission
        
        $category = $this->categoryRepo->findCategoryById($id);

        $cat = new CategoryRepository($category);
		
		$total_products = array();
		
		if(!empty($category->children)) {
		
		foreach($category->children as $listproduct) {
		
			//echo "<pre>"; print_r($listproduct); exit;
			$parent_category = $listproduct->id;
			
			$total_products[$listproduct->id] = $this->totalProducts($listproduct->id);
			
		} 
		
		}
		
		

        return view('admin.categories.show', [
            'category' => $category,
            'categories' => $category->children,
            'products' => $cat->findProducts()->where('type', 'Bulk'),
            'total_products' => $total_products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*
        * check permission
        */ 
        $is_allow = $this->permission->check_permission('update-category');

        if(isset($is_allow) && $is_allow == 0) {

            return view('admin.permissions.permission_denied');
            exit;
        }
       // end permission
        
        return view('admin.categories.edit', [
            'categories' => $this->categoryRepo->listCategories('name', 'asc', $id),
            'category' => $this->categoryRepo->findCategoryById($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCategoryRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    { 
        $category = $this->categoryRepo->findCategoryById($id);
		
        $update = new CategoryRepository($category);
        $update->updateCategory($request->except('_token', '_method'));

        $request->session()->flash('message', 'Update successful');
        return redirect()->route('admin.categories.edit', $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        /*
        * check permission
        */ 
        $is_allow = $this->permission->check_permission('delete-category');

        if(isset($is_allow) && $is_allow == 0) {

            return view('admin.permissions.permission_denied');
            exit;
        }
       // end permission
        
        $category = $this->categoryRepo->findCategoryById($id);
        $category->products()->sync([]);
        $category->delete();

        request()->session()->flash('message', 'Delete successful');
        return redirect()->route('admin.categories.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeImage(Request $request)
    {	//echo "<pre>"; print_r($request->only('category')); exit;
        $this->categoryRepo->deleteFile($request->only('category'));
        request()->session()->flash('message', 'Image delete successful');
        return redirect()->route('admin.categories.edit', $request->input('category'));
    }
	
	/**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function totalProducts($parent_cat_id)
    {
	
		
		$total_products_count = 0;
		
		$all_categories = DB::table('categories')
				->select('id')
				->whereIn('parent_id', [$parent_cat_id])
				->get();
				
				$categories_ids[] = $parent_cat_id;
				
				foreach($all_categories as $category) {
				
					$categories_ids[] = $category->id;
					
					
				}
				
				//print_r($categories_ids); exit;
				
				$total_products = DB::table('category_product')
                                        ->leftjoin('products', 'products.id', '=', 'category_product.product_id')
                                        ->select(DB::raw('count(DISTINCT product_id) as total_product'))
                                        ->whereIn('category_id', $categories_ids)
                                        ->where('products.type', 'Bulk')
					 ->first();
                                
                                $total_products_count += $total_products->total_product;
				
			
			
			return $total_products_count;
			//echo "<pre>";
			//print_r($all_categories); exit; 
        
    }
	
	
}
