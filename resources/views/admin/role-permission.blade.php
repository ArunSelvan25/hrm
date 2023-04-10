@extends('admin.layout.app')
@section('title') Roles And Permission | Admin @endsection('title')
@section('content')
    <h3 class="text-dark">Roles</h3>
    <div>
        <form method="post" action="{{ route('admin.create-role') }}">
            @csrf
            @if(Auth::guard('admin')->user()->can('role-list'))
                <div class="form-floating mb-3">
                    <label for="role text-dark">New Role</label>
                    <input name="role" type="text" class="form-control" id="role">
                </div>
            @endif
            @foreach($permissions as $permission)
                <div class="form-check form-check-inline p-5">
                    <input class="form-check-input" type="checkbox" name="permission[]" id="inlineCheckbox{{$permission->id}}" value="{{ $permission->id }}">
                    <label class="form-check-label text-dark" for="inlineCheckbox{{$permission->id}}">{{ $permission->name }}</label>
                </div>
            @endforeach
            @if(Auth::guard('admin')->user()->can('role-list'))
                <button class="btn btn-primary" type="submit">Add role</button>
            @endif

        </form>

    </div>


@endsection('content')
