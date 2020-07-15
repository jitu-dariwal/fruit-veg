<?php

namespace App\Http\Controllers\Admin\PostCode;

use App\Http\Controllers\Controller;
use App\Shop\PostCodes\Repositories\PostCodeRepository;
use App\Shop\PostCodes\Repositories\PostCodeRepositoryInterface;
use App\Shop\PostCodes\PostCode;
use App\Shop\PostCodes\Requests\CreatePostCodeRequest;
use App\Shop\PostCodes\Requests\UpdatePostCodeRequest;
use App\Helper\Generalfnv;

class PostCodeController extends Controller
{
    /**
     * @var FaqRepositoryInterface
     */
    private $postCodeRepo;
    private $permission;
    
	/**
     * BrandController constructor.
     *
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(PostCodeRepositoryInterface $postCodeRepository, Generalfnv $per_check)
    {
        $this->postCodeRepo = $postCodeRepository;
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
		$is_allow = $this->permission->check_permission('view-postcode');

		if(isset($is_allow) && $is_allow == 0) {

			return view('admin.permissions.permission_denied');
			exit;
		}
        // end permission
            
        $record_per_page = config('constants.RECORDS_PER_PAGE');
        
        if (request()->has('q') && request()->input('q') != '') {
            $searchValue = request()->input('q');
            $list = PostCode::where('title', "LIKE", '%'.$searchValue.'%')->paginate($record_per_page);
        }else{
			$list = PostCode::paginate($record_per_page);
		}
        
        return view('admin.post-codes.list', ['postCodes' => $list]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        /*
		* check permission
		*/ 
		$is_allow = $this->permission->check_permission('create-postcode');

		if(isset($is_allow) && $is_allow == 0) {

			return view('admin.permissions.permission_denied');
			exit;
		}
        // end permission
            
        return view('admin.post-codes.create');
    }

    /**
     * @param CreateFaqRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreatePostCodeRequest $request)
    {
		$data = $request->all();
		
		if(array_key_exists('week_days',$data) && !empty($data['week_days']))
			$data['week_days'] = implode(',',$data['week_days']);
		
        $this->postCodeRepo->createPostCode($data);

        return redirect()->route('admin.post-code.index')->with('message', 'Create post code successful!');
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
		$is_allow = $this->permission->check_permission('update-postcode');

		if(isset($is_allow) && $is_allow == 0) {

			return view('admin.permissions.permission_denied');
			exit;
		}
        // end permission
            
        return view('admin.post-codes.edit', ['postCode' => $this->postCodeRepo->findPostCodeById($id)]);
    }

    /**
     * @param UpdateBrandRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Shop\Brands\Exceptions\UpdateBrandErrorException
     */
    public function update(UpdatePostCodeRequest $request, $id)
    {
        $data = $request->all();
		
		if(array_key_exists('week_days',$data) && !empty($data['week_days']))
			$data['week_days'] = implode(',',$data['week_days']);
		
        $postCode = $this->postCodeRepo->findPostCodeById($id);
		
		$postCodeRepo = new postCodeRepository($postCode);
        $postCodeRepo->updatePostCode($data);

        return redirect()->route('admin.post-code.edit', $id)->with('message', 'Update successful!');
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
		$is_allow = $this->permission->check_permission('delete-postcode');

		if(isset($is_allow) && $is_allow == 0) {

			return view('admin.permissions.permission_denied');
			exit;
		}
        // end permission
            
        $postCode = $this->postCodeRepo->findPostCodeById($id);
		$postCodeRepo = new PostCodeRepository($postCode);
        $postCodeRepo->deletePostCode();

        return redirect()->route('admin.post-code.index')->with('message', 'Delete successful!');
    }
}
