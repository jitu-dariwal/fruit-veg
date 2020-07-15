<?php

namespace App\Shop\OrderDetails\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateOrderDetailRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'email'],
            'company_name' => ['required'],
            'street_address' => ['required'],
            'post_code' => ['required'],
            'city' => ['required'],
            'country_state' => ['required'],
            'tel_num' => ['required'],
        ];
    }
}
