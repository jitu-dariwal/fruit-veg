<?php

namespace App\Shop\Categories;

use App\Shop\Products\Product;
use Illuminate\Database\Eloquent\Model;
use DB;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover',
        'image_alt_txt',
        'status',
        'meta_title',
        'meta_description',
        'meta_keyword'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }
    
    /**
     * Get all categories.
     *
     * @return category array
     */
    public function listMyaccoutcategories($product_required = '')
    {
        $cat_qry = DB::table('categories')->select('id','name')->where('status', 1);
        $cat_qry = $cat_qry->where('parent_id', '=', 0);
        $parent_categories = $cat_qry->get();
        
        $categories = array();
		foreach ($parent_categories as $key2=>$parent_cat) {

			$categories[$key2]['id'] = $parent_cat->id;
			$categories[$key2]['name'] = $parent_cat->name;
			
			//sub categories
			$subcat_qry = DB::table('categories');
			$subcat_qry = $subcat_qry->where('parent_id', '=', $parent_cat->id)->where('status', 1);
			
			$subcats = $subcat_qry->select("id", "name")->get();
			
			$sub_categories = array();
			foreach ($subcats as $key=>$subcat) {
				
				$sub_categories[$key]['name'] = $subcat->name;
				$sub_categories[$key]['cat_id'] = $subcat->id;
				
				$childcat_qry = DB::table('categories');
				$childcat_qry = $childcat_qry->where('parent_id', '=', $subcat->id)->where('status', 1);
				
				$childcats = $childcat_qry->select("id", "name")->get();
				
				$child_categories = array();
				foreach($childcats as $k => $childCat){
					
					$child_categories[$k]['name'] = $childCat->name;
					$child_categories[$k]['cat_id'] = $childCat->id;
				
					if($product_required == 1) {
						
						$products_qry = DB::table('products')
									->leftjoin('category_product', 'category_product.product_id', '=', 'products.id')
									->leftjoin('categories', 'categories.id', '=', 'category_product.category_id')
									->select('products.id', 'products.name');
							 
						$products_qry = $products_qry->where('category_product.category_id', '=', $subcat->id);
					   

						$products = $products_qry->get();
						
						$child_categories[$k]['products'] = $products;
					}
				}
				$sub_categories[$key]['childcategories'] = $child_categories;
			}
			$categories[$key2]['subcategories'] = $sub_categories;
		} 
         
        return $categories;
        
    }
    
     /**
     * Get all category products.
     *
     * @return category products
     */
    public function listCategoryproducts($catid = '', $product_name = '')
    {   //echo $catid."<br>";
    //echo $product_name; exit;
        $products_qry = DB::table('products')
                                    ->leftjoin('category_product', 'category_product.product_id', '=', 'products.id')
                                    ->leftjoin('categories', 'categories.id', '=', 'category_product.category_id')
                                    ->select('products.id', 'products.name', 'products.product_code', 'products.description', 'products.products_status', 'products.products_status_2', 'products.packet_size', 
                                             'products.type', 'products.price', 'categories.id as catid', 'categories.name as catname');
        if($catid != '') {          
           $products_qry = $products_qry->where('category_product.category_id', '=', $catid);
        }
        
        if($product_name != '') {
            $products_qry = $products_qry->where('products.name', 'like', '%'.$product_name.'%');
        }
        
        $products_qry = $products_qry->where('products.status', '=', 1);
        
        $products = $products_qry->get();
         
        return $products;
        
    }
	
     /**
     * Get all category products by search product name string.
     *
     * @return category products
     */
    public function searchCategoryProducts($catid = '', $product_name = '')
    {   //echo $catid."<br>";
    //echo $product_name; exit;
        $products_qry = Product::leftjoin('category_product', 'category_product.product_id', '=', 'products.id')
                                    ->leftjoin('categories', 'categories.id', '=', 'category_product.category_id')
                                    ->select('products.id', 'products.name', 'products.product_code', 'products.description', 'products.products_status', 'products.products_status_2', 'products.packet_size', 
                                             'products.type', 'products.price', 'categories.id as catid', 'categories.name as catname');
        if($catid != '') {          
           $products_qry = $products_qry->where('category_product.category_id', '=', $catid);
        }
        
        if($product_name != '') {
            $products_qry = $products_qry->where('products.name', 'like', '%'.$product_name.'%');
        }
        
        $products_qry = $products_qry->where('products.status', '=', 1);
        
        $products = $products_qry->paginate(20);
         
        return $products;
        
    }
    
     /**
     * Get all fav-products.
     *
     * @return fav  category products
     */
    public static function listFavCategoryProducts($catid = [], $customer_id = '' , $count = false)
    {   
		$products_qry = Product::leftjoin('category_product', 'category_product.product_id', '=', 'products.id')
			->leftjoin('categories', 'categories.id', '=', 'category_product.category_id')
			->leftjoin('customer_favourite_products', 'customer_favourite_products.product_id', '=', 'products.id')
			->select('products.id', 'products.name', 'products.product_code', 'products.description', 'products.products_status', 'products.products_status_2', 'products.packet_size', 
					 'products.type', 'products.price', 'categories.id as catid', 'categories.name as catname');
        if(!empty($catid)) {          
           $products_qry = $products_qry->whereIn('category_product.category_id', $catid);
        }
        
        if($customer_id  != '') {
            $products_qry = $products_qry->where('customer_favourite_products.customer_id', $customer_id);
        }
        
        $products_qry->where('products.status', '=', 1);
		
        $products_qry = $products_qry->groupBy('products.id');
        
		if($count)
			$products = $products_qry->get()->count();
		else
			$products = $products_qry->paginate(20);
         
        return $products;
        
    }
    
    /**
     * create temp basket of customer.
     *
     * @return category products
     */
    
    public function creatTempbasket($request_data)
    {
        $amount = number_format($request_data['pvalue']*str_replace(',','',$request_data['price']),2);
		
        $amount = str_replace(',','',$amount);
        
        $prd_id = $request_data['pid'];
        $qty = $request_data['pvalue'];
		$order_id = 0;
        if(isset($request_data['order_id'])){
			$order_id = $request_data['order_id'];
			$current_customer_id = $request_data['uid'];
		}else{
			$current_customer_id = Auth()->user()->id;
		}
		
        $data = [
			'customers_id' => $current_customer_id, 
			'products_id' => $prd_id,
			'customers_basket_quantity' => $qty,
			'catid' => $request_data['catid'],
			'price' => $amount,
			'customers_basket_date_added' => now(),
			'order_id' => $order_id
		];
        
        $prd_exists = DB::table('customers_temp_basket')->where(['customers_id' => $current_customer_id, 'products_id' => $prd_id, 'order_id' => $order_id])->count();
 
        if ($prd_exists > 0) {
            if ($qty > 0) {
				DB::table('customers_temp_basket')->where(['customers_id' => $current_customer_id, 'products_id' => $prd_id, 'order_id' => $order_id])->update($data);
            } else {
				DB::table('customers_temp_basket')->where(['customers_id' => $current_customer_id, 'products_id' => $prd_id, 'order_id' => $order_id])->delete();
            }
        } else {       
			DB::table('customers_temp_basket')->insert($data);
        }
    }
}
