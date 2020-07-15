<?php

namespace App\Shop\ContactUs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ContactUsRequest extends FormRequest
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
			'name' => ['sometimes','required','string','max:255'],
			'email' => ['sometimes','required','string','email','max:255'],
			'tel_num' => ['sometimes','required','numeric'],
			'subject' => ['sometimes','required','string','max:255'],
			'enquiry' => ['sometimes','required'],
			'g-recaptcha-response' => ['required','recaptcha']
        ];
    }
    
    public function messages()
    {
        return [
		   'tel_num.required' => "Telephone number field is required.",
		   'g-recaptcha-response.required' => "reCaptcha field is required.",
		   'g-recaptcha-response.recaptcha' => "reCaptcha select.",
		];
    }
}
