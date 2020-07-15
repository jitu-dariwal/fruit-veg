<?php

namespace App\Shop\Faqs\Requests;

use App\Shop\Base\BaseFormRequest;

class CreateFaqRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question' => ['required','max:255'],
            'answer' => ['required'],
            'status' => ['required'],
        ];
    }
}
