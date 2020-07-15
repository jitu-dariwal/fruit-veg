<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Cashier\Cashier;
use Config;
use App\Helper\Generalfnv;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	  //require_once app_path() . '/Helper/Generalfnv.php';
	  $settings_data = Generalfnv::getConstantsfnv();
	  Config::set('constants.RECORDS_PER_PAGE', $settings_data->records_per_page);
	  Config::set('constants.DEFAULT_MINIMUM_ORDER', $settings_data->minimum_order);
	  Config::set('constants.TOTAL_DELIVERY_DAYS', $settings_data->total_delivery_days);
	  Config::set('constants.FACEBOOK_URL', $settings_data->fb_url);
	  Config::set('constants.TWITTER_URL', $settings_data->twitter_url);
	  Config::set('constants.YOUTUBE_URL', $settings_data->youtube_url);
	  Config::set('constants.COMPANY_ADDRESS', $settings_data->company_address);
	  
	  //parent categories to show into the footer
	  $parent_cats = Generalfnv::getParentCategories();
	  Config::set('constants.PARENTCATEGORIES', $parent_cats);
	  
	  //pages links to show into the footer
	  $site_links = Generalfnv::getPagesLinks();
	  Config::set('constants.SITELINKS', $site_links);
	  
	  Schema::defaultStringLength(191);
      Cashier::useCurrency(config('cart.currency'), config('cart.currency_symbol'));
	  
	  //google captcha
	  Validator::extend('recaptcha', 'App\Validators\ReCaptcha@validate');
	}

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      //
    }
}
