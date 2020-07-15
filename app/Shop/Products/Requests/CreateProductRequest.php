<?php

namespace App\Shop\Products\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateProductRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'name' => ['required', 'unique:products'],
                'quantity' => ['required', 'numeric'],
                'gross_price_bulk' => ['required'],
                //'cover' => ['required', 'file', 'image:png,jpeg,jpg,gif'],
                'product_code' => ['required', 'unique:products'],
                'packvalue_quantity' => ['required', 'numeric'],
        //	'split_quantity' => ['', 'numeric'],
                'product_code_split' => ['', '', 'different:product_code'],
                'weight' => ['nullable', 'numeric']
		];
    }

    public function messages()
    {
        return [
                    'product_code_split.different' => "Product Code Bulk and Product Code Split Must Be Different.",
		   'gross_price_bulk.required' => "Gross Price field is required.",
		   'product_code.required' => "Product Code Bulk field is required.",
		   'product_code.unique' => "Product Code Bulk field must be unique.",
		   'packvalue_quantity.required' => "Pack Value Bulk field is required.",
                   'weight.numeric' => "Weight should be in decimal and number, do not use special characters"
		];
    }
	
}