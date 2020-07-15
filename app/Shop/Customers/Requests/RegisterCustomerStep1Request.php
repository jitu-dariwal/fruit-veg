<?php

namespace App\Shop\Customers\Requests;

use App\Shop\Base\BaseFormRequest;

class RegisterCustomerStep1Request extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		// alphanumeric validateion -> 'regex:/^[a-zA-Z0-9_\-]*$/'
		// alphanumeric validateion -> 'regex:/^[\w-]*$/'
		
        return [
            'first_name' => 'required|string|regex:/^[a-zA-Z0-9]*$/|max:255',
            'last_name' => 'string|regex:/^[a-zA-Z0-9]*$/|max:255',
            'register_email' => 'required|string|email|max:255|unique:customers,email|confirmed',
            'register_email_confirmation' => 'required|string|email|max:255',
            'company_name' => 'required|string|max:255',
            'tel_num' => 'required|numeric|min:7',
        ];
    }
    
    public function messages() {
        return [
			'first_name.regex' => "The first name  value can only contain alphanumeric characters.",
			'last_name.regex' => "The last name  value can only contain alphanumeric characters",
			'tel_num.required' => "Telephone number field is required.",
			'register_email.required' => "The email field is required.",
			'register_email_confirmation.required' => "The confirm email field is required.",
			'register_email.email' => "The email must be a valid email address.",
			'register_email_confirmation.email' => "The email must be a valid email address.",
			'register_email.max' => "The email may not be greater than 255 characters.",
			'register_email_confirmation.max' => "The email may not be greater than 255 characters.",
			'register_email.unique' => "The email has already been taken.",
			'register_email.confirmed' => "The email confirmation does not match.",
        ];
    }
}
