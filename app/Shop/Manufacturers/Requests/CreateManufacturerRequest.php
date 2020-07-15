<?php

namespace App\Shop\Manufacturers\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateManufacturerRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'unique:manufacturers']
        ];
    }
}
