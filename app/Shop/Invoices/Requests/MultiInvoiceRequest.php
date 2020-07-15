<?php

namespace App\Shop\Invoices\Requests;

use App\Shop\Base\BaseFormRequest;

class MultiInvoiceRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoiceid' => ['required'],
            'status' => ['required']
        ];
    }
}
