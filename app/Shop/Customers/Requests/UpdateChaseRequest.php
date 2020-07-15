<?php

namespace App\Shop\Customers\Requests;

use App\Shop\Base\BaseFormRequest;

class UpdateChaseRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'update_chase' => ['required']
        ];
    }
}
