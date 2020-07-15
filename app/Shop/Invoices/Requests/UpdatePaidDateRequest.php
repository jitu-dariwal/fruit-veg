<?php

namespace App\Shop\Invoices\Requests;

use App\Shop\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdatePaidDateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'paid_date' => ['required'],
            'invoice_id' => ['required']
        ];
    }
}
