<?php

namespace App\Http\Controllers;

use App\Http\Requests\{UserRegisterRequest, UserEditRequest};
use Illuminate\Http\Request;
use App\Models\{User};
use Illuminate\Support\Facades\{Hash, Crypt, Http};
use Illuminate\Support\Facades\{Auth, Log};
use Spatie\Permission\Models\Role;

/**
 *
 */
class UserManagementController extends Controller
{
    /**
     * @param User $user
     */
    public function __construct(User $user) {
        $this->user = $user;
        $this->middleware(['permission:user-create'])->only('addUser');
        $this->middleware(['permission:user-list'])->only('getUser,userList');
        $this->middleware(['permission:user-edit'])->only('editUser');
        $this->middleware(['permission:user-delete'])->only('deleteUser');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getUser()
    {
        return view('user.user');
    }

    /**
     * @param UserRegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addUser(UserRegisterRequest $request)
    {
        $this->user->create([
            'tenant_id' => $request->tenant_id,
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'profile' => 'need to add profile image',
            'password' => Hash::make($request['password']),
            'original_password' => Crypt::encryptString($request['password']),
        ])->assignRole('web');
        return redirect()->route('user.user')->with('success','User Created successfully');
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function userList(Request $request){
        $query = $this->user->with('tenant','tenant.property')
                    ->when(getGuard() == 'admin', function ($q) {
                        return $q;
                    })->when(getGuard() == 'house-owner', function ($q) {
                        return $q->whereHas('tenant.property', function($query){
                            return $query->where('house_owner_id',auth()->guard('house-owner')->user()->id);
                        });
                    })->when(getGuard() == 'tenant', function ($q) {
                        return $q->where('tenant_id',auth()->guard('tenant')->user()->id);
                    })->when(getGuard() == 'web', function ($q) {
                        return $q->where('id',auth()->guard('web')->user()->id);
                    });
        $limit = $request->iDisplayLength;
        $offset = $request->iDisplayStart;

        if ($request->sSearch!='') {
            $keyword = $request->sSearch;
            $query = $query->when(
                $keyword!='',
                function ($q) use ($keyword) {
                    return $q->where('name', 'like', '%'.$keyword.'%')
                            ->orWhere('email', 'like', '%'.$keyword.'%');
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
            if(Auth::guard(getGuard())->user()->hasPermissionTo("user-edit",getGuard())) {
                $action = '<button class="btn btn-outline-primary" href="#" data-toggle="modal"
                        data-target="#userEditModal-'.$value->id.'">
                        <i class="fas fa-solid fa-user-edit"></i>
                        Edit
                     </button>
                      

                     <div class="modal fade" id="userEditModal-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="userEdit-'.$value->id.'"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form class="container" method="post" action="'.route('user.edit',['locale' => app()->getLocale()]).'">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="userEdit-'.$value->id.'">Edit User</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                        <input type="hidden" name="user_id" value="'.$value->id.'" readonly>
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

            if(Auth::guard(getGuard())->user()->hasPermissionTo("user-delete",getGuard())) {
                $action .= '<button class="btn btn-outline-warning" href="#" data-toggle="modal"
                data-target="#userDeleteModal-'.$value->id.'">
                <i class="fas fa-solid fa-trash"></i>
                Delete
             </button>
             <div class="modal fade" id="userDeleteModal-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="userDelete-'.$value->id.'"
                         aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form class="container" method="post" action="'.route('user.delete',['locale' => app()->getLocale()]).'">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="userDelete-'.$value->id.'">Delete User</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                        <input type="hidden" name="user_id" value="'.$value->id.'" readonly>
                                        <p>Are you sure you want to delete <strong>User - '.$value->name.'?</strong></p>
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
            $col['property_name'] = $value->tenant->property->title ?? '-';
            $col['tenant_name'] = $value->tenant->name ?? '-';
            $col['name'] = $value->name ?? '-';
            $col['email'] =$value->email ?? '-';
            $col['phone'] =$value->phone ?? '-';
            $col['action']=($action != '') ? $action : '-';

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
     * @param UserEditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editUser(UserEditRequest $request)
    {
        $userData = $this->user->findorFail($request->user_id);
        $userData->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone']
        ]);
        return back()->with('success','User Edited successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser(Request $request)
    {
        $userData = $this->user->findorFail($request->user_id);
        $userData->delete();
        return back()->with('success','User Deleted successfully');
    }
}
