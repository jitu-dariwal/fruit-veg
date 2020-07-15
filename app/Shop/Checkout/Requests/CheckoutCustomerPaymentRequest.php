<?php

namespace App\Shop\Checkout\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class CheckoutCustomerPaymentRequest extends FormRequest
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
		$validationArr = ['pay_by' => 'required'];
		
		if(isset($data['pay_by']) && $data['pay_by'] == 'PayByPaypal'){
			
		}else if(isset($data['pay_by']) && $data['pay_by'] == 'PayByInvoice'){
			$validationArr['pay_by_invoice'] = ['required'];
		}else if(isset($data['pay_by']) && $data['pay_by'] == 'PayByCard'){
			if($data['payBySaveCard'] == 'new'){
				$validationArr['name'] = ['required'];
				$validationArr['number'] = ['required','numeric','digits:16'];
				$validationArr['exp_month'] = ['required'];
				$validationArr['exp_year'] = ['required'];
				$validationArr['cvv'] = ['required'];
			}else{
				$validationArr['cvv_'.$data['payBySaveCard']] = ['required'];
			}
		}
		
		return $validationArr;
    }
    
    public function messages()
    {
        return [
			'cvv_*.required' => "The cvv field is required.",
			'pay_by_invoice.required' => "You must check the checkbox for pay by invoice.",
		];
    }
    
}
