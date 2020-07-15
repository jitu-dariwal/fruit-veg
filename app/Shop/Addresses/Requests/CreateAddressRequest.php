<?php

namespace App\Shop\Addresses\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateAddressRequest extends BaseFormRequest
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
            'company_name' => ['required'],
            'street_address' => ['required'],
            'post_code' => ['required', 'regex:/[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}/i', 'min:5'],
            'city' => ['required'],
            'county_state' => ['required'],
            'delivery_window' => ['required']
        ];
    }
}
