<?php

namespace App\Http\Controllers\Front;

use DB;
use App\Helper\Finder;
use App\Shop\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Shop\Products\Transformations\ProductTransformable;

class HomeController extends Controller
{
    use ProductTransformable;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepo;

    /**
     * HomeController constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $cat = DB::table('categories')->where(['parent_id' => 0])->get();
		
		$dashboardSections = Finder::getDashboardSectionsData();
		
        return view('front.index', compact('cat','dashboardSections'));
    }
	
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function faq()
    {
        $faqs = DB::table('faqs')->where(['status' => 1])->get();
		return view('front.faq', compact('faqs'));
    }
}
