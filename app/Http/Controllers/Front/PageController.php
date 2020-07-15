<?php

namespace App\Http\Controllers\Front;

use DB;
use App\Shop\Categories\Category;
use App\Http\Controllers\Controller;
use App\Shop\ContactUs\ContactUsRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendEmailtoAdminOnNewEnquiry;
use App\Mail\sendEmailtoCustomers;

class PageController extends Controller
{
   
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($page_slug)
    {
        $page_details = DB::table('pages')->where('slug',$page_slug)->first();
		
		return view('front.pages.index', compact('page_details'));
    }
	
    /**
	 * Contact us details and form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contactUs()
    {
		return view('front.pages.contact_us', compact(''));
    }
	
    /**
	 * Save contact us form
     * @redirect to the form
     */
    public function saveContactUs(ContactUsRequest $request)
    {
        $data = $request->all();
		
		$setting_data_obj = DB::select("CALL `getSettingData`");
		$setting_data = $setting_data_obj[0];
		
		Mail::to($setting_data->admin_email)->send(new sendEmailtoAdminOnNewEnquiry($data));
		
		return redirect()->route('page.contactUs')->with('status', 'Thank you for enquiry with us.');
    }
	
    public function sitemap(){
		$categories = Category::where(['parent_id' => 0, 'status' => 1])->get();
		
		return view('front.pages.sitemap', compact('categories'));
	}
}
