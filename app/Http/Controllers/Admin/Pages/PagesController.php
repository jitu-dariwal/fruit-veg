<?php

namespace App\Http\Controllers\Admin\Pages;
use App\Shop\Tools\UploadableTrait;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use DB;
use App\Helper\Generalfnv;

class PagesController extends Controller
{
    use UploadableTrait;
    private $permission;
    
    public function __construct(Generalfnv $per_check)
    {
        $this->permission = $per_check;
    }
    
    /**
     * Display a listing of the pages.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        * check permission
        */ 
            $is_allow = $this->permission->check_permission('view-pages');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
		$record_per_page = config('constants.RECORDS_PER_PAGE');
		$pages = DB::table('pages')->where('type','page')->orderBy("id", "DESC")
					  ->paginate($record_per_page);
					 // $this->categoryRepo
		 return view('admin.pages.list', ['pages' => $pages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            /*
            * check permission
            */ 
                $is_allow = $this->permission->check_permission('create-page');

                if(isset($is_allow) && $is_allow == 0) {

                    return view('admin.permissions.permission_denied');
                    exit;
                }
            // end permission
                
		return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->all();
		
		$data['page_content'] = str_replace(url('/') ,"[app_url]", $data['page_content']);
		$data['meta_description'] = str_replace(url('/') ,"[app_url]", $data['meta_description']);
		
	   /*   if (empty($data['order_now_btn'])) {
			$data['order_now_btn'] = 0;
		}
		
		
		
		if (isset($data['banner_image']) && ($data['banner_image'] instanceof UploadedFile)) {
				$imagename = explode(".", $data['banner_image']->getClientOriginalName());
				$image_file = $this->uploadOne($data['banner_image'], 'pagesimages', 'uploads', $imagename[0]);
		} else {
			$image_file = '';
		}
		*/
	   
		DB::table('pages')->insert(
				['name' => $data['page_name'], 'title' => $data['page_title'], 'slug' => $data['page_slug'], 'content' => $data['page_content'], 'meta_title' => $data['meta_title'], 'meta_description' => $data['meta_description'], 'meta_keyword' => $data['meta_keyword'], 'created_at' => date('Y-m-d H:i:s')]
		);

		return redirect()->route('admin.pages.index')->with('message', 'Page created successfully');
    }

    
    /**
     * Show the form for editing the specified page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
            /*
            * check permission
            */ 
                $is_allow = $this->permission->check_permission('update-page');

                if(isset($is_allow) && $is_allow == 0) {

                    return view('admin.permissions.permission_denied');
                    exit;
                }
            // end permission
                
		$pages = DB::table('pages')
                                        ->where('id', $id)
                                        ->first();
		
		return view('admin.pages.edit', ['pages' => $pages]);
    }
	
	/**
     * Update the specified resource in storage.
     *
     * @param  UpdateCustomerRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $data = request()->all();
		
		$data['page_content'] = str_replace(url('/') ,"[app_url]", $data['page_content']);
		$data['meta_description'] = str_replace(url('/') ,"[app_url]", $data['meta_description']);
		
        /* if (empty($data['order_now_btn'])) {
            $data['order_now_btn'] = 0;
        }
        
        if (isset($data['banner_image']) && ($data['banner_image'] instanceof UploadedFile)) {
                    
                $imagename = explode(".", $data['banner_image']->getClientOriginalName());
                $image_file = $this->uploadOne($data['banner_image'], 'pagesimages', 'uploads', $imagename[0]);
                DB::table('pages')->where('id', $id)
                                  ->update(['banner_image' => $image_file]);     
                    
        } */
        
        //update group data
        DB::table('pages')->where('id', $id)
                          ->update(
                            ['name' => $data['page_name'], 'title' => $data['page_title'], 'slug' => $data['page_slug'], 
                            'content' => $data['page_content'], 'meta_title' => $data['meta_title'], 'meta_description' => $data['meta_description'], 
                            'meta_keyword' => $data['meta_keyword']]);


        return redirect()->route('admin.pages.index')->with('message', 'Page updated successfully');
    }
	
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        /*
        * check permission
        */ 
            $is_allow = $this->permission->check_permission('delete-page');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
                
        DB::table('pages')->where('id', '=', $id)->delete();
        return redirect()->route('admin.pages.index')->with('message', 'Page Deleted successfully');
    }

    /**
     * Show Dashboard Section listing of frontend.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function dashboardSections()
    {
        /*
        * check permission
        */ 
            $is_allow = $this->permission->check_permission('view-page');

            if(isset($is_allow) && $is_allow == 0) {
                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
                
        $record_per_page = config('constants.RECORDS_PER_PAGE');
		$pages = DB::table('pages')->where('type', 'dashboard_section')->orderBy("id", "DESC")->paginate($record_per_page);
		
		return view('admin.pages.dashboard_sections', compact(['pages']));
    }
	
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboardSectionCreate()
    {
		/*
		* check permission
		*/ 
			$is_allow = $this->permission->check_permission('view-page');

			if(isset($is_allow) && $is_allow == 0) {
				return view('admin.permissions.permission_denied');
				exit;
			}
		// end permission
                
		return view('admin.pages.create_dashboard_section');
    }
	
    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateDashboardSectionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function dashboardSectionSave()
    {
        $data = request()->all();
		
		$data['page_content'] = str_replace(url('/') ,"[app_url]", $data['page_content']);
		  
		if (isset($data['banner_image']) && ($data['banner_image'] instanceof UploadedFile)) {
				$imagename = explode(".", $data['banner_image']->getClientOriginalName());
				$image_file = $this->uploadOne($data['banner_image'], 'pagesimages', 'uploads', $imagename[0]);
		} else {
			$image_file = '';
		}
	   
		$page = [
			'type' => 'dashboard_section',
			'name' => $data['page_name'],
			'title' => $data['page_title'],
			'content' => $data['page_content'],
			'banner_image' => $image_file,
			'banner_text' => $data['banner_text'],
			'created_at' => date('Y-m-d H:i:s')
		];
		
		DB::table('pages')->insert($page);

		return redirect()->route('admin.dashboardSections')->with('message', 'Dashboard Section created successfully');
    }
	
    /**
     * Show the form for editing the specified page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function dashboardSectionEdit($id)
    {
		/*
		* check permission
		*/ 
			$is_allow = $this->permission->check_permission('view-page');

			if(isset($is_allow) && $is_allow == 0) {
				return view('admin.permissions.permission_denied');
				exit;
			}
		// end permission
                
		$pages = DB::table('pages')->where('id', $id)->first();
		
		return view('admin.pages.edit_dashboard_section', ['pages' => $pages]);
    }
	
	/**
     * Update the specified resource in storage.
     *
     * @param  UpdateDashboardSectionRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function dashboardSectionUpdate($id)
    {
        $data = request()->all();
		
		$data['page_content'] = str_replace(url('/') ,"[app_url]", $data['page_content']);
		
		$page = [
			'name' => $data['page_name'],
			'title' => $data['page_title'],
			'content' => $data['page_content'],
			'banner_text' => $data['banner_text']
		];
		
        if (isset($data['banner_image']) && ($data['banner_image'] instanceof UploadedFile)) {        
			$imagename = explode(".", $data['banner_image']->getClientOriginalName());
			$page['banner_image'] = $this->uploadOne($data['banner_image'], 'pagesimages', 'uploads', $imagename[0]);
        }
        
        //update group data
        DB::table('pages')->where('id', $id)->update($page);
		
        return redirect()->route('admin.dashboardSections')->with('message', 'Dashboard Section updated successfully');
    }
}
