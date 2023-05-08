<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\HouseOwnerEditRequest;
use App\Models\HouseOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};

class HouseOwnerManagementController extends Controller
{
    /**
     * @param HouseOwner $houseOwner
     */
    public function __construct(HouseOwner $houseOwner)
    {
        $this->houseOwner = $houseOwner;
        $this->middleware(['permission:owner-list'])->only('getHouseOwner');
        $this->middleware(['permission:owner-edit'])->only('editHouseOwner');
        $this->middleware(['permission:owner-delete'])->only('deleteHouseOwner');
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getHouseOwner(){
        try {
            // dd(app()->getLocale());
            return view('house-owner.house-owner');
        } catch (Exception $e) {
            Log::error('getHouseOwner',[$e]);
            return back()->with('errors',$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function houseOwnerList(Request $request){
        $query = $this->houseOwner
                    ->when(getGuard() == 'admin', function ($q) {
                        return $q;
                    })->when(getGuard() == 'house-owner', function ($q) {
                        return $q->where('id',auth()->guard('house-owner')->user()->id);
                    })->when(getGuard() == 'tenant', function ($q) {
                        return $q->with('properties')->whereHas('properties', function($query){
                            return $query->where('id',auth()->guard('tenant')->user()->property_id);
                        });
                    })->when(getGuard() == 'web', function ($q) {
                        return $q->with('properties.tenant')->whereHas('properties.tenant', function($query){
                            $query->where('id',auth()->guard('web')->user()->tenant_id);
                        });
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
            if(Auth::guard(getGuard())->user()->hasPermissionTo("property-create",getGuard())) {
                $action .= '<button class="btn btn-outline-success" href="#" data-toggle="modal"
                                data-target="#propertyAddModal-' . $value->id . '">
                            <i class="fas fa-solid fa-home"></i>
                            Add Property
                            </button>

                            <div class="modal fade" id="propertyAddModal-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="propertyAdd-'.$value->id.'"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form class="container" method="post" action="'.route('property.add',['locale' => app()->getLocale()]).'">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="propertyAdd-'.$value->id.'">Add property for <strong>'.$value->name.'</strong></h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                                <input type="hidden" name="house_owner_id" value="'.$value->id.'" readonly>
                                                <div class="mb-3">
                                                    <label for="title" class="form-label">Title</label>
                                                    <input type="text" name="title" class="form-control" id="title" placeholder="Title">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea type="text" name="description" class="form-control" id="description"></textarea>
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
            if(Auth::guard(getGuard())->user()->hasPermissionTo("owner-edit",getGuard())) {
                $action .= '<button class="btn btn-outline-primary" href="#" data-toggle="modal"
                                data-target="#houseOwnerEditModal-' . $value->id . '">
                                <i class="fas fa-solid fa-user-edit"></i>
                                Edit
                             </button>

                             <div class="modal fade" id="houseOwnerEditModal-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="houseOwnerEdit-'.$value->id.'"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form class="container" method="post" action="'.route('house-owner.edit',['locale' => app()->getLocale()]).'">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="houseOwnerEdit-'.$value->id.'">Edit house owner</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                                <input type="hidden" name="owner_id" value="'.$value->id.'" readonly>
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" value="'.$value->name.'" name="name" class="form-control" id="name" placeholder="Name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email address</label>
                                                    <input type="email" value="'.$value->email.'" name="email" class="form-control" id="email" placeholder="name@example.com">
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

            if(Auth::guard(getGuard())->user()->hasPermissionTo("owner-delete",getGuard())) {
                $action .= '<button class="btn btn-outline-warning" href="#" data-toggle="modal"
                                data-target="#houseOwnerDeleteModal-'.$value->id.'">
                                <i class="fas fa-solid fa-trash"></i>
                                Delete
                             </button>

                             <div class="modal fade" id="houseOwnerDeleteModal-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="houseOwnerDelete-'.$value->id.'"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form class="container" method="post" action="'.route('house-owner.delete',['locale' => app()->getLocale()]).'">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="houseOwnerDelete-'.$value->id.'">Delete House Owner</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                                <input type="hidden" name="owner_id" value="'.$value->id.'" readonly>
                                                <p>Are you sure you want to delete <strong>Owner - '.$value->name.'?</strong></p>
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
            $col['name'] = $value->name ?? '-';
            $col['email'] =$value->email ?? '-';
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
     * @param HouseOwnerEditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editHouseOwner(HouseOwnerEditRequest $request)
    {
        try {
            $houseOwnerData = $this->houseOwner->findorFail($request->owner_id);
            $houseOwnerData->update([
                'name' => $request['name'],
                'email' => $request['email']
            ]);
            return back()->with('success','Owner Edited successfully');
        } catch (Exception $e) {
            Log::error('editHouseOwner',[$e]);
            return back()->with('errors',$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteHouseOwner(Request $request)
    {
        try {
            $houseOwnerData = $this->houseOwner->findorFail($request->owner_id);
            $houseOwnerData->delete();
            return back()->with('success','Owner Deleted successfully');
        } catch (Exception $e) {
            Log::error('editHouseOwner',[$e]);
            return back()->with('errors',$e->getMessage());
        }
    }
}
