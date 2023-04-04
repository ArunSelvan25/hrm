<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap CDN 5.3.0 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>
<body class="antialiased bg-light">
    @yield('content')
</body>

{{--  Bootstrap CDN Script 5.3.0  --}}
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.min.js"
        integrity="sha384-heAjqF+bCxXpCWLa6Zhcp4fu20XoNIA98ecBC1YkdXhszjoejr5y9Q77hIrv8R9i"
        crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>


{{-- Socket --}}
<script src="https://cdn.socket.io/4.5.3/socket.io.min.js"
        integrity="sha384-WPFUvHkB1aHA5TDSZi6xtDgkF0wXJcIIxXhC6h8OT8EH3fC5PWro5pWJ1THjcfEi"
        crossorigin="anonymous"></script>

<script>

    @if(Session::has('errors'))
    swal("Oops!", 'Something went wrong');
    @elseif(Session::has('success'))
    swal("Success!", 'Login successfull');
    @endif

    $(document).on('click', '#delete_all_notifications', function(){
        getAjaxRequest('{{ url('admin/notification/delete') }}')
    })

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
        socket.on('houseOwnerCreatedNotification', (message) => {
            var oldValue = {{ getNotificationsCount() }};
            var newValue = 1;
            console.log('getNotificationsCount', {{ getNotificationsCount() }});
            console.log('oldValue + newValue',oldValue + newValue)
            $('#notification_count').text( oldValue + newValue);
        });
    })





</script>
</html>
