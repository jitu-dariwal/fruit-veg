<?php

namespace App\Http\Controllers\Admin;

use App\Shop\Admins\Requests\CreateEmployeeRequest;
use App\Shop\Admins\Requests\UpdateEmployeeRequest;
use App\Shop\Employees\Repositories\EmployeeRepository;
use App\Shop\Employees\Repositories\Interfaces\EmployeeRepositoryInterface;
use App\Shop\Roles\Repositories\RoleRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;
use App\Helper\Generalfnv;

class EmployeeController extends Controller
{
    /**
     * @var EmployeeRepositoryInterface
     */
    private $employeeRepo;
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepo;

    /**
     * EmployeeController constructor.
     *
     * @param EmployeeRepositoryInterface $employeeRepository
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(
        EmployeeRepositoryInterface $employeeRepository,
        RoleRepositoryInterface $roleRepository,
        Generalfnv $per_check
    ) {
        $this->employeeRepo = $employeeRepository;
        $this->roleRepo = $roleRepository;
        $this->permission = $per_check;
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       /*
        * check permission
        */ 
        $is_allow = $this->permission->check_permission('view-admin');
        
        if(isset($is_allow) && $is_allow == 0) {
            
            return view('admin.permissions.permission_denied');
            exit;
        }
       // end permission
       
        
        $list = $this->employeeRepo->listEmployees('created_at', 'desc');
        $record_per_page = config('constants.RECORDS_PER_PAGE');
		
        if (request()->has('q')) {
            $list = $this->employeeRepo->searchEmployee(request()->input('q'));
        }
		
		
		
        return view('admin.employees.list', [
            'employees' => $this->employeeRepo->paginateArrayResults($list->all(), $record_per_page)
        ])->with('i', (request()->input('page', 1) - 1) * $record_per_page);
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
        $is_allow = $this->permission->check_permission('create-admin');
        
        if(isset($is_allow) && $is_allow == 0) {
            
            return view('admin.permissions.permission_denied');
            exit;
        }
       // end permission
        
        $roles = $this->roleRepo->listRoles('id', 'desc')->where('name','!=','superadmin');

        return view('admin.employees.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateEmployeeRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateEmployeeRequest $request)
    {
        $employee = $this->employeeRepo->createEmployee($request->all());

        if ($request->has('role')) {
            $employeeRepo = new EmployeeRepository($employee);
            $employeeRepo->syncRoles([$request->input('role')]);
        }

        return redirect()->route('admin.employees.index')->with('message', "User Created Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
         /*
        * check permission
        */ 
        $is_allow = $this->permission->check_permission('view-admin');
        
        if(isset($is_allow) && $is_allow == 0) {
            
            return view('admin.permissions.permission_denied');
            exit;
        }
       // end permission
        
        $employee = $this->employeeRepo->findEmployeeById($id);
        return view('admin.employees.show', ['employee' => $employee]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
         /*
        * check permission
        */ 
        $is_allow = $this->permission->check_permission('update-admin');
        
        if(isset($is_allow) && $is_allow == 0) {
            
            return view('admin.permissions.permission_denied');
            exit;
        }
       // end permission
        
        $employee = $this->employeeRepo->findEmployeeById($id);
        $roles = $this->roleRepo->listRoles('id', 'desc')->where('name','!=','superadmin');
        $isCurrentUser = $this->employeeRepo->isAuthUser($employee);

        return view(
            'admin.employees.edit',
            [
                'employee' => $employee,
                'roles' => $roles,
                'isCurrentUser' => $isCurrentUser,
                'selectedIds' => $employee->roles()->pluck('role_id')->all()
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEmployeeRequest $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, $id)
    {
		
        $employee = $this->employeeRepo->findEmployeeById($id);
        $isCurrentUser = $this->employeeRepo->isAuthUser($employee);

        $empRepo = new EmployeeRepository($employee);
        $empRepo->updateEmployee($request->except('_token', '_method', 'password'));

        if ($request->has('password') && !empty($request->input('password'))) {
            $employee->password = Hash::make($request->input('password'));
            $employee->save();
        }
		
		if ($request->has('role') and !$isCurrentUser) { 
            $employeeRepo = new EmployeeRepository($employee);
            $employeeRepo->syncRoles([$request->input('role')]);
        }

      /*  if ($request->has('role') and !$isCurrentUser) {
            $employee->roles()->sync($request->input('roles'));
        } elseif (!$isCurrentUser) {
            $employee->roles()->detach();
        } */

        return redirect()->route('admin.employees.edit', $id)
            ->with('message', 'Update successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(int $id)
    {
        //$employee = $this->employeeRepo->findEmployeeById($id);
       // $employeeRepo = new EmployeeRepository($employee);
        //$employeeRepo->deleteEmployee();
       /*
        * check permission
        */ 
        $is_allow = $this->permission->check_permission('delete-admin');
        
        if(isset($is_allow) && $is_allow == 0) {
            
            return view('admin.permissions.permission_denied');
            exit;
        }
       // end permission
         
        
        DB::table('employees')->where('id', '=', $id)->delete();

        return redirect()->route('admin.employees.index')->with('message', 'Delete successful');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getProfile($id)
    {
        $employee = $this->employeeRepo->findEmployeeById($id);
        return view('admin.employees.profile', ['employee' => $employee]);
    }

    /**
     * @param UpdateEmployeeRequest $request
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(UpdateEmployeeRequest $request, $id)
    {	
		
        $this->updateEmployee($request, $id);

        $request->session()->flash('message', 'Update successful');
        return redirect()->route('admin.employee.profile', $id);
    }

    /**
     * @param UpdateEmployeeRequest $request
     * @param $id
     */
    private function updateEmployee(UpdateEmployeeRequest $request, $id)
    {
        $employee = $this->employeeRepo->findEmployeeById($id);

        $update = new EmployeeRepository($employee);
	if ($request->has('password') && !empty($request->input('password'))) {
            $employee->password = Hash::make($request->input('password'));
            $employee->save();
        }
        $update->updateEmployee($request->except('_token', '_method', 'password'));
    }
}
