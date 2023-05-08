@extends('layout.app')
@section('title') Roles And Permission | {{ getGuard() }} @endsection('title')
@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row col-md-12">
                Add Role
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                @include('roles-and-permission.roles-permission-list')
            </div>
        </div>
    </div>
@endsection('content')


