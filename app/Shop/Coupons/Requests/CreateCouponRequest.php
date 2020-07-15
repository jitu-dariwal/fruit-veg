<?php

namespace App\Shop\Coupons\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateCouponRequest extends BaseFormRequest
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
            'coupon_code' => ['required', 'unique:coupons'],
            'coupon_start_date' => ['required'],
            'coupon_expire_date' => ['required']
        ];
    }
}
