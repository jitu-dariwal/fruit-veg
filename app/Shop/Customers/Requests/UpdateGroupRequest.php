<?php

namespace App\Shop\Customers\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use DB;

class UpdateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
	
		 $categories_records = DB::table('categories')
								->select('id','name')
								->where('parent_id', 1)
								->get();
				$valid_field = array();				
		//	foreach ($categories_records as $categories_record) {
			
           // $valid_field[] = "'bulk_'".$categories_record['id']." => ['required'],";
			//$valid_field[] = "'split_'".$categories_record['id']." => ['required'],";
			//'split_'.$categories_record['id'] => ['required']
				///$valid_field = [ $categories_record->name.$categories_record->id => ['required'], ];
				//return $valid_field;
			//}
			//return $valid_field[];
			//print_r($validation_obj); exit;
			/*foreach($valid_field as $key=>$value) {
			return $value[$key];
			}
			*/
			//echo "<pre>"; print_r($valid_field); exit;
					
        //return [
			
           // 'bulk_1' => ['required'],
			
       // ];
    }
}
