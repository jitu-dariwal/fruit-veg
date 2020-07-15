<?php

namespace App\Http\Controllers\Admin\Manufacturers;

use App\Http\Controllers\Controller;
use App\Shop\Manufacturers\Repositories\ManufacturerRepository;
use App\Shop\Manufacturers\Repositories\ManufacturerRepositoryInterface;
use App\Shop\Manufacturers\Requests\CreateManufacturerRequest;
use App\Shop\Manufacturers\Requests\UpdateManufacturerRequest;

class ManufacturerController extends Controller
{
    /**
     * @var BrandRepositoryInterface
     */
    private $manufacturerRepo;

    /**
     * BrandController constructor.
     *
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(ManufacturerRepositoryInterface $manufacturerRepository)
    {
        $this->manufacturerRepo = $manufacturerRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
		$record_per_page = config('constants.RECORDS_PER_PAGE');
        $data = $this->manufacturerRepo->paginateArrayResults($this->manufacturerRepo->listManufacturers(['*'], 'name', 'asc')->all(), $record_per_page);
		return view('admin.manufacturers.list', ['manufacturers' => $data]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
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
        $manufacturer = $this->manufacturerRepo->findManufacturerById($id);

        $manufacturerRepo = new ManufacturerRepository($manufacturer);
        $manufacturerRepo->deleteManufacturer();

        return redirect()->route('admin.manufacturers.index')->with('message', 'Delete successful!');
    }
}
