<?php

namespace App\Http\Controllers\Front;

use App\Shop\Products\Product;
use App\Shop\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Categories\Category;
use App\Shop\Tools\MarkuppriceTrait;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    use ProductTransformable;
    use MarkuppriceTrait;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepo;
    
	/**
     * @var CategoryRepositoryInterface
     */
    private $category;
    
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;
	
	/**
     * @var CartRepositoryInterface
     */
    private $cartRepo;
	
    /**
     * ProductController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository, Category $category, CategoryRepositoryInterface $categoryRepository, CartRepositoryInterface $cartRepository)
    {
        $this->productRepo = $productRepository;
        $this->category = $category;
        $this->categoryRepo = $categoryRepository;
		$this->cartRepo = $cartRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
		if(!isset(Auth()->user()->id) && empty(Auth()->user()->id)) {
            
            return redirect()->route('login');
        }
		
        $data = request()->all();
        
		if(!array_key_exists('search', $data))
			$data['search'] = '';
		
		$cartItems = [];
		$categories = $this->categoryRepo->listParentCategories();
		
		$obj = new Product();
		$products = $obj->searchProductGlobal($data['search']);
		
		$category = [];
		/** Set cart items **/
		$cartItems = $this->cartRepo->getCartItemsTransformed();
		$cart_total = $this->cartRepo->getSubTotal();
        
		$total_products_price = str_replace(",", "", $cart_total);
		$total_price = str_replace(",", "", $cart_total);
        
		$default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
		if(isset(Auth()->User()->id)){
			$customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();

			if(isset($customer_minimum_order->minimum_order) && $customer_minimum_order->minimum_order != 0) {
				$default_minimum_order = $customer_minimum_order->minimum_order;
			}
		}
		
		/** End cart items **/
		
		return view('front.products.product-search', compact('cartItems','categories', 'products','category', 'data'))->with([
			'total_price' => number_format($total_price, 2),
			'total_products_price' => number_format($total_products_price, 2), 
			'default_minimum_order' => $default_minimum_order,
		]);
    }
    
    /**
     * @return filtered products
     */
    public function searchproduct() {
        
        if(!isset(Auth()->user()->id) && empty(Auth()->user()->id)) {
            
            return redirect()->route('login');
        }
        
        $categories = $this->category->listMyaccoutcategories();
        
        return view('front.products.product-search-form', compact('categories'));
        
        
    }
	
	/**
     * @return favourites products
     */
    public function getFavouritesProducts(Request $request,$customer_id = null) {
        $data = $request->all();
		
		$cartItems = [];
		$categories = $this->categoryRepo->listParentCategories();
		
		$category = [];
		$searchCatIds = [];
		
		if(isset($data['cat']) && !empty($data['cat'])){
			$searchCatIds[] = $data['cat'];
			$category = Category::with('parent')->find($data['cat']);
		}
		
		$products_arr = Category::listFavCategoryProducts($searchCatIds, ((isset(\Auth::user()->id)) ? \Auth::user()->id : ''));
		
		$products = $products_arr;
		
		foreach($products_arr as $key=>$product_arr) {
			if(isset(Auth()->user()->id)) {
				$pricewith_markup = $this->product_price_with_markup($product_arr->type, $product_arr->price, $product_arr->catid, Auth()->user()->id);
			} else {
				$pricewith_markup = $this->product_price_with_markup($product_arr->type, $product_arr->price, $product_arr->catid, '');
			}
			//$product_arr->price = str_replace(",", "", $pricewith_markup);
			
			//$products[] = $product_arr;
			
			$products[$key]->price = str_replace(",", "", $pricewith_markup);
		}
		
		$favProductCount = Category::listFavCategoryProducts([],((isset(\Auth::user()->id)) ? \Auth::user()->id : ''), true);
		
		/** Set cart items **/
		$cartItems = $this->cartRepo->getCartItemsTransformed();
		$cart_total = $this->cartRepo->getSubTotal();
        
		$total_products_price = str_replace(",", "", $cart_total);
		$total_price = str_replace(",", "", $cart_total);
        
		$default_minimum_order = config('constants.DEFAULT_MINIMUM_ORDER');
       
		if(isset(Auth()->User()->id)){
			$customer_minimum_order = DB::table('customers')->select('minimum_order')->where('id', Auth()->user()->id)->first();

			if(isset($customer_minimum_order->minimum_order) && $customer_minimum_order->minimum_order != 0) {
				$default_minimum_order = $customer_minimum_order->minimum_order;
			}
		}
		//echo $favProductCount;
		//pr($products); exit;
		/** End cart items **/
		
		return view('front.products.favouriteproducts', compact('cartItems','categories', 'products','category','favProductCount'))->with([
			'total_price' => number_format($total_price, 2),
			'total_products_price' => number_format($total_products_price, 2), 
			'default_minimum_order' => $default_minimum_order,
		]);
    }
    
     /**
     * @param catid
     *
     * @return view
     */
    public function subcategories(int $parent_cat)
    {
       // echo $parent_cat; exit;
        //$subcategories = array();
        if(isset($parent_cat)) {
            $subcategories = $this->categoryRepo->listCategories('name', 'asc')->where('parent_id', $parent_cat);
        }
       //echo "<pre>"; print_r($subcategories); exit;
        return view('admin.products.subcategories', compact('subcategories'));
    }
	
    /**
     * Get the product
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(string $slug)
    {
        $product = $this->productRepo->findProductBySlug(['slug' => $slug]);
        $images = $product->images()->get();
        $category = $product->categories()->first();
        $productAttributes = $product->attributes;

        return view('front.products.product', compact(
            'product',
            'images',
            'productAttributes',
            'category',
            'combos'
        ));
    }

}
