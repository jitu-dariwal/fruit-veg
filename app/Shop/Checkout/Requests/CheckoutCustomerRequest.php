<?php

namespace App\Shop\Checkout\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    { 
        return [
                'shipdate' => ['required'],
                'payment_name' => ['required'],
				'billing_address' => ['required'],
				'delivery_address' => ['required']
        ];
    }
    
    public function messages()
    {
        return [
                   'shipdate.required' => "Please select a date for your shipment to arrive on.",
				   'payment_name.required' => "Please select payment to process."
		];
    }
    
}
