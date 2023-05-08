<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title')</title>

    {{--    Custom fonts for this template    --}}
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    {{--    Custom styles for this template    --}}
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    {{--    Select2 4.1.0    --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @yield('page-styles')
</head>

<body id="page-top">
{{--    Jquery    --}}
<script src="{{ asset('vendor/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

{{--    Core plugin JavaScript    --}}
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
{{--    Select2 4.1.0    --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <div id="wrapper">

        @include('includes.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                @include('includes.navbar')
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>


    {{-- Socket --}}
    <script src="https://cdn.socket.io/4.5.3/socket.io.min.js"
            integrity="sha384-WPFUvHkB1aHA5TDSZi6xtDgkF0wXJcIIxXhC6h8OT8EH3fC5PWro5pWJ1THjcfEi"
            crossorigin="anonymous"></script>

    {{--    Custom scripts for all pages    --}}
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    {{--    Sweet alert    --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    @yield('page-script')
    <script>

        @if(Session::has('errors'))
        swal("Oops!",  "{{Session::get('errors')}}", 'error');
        @elseif(Session::has('success'))
            swal("Success!", "{{Session::get('success')}}", 'success');
        @endif

        {{--    Common AJAX GET request    --}}
        function getAjaxRequest(url) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url : url,
                type : 'GET',
                dataType : 'json',
                success : function(result){
                }
            });
        }


        $(document).ready(function(){
            let ip_address = '127.0.0.1';
            let socket_port = '3000';
            let socket = io(ip_address+ ':' +socket_port);
            {{--socket.on('houseOwnerCreatedNotification', (message) => {--}}
            {{--    var oldValue = {{ getNotificationsCount() }};--}}
            {{--    var newValue = 1;--}}
            {{--    alert(oldValue)--}}
            {{--    $('#notification_count').text( oldValue + newValue);--}}
            {{--});--}}
        })
    </script>

@yield('script')

</body>
