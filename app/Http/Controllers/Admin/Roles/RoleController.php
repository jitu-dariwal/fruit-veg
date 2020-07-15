<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Controller;
use App\Shop\Permissions\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Shop\Roles\Repositories\RoleRepository;
use App\Shop\Roles\Repositories\RoleRepositoryInterface;
use App\Shop\Roles\Requests\CreateRoleRequest;
use App\Shop\Roles\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use DB;
use App\Helper\Generalfnv;

class RoleController extends Controller
{
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepo;

    /**
     * @var PermissionRepositoryInterface
     */
    private $permissionRepository;

    /**
     * RoleController constructor.
     *
     * @param RoleRepositoryInterface $roleRepository
     * @param PermissionRepositoryInterface $permissionRepository
     */
    public function __construct(
        RoleRepositoryInterface $roleRepository,
        PermissionRepositoryInterface $permissionRepository,
        Generalfnv $per_check
    ) {
        $this->roleRepo = $roleRepository;
        $this->permissionRepository = $permissionRepository;
        $this->permission = $per_check;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $list = $this->roleRepo->listRoles('name', 'asc')->all();

        $roles = $this->roleRepo->paginateArrayResults($list);

        return view('admin.roles.list', compact('roles'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * @param CreateRoleRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateRoleRequest $request)
    {
        $this->roleRepo->createRole($request->except('_method', '_token'));

        return redirect()->route('admin.roles.index')
            ->with('message', 'Create role successful!');
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $role = $this->roleRepo->findRoleById($id);
        
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * @param UpdateRoleRequest $request
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(UpdateRoleRequest $request, $id)
    {
      $role = $this->roleRepo->findRoleById($id);
      $roleRepo = new RoleRepository($role);
      
      $roleRepo->updateRole($request->except('_method', '_token'));

      return redirect()->route('admin.roles.edit', $id)
            ->with('message', 'Update role successful!');
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
        $role = $this->roleRepo->findRoleById($id);
        $roleRepo = new RoleRepository($role);
        $roleRepo->deleteRole();

        return redirect()->route('admin.roles.index')->with('message', 'Delete successful');
    }
    
     /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function managePermissions()
    {
        /*
        * check permission
        */ 
        $is_allow = $this->permission->check_permission();
        
        if(isset($is_allow) && $is_allow == 0) {
            
            return view('admin.permissions.permission_denied');
            exit;
        }
       // end permission
        
        $roles = $this->roleRepo->listRoles('id', 'desc')->where('name','!=','superadmin')->all();        
        $permissions = $this->permissionRepository->listPermissions(['*'], 'name', 'asc');
        
        $role_perm = DB::table('permission_role')->get();
        
        $active_perm = array();
        foreach ($role_perm as $rp){
            $active_perm[$rp->role_id][$rp->permission_id] = 1;
        }
        
        $perms = array();
        foreach ($permissions as $p){
            $perms[$p->module][$p->name] = $p;
        }
        $totoal_roles = count($roles);
        $grid_perc = (int) (80 / $totoal_roles);
        return view('admin.roles.permissions', compact('roles','permissions','perms','active_perm','grid_perc'));
    }
    
    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function savePermissions(Request $request){
        
        $inputs = $request->all();
        DB::table('permission_role')->truncate(); 
        
        if(isset($inputs['perm'])){          
            $permissions = $inputs['perm'];
            
            foreach($permissions as $roleId => $perm){
                $permission_ids = array_keys($perm);
                
                if (!empty($permission_ids)) {
                    $role = $this->roleRepo->findRoleById($roleId);
                    $roleRepo = new RoleRepository($role);
                    $roleRepo->syncPermissions($permission_ids);
                }
            }            
        }
	  
        return redirect()->route('admin.roles.permissions')
                ->with('message', 'Permissions has been saved successfully!');
    }
    
}
