@if(Auth::guard(getGuard())->user()->hasPermissionTo("property-create",getGuard()))
    <div class="col-md-2">
        <button class="btn btn-primary" href="#" data-toggle="modal" data-target="#houseOwnerModal">
            <i class="fas fa-solid fa-home"></i>
            {{ __('main_table.property.add') }}
        </button>
    </div>
@endif


{{--    Property Add modal    --}}
<div class="modal fade" id="houseOwnerModal" tabindex="-1" role="dialog" aria-labelledby="houseOwnerAdd"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="container" method="post" action="{{ route('property.add') }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="houseOwnerAdd">{{ __('main_table.property.add') }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('main_table.property.add_property.title') }}</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="title">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('main_table.property.add_property.description') }}</label>
                        <textarea type="text" name="description" class="form-control" id="description" placeholder="Description"></textarea>
                    </div>
                    <div class="mb-3" >
                        <label for="description" class="form-label">{{ __('main_table.property.add_property.select_house_owner') }}</label>
                        <select class="house-owner form-label" style="width:100%" name="house_owner_id" placeholder="Select house owner">
                            <option selected="true" disabled>{{ __('main_table.property.add_property.select_house_owner') }}</option>
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
