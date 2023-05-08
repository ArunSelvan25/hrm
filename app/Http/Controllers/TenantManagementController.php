<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\{TenantRegisterRequest, TenantEditRequest};
use App\Models\{Tenant};
use Illuminate\Support\Facades\{Hash, Crypt, Http};
use Illuminate\Support\Facades\{Auth, Log};
use Spatie\Permission\Models\Role;

/**
 *
 */
class TenantManagementController extends Controller
{
    /**
     * @param Tenant $tenant
     */
    public function __construct(Tenant $tenant) {
        $this->tenant = $tenant;
        // $this->middleware(['permission:tenant-create'])->only('addTenant');
        $this->middleware(['permission:tenant-list'])->only('getTenant,tenantList');
        $this->middleware(['permission:tenant-edit'])->only('editTenant');
        $this->middleware(['permission:tenant-delete'])->only('deleteTenant');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getTenant()
    {
        return view('tenant.tenant');
    }

    /**
     * @param TenantRegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addTenant(TenantRegisterRequest $request)
    {
        $this->tenant->create([
            'property_id' => $request->property_id,
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request->phone,
            'profile' => 'need to add profile image',
            'password' => Hash::make($request['password']),
            'original_password' => Crypt::encryptString($request['password']),
        ])->assignRole('tenant');
        return redirect()->route('tenant.tenant')->with('success','Property Created successfully');
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function tenantList(Request $request){
        $query = $this->tenant->with('property')
                    ->when(getGuard() == 'admin', function ($q) {
                        return $q;
                    })->when(getGuard() == 'house-owner', function ($q) {
                        return $q->whereHas('property', function($query){
                            return $query->where('house_owner_id',auth()->guard('house-owner')->user()->id);
                        });
                    })->when(getGuard() == 'tenant', function ($q) {
                        return $q->where('id',auth()->guard('tenant')->user()->id);
                    })->when(getGuard() == 'web', function ($q) {
                        return $q->where('id',auth()->guard('web')->user()->tenant_id);
                    });
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
            $action = '';
            if(Auth::guard(getGuard())->user()->hasPermissionTo("user-create",getGuard())) {
            $action .= '<button class="btn btn-outline-success" href="#" data-toggle="modal"
                                data-target="#userAddModal-'.$value->id.'">
                            <i class="fas fa-duotone fa-users"></i>
                            Add User
                            </button>

                             <div class="modal fade" id="userAddModal-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="userAdd-'.$value->id.'"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form class="container" method="post" action="'.route('user.add',['locale' => app()->getLocale()]).'">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="userAdd-'.$value->id.'">Add User for <strong>'.$value->name.'</strong></h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                                <input type="hidden" name="tenant_id" value="'.$value->id.'" readonly>
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email address</label>
                                                    <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Phone</label>
                                                    <input type="number" name="phone" class="form-control" id="phone" placeholder="1234567890">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Password</label>
                                                    <input type="password" name="password" class="form-control" id="password" placeholder="********">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="********">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <button id="submit" type="submit" class="btn btn-primary">Add</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>';
            }


            if(Auth::guard(getGuard())->user()->hasPermissionTo("tenant-edit",getGuard())) {
                    $action .= '<button class="btn btn-outline-primary" href="#" data-toggle="modal"
                                    data-target="#tenantEditModal-'.$value->id.'">
                                    <i class="fas fa-solid fa-user-edit"></i>
                                    Edit
                                </button>
                                <div class="modal fade" id="tenantEditModal-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="tenantEdit-'.$value->id.'"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form class="container" method="post" action="'.route('tenant.edit',['locale' => app()->getLocale()]).'">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="tenantEdit-'.$value->id.'">Edit tenant</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                                <input type="hidden" name="tenant_id" value="'.$value->id.'" readonly>
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" value="'.$value->name.'" name="name" class="form-control" id="name" placeholder="Name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email address</label>
                                                    <input type="email" value="'.$value->email.'" name="email" class="form-control" id="email" placeholder="name@example.com">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Phone number</label>
                                                    <input type="number" value="'.$value->phone.'" name="phone" class="form-control" id="phone" placeholder="Phone number">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <button id="submit" type="submit" class="btn btn-primary">Edit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>';
            }

            if(Auth::guard(getGuard())->user()->hasPermissionTo("tenant-delete",getGuard())) {
                        $action .= ' <button class="btn btn-outline-warning" href="#" data-toggle="modal"
                                        data-target="#tenantrDeleteModal-'.$value->id.'">
                                        <i class="fas fa-solid fa-trash"></i>
                                        Delete
                                    </button>
                                    <div class="modal fade" id="tenantrDeleteModal-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="tenantDelete-'.$value->id.'"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form class="container" method="post" action="'.route('tenant.delete',['locale' => app()->getLocale()]).'">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="tenantDelete-'.$value->id.'">Delete Tenant</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                                <input type="hidden" name="tenant_id" value="'.$value->id.'" readonly>
                                                <p>Are you sure you want to delete <strong>Tenant - '.$value->name.'?</strong></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <button id="submit" type="submit" class="btn btn-primary">Delete</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>';
            }

            $col['id'] = $offset+1;
            $col['property_name'] = $value->property->title ?? '-';
            $col['name'] = $value->name ?? '-';
            $col['email'] =$value->email ?? '-';
            $col['phone'] =$value->phone ?? '-';
            $col['action']= ($action != '') ? $action : '-';

            array_push($column, $col);
            $offset++;
        }
        $data['sEcho']=$request->sEcho;
        $data['aaData']=$column;
        $data['iTotalRecords']=$totalCount;
        $data['iTotalDisplayRecords']=$totalCount;

        return json_encode($data);
    }

    /**
     * @param TenantEditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editTenant(TenantEditRequest $request)
    {
        $tenantData = $this->tenant->findorFail($request->tenant_id);
        $tenantData->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone']
        ]);
        return back()->with('success','Tenant Edited successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTenant(Request $request)
    {
        $tenantData = $this->tenant->findorFail($request->tenant_id);
        $tenantData->delete();
        return back()->with('success','Tenant Deleted successfully');
    }

}
