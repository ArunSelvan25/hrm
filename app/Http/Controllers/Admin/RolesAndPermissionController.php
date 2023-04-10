<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\{Role, Permission};
use Illuminate\Http\Request;

class RolesAndPermissionController extends Controller
{
    public function viewRolePermission(){
        $permissions = Permission::all();
        return view('admin.role-permission', compact('permissions'));
    }

    public function createRole(Request $request){
        $role = Role::create([
            'name' => $request->role,
            'guard_name' => 'admin',
        ]);

        $permissions = Permission::whereIn('id',$request->permission)->get();

        $role->syncPermissions($permissions);

        return back();
    }
}
