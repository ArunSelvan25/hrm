@extends('layout.app')
@section('title') Roles And Permission | {{ getGuard() }} @endsection('title')
@section('content')

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row col-md-12">
                Add Role
            </div>
        </div>

        <div class="card-body col-md-12">
            <div class="">
                <form method="post" action="{{ route('admin.create-role') }}">
                    @csrf
                    @if(Auth::guard('admin')->user()->hasPermissionTo('role-create', 'admin'))
                        <div class="mb-3">
                            <label for="role_name" class="form-label">Select House Owner</label>
                            <input type="text" name="role_name" class="form-control" id="role_name" placeholder="role">
                        </div>

                        <div class="row col-md-12">
                            <div class="mb-3 col-md-6">
                                <label for="permission_name" class="form-label">Select Permission</label>
                                <select class="select-2 form-label" style="width:100%" name="permission_name[]" id="permission_name" multiple="multiple" placeholder="Select permission">
                                    <option selected="true" disabled>Select Permission</option>
                                    @foreach($permissions as $permission)
                                        <option value="{{$permission}}">{{$permission}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label for="guard_name" class="form-label">Select guard</label>
                                <select class="select-2 form-label" style="width:100%" name="guard_name" id="guard_name" placeholder="Select guard">
                                    <option selected="true" disabled>Select Guard</option>
                                    @foreach($guards as $guard)
                                        <option value="{{$guard}}">{{$guard}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">Add role</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>


@endsection('content')

@section('script')
    <script>
        $('.select-2').select2();
    </script>
@endsection('script')
