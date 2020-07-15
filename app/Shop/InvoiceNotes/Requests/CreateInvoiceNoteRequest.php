<?php

namespace App\Shop\InvoiceNotes\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateInvoiceNoteRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => ['required'],
            'invoice_id' => ['required'],
            'notes' => ['required']
        ];
    }
}
