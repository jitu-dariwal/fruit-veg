<?php

namespace App\Shop\Products\Transformations;

use App\Shop\Products\Product;
use Illuminate\Support\Facades\Storage;

trait ProductTransformable
{
    /**
     * Transform the product
     *
     * @param Product $product
     * @return Product
     */
    protected function transformProduct(Product $product)
    {
        $prod = new Product;
        $prod->id = (int) $product->id;
        $prod->name = $product->name;
        $prod->sku = $product->sku;
        $prod->slug = $product->slug;
        $prod->description = $product->description;
        $prod->cover = $product->cover;
        $prod->quantity = $product->quantity;
        $prod->price = $product->price;
        $prod->status = $product->status;
        $prod->weight = $product->weight;
        $prod->mass_unit = $product->mass_unit;
        $prod->sale_price = $product->sale_price;
        $prod->brand_id = (int) $product->brand_id;
        $prod->product_code = $product->product_code;
        
      /* if(isset($product->catname) && !empty($product->catname)) {
            $prod->catname = $product->catname;
        }  */
        
        $prod->packet_size = $product->packet_size;
        $prod->packet_brand = $product->packet_brand;
        $prod->packvalue_quantity = $product->packvalue_quantity;
        $prod->type = $product->type;
        $prod->meta_title = $product->meta_title;
        $prod->meta_description = $product->meta_description;
        $prod->meta_keyword = $product->meta_keyword;
        $prod->is_split = $product->is_split;
        $prod->products_status = $product->products_status;
        $prod->products_status_2 = $product->products_status_2;
        $prod->coverimage_alt_text = $product->coverimage_alt_text;
        return $prod;
    }
}
