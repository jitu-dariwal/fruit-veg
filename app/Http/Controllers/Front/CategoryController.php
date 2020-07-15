<?php

namespace App\Http\Controllers\Front;

use App\Shop\Categories\Category;
use App\Shop\Categories\Repositories\CategoryRepository;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Products\Repositories\ProductRepository;
use App\Shop\Tools\MarkuppriceTrait;
use DB;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;
	
	/**
     * @var CartRepositoryInterface
     */
    private $cartRepo;
	
	/**
     * @var ProductRepositoryInterface
     */
    private $productRepo;

	use MarkuppriceTrait;
    /**
     * CategoryController constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, CartRepositoryInterface $cartRepository, ProductRepositoryInterface $productRepository)
    {
        $this->categoryRepo = $categoryRepository;
		$this->cartRepo = $cartRepository;
		$this->productRepo = $productRepository;
    }

    /**
     * show all parent categories
     *
     * @return \App\Shop\Categories\Category
    */
	public function shop(){
		$parent_categories = $this->categoryRepo->listParentCategories();
		//pr($parent_categories->toArray());die;
		
		$total_price = 0;
		
		//cart items
		$cartItems = $this->cartRepo->getCartItemsTransformed();
		
		$cart_total = $this->cartRepo->getSubTotal();
		
		$total_products_price = str_replace(",", "", $cart_total);
		$total_price = str_replace(",", "", $cart_total);
		
		$default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
	   
		if(isset(Auth()->User()->id)) {
			$customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
			
			if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {
				$default_minimum_order = $customer_minimum_order->minimum_order;
			}
		}
		
		return view('front.categories.shop', ['categories' => $parent_categories, 'cartItems' => $cartItems])->with(['total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $default_minimum_order]); 
	}
	
	/**
     * Find the data via the slug
     *
     * @param string $slug
     * @return \App\Shop\Categories\Category
    */
	public function index(string $slug, string $child_slug = null, string $childOfChild_slug = null){
		if($childOfChild_slug != null){
			$parent_categories = $this->categoryRepo->listParentCategories();
		
			$category = $this->categoryRepo->findCategoryBySlug(['slug' => $childOfChild_slug]);
			$parent_category = $this->categoryRepo->findCategoryBySlug(['slug' => $child_slug]);
			$parentOfParent_category = $this->categoryRepo->findCategoryBySlug(['slug' => $slug]);
			
			$repo = new CategoryRepository($category);

			if(empty(request()->get('cat-product'))){
				$products_arr = $repo->findProducts()->where('status', 1)->all();
			}else{
				$obj = new Category();
				$products_arr = $obj->searchCategoryProducts($category->id, request()->get('cat-product'));
			}
			
			$products = array();
			
			foreach($products_arr as $product_arr) {
				if(isset(Auth()->user()->id)) {
					$pricewith_markup = $this->product_price_with_markup($product_arr->type, $product_arr->price, $category->id, Auth()->user()->id);
				} else {
					$pricewith_markup = $this->product_price_with_markup($product_arr->type, $product_arr->price, $category->id, '');
				}
				
				$product_arr->price = str_replace(",", "", $pricewith_markup);
				
				$products[] = $product_arr;
			}
			
			//cart items
			$cartItems = $this->cartRepo->getCartItemsTransformed();
			
			$cart_total = $this->cartRepo->getSubTotal();
			
			$total_products_price = str_replace(",", "", $cart_total);
			$total_price = str_replace(",", "", $cart_total);
			
			$default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
		   
			if(isset(Auth()->User()->id)) {
				$customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
			
				if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {	 
					$default_minimum_order = $customer_minimum_order->minimum_order;
				}
			}

			return view('front.categories.category-child', [
				'search_product' => request()->get('cat-product'),
				'parentOfParent_category' => $parentOfParent_category,
				'parent_category' => $parent_category,
				'category' => $category,
				'categories' => $parent_categories,
				'cartItems' => $cartItems,
				//'product_data' => $product_data,
				'products' => $repo->paginateArrayResults($products, 20)
			])->with(['total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $default_minimum_order, 'cat_id' => $category->id]);
		}
		
		if($child_slug != null){
			$parent_categories = $this->categoryRepo->listParentCategories();
		
			$category = $this->categoryRepo->findCategoryBySlug(['slug' => $child_slug]);
			$parent_category = $this->categoryRepo->findCategoryBySlug(['slug' => $slug]);
			
			//pr($category->children->toArray());die;
			
			$repo = new CategoryRepository($category);
			
			$products = array();
			
			if($category->children->count() < 1){
				if(empty(request()->get('cat-product'))){
					$products_arr = $repo->findProducts()->where('status', 1)->all();
				}else{
					$obj = new Category();
					$products_arr = $obj->searchCategoryProducts($category->id, request()->get('cat-product'));
				}
				
				foreach($products_arr as $product_arr) {
					if(isset(Auth()->user()->id)) {
						$pricewith_markup = $this->product_price_with_markup($product_arr->type, $product_arr->price, $category->id, Auth()->user()->id);
					} else {
						$pricewith_markup = $this->product_price_with_markup($product_arr->type, $product_arr->price, $category->id, '');
					}
					
					$product_arr->price = str_replace(",", "", $pricewith_markup);
					
					$products[] = $product_arr;
				}
			}
			
			//cart items
			$cartItems = $this->cartRepo->getCartItemsTransformed();
			
			$cart_total = $this->cartRepo->getSubTotal();
			
			$total_products_price = str_replace(",", "", $cart_total);
			$total_price = str_replace(",", "", $cart_total);
			
			$default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
		   
			if(isset(Auth()->User()->id)) {
				$customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
			
				if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {	 
					$default_minimum_order = $customer_minimum_order->minimum_order;
				}
			}

			return view('front.categories.category', [
				'search_product' => request()->get('cat-product'),
				'parent_category' => $parent_category,
				'category' => $category,
				'categories' => $parent_categories,
				'cartItems' => $cartItems,
				//'product_data' => $product_data,
				'products' => $repo->paginateArrayResults($products, 20)
			])->with(['total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $default_minimum_order, 'cat_id' => $category->id]);
		}
		
		$category = DB::table('categories')->where('slug', $slug)->first();
		if($category){
			$parent_categories = $this->categoryRepo->listParentCategories();
			$total_price = 0;
			
			//cart items
			$cartItems = $this->cartRepo->getCartItemsTransformed();
			
			$cart_total = $this->cartRepo->getSubTotal();
			
			$total_products_price = str_replace(",", "", $cart_total);
			$total_price = str_replace(",", "", $cart_total);
			
			$default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
		   
			if(isset(Auth()->User()->id)) {
				$customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
				
				if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {
					$default_minimum_order = $customer_minimum_order->minimum_order;
				}
			}
			
			$category = $this->categoryRepo->findCategoryBySlug(['slug' => $slug]);
			$subcategories = $this->categoryRepo->listSubCategories($category->id, 'id', 'ASC');
			
			return view('front.categories.subcategories', ['category' => $category, 'categories' => $parent_categories, 'subcategories' => $subcategories, 'cartItems' => $cartItems])->with(['total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $default_minimum_order, 'cat_id' => $category->id]); 
		}
		
		$page_details = DB::table('pages')->where('slug',$slug)->first();
		if($page_details){
			return view('front.pages.index', compact('page_details'));
		}
		
		return view('layouts.errors.404', compact(''));
	}
	 
	/**
     * Find the category via the slug
     *
     * @param string $slug
     * @return \App\Shop\Categories\Category
     */
    public function getCategory(string $parent_category, string $slug)
    {
	
		$parent_categories = $this->categoryRepo->listParentCategories();
		
        $category = $this->categoryRepo->findCategoryBySlug(['slug' => $slug]);
		$parent_category = $this->categoryRepo->findCategoryBySlug(['slug' => $parent_category]);

        $repo = new CategoryRepository($category);

		if(empty(request()->get('cat-product'))){
			$products_arr = $repo->findProducts()->where('status', 1)->all();
		}else{
			$obj = new Category();
			$products_arr = $obj->searchCategoryProducts($category->id, request()->get('cat-product'));
		}
		
		$products = array();
		
		foreach($products_arr as $product_arr) {
			
			if(isset(Auth()->user()->id)) {
				$pricewith_markup = $this->product_price_with_markup($product_arr->type, $product_arr->price, $category->id, Auth()->user()->id);
			} else {
				$pricewith_markup = $this->product_price_with_markup($product_arr->type, $product_arr->price, $category->id, '');
			}
			
			$product_arr->price = str_replace(",", "", $pricewith_markup);
			
			$products[] = $product_arr;
			
		}
		
		
		
		//echo "<pre>"; print_r($repo->paginateArrayResults($products, 20)); exit;
		
		//cart items
		$cartItems = $this->cartRepo->getCartItemsTransformed();
		
		$cart_total = $this->cartRepo->getSubTotal();
        
		//$total_products_price = str_replace(",", "", $cart_total)+str_replace(",", "", $total_price);
		$total_products_price = str_replace(",", "", $cart_total);
		$total_price = str_replace(",", "", $cart_total);
        
		$default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
		if(isset(Auth()->User()->id)) {
			$customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
        
		if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {
             
              $default_minimum_order = $customer_minimum_order->minimum_order;
              
         } 
        
       }

		
		//pr($category); exit;
		
		return view('front.categories.category', [
			'search_product' => request()->get('cat-product'),
			'parent_category' => $parent_category,
            'category' => $category,
			'categories' => $parent_categories,
			'cartItems' => $cartItems,
			//'product_data' => $product_data,
            'products' => $repo->paginateArrayResults($products, 20)
        ])->with(['total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $default_minimum_order, 'cat_id' => $category->id]);
    }
	
	/**
     * Parent categories lists
     *
     * @param string $slug
    */
    public function getParentCategories()
    {	
        $categories = $this->categoryRepo->listParentCategories();
		return view('front.categories.parentcategories', ['categories' => $categories]); 
    }
	
	/**
     * Sub categories lists
     *
     * @param string $slug
    */
    public function getSubCategories(string $slug)
    {	
		$parent_categories = $this->categoryRepo->listParentCategories();
		
		
        $total_price = 0;
		
		//cart items
		$cartItems = $this->cartRepo->getCartItemsTransformed();
        
		$cart_total = $this->cartRepo->getSubTotal();
        
       $total_products_price = str_replace(",", "", $cart_total);
	   $total_price = str_replace(",", "", $cart_total);
        
       $default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
       if(isset(Auth()->User()->id)) {
           
        $customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();
        

         if(isset($customer_minimum_order->minimum_order) && !empty($customer_minimum_order->minimum_order)) {

             $default_minimum_order = $customer_minimum_order->minimum_order;
              
         } 
        
       }
		
		$category = $this->categoryRepo->findCategoryBySlug(['slug' => $slug]);
		$subcategories = $this->categoryRepo->listSubCategories($category->id, 'id', 'ASC');
		return view('front.categories.subcategories', ['category' => $category, 'categories' => $parent_categories, 'subcategories' => $subcategories, 'cartItems' => $cartItems])->with(['total_price' => number_format($total_price, 2), 'total_products_price' => number_format($total_products_price, 2), 'default_minimum_order' => $default_minimum_order, 'cat_id' => $category->id]); 
    }
	
	
}
