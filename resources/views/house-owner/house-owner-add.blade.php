

@if(Auth::guard(getGuard())->user()->hasPermissionTo("owner-create",getGuard()))
    <div class="col-md-2">
        <button class="btn btn-primary" href="#" data-toggle="modal" data-target="#houseOwnerModal">
            <i class="fas fa-solid fa-user-plus"></i>
            {{ __('main_table.house_owner.add') }}
        </button>
    </div>

    {{--    House Owner Add modal    --}}
    <div class="modal fade" id="houseOwnerModal" tabindex="-1" role="dialog" aria-labelledby="houseOwnerAdd"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="container" method="post" action="{{ route('house-owner.post-register') }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="houseOwnerAdd">{{ __('main_table.house_owner.add_owner.form_title') }}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('main_table.house_owner.add_owner.name') }}</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('main_table.house_owner.add_owner.email_address') }}</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('main_table.house_owner.add_owner.password') }}</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="********">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('main_table.house_owner.add_owner.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="********">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ __('main_table.house_owner.add_owner.cancel') }}</button>
                        <button id="submit" type="submit" class="btn btn-primary">{{ __('main_table.house_owner.add_owner.submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif

