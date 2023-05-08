<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log};

class PropertyManagementController extends Controller
{
    /**
     * @param Property $property
     */
    public function __construct(Property $property)
    {
        $this->property = $property;
        $this->middleware(['permission:property-create'])->only('addProperty');
        $this->middleware(['permission:property-list'])->only('getProperty,propertyList');
        $this->middleware(['permission:property-edit'])->only('editProperty');
        $this->middleware(['permission:property-delete'])->only('deleteProperty');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addProperty(Request $request)
    {
        try {
            $this->property->create([
                'house_owner_id' => $request->house_owner_id,
                'title' => $request->title,
                'description' => $request->description,
                'status' => 1,
            ]);
            return redirect()->route('property.property')->with('success','Property Created successfully');
        } catch (Exception $e) {
            Log::error('addProperty',[$e]);
            return back()->with('errors',$e->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getProperty()
    {
        try {
            return view('property.property');
        } catch (Exception $e) {
            Log::error('addProperty',[$e]);
            return back()->with('errors',$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function propertyList(Request $request){
        $query = $this->property->with('houseOwner')
                    ->when(getGuard() == 'admin', function ($q) {
                        return $q;
                    })->when(getGuard() == 'house-owner', function ($q) {
                        return $q->where('house_owner_id',auth()->guard('house-owner')->user()->id);
                    })->when(getGuard() == 'tenant', function ($q) {
                        return $q->where('id',auth()->guard('tenant')->user()->property_id);
                    })->when(getGuard() == 'web', function ($q) {
                        return $q->with('tenant')->whereHas('tenant', function($query){
                            return $query->where('id',auth()->guard('web')->user()->tenant_id);
                        });
                    });
        $limit = $request->iDisplayLength;
        $offset = $request->iDisplayStart;

        if ($request->sSearch!='') {
            $keyword = $request->sSearch;
            $query = $query->when(
                $keyword!='',
                function ($q) use ($keyword) {
                    return $q->where('title', 'like', '%'.$keyword.'%');
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
            if(Auth::guard(getGuard())->user()->hasPermissionTo("tenant-create",getGuard())) {
                $action = '<button class="btn btn-outline-success" href="#" data-toggle="modal"
                                data-target="#tenantAddModal-' . $value->id . '">
                            <i class="fas fa-solid fa-user-tie"></i>
                            Add Tenant
                            </button>

                            <div class="modal fade" id="tenantAddModal-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="tenantAdd-'.$value->id.'"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form class="container" method="post" action="'. route('tenant.add',['locale' => app()->getLocale()]).'">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="tenantAdd-'.$value->id.'">Add tenant for <strong>'.$value->title.'</strong></h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                                <input type="hidden" name="property_id" value="'.$value->id.'" readonly>
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

            if(Auth::guard(getGuard())->user()->hasPermissionTo("property-edit",getGuard())){
                $action .= '<button class="btn btn-outline-primary" href="#" data-toggle="modal"
                                data-target="#propertyEditModal-'.$value->id.'">
                                <i class="fas fa-solid fa-pen"></i>
                                Edit
                            </button>

                            <div class="modal fade" id="propertyEditModal-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="propertyEdit-'.$value->id.'"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form class="container" method="post" action="'.route('property.edit',['locale' => app()->getLocale()]).'">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="propertyEdit-'.$value->id.'">Edit house owner</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                                <input type="hidden" name="property_id" value="'.$value->id.'" readonly>
                                                <div class="mb-3">
                                                    <label for="title" class="form-label">Title</label>
                                                    <input type="text" value="'.$value->title.'" name="title" class="form-control" id="title" placeholder="Title">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea type="text" name="description" class="form-control" id="description">'.$value->description.'</textarea>
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

            if(Auth::guard(getGuard())->user()->hasPermissionTo("property-delete",getGuard())){
                $action .= '<button class="btn btn-outline-warning" href="#" data-toggle="modal"
                                data-target="#propertyDeleteModal-'.$value->id.'">
                                <i class="fas fa-solid fa-trash"></i>
                                Delete
                             </button>

                             <div class="modal fade" id="propertyDeleteModal-'.$value->id.'" tabindex="-1" role="dialog" aria-labelledby="propertyDelete-'.$value->id.'"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form class="container" method="post" action="'.route('property.delete',['locale' => app()->getLocale()]).'">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="propertyDelete-'.$value->id.'">Delete Property</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="'.csrf_token().'" readonly>
                                                <input type="hidden" name="property_id" value="'.$value->id.'" readonly>
                                                <p>Are you sure you want to delete <strong>Property - '.$value->title.'?</strong></p>
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
            $col['title'] = $value->title ?? '-';
            $col['house_owner'] = $value->houseOwner->name ?? '-';
            $col['description'] =$value->description ?? '-';
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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editProperty(Request $request)
    {
        try {
            $propertyData = $this->property->findorFail($request->property_id);
            $propertyData->update([
                'title' => $request->title,
                'description' => $request->description
            ]);
            return back()->with('success','Property Edited successfully');
        } catch (Exception $e) {
            Log::error('editProperty',[$e]);
            return back()->with('errors',$e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteProperty(Request $request)
    {
        try {
            $propertyData = $this->property->findorFail($request->property_id);
            $propertyData->delete();
            return back()->with('success','Property Deleted successfully');
        } catch (Exception $e) {
            Log::error('deleteProperty',[$e]);
            return back()->with('errors',$e->getMessage());
        }
    }
}
