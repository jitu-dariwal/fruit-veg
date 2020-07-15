<?php

namespace App\Shop\Products\Requests;

use App\Shop\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'name' => ['required'],
                'quantity' => ['required', 'numeric'],
                'gross_price_bulk' => ['required'],
                'packvalue_quantity' => ['required', 'numeric'],
                //'split_quantity' => ['', 'numeric'],
                'product_code_split' => ['', '', 'different:product_code'],
                'weight' => ['nullable', 'numeric']
        ];
    }
	
	public function messages()
    {
        return [
                   'product_code_split.different' => "Product Code Bulk and Product Code Split Must Be Different.",
		   'gross_price_bulk.required' => "Gross Price field is required.",
		   'packvalue_quantity.required' => "Pack Value Bulk field is required.",
                   'weight.numeric' => "Weight should be in decimal and number, do not use special characters"
		];
    }
}
