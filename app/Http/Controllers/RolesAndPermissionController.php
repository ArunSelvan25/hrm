<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\{Permission, Role};

class RolesAndPermissionController extends Controller
{
    /**
     * @var Role
     */
    private Role $role;
    /**
     * @var Permission
     */
    private Permission $permission;

    /**
     * @param Role $role
     * @param Permission $permission
     */
    public function __construct(Role $role, Permission $permission) {
        $this->role = $role;
        $this->permission = $permission;
        $this->middleware('role:admin');
    }
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewRolePermission(){
        try {
            $permissions = $this->permission;
            $guards = Permission::select('guard_name')->get()->groupBy('guard_name')->keys();
            $roles = Role::with('permissions')->get();
            return view('roles-and-permission.roles-and-permission',
                        compact('permissions', 'guards','roles'));
        } catch (Exception $e) {
            Log::error('viewRolePermission',[$e]);
            return back()->with('errors',$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function syncPermissionWithRole(Request $request)
    {
        try {
            $roleToAssignPermission = $this->role->where('id',$request->role_id)->first();
            $permissionToRole = $this->permission->whereIn('id',$request->permission_id)->get();
            $roleToAssignPermission->syncPermissions($permissionToRole);
            return back()->with('success','Permission assigned successfully to role '.$roleToAssignPermission->name);
        } catch (Exception $e) {
            Log::error('syncPermissionWithRole',[$e]);
            return back()->with('errors',$e->getMessage());
        }
    }
}
