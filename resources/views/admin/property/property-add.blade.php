<div class="col-md-10">
    <h4 class="m-0 font-weight-bold text-primary">House Owner List</h4>
</div>
<div class="col-md-2">
    <button class="btn btn-primary" href="#" data-toggle="modal" data-target="#houseOwnerModal">
    <i class="fas fa-solid fa-user-tie"></i>
        Add Property
    </button>
</div>


{{--    House Owner Add modal    --}}
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
                        <input type="text" name="description" class="form-control" id="description" placeholder="Description">
                    </div>
                    Need to add house owner select2
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
