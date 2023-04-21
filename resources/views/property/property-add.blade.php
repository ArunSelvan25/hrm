<div class="col-md-10">
    <h4 class="m-0 font-weight-bold text-primary">Property List</h4>
</div>

@if(Auth::guard(getGuard())->user()->hasPermissionTo("property-create",getGuard()))
    <div class="col-md-2">
        <button class="btn btn-primary" href="#" data-toggle="modal" data-target="#houseOwnerModal">
            <i class="fas fa-solid fa-home"></i>
            Add Property
        </button>
    </div>
@endif


{{--    Property Add modal    --}}
<div class="modal fade" id="houseOwnerModal" tabindex="-1" role="dialog" aria-labelledby="houseOwnerAdd"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="container" method="post" action="{{ route('admin.property.add') }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="houseOwnerAdd">Add house owner</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="title">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea type="text" name="description" class="form-control" id="description" placeholder="Description"></textarea>
                    </div>
                    <div class="mb-3" >
                        <label for="description" class="form-label">Select House Owner</label>
                        <select class="house-owner form-label" style="width:100%" name="house_owner_id" placeholder="Select house owner">
                            <option selected="true" disabled>Select House Owner</option>
                            @foreach(getHouseOwnerList() as $houserOwnerList)
                                <option value="{{$houserOwnerList->id}}">{{$houserOwnerList->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
