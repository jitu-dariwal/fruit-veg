<?php

namespace App\Shop\Products;

use App\Shop\Brands\Brand;
use App\Shop\Categories\Category;
use App\Shop\ProductAttributes\ProductAttribute;
use App\Shop\ProductImages\ProductImage;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Nicolaslopezj\Searchable\SearchableTrait;

class Product extends Model implements Buyable
{
    use SearchableTrait;

    public const MASS_UNIT = [
        'OUNCES' => 'oz',
        'GRAMS' => 'gms',
        'POUNDS' => 'lbs'
    ];

    public const DISTANCE_UNIT = [
        'CENTIMETER' => 'cm',
        'METER' => 'mtr',
        'INCH' => 'in',
        'MILIMETER' => 'mm',
        'FOOT' => 'ft',
        'YARD' => 'yd'
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'products.name' => 10,
            'products.description' => 5
        ]
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sku',
        'name',
        'description',
        'cover',
        'quantity',
        'price',
        'brand_id',
        'status',
        'weight',
        'mass_unit',
        'products_status',
        'products_status_2',
        'sale_price',
        'length',
        'width',
        'height',
        'distance_unit',
        'slug',
        'product_code',
        'packet_size',
        'packet_brand',
        'packvalue_quantity',
        'type',
        'is_split',
        'meta_title',
        'meta_description',
        'meta_keyword',
		
	];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the identifier of the Buyable item.
     *
     * @param null $options
     * @return int|string
     */
    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @param null $options
     * @return string
     */
    public function getBuyableDescription($options = null)
    {
        return $this->name;
    }

    /**
     * Get the price of the Buyable item.
     *
     * @param null $options
     * @return float
     */
    public function getBuyablePrice($options = null)
    {
        return $this->price;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * @param string $term
     * @return Collection
     */
    public function searchProduct(string $term) : Collection
    {
        return self::search($term)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

	public function searchProductGlobal($searchStr = null){
		$products_qry = $this->leftjoin('category_product', 'category_product.product_id', '=', 'products.id');
		
		$products_qry->leftjoin('categories', 'categories.id', '=', 'category_product.category_id');
		
		$products_qry->leftjoin('customer_favourite_products', 'customer_favourite_products.product_id', '=', 'products.id');
		
		$products_qry->select('products.id', 'products.name', 'products.product_code', 'products.description', 'products.products_status', 'products.products_status_2', 'products.packet_size', 'products.type', 'products.price', 'categories.id as catid', 'categories.name as catname');
		
		if(!empty($searchStr)) {          
           $products_qry = $products_qry->where('products.name', 'LIKE', '%'. $searchStr . '%');
        }
		
		$products = $products_qry->paginate(20);
		
		return $products;
	}
}
