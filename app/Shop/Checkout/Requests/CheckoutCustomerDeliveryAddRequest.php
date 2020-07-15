<?php

namespace App\Shop\Checkout\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutCustomerDeliveryAddRequest extends FormRequest
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
                'delivery_date' => ['required'],
                'address' => ['required'],
                'delivery_window' => ['required'],
        ];
    }
    
    public function messages()
    {
        return [
                   'delivery_date.required' => "Please select a date for your shipment to arrive on.",
		];
    }
    
}
