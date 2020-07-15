<?php

namespace App\Shop\Invoices\Requests;

use App\Shop\Base\BaseFormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoiceid' => ['required', Rule::unique('invoices')->ignore($this->segment(3))],
            'status' => ['required']
        ];
    }
}
