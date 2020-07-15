<?php

namespace App\Shop\Coupons\Requests;

use App\Shop\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'coupon_amount' => ['required'],
            'coupon_code' => ['required', Rule::unique('coupons')->ignore($this->segment(3))],
            'coupon_start_date' => ['required'],
            'coupon_expire_date' => ['required']
        ];
    }
}
