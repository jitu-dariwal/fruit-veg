<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api\V1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
	Route::post('ipadwebservices', 'WebserviceipadController@ipadwebservice');
	Route::get('ipadapis', 'WebserviceipadController@ipadapis');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/* Function for print array in formated form */
if(!function_exists('pr')){
	function pr($array){
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
}
	
/* Function for print query log */
if(!function_exists('qLog')){
	DB::enableQueryLog();
	function qLog(){
		pr(DB::getQueryLog());
	}
}
