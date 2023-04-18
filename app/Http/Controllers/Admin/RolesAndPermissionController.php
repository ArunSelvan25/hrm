<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\{Role, Permission};
use Illuminate\Http\Request;

class RolesAndPermissionController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewRolePermission(){
        $permissions = Permission::get()->groupBy('name')->keys();
        $guards = Permission::select('guard_name')->get()->groupBy('guard_name')->keys();
        $roles = Role::all();

        return view('admin.role-permission', compact('permissions', 'guards','roles'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createRole(Request $request){
        $role = Role::updateOrCreate([
            'name' => $request->role_name,
            'guard_name' => $request->guard_name,
        ],[
            'name' => $request->role_name,
            'guard_name' => $request->guard_name,
        ]);
        $permissions = Permission::whereIn('name',$request->permission_name)->where('guard_name',$request->guard_name)->get();

        $role->syncPermissions($permissions);

        return back();
    }
}
