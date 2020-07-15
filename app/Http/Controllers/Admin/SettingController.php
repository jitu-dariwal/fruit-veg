<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Helper\Generalfnv;

class SettingController extends Controller
{
    /**
     * update settings form.
     *
     * @return \Illuminate\Http\Response
     */
        private $permission;
        
        public function __construct(
            Generalfnv $per_check
        ) {
            $this->permission = $per_check;
       }
	
	public function create(){
		/*
		* check permission
		*/ 
			$is_allow = $this->permission->check_permission('site-setting');

			if(isset($is_allow) && $is_allow == 0) {

				return view('admin.permissions.permission_denied');
				exit;
			}
		// end permission
		
		$setting_data_obj = DB::select("CALL `getSettingData`");
		$setting_data = $setting_data_obj[0];
		return view('admin.setting.edit', compact('setting_data'));
	}
	
	/**
     * update settings data.
     *
     * @return \Illuminate\Http\Response
     */
	public function update($id){
		/*
		* check permission
		*/ 
			$is_allow = $this->permission->check_permission('site-setting');

			if(isset($is_allow) && $is_allow == 0) {

				return view('admin.permissions.permission_denied');
				exit;
			}
		// end permission
			
		$data = request()->all();
		
		$urlRgx = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
		
		$validator = Validator::make($data, [
			'records_per_page' => 'required|numeric',
			'minimum_order' => 'required|numeric',
			'total_delivery_days' => 'required|numeric',
			'admin_email' => 'required|string|email|max:50',
			'fb_url' => 'required|max:191|regex:'.$urlRgx,
			'twitter_url' => 'required|max:191|regex:'.$urlRgx,
			'youtube_url' => 'required|max:191|regex:'.$urlRgx,
			'company_address' => 'required',
		]);
        
		if ($validator->fails()) {
			return redirect()->route('admin.settings.create')->withErrors($validator)->withInput($data)->with('error', 'Please correct errors.');
		}else{
			
			DB::statement("CALL `updateSettingData`('".$id."', '".$data['records_per_page']."', '".$data['minimum_order']."', '".$data['total_delivery_days']."', '".$data['admin_email']."', '".$data['fb_url']."', '".$data['twitter_url']."', '".$data['youtube_url']."', '". $data['company_address']."')");
				
			return redirect()->route('admin.settings.create')->with('message', 'Update successful');
		}
	}
}
