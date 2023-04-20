<div class="col-md-10">
    <h4 class="m-0 font-weight-bold text-primary">House Owner List</h4>
</div>

@if(Auth::guard(getGuard())->user()->hasPermissionTo("owner-create",getGuard()))
    <div class="col-md-2">
        <button class="btn btn-primary" href="#" data-toggle="modal" data-target="#houseOwnerModal">
            <i class="fas fa-solid fa-user-plus"></i>
            Add Owner
        </button>
    </div>
@endif


{{--    House Owner Add modal    --}}
<div class="modal fade" id="houseOwnerModal" tabindex="-1" role="dialog" aria-labelledby="houseOwnerAdd"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="container" method="post" action="{{ route('house-owner.post-register') }}">
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
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com">
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
                    <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
