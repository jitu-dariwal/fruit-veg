<?php

namespace App\Http\Controllers\Admin\Manufacturers;

use App\Http\Controllers\Controller;
use App\Shop\Manufacturers\Repositories\ManufacturerRepository;
use App\Shop\Manufacturers\Repositories\ManufacturerRepositoryInterface;
use App\Shop\Manufacturers\Requests\CreateManufacturerRequest;
use App\Shop\Manufacturers\Requests\UpdateManufacturerRequest;
use App\Helper\Generalfnv;

class ManufacturerController extends Controller
{
    /**
     * @var BrandRepositoryInterface
     */
    private $manufacturerRepo;
    private $permission;
    /**
     * BrandController constructor.
     *
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(ManufacturerRepositoryInterface $manufacturerRepository, Generalfnv $per_check)
    {
        $this->manufacturerRepo = $manufacturerRepository;
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
            $is_allow = $this->permission->check_permission('view-manufacturer');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $record_per_page = config('constants.RECORDS_PER_PAGE');
        
        if (request()->has('q') && request()->input('q') != '') {
            
            $searchValue = request()->input('q');
            $list = $this->manufacturerRepo->listManufacturers(['*'], 'name', 'asc')->where('name', "=", $searchValue)->all();
        } else {
            
            $list = $this->manufacturerRepo->listManufacturers(['*'], 'name', 'asc')->all();
        }
        
        $data = $this->manufacturerRepo->paginateArrayResults($list, $record_per_page);
        return view('admin.manufacturers.list', ['manufacturers' => $data]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        /*
            * check permission
            */ 
            $is_allow = $this->permission->check_permission('create-manufacturer');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        return view('admin.manufacturers.create');
    }

    /**
     * @param CreateBrandRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateManufacturerRequest $request)
    {
        $this->manufacturerRepo->createManufacturer($request->all());

        return redirect()->route('admin.manufacturers.index')->with('message', 'Create manufacturer successful!');
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
            $is_allow = $this->permission->check_permission('update-manufacturer');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        return view('admin.manufacturers.edit', ['manufacturer' => $this->manufacturerRepo->findManufacturerById($id)]);
    }

    /**
     * @param UpdateBrandRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Shop\Brands\Exceptions\UpdateBrandErrorException
     */
    public function update(UpdateManufacturerRequest $request, $id)
    {
        $manufacturer = $this->manufacturerRepo->findManufacturerById($id);

        $manufacturerRepo = new ManufacturerRepository($manufacturer);
        $manufacturerRepo->updateManufacturer($request->all());

        return redirect()->route('admin.manufacturers.edit', $id)->with('message', 'Update successful!');
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
            $is_allow = $this->permission->check_permission('delete-manufacturer');

            if(isset($is_allow) && $is_allow == 0) {

                return view('admin.permissions.permission_denied');
                exit;
            }
        // end permission
            
        $manufacturer = $this->manufacturerRepo->findManufacturerById($id);
		$manufacturerRepo = new ManufacturerRepository($manufacturer);
        $manufacturerRepo->deleteManufacturer();

        return redirect()->route('admin.manufacturers.index')->with('message', 'Delete successful!');
    }
}
