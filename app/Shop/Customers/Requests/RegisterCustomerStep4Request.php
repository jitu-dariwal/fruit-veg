<?php

namespace App\Shop\Customers\Requests;

use App\Shop\Base\BaseFormRequest;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;



class RegisterCustomerStep4Request extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
		return [
            'password' => 'required|string|min:6|regex:/^(?=.*[\w])(?=.*[\W])[\w\W]{6,}$/|confirmed',
            'term_cond' => 'required',
        ];
    }
	
    public function messages()
    {
        return [
			   'password.regex' => "Password contains <li>At least one lowercase</li><li>At least one uppercase</li><li>At least one digit</li><li>At least one special character</li><li>At least it should have 6 characters long</li>",
			   'term_cond.required' => "Privacy Policy field is required."
		];
    }
}
