<?php

namespace App\Http\Controllers\Admin\Bankholidays;

use App\Http\Controllers\Controller;
use App\Shop\Bankholidays\Repositories\BankholidayRepository;
use App\Shop\Bankholidays\Repositories\BankholidayRepositoryInterface;
use App\Shop\Bankholidays\Requests\CreateBankholidayRequest;
use App\Shop\Bankholidays\Requests\UpdateBankholidayRequest;
use DB;
use App\Helper\Generalfnv;

class BankholidayController extends Controller
{
    /**
     * @var BrandRepositoryInterface
     */
    private $bankholidayRepo;
    private $permission;
    /**
     * BrandController constructor.
     *
     * @param BrandRepositoryInterface $brandRepository
     */
    public function __construct(BankholidayRepositoryInterface $bankholidayRepository, Generalfnv $per_check)
    {
        $this->bankholidayRepo = $bankholidayRepository;
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
                $is_allow = $this->permission->check_permission('view-reports');

                if(isset($is_allow) && $is_allow == 0) {

                    return view('admin.permissions.permission_denied');
                    exit;
                }
            // end permission
           
        //list holidays using procedure getholidays
        $holidays_data = DB::select("CALL `getBankholidays`");
		
	$record_per_page = config('constants.RECORDS_PER_PAGE');
        $data = $this->bankholidayRepo->paginateArrayResults($holidays_data, $record_per_page);
        //echo "<pre>";
        //print_r($data); exit;
        
	return view('admin.bankholidays.list', ['bankholidays' => $data]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        /*
            * check permission
            */ 
                $is_allow = $this->permission->check_permission('view-reports');

                if(isset($is_allow) && $is_allow == 0) {

                    return view('admin.permissions.permission_denied');
                    exit;
                }
        // end permission
                
        return view('admin.bankholidays.create');
    }

    /**
     * @param CreateBrandRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateBankholidayRequest $request)
    {
        $data = $request->all();
        
        $name = $data['name'];
        $holiday_date = date('Y-m-d H:i:s', strtotime($data['holiday_date']));
       
        
        DB::statement("CALL `createBankholiday`('".$name."','".$holiday_date."')");
       
        //$this->couponRepo->createCoupon($request->all());

        return redirect()->route('admin.bankholidays.index')->with('message', 'Create bank holiday successful!');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $holiday_data = DB::select("CALL `GetBankholidayById`('$id')");
		return view('admin.bankholidays.edit', ['bankholiday' => $holiday_data[0]]);
    }

    /**
     * @param UpdateBrandRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \App\Shop\Brands\Exceptions\UpdateBrandErrorException
     */
    public function update(UpdateBankholidayRequest $request, $id)
    {
       $data = $request->all();
        
        $name = $data['name'];
        $holiday_date = date('Y-m-d H:i:s', strtotime($data['holiday_date']));
        
        DB::statement("CALL `updateBankholiday`('".$id."','".$name."','".$holiday_date."')");

        return redirect()->route('admin.bankholidays.edit', $id)->with('message', 'Update successful!');
    }
    
     
    /**
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $holiday_data = $this->bankholidayRepo->findBankholidayById($id);
	$bankholidayRepo = new BankholidayRepository($holiday_data);
        $bankholidayRepo->deleteBankholiday();

        return redirect()->route('admin.bankholidays.index')->with('message', 'Delete successful!');
    }
}
