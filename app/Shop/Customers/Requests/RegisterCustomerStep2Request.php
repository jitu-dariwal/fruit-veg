<?php

namespace App\Shop\Customers\Requests;

use App\Shop\Base\BaseFormRequest;

class RegisterCustomerStep2Request extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'line1' => 'required|string|max:255',
            'line2' => 'max:255',
            'line3' => 'max:255',
            'town' => 'required|string|max:255',
            'county' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
        ];
    }
	
	public function messages()
    {
        return [
			'line1.required' => "This field is required.",
			'line1.string' => "This field must be a string.",
			'line1.max' => "This field not grater then 255 character.",
			'line2.max' => "This field not grater then 255 character."
		];
    }
}
