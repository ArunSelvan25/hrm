<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\{Role, Permission};
use Illuminate\Http\Request;

class RolesAndPermissionController extends Controller
{
    private Role $role;
    private Permission $permission;

    public function __construct(Role $role, Permission $permission) {
        $this->role = $role;
        $this->permission = $permission;
        $this->middleware('role:admin');
    }
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewRolePermission(){
        $permissions = $this->permission;
        $guards = Permission::select('guard_name')->get()->groupBy('guard_name')->keys();
        $roles = Role::with('permissions')->get();

        return view('roles-and-permission.roles-and-permission', compact('permissions', 'guards','roles'));
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

    public function listRolePermission(Request $request) {
        $query = $this->role;
        $limit = $request->iDisplayLength;
        $offset = $request->iDisplayStart;

        if ($request->sSearch!='') {
            $keyword = $request->sSearch;
            $query = $query->when(
                $keyword!='',
                function ($q) use ($keyword) {
                    return $q->where('name', 'like', '%'.$keyword.'%');
                }
            );
        }
        $totalCount = $query->count();

        $query = $query->when(
            ($limit!='-1' && isset($offset)),
            function ($q) use ($limit, $offset) {
                return $q->offset($offset)->limit($limit);
            }
        );
        $data = $query->latest()->get();
        $column = array();
        foreach ($data as $value) {
            $permissionsAll = $this->permission->where('guard_name',$value->guard_name)->get();
            $permissionShow = array();
            foreach($permissionsAll as $getPermission) {
               $permissionShows[] = '<input type="checkbox" id="'.$getPermission->id.'" name="vehicle1[]" value="Bike">
                 <label for="'.$getPermission->id.'" class="form-label">'. $getPermission->name.'</label><br>';
                array_push($permissionShow, $permissionShows);
            }
            $action = '<button class="btn btn-outline-success" href="#" data-toggle="modal"
                                data-target="#changePermission-'.$value->id.'">
                            <i class="fas fa-solid fa-unlock"></i>
                            Change Permission
                            </button>

                            <div class="modal fade" id="changePermission-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="permission-'.$value->id.'"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form class="container" method="post" action="#">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="permission-'.$value->id.'">Change permission <strong>'.$value->name.'</strong> role</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                                <input type="hidden" name="role_id" value="'.$value->id.'" readonly>
                                                <div class="mb-3 col-md-6">



                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <button id="submit" type="submit" class="btn btn-primary">Sync</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>';
            $col['role'] = $value->name ?? '-';
            $col['permission'] = $value->permissions()->count() ?? '-';
            $col['action']=$action ?? '-';

            array_push($column, $col);
            $offset++;
        }
        $data['sEcho']=$request->sEcho;
        $data['aaData']=$column;
        $data['iTotalRecords']=$totalCount;
        $data['iTotalDisplayRecords']=$totalCount;

        return json_encode($data);
    }

    public function syncPermissionWithRole(Request $request)
    {
        $roleToAssignPermission = $this->role->where('id',$request->role_id)->first();
        $permissionToRole = $this->permission->whereIn('id',$request->permission_id)->get();
        $roleToAssignPermission->syncPermissions($permissionToRole);
        return back()->with('success','Permission assigned successfully to role '.$roleToAssignPermission->name);
    }
}
