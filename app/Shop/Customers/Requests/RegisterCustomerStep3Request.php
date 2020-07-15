<?php

namespace App\Shop\Customers\Requests;

use App\Shop\Base\BaseFormRequest;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;



class RegisterCustomerStep3Request extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
		return [
            'company_name' => 'required|string|max:255',
            'line1' => 'required|string|max:255',
            'line2' => 'max:255',
            'line3' => 'max:255',
            'town' => 'required|string|max:255',
            'county' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'delivery_notes' => 'required|string',
            'delivery_window' => ['required']
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
	
	/**
	* Handle a failed validation attempt.
	*
	* @param  \Illuminate\Contracts\Validation\Validator $validator
	* @return void
	*
	* @throws \Illuminate\Validation\ValidationException
	*/
	
	protected function failedValidation(Validator $validator) {
		$errors = (new ValidationException($validator))->errors();
			
		throw new HttpResponseException(
		  response()->json([
			'status' => false,
			'messages' => $errors
		  ], 200)
		); 
	}	
}
