
@extends('house-owner.app')
@section('title')
    Registration | {{ getGuard() }}
@endsection('title')
@section('content')
    <div class="container pt-5 mt-5">
        <div class="card mx-auto col-3">
            <form class="container" method="post" action="{{ route('house-owner.post-register',['locale' => app()->getLocale()]) }}">
                @csrf
                <div class="card-header text-center">
                    Register Here Owner!
                </div>
                <div class="card-body">
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
                <div class="card-footer text-center text-muted">
                    <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection('content')

@section('script')
<script>
    $(document).ready(function(){
        // let ip_address = '127.0.0.1';
        // let socket_port = '3000';
        // let socket = io(ip_address+ ':' +socket_port);
        // $('#submit').on('click', function(){
        //         socket.emit('houseOwnerCreated');
        // });
    })




</script>
@endsection('script')
