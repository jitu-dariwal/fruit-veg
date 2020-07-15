<?php

namespace App\Shop\PostCodes\Requests;

use App\Shop\Base\BaseFormRequest;

class UpdatePostCodeRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required','max:255'],
            'week_days' => ['required'],
            'post_codes' => ['required'],
            'status' => ['required'],
        ];
    }
}
