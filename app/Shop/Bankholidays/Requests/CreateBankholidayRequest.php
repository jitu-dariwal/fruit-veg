<?php

namespace App\Shop\Bankholidays\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateBankholidayRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'holiday_date' => ['required']
        ];
    }
}
