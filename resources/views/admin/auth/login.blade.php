@extends('admin.app')
@section('title') Login | {{ getGuard() }} @endsection('title')
@section('content')
    <div class="container pt-5 mt-5">
        <div class="card mx-auto col-3">
            <form class="container" method="post" action="{{ route('admin.post-login') }}">
                @csrf
                <div class="card-header text-center">
                    Login Admin! We are waiting
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="********">
                    </div>
                </div>
                <div class="card-footer text-center text-muted">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection('content')
