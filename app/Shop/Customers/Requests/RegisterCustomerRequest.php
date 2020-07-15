<?php

namespace App\Shop\Customers\Requests;

use App\Shop\Base\BaseFormRequest;

class RegisterCustomerRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'register_email' => 'required|string|email|max:255|unique:customers,email|confirmed',
            'register_email_confirmation' => 'required|string|email|max:255',
            'register_password' => 'required|string|min:6|confirmed',
            'company_name' => 'required|string|max:255',
			// 'venue' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'county_state' => 'required|string|max:255',
            //'post_code' => 'required|regex:/[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}/i|min:5',
            'tel_num' => 'required|numeric|min:7',
			//  'tel_num' => 'required|integer',
            'Hear_About_Us' => 'required',
        ];
    }
    
    public function messages() {
        return [
			'tel_num.required' => "Telephone number field is required.",
			'Hear_About_Us.required' => "This field is required.",
			'register_email.required' => "The email field is required.",
			'register_email_confirmation.required' => "The confirm email field is required.",
			'register_email.email' => "The email must be a valid email address.",
			'register_email_confirmation.email' => "The email must be a valid email address.",
			'register_email.max' => "The email may not be greater than 255 characters.",
			'register_email_confirmation.max' => "The email may not be greater than 255 characters.",
			'register_email.unique' => "The email has already been taken.",
			'register_password.required' => "The password field is required.",
			'register_email.confirmed' => "The email confirmation does not match.",
			'register_password.confirmed' => "The password confirmation does not match."
        ];
    }
}
