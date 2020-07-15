<?php

namespace App\Shop\Invoices\Requests;

use App\Shop\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateRemittanceRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'remittance' => ['required'],
            'invoice_id' => ['required']
        ];
    }
}
