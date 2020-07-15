<?php

namespace App\Http\Controllers\Admin\Faq;

use App\Http\Controllers\Controller;
use App\Shop\Faqs\Repositories\FaqRepository;
use App\Shop\Faqs\Repositories\FaqRepositoryInterface;
use App\Shop\Faqs\Requests\CreateFaqRequest;
use App\Shop\Faqs\Requests\UpdateFaqRequest;
use App\Helper\Generalfnv;
use App\Shop\Faqs\Faq;

class FaqController extends Controller
{
    /**
     * @var FaqRepositoryInterface
     */
    private $faqRepo;
    private $permission;
    
	/**
     * BrandController constructor.
     *
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(FaqRepositoryInterface $faqRepository, Generalfnv $per_check)
    {
        $this->faqRepo = $faqRepository;
        $this->permission = $per_check;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        /*
		* check permission
		*/ 
		$is_allow = $this->permission->check_permission('view-faqs');

		if(isset($is_allow) && $is_allow == 0) {

			return view('admin.permissions.permission_denied');
			exit;
		}
        // end permission
            
        $record_per_page = config('constants.RECORDS_PER_PAGE');
        
        if (request()->has('q') && request()->input('q') != '') {
            $searchValue = request()->input('q');
            $list = Faq::where('question', "LIKE", '%'.$searchValue.'%')->paginate($record_per_page);
        }else{
			$list = Faq::paginate($record_per_page);
		}
        
        return view('admin.faqs.list', ['faqs' => $list]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        /*
		* check permission
		*/ 
		$is_allow = $this->permission->check_permission('create-faq');

		if(isset($is_allow) && $is_allow == 0) {

			return view('admin.permissions.permission_denied');
			exit;
		}
        // end permission
            
        return view('admin.faqs.create');
    }

    /**
     * @param CreateFaqRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateFaqRequest $request)
    {
        $this->faqRepo->createFaq($request->all());

        return redirect()->route('admin.faq.index')->with('message', 'Create faq successful!');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        /*
		* check permission
		*/ 
		$is_allow = $this->permission->check_permission('update-faq');

		if(isset($is_allow) && $is_allow == 0) {

			return view('admin.permissions.permission_denied');
			exit;
		}
        // end permission
            
        return view('admin.faqs.edit', ['faq' => $this->faqRepo->findFaqById($id)]);
    }

    /**
     * @param UpdateBrandRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Shop\Brands\Exceptions\UpdateBrandErrorException
     */
    public function update(UpdateFaqRequest $request, $id)
    {
        $faq = $this->faqRepo->findFaqById($id);

        $faqRepo = new FaqRepository($faq);
        $faqRepo->updateFaq($request->all());

        return redirect()->route('admin.faq.edit', $id)->with('message', 'Update successful!');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        /*
		* check permission
		*/ 
		$is_allow = $this->permission->check_permission('delete-faq');

		if(isset($is_allow) && $is_allow == 0) {

			return view('admin.permissions.permission_denied');
			exit;
		}
        // end permission
            
        $faq = $this->faqRepo->findFaqById($id);
		$faqRepo = new FaqRepository($faq);
        $faqRepo->deleteFaq();

        return redirect()->route('admin.faq.index')->with('message', 'Delete successful!');
    }
}
