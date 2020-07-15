<?php

namespace App\Shop\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
                'first_name' => ['sometimes','required','string','regex:/^[a-zA-Z0-9]*$/','max:255'],
                'last_name' => ['sometimes','string','regex:/^[a-zA-Z0-9]*$/','max:255'],
                'tel_num' => ['sometimes','required','numeric'],
                'old_password' => [
					'sometimes',
					'string',
					'min:6',
					function ($attribute, $value, $fail) {
						if (!\Hash::check($value, \Auth::user()->password)) {
							return $fail(__('The current password is incorrect.'));
						}
					}
				],
                'password' => 'sometimes|required|string|min:6|regex:/^(?=.*[\w])(?=.*[\W])[\w\W]{6,}$/|confirmed',
                'credit_application_form' => ['mimes:pdf'],
                'email' => ['sometimes','required', 'email', Rule::unique('customers')->ignore($this->segment(3))]
        ];
    }
    
    public function messages()
    {
        return [
                   'password.regex' => "Password contains <li>At least one lowercase</li><li>At least one uppercase</li><li>At least one digit</li><li>At least one special character</li><li>At least it should have 6 characters long</li>",
                   'tel_num.required' => "Telephone number field is required."
		];
    }
    
	public function withValidator($validator)
	{
		$validator->after(function ($validator) {
			if($validator->errors()->count() > 0){
				session()->flash('warning','Please correct the errors.');
			}
		});
	}
}
