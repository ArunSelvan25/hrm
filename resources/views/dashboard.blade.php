@extends('layout.app')
@section('title') Dashboard | {{ getGuard() }} @endsection('title')
@section('content')

@endsection('content')

@section('script')
<script>
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

@endsection('script')
