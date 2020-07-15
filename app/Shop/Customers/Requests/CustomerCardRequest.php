<?php

namespace App\Shop\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class CustomerCardRequest extends FormRequest
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
    public function rules(Request $request)
    {
		$data = $request->all();
		//pr($data);die;
		
        return [
			'name' => ['sometimes','required'],
			'number' => ['sometimes','required','numeric','digits:16','unique:customers_pay360_tokens,pay360_token,'.$data['id'].',id,customers_id,'.\Auth::user()->id],
			'exp_month' => ['sometimes','required'],
			'exp_year' => ['sometimes','required'],
        ];
    }
    
    public function messages()
    {
        return [
		   'tel_num.required' => "Telephone number field is required."
		];
    }
}
